<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\Invoice;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use App\Services\PdfService;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class InvoiceController extends Controller
{
    public function index(Request $request)
    {
        $query = auth()->user()->invoices()->with('client');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('client_id')) {
            $query->where('client_id', $request->client_id);
        }

        if ($request->filled('an')) {
            $query->whereYear('issue_date', $request->an);
        }

        $invoices = $query->latest()->paginate(15)->withQueryString();

        $clients = auth()->user()->clients()->orderBy('name')->get();
        $ani = auth()->user()->invoices()
            ->selectRaw('YEAR(issue_date) as an')
            ->distinct()
            ->orderByDesc('an')
            ->pluck('an');

        return view('invoices.index', compact('invoices', 'clients', 'ani'));
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
            'client_id'  => ['required', Rule::exists('clients', 'id')->where('user_id', auth()->id())],
            'number'     => 'required|string|unique:invoices,number',
            'issue_date' => 'required|date',
            'due_date'   => 'required|date|after_or_equal:issue_date',
            'currency'   => 'required|in:RON,EUR',
            'vat_rate'   => 'nullable|numeric|in:5,11,19,21',
            'notes'      => 'nullable|string|max:1000',
            'items'      => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity'    => 'required|numeric|min:0.01',
            'items.*.unit_price'  => 'required|numeric|min:0',
            'items.*.product_id'  => ['nullable', Rule::exists('products', 'id')->where('user_id', auth()->id())],
        ]);

        $invoice = DB::transaction(function () use ($validated) {
            $invoice = auth()->user()->invoices()->create([
                'client_id'      => $validated['client_id'],
                'number'         => $validated['number'],
                'issue_date'     => $validated['issue_date'],
                'due_date'       => $validated['due_date'],
                'currency'       => $validated['currency'],
                'vat_rate'       => $validated['vat_rate'] ?? null,
                'notes'          => $validated['notes'] ?? null,
                'status'         => 'draft',
                'total'          => 0,
                'vat_amount'     => 0,
                'total_with_vat' => 0,
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

            $vatRate   = $validated['vat_rate'] ?? null;
            $vatAmount = $vatRate ? round($total * $vatRate / 100, 2) : 0;

            $invoice->update([
                'total'          => $total,
                'vat_amount'     => $vatAmount,
                'total_with_vat' => $total + $vatAmount,
            ]);

            return $invoice;
        });

        return redirect()->route('invoices.show', $invoice)->with('success', 'Factură creată cu succes!');
    }

    public function show(Invoice $invoice)
    {
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
            'client_id'  => ['required', Rule::exists('clients', 'id')->where('user_id', auth()->id())],
            'issue_date' => 'required|date',
            'due_date'   => 'required|date|after_or_equal:issue_date',
            'currency'   => 'required|in:RON,EUR',
            'vat_rate'   => 'nullable|numeric|in:5,11,19,21',
            'notes'      => 'nullable|string|max:1000',
            'items'      => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity'    => 'required|numeric|min:0.01',
            'items.*.unit_price'  => 'required|numeric|min:0',
            'items.*.product_id'  => ['nullable', Rule::exists('products', 'id')->where('user_id', auth()->id())],
        ]);

        DB::transaction(function () use ($validated, $invoice) {
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

            $vatRate   = $validated['vat_rate'] ?? null;
            $vatAmount = $vatRate ? round($total * $vatRate / 100, 2) : 0;

            $invoice->update([
                'client_id'      => $validated['client_id'],
                'issue_date'     => $validated['issue_date'],
                'due_date'       => $validated['due_date'],
                'currency'       => $validated['currency'],
                'vat_rate'       => $vatRate,
                'notes'          => $validated['notes'] ?? null,
                'total'          => $total,
                'vat_amount'     => $vatAmount,
                'total_with_vat' => $total + $vatAmount,
            ]);
        });

        return redirect()->route('invoices.show', $invoice)
            ->with('success', 'Factură actualizată cu succes!');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->items()->delete();
        $invoice->delete();
        return redirect()->route('invoices.index')->with('success', 'Factură ștearsă!');
    }

    public function markAsSent(Invoice $invoice)
    {
        abort_if($invoice->status !== 'draft', 403);
        $invoice->update(['status' => 'sent']);
        return redirect()->back()->with('success', 'Factura marcată ca trimisă!');
    }

    public function markAsPaid(Invoice $invoice)
    {
        abort_if($invoice->status === 'paid', 403);
        $invoice->update(['status' => 'paid']);
        return redirect()->back()->with('success', 'Factura marcată ca încasată!');
    }

    private function generateInvoiceNumber(): string
    {
        $year = date('Y');
        $prefix = 'CASH-' . $year . '-';
        $last = Invoice::where('user_id', Auth::id())
            ->whereYear('created_at', $year)
            ->where('number', 'like', $prefix . '%')
            ->max('number');

        $next = $last ? (int) substr($last, strlen($prefix)) + 1 : 1;
        return $prefix . str_pad($next, 3, '0', STR_PAD_LEFT);
    }

    public function downloadPdf(Invoice $invoice, PdfService $pdfService)
    {
        $pdf = $pdfService->generateInvoicePdf($invoice);
        return $pdf->download('factura-' . $invoice->number . '.pdf');
    }

    public function exportCsv()
    {
        $invoices = Invoice::where('user_id', Auth::id())->with('client')->latest()->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Facturi');

        $headers = ['Număr', 'Client', 'Data emiterii', 'Scadență', 'Status', 'Subtotal', 'TVA', 'Total cu TVA', 'Monedă'];
        $sheet->fromArray($headers, null, 'A1');

        // Header bold
        $sheet->getStyle('A1:I1')->getFont()->setBold(true);

        $statusMap = [
            'draft'   => 'Draft',
            'sent'    => 'Trimisă',
            'paid'    => 'Încasată',
            'overdue' => 'Restantă',
        ];

        $row = 2;
        foreach ($invoices as $invoice) {
            $sheet->fromArray([
                $invoice->number,
                $invoice->client->name ?? '-',
                $invoice->issue_date->format('d.m.Y'),
                $invoice->due_date ? $invoice->due_date->format('d.m.Y') : '-',
                $statusMap[$invoice->status] ?? $invoice->status,
                (float) $invoice->total,
                (float) $invoice->vat_amount,
                (float) ($invoice->total_with_vat > 0 ? $invoice->total_with_vat : $invoice->total),
                $invoice->currency,
            ], null, 'A' . $row);
            $row++;
        }

        // Auto-fit pe toate coloanele
        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'facturi-' . date('Y-m-d') . '.xlsx';
        $writer   = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
