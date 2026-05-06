<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Stripe\BillingPortal\Session as PortalSession;
use Stripe\Checkout\Session as CheckoutSession;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Stripe;
use Stripe\Webhook;

class StripeController extends Controller
{
    public function index()
    {
        return view('subscription.index', ['user' => auth()->user()]);
    }

    public function checkout()
    {
        $user = auth()->user();

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = CheckoutSession::create([
            'mode'                 => 'subscription',
            'payment_method_types' => ['card'],
            'line_items'           => [[
                'price'    => config('services.stripe.price_id'),
                'quantity' => 1,
            ]],
            'customer_email' => $user->stripe_customer_id ? null : $user->email,
            'customer'       => $user->stripe_customer_id ?: null,
            'success_url'    => route('subscription.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url'     => route('subscription.index'),
            'metadata'       => ['user_id' => $user->id],
        ]);

        return redirect($session->url);
    }

    public function success()
    {
        return view('subscription.success');
    }

    public function portal()
    {
        $user = auth()->user();

        abort_if(!$user->stripe_customer_id, 404);

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = PortalSession::create([
            'customer'   => $user->stripe_customer_id,
            'return_url' => route('subscription.index'),
        ]);

        return redirect($session->url);
    }

    public function webhook(Request $request)
    {
        $payload   = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sigHeader,
                config('services.stripe.webhook_secret')
            );
        } catch (SignatureVerificationException) {
            return response('Invalid signature', 400);
        }

        $object = $event->data->object;

        if ($event->type === 'checkout.session.completed') {
            $this->handleCheckoutCompleted($object);
        } elseif ($event->type === 'customer.subscription.created') {
            $this->handleSubscriptionUpdated($object);
        } elseif ($event->type === 'customer.subscription.updated') {
            $this->handleSubscriptionUpdated($object);
        } elseif ($event->type === 'customer.subscription.deleted') {
            $this->handleSubscriptionDeleted($object);
        }

        return response('OK', 200);
    }

    private function handleCheckoutCompleted(object $session): void
    {
        if ($session->mode !== 'subscription') return;

        $userId = $session->metadata->user_id ?? null;
        $user   = $userId ? User::find($userId) : null;

        if ($user) {
            $user->update(['stripe_customer_id' => $session->customer]);
        }
    }

    private function handleSubscriptionUpdated(object $subscription): void
    {
        $user = User::where('stripe_customer_id', $subscription->customer)->first();

        if ($user) {
            $user->update([
                'stripe_subscription_id' => $subscription->id,
                'subscription_status'    => $subscription->status,
                'subscription_ends_at'   => Carbon::createFromTimestamp($subscription->current_period_end),
            ]);
        }
    }

    private function handleSubscriptionDeleted(object $subscription): void
    {
        $user = User::where('stripe_customer_id', $subscription->customer)->first();

        if ($user) {
            $user->update([
                'subscription_status'  => 'canceled',
                'subscription_ends_at' => now(),
            ]);
        }
    }
}
