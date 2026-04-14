<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Client;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Services\PdfService;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = auth()->user()->invoices()
            ->with('client')
            ->latest()
            ->get();
        return view('invoices.index', compact('invoices'));
    }

    public function create()
    {
        $clients = auth()->user()->clients()->where('status', 'active')->get();
        $products = auth()->user()->products()->get();
        $nextNumber = $this->generateInvoiceNumber();
        return view('invoices.create', compact('clients', 'products', 'nextNumber'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'client_id'  => 'required|exists:clients,id',
            'number'     => 'required|string|unique:invoices,number',
            'issue_date' => 'required|date',
            'due_date'   => 'required|date|after_or_equal:issue_date',
            'currency'   => 'required|in:RON,EUR',
            'notes'      => 'nullable|string|max:1000',
            'items'      => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity'    => 'required|numeric|min:0.01',
            'items.*.unit_price'  => 'required|numeric|min:0',
        ]);

        $invoice = auth()->user()->invoices()->create([
            'client_id'  => $validated['client_id'],
            'number'     => $validated['number'],
            'issue_date' => $validated['issue_date'],
            'due_date'   => $validated['due_date'],
            'currency'   => $validated['currency'],
            'notes'      => $validated['notes'] ?? null,
            'status'     => 'draft',
            'total'      => 0,
        ]);

        $total = 0;
        foreach ($validated['items'] as $item) {
            $itemTotal = $item['quantity'] * $item['unit_price'];
            $total += $itemTotal;

            $invoice->items()->create([
                'description' => $item['description'],
                'quantity'    => $item['quantity'],
                'unit_price'  => $item['unit_price'],
                'total'       => $itemTotal,
                'product_id'  => $item['product_id'] ?? null,
            ]);
        }

        $invoice->update(['total' => $total]);

        return redirect()->route('invoices.show', $invoice)->with('success', 'Factură creată cu succes!');
    }

    public function show(Invoice $invoice)
    {
        if ($invoice->user_id !== auth()->id()) {
            abort(403);
        }
        $invoice->load('client', 'items');
        return view('invoices.show', compact('invoice'));
    }

        public function edit(Invoice $invoice)
    {
        $invoice->load('client', 'items');
        $clients = auth()->user()->clients()->where('status', 'active')->get();
        $products = auth()->user()->products()->get();
        return view('invoices.edit', compact('invoice', 'clients', 'products'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $validated = $request->validate([
            'client_id'  => 'required|exists:clients,id',
            'issue_date' => 'required|date',
            'due_date'   => 'required|date|after_or_equal:issue_date',
            'currency'   => 'required|in:RON,EUR',
            'notes'      => 'nullable|string|max:1000',
            'items'      => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity'    => 'required|numeric|min:0.01',
            'items.*.unit_price'  => 'required|numeric|min:0',
        ]);

        $invoice->update([
            'client_id'  => $validated['client_id'],
            'issue_date' => $validated['issue_date'],
            'due_date'   => $validated['due_date'],
            'currency'   => $validated['currency'],
            'notes'      => $validated['notes'] ?? null,
        ]);

        // Stergem item-urile vechi si le recreem
        $invoice->items()->delete();

        $total = 0;
        foreach ($validated['items'] as $item) {
            $itemTotal = $item['quantity'] * $item['unit_price'];
            $total += $itemTotal;

            $invoice->items()->create([
                'description' => $item['description'],
                'quantity'    => $item['quantity'],
                'unit_price'  => $item['unit_price'],
                'total'       => $itemTotal,
                'product_id'  => $item['product_id'] ?? null,
            ]);
        }

        $invoice->update(['total' => $total]);

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Factură actualizată cu succes!');
    }

    public function destroy(Invoice $invoice)
    {
        if ($invoice->user_id !== auth()->id()) {
            abort(403);
        }
        $invoice->items()->delete();
        $invoice->delete();
        return redirect()->route('invoices.index')->with('success', 'Factură ștearsă!');
    }

    public function markAsPaid(Invoice $invoice)
    {
        if ($invoice->user_id !== auth()->id()) {
            abort(403);
        }
        $invoice->update(['status' => 'paid']);
        return redirect()->back()->with('success', 'Factura marcată ca încasată!');
    }

    private function generateInvoiceNumber(): string
    {
        $year = date('Y');
        $count = auth()->user()->invoices()
            ->whereYear('created_at', $year)
            ->count();
        return 'CASH-' . $year . '-' . str_pad($count + 1, 3, '0', STR_PAD_LEFT);
    }

    public function downloadPdf(Invoice $invoice, PdfService $pdfService)
    {
        if ($invoice->user_id !== auth()->id()) {
            abort(403);
        }

        $pdf = $pdfService->generateInvoicePdf($invoice);

        return $pdf->download('factura-' . $invoice->number . '.pdf');
    }

}
