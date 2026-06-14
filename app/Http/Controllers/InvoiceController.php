<?php

namespace App\Http\Controllers;

use App\Mail\InvoiceMail;
use App\Models\Invoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use App\Services\PdfService;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class InvoiceController extends Controller
{

    public function index(Request $request)
    {

        $query = auth()->user()->invoices()->with('client');

        if ($request->filled('status')) {
            $statusuriPermise = ['draft', 'sent', 'paid', 'overdue', 'cancelled'];
            if (in_array($request->status, $statusuriPermise)) {
                $query->where('status', $request->status);
            }
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
            'client_id'           => ['required', Rule::exists('clients', 'id')->where('user_id', auth()->id())],
            'number'              => ['required', 'string', Rule::unique('invoices', 'number')->where('user_id', auth()->id())->whereNull('deleted_at')],
            'issue_date'          => 'required|date',
            'due_date'            => 'required|date|after_or_equal:issue_date',
            'currency'            => 'required|in:RON,EUR',
            'vat_rate'            => 'nullable|numeric|in:5,11,19,21',
            'notes'               => 'nullable|string|max:1000',
            'items'               => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity'    => 'required|numeric|min:0.01',
            'items.*.unit_price'  => 'required|numeric|min:0.01',
            'items.*.product_id'  => ['nullable', Rule::exists('products', 'id')->where('user_id', auth()->id())],
        ], [
            'client_id.required'           => 'Te rugăm să selectezi un client.',
            'client_id.exists'             => 'Clientul selectat nu este valid.',
            'number.required'              => 'Numărul facturii este obligatoriu.',
            'number.unique'                => 'Există deja o factură cu acest număr.',
            'issue_date.required'          => 'Data emiterii este obligatorie.',
            'issue_date.date'              => 'Data emiterii nu este validă.',
            'due_date.required'            => 'Scadența este obligatorie.',
            'due_date.date'                => 'Data scadenței nu este validă.',
            'due_date.after_or_equal'      => 'Scadența trebuie să fie după sau egală cu data emiterii.',
            'currency.required'            => 'Moneda este obligatorie.',
            'currency.in'                  => 'Moneda selectată nu este acceptată (RON sau EUR).',
            'vat_rate.in'                  => 'Cota TVA selectată nu este validă.',
            'notes.max'                    => 'Notele nu pot depăși 1000 de caractere.',
            'items.required'               => 'Factura trebuie să conțină cel puțin un produs sau serviciu.',
            'items.*.description.required' => 'Descrierea produsului/serviciului este obligatorie.',
            'items.*.description.max'      => 'Descrierea nu poate depăși 255 de caractere.',
            'items.*.quantity.required'    => 'Cantitatea este obligatorie.',
            'items.*.quantity.min'         => 'Cantitatea trebuie să fie cel puțin 0.01.',
            'items.*.unit_price.required'  => 'Prețul unitar este obligatoriu.',
            'items.*.unit_price.min'       => 'Prețul unitar trebuie să fie cel puțin 0.01.',
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
                $total = $total + $itemTotal;

                $invoice->items()->create([
                    'description' => $item['description'],
                    'quantity'    => $item['quantity'],
                    'unit_price'  => $item['unit_price'],
                    'total'       => $itemTotal,
                    'product_id'  => $item['product_id'] ?? null,
                ]);
            }

            if (isset($validated['vat_rate']) && $validated['vat_rate'] !== null) {
                $vatRate   = $validated['vat_rate'];
                $vatAmount = round($total * $vatRate / 100, 2);
            } else {
                $vatRate   = null;
                $vatAmount = 0;
            }

            $invoice->update([
                'total'          => $total,
                'vat_amount'     => $vatAmount,
                'total_with_vat' => $total + $vatAmount,
            ]);

            return $invoice;
        });

        return redirect()->route('invoices.show', $invoice)->with('success', 'Factura a fost creata cu succes!');
    }

    public function show(Invoice $invoice)
    {
        $this->authorize('view', $invoice);
        $invoice->load('client', 'items');

        $clientEmail = null;
        if ($invoice->client !== null) {
            $clientEmail = $invoice->client->email;
        }

        return view('invoices.show', compact('invoice', 'clientEmail'));
    }

    public function edit(Invoice $invoice)
    {
        $this->authorize('update', $invoice);
        $invoice->load('client', 'items');

        $clients = auth()->user()->clients()
            ->where(function ($q) use ($invoice) {
                $q->where('status', 'active')->orWhere('id', $invoice->client_id);
            })
            ->get();

        $products = auth()->user()->products()->get();

        return view('invoices.edit', compact('invoice', 'clients', 'products'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $this->authorize('update', $invoice);

        $validated = $request->validate([
            'client_id'           => ['required', Rule::exists('clients', 'id')->where('user_id', auth()->id())],
            'issue_date'          => 'required|date',
            'due_date'            => 'required|date|after_or_equal:issue_date',
            'currency'            => 'required|in:RON,EUR',
            'vat_rate'            => 'nullable|numeric|in:5,11,19,21',
            'notes'               => 'nullable|string|max:1000',
            'items'               => 'required|array|min:1',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity'    => 'required|numeric|min:0.01',
            'items.*.unit_price'  => 'required|numeric|min:0.01',
            'items.*.product_id'  => ['nullable', Rule::exists('products', 'id')->where('user_id', auth()->id())],
        ], [
            'client_id.required'           => 'Te rugăm să selectezi un client.',
            'client_id.exists'             => 'Clientul selectat nu este valid.',
            'issue_date.required'          => 'Data emiterii este obligatorie.',
            'issue_date.date'              => 'Data emiterii nu este validă.',
            'due_date.required'            => 'Scadența este obligatorie.',
            'due_date.date'                => 'Data scadenței nu este validă.',
            'due_date.after_or_equal'      => 'Scadența trebuie să fie după sau egală cu data emiterii.',
            'currency.required'            => 'Moneda este obligatorie.',
            'currency.in'                  => 'Moneda selectată nu este acceptată (RON sau EUR).',
            'vat_rate.in'                  => 'Cota TVA selectată nu este validă.',
            'notes.max'                    => 'Notele nu pot depăși 1000 de caractere.',
            'items.required'               => 'Factura trebuie să conțină cel puțin un produs sau serviciu.',
            'items.*.description.required' => 'Descrierea produsului/serviciului este obligatorie.',
            'items.*.description.max'      => 'Descrierea nu poate depăși 255 de caractere.',
            'items.*.quantity.required'    => 'Cantitatea este obligatorie.',
            'items.*.quantity.min'         => 'Cantitatea trebuie să fie cel puțin 0.01.',
            'items.*.unit_price.required'  => 'Prețul unitar este obligatoriu.',
            'items.*.unit_price.min'       => 'Prețul unitar trebuie să fie cel puțin 0.01.',
        ]);

        DB::transaction(function () use ($validated, $invoice) {

            $invoice->items()->delete();

            $total = 0;
            foreach ($validated['items'] as $item) {
                $itemTotal = $item['quantity'] * $item['unit_price'];
                $total = $total + $itemTotal;

                $invoice->items()->create([
                    'description' => $item['description'],
                    'quantity'    => $item['quantity'],
                    'unit_price'  => $item['unit_price'],
                    'total'       => $itemTotal,
                    'product_id'  => $item['product_id'] ?? null,
                ]);
            }

            if (isset($validated['vat_rate']) && $validated['vat_rate'] !== null) {
                $vatRate   = $validated['vat_rate'];
                $vatAmount = round($total * $vatRate / 100, 2);
            } else {
                $vatRate   = null;
                $vatAmount = 0;
            }

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

        if ($invoice->pdf_path !== null) {
            if (Storage::exists($invoice->pdf_path)) {
                Storage::delete($invoice->pdf_path);
            }
        }
        $invoice->update(['pdf_path' => null]);

        return redirect()->route('invoices.show', $invoice)->with('success', 'Factura a fost actualizata cu succes!');
    }

    public function destroy(Invoice $invoice)
    {
        $this->authorize('delete', $invoice);

        if ($invoice->status === 'paid') {
            abort(403, 'Facturile incasate nu pot fi sterse.');
        }

        $invoice->items()->delete();
        $invoice->delete();

        return redirect()->route('invoices.index')->with('success', 'Factura a fost stearsa!');
    }

    public function markAsSent(Invoice $invoice)
    {
        $this->authorize('markAsSent', $invoice);

        if ($invoice->status !== 'draft') {
            abort(403);
        }

        $invoice->update(['status' => 'sent']);

        return redirect()->back()->with('success', 'Factura a fost marcata ca trimisa!');
    }

    public function markAsPaid(Invoice $invoice)
    {
        $this->authorize('markAsPaid', $invoice);

        if ($invoice->status === 'paid' || $invoice->status === 'cancelled') {
            abort(403);
        }

        $invoice->update(['status' => 'paid']);

        return redirect()->back()->with('success', 'Factura a fost marcata ca incasata!');
    }

    public function markAsCancelled(Invoice $invoice)
    {
        $this->authorize('markAsCancelled', $invoice);

        if ($invoice->status === 'paid') {
            abort(403, 'Facturile incasate nu pot fi anulate.');
        }

        $invoice->update(['status' => 'cancelled']);

        return redirect()->back()->with('success', 'Factura a fost anulata!');
    }

    private function generateInvoiceNumber()
    {
        $anulCurent = date('Y');
        $prefix     = 'CASH-' . $anulCurent . '-';

        $ultimaFactura = Invoice::withoutGlobalScope('user')
            ->where('user_id', Auth::id())
            ->whereYear('issue_date', $anulCurent)
            ->where('number', 'like', $prefix . '%')
            ->lockForUpdate()
            ->max('number');

        if ($ultimaFactura !== null) {
            $numarVechi  = (int) substr($ultimaFactura, strlen($prefix));
            $numarNou    = $numarVechi + 1;
        } else {
            $numarNou = 1;
        }

        return $prefix . str_pad($numarNou, 3, '0', STR_PAD_LEFT);
    }

    public function duplicate(Invoice $invoice)
    {
        $this->authorize('duplicate', $invoice);

        if ($invoice->status === 'paid' || $invoice->status === 'cancelled') {
            abort(403, 'Facturile incasate sau anulate nu pot fi duplicate.');
        }

        $invoice->load('items');

        $newInvoice = DB::transaction(function () use ($invoice) {

            $new = $invoice->replicate(['number', 'status', 'pdf_path']);
            $new->number     = $this->generateInvoiceNumber();
            $new->status     = 'draft';
            $new->issue_date = today();

            if ($invoice->issue_date !== null && $invoice->due_date !== null) {
                $zileDiferenta = $invoice->issue_date->diffInDays($invoice->due_date);
            } else {
                $zileDiferenta = 30;
            }
            $new->due_date = today()->addDays($zileDiferenta);

            $new->save();

            foreach ($invoice->items as $item) {
                $new->items()->create($item->only(['description', 'quantity', 'unit_price', 'total', 'product_id']));
            }

            return $new;
        });

        return redirect()->route('invoices.edit', $newInvoice)
            ->with('success', 'Factura a fost duplicata ca ' . $newInvoice->number . '. Verifica si salveaza.');
    }

    public function sendEmail(Invoice $invoice)
    {
        $this->authorize('sendEmail', $invoice);

        $invoice->load('client', 'items', 'user');

        if ($invoice->client === null) {
            return redirect()->back()->with('error', 'Clientul nu are adresa de email setata.');
        }

        if (empty($invoice->client->email)) {
            return redirect()->back()->with('error', 'Clientul nu are adresa de email setata.');
        }

        Mail::to($invoice->client->email)->send(new InvoiceMail($invoice));

        if ($invoice->status === 'draft' || $invoice->status === 'sent') {
            $invoice->update(['status' => 'sent']);
        }

        return redirect()->back()->with('success', 'Factura a fost trimisa pe email la ' . $invoice->client->email . '.');
    }

    public function downloadPdf(Invoice $invoice, PdfService $pdfService)
    {
        $this->authorize('downloadPdf', $invoice);

        if ($invoice->pdf_path === null || !Storage::exists($invoice->pdf_path)) {
            $path = $pdfService->savePdf($invoice);
            $invoice->update(['pdf_path' => $path]);
        }

        return Storage::download($invoice->pdf_path, 'factura-' . $invoice->number . '.pdf');
    }

    public function exportCsv()
    {

        $invoices = Invoice::where('user_id', Auth::id())->with('client')->latest()->lazy(100);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Facturi');

        $headers = ['Numar', 'Client', 'Data emiterii', 'Scadenta', 'Status', 'Subtotal', 'TVA', 'Total cu TVA', 'Moneda'];
        $sheet->fromArray($headers, null, 'A1');
        $sheet->getStyle('A1:I1')->getFont()->setBold(true);

        $statusMap = [
            'draft'     => 'Draft',
            'sent'      => 'Trimisa',
            'paid'      => 'Incasata',
            'overdue'   => 'Restanta',
            'cancelled' => 'Anulata',
        ];

        $rand = 2;
        foreach ($invoices as $invoice) {

            if ($invoice->client !== null) {
                $numeClient = $invoice->client->name;
            } else {
                $numeClient = '-';
            }

            if ($invoice->due_date !== null) {
                $dataScadenta = $invoice->due_date->format('d.m.Y');
            } else {
                $dataScadenta = '-';
            }

            if (isset($statusMap[$invoice->status])) {
                $statusRo = $statusMap[$invoice->status];
            } else {
                $statusRo = $invoice->status;
            }

            $sheet->fromArray([
                $invoice->number,
                $numeClient,
                $invoice->issue_date->format('d.m.Y'),
                $dataScadenta,
                $statusRo,
                (float) $invoice->total,
                (float) $invoice->vat_amount,
                $invoice->effective_total,
                $invoice->currency,
            ], null, 'A' . $rand);

            $rand++;
        }

        foreach (range('A', 'I') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $numeFisier = 'facturi-' . date('Y-m-d') . '.xlsx';
        $writer     = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $numeFisier, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
