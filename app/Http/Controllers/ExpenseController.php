<?php

namespace App\Http\Controllers;

use App\Models\Expense;
use App\Models\ExpenseCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExpenseController extends Controller
{
    public function index(Request $request)
    {
        $query = auth()->user()->expenses()->with('category');

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('an')) {
            $query->whereYear('date', $request->an);
        }

        $expenses = $query->latest()->paginate(15)->withQueryString();

        $categories = auth()->user()->expenseCategories()->orderBy('name')->get();
        $ani = auth()->user()->expenses()
            ->selectRaw('YEAR(date) as an')
            ->distinct()
            ->orderByDesc('an')
            ->pluck('an');

        return view('expenses.index', compact('expenses', 'categories', 'ani'));
    }

    public function create()
    {
        $categories = auth()->user()->expenseCategories()->get();
        return view('expenses.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'amount'      => 'required|numeric|min:0.01',
            'currency'    => 'required|in:RON,EUR',
            'date'        => 'required|date',
            'category_id' => [
                'nullable',
                Rule::exists('expense_categories', 'id')->where('user_id', auth()->id()),
            ],
            'receipt'     => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
        ]);

        $receiptPath = null;
        if ($request->hasFile('receipt')) {
            $receiptPath = $request->file('receipt')->store('receipts/' . auth()->id(), 'private');
        }

        auth()->user()->expenses()->create(array_merge($validated, ['receipt_path' => $receiptPath]));

        return redirect()->route('expenses.index')->with('success', 'Cheltuială adăugată cu succes!');
    }

    public function show(Expense $expense)
    {
        abort(404);
    }

    public function edit(Expense $expense)
    {
        $this->authorize('update', $expense);
        $categories = auth()->user()->expenseCategories()->get();
        return view('expenses.edit', compact('expense', 'categories'));
    }

    public function update(Request $request, Expense $expense)
    {
        $this->authorize('update', $expense);

        $validated = $request->validate([
            'description'    => 'required|string|max:255',
            'amount'         => 'required|numeric|min:0.01',
            'currency'       => 'required|in:RON,EUR',
            'date'           => 'required|date',
            'category_id'    => [
                'nullable',
                Rule::exists('expense_categories', 'id')->where('user_id', auth()->id()),
            ],
            'receipt'        => 'nullable|file|mimes:jpg,jpeg,png,pdf|max:5120',
            'remove_receipt' => 'nullable|boolean',
        ]);

        if ($request->boolean('remove_receipt') && $expense->receipt_path) {
            Storage::disk('private')->delete($expense->receipt_path);
            $validated['receipt_path'] = null;
        } elseif ($request->hasFile('receipt')) {
            if ($expense->receipt_path) {
                Storage::disk('private')->delete($expense->receipt_path);
            }
            $validated['receipt_path'] = $request->file('receipt')->store('receipts/' . auth()->id(), 'private');
        }

        unset($validated['receipt'], $validated['remove_receipt']);
        $expense->update($validated);

        return redirect()->route('expenses.index')->with('success', 'Cheltuială actualizată!');
    }

    public function destroy(Expense $expense)
    {
        $this->authorize('delete', $expense);
        if ($expense->receipt_path) {
            Storage::disk('private')->delete($expense->receipt_path);
        }
        $expense->delete();
        return redirect()->route('expenses.index')->with('success', 'Cheltuială ștearsă!');
    }

    public function downloadReceipt(Expense $expense)
    {
        $this->authorize('downloadReceipt', $expense);
        abort_if(!$expense->receipt_path, 404);

        return Storage::disk('private')->download($expense->receipt_path);
    }

    public function exportCsv()
    {
        $expenses = Expense::where('user_id', Auth::id())->with('category')->latest()->get();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Cheltuieli');

        $headers = ['Data', 'Categorie', 'Descriere', 'Sumă', 'Monedă'];
        $sheet->fromArray($headers, null, 'A1');

        // Header bold
        $sheet->getStyle('A1:E1')->getFont()->setBold(true);

        $row = 2;
        foreach ($expenses as $expense) {
            $sheet->fromArray([
                $expense->date->format('d.m.Y'),
                $expense->category->name ?? '-',
                $expense->description,
                (float) $expense->amount,
                $expense->currency,
            ], null, 'A' . $row);
            $row++;
        }

        // Auto-fit pe toate coloanele
        foreach (range('A', 'E') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        $filename = 'cheltuieli-' . date('Y-m-d') . '.xlsx';
        $writer   = new Xlsx($spreadsheet);

        return response()->streamDownload(function () use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
