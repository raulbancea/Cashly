<?php

namespace App\Http\Controllers;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Stripe\BillingPortal\Session as PortalSession;
use Stripe\Checkout\Session as CheckoutSession;
use Stripe\Exception\SignatureVerificationException;
use Stripe\Stripe;
use Stripe\Subscription;
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

        if ($user->stripe_customer_id) {

            $customerEmail = null;
            $customerId    = $user->stripe_customer_id;
        } else {

            $customerEmail = $user->email;
            $customerId    = null;
        }

        $session = CheckoutSession::create([
            'mode'                 => 'subscription',
            'payment_method_types' => ['card'],
            'line_items'           => [[
                'price'    => config('services.stripe.price_id'),
                'quantity' => 1,
            ]],
            'customer_email' => $customerEmail,
            'customer'       => $customerId,
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

        if (!$user->stripe_customer_id) {
            abort(404);
        }

        Stripe::setApiKey(config('services.stripe.secret'));

        $session = PortalSession::create([
            'customer'   => $user->stripe_customer_id,
            'return_url' => route('subscription.index'),
        ]);

        return redirect($session->url);
    }

    public function webhook(Request $request)
    {

        Stripe::setApiKey(config('services.stripe.secret'));

        $payload   = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');

        try {
            $event = Webhook::constructEvent(
                $payload,
                $sigHeader,
                config('services.stripe.webhook_secret')
            );
        } catch (SignatureVerificationException $e) {

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
        } elseif ($event->type === 'invoice.paid') {
            $this->handleInvoicePaid($object);
        }

        return response('OK', 200);
    }

    private function handleCheckoutCompleted($session)
    {

        if ($session->mode !== 'subscription') {
            return;
        }

        if (isset($session->metadata->user_id)) {
            $userId = $session->metadata->user_id;
        } else {
            $userId = null;
        }

        if ($userId !== null) {
            $user = User::find($userId);
        } else {
            $user = null;
        }

        if ($user !== null) {
            $user->update(['stripe_customer_id' => $session->customer]);
        }
    }

    private function handleSubscriptionUpdated($subscription)
    {

        $user = User::where('stripe_customer_id', $subscription->customer)->first();

        if ($user !== null) {
            $fresh = Subscription::retrieve($subscription->id);

            \Illuminate\Support\Facades\Log::info('stripe_subscription_debug', [
                'current_period_end'          => $fresh->current_period_end ?? 'NULL',
                'items_period_end'            => $fresh->items->data[0]->current_period_end ?? 'NULL',
                'billing_cycle_anchor'        => $fresh->billing_cycle_anchor ?? 'NULL',
                'status'                      => $fresh->status ?? 'NULL',
                'keys'                        => array_keys($fresh->toArray()),
            ]);

            $periodEnd = $fresh->current_period_end
                ?? ($fresh->items->data[0]->current_period_end ?? null);

            $user->update([
                'stripe_subscription_id' => $subscription->id,
                'subscription_status'    => $subscription->status,
                'subscription_ends_at'   => $periodEnd ? Carbon::createFromTimestamp($periodEnd) : null,
            ]);
        }
    }

    private function handleInvoicePaid($invoice)
    {
        if (!$invoice->subscription) {
            return;
        }

        $user = User::where('stripe_customer_id', $invoice->customer)->first();

        if ($user === null) {
            return;
        }

        $periodEnd = null;
        if (!empty($invoice->lines->data)) {
            $periodEnd = $invoice->lines->data[0]->period->end ?? null;
        }

        if ($periodEnd) {
            $user->update(['subscription_ends_at' => Carbon::createFromTimestamp($periodEnd)]);
        }
    }

    private function handleSubscriptionDeleted($subscription)
    {

        $user = User::where('stripe_customer_id', $subscription->customer)->first();

        if ($user !== null) {
            $user->update([
                'subscription_status'  => 'canceled',
                'subscription_ends_at' => now(),
            ]);
        }
    }
}
