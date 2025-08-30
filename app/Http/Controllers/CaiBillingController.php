<?php

namespace App\Http\Controllers;

use App\Models\CaiBilling;
use App\Models\CaiBillingItem;
use App\Models\Customer;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class CaiBillingController extends AppBaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = CaiBilling::with('customer')->orderBy('created_at', 'desc');

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by date range
        if ($request->filled('start_date') && $request->filled('end_date')) {
            $query->whereBetween('issue_date', [$request->start_date, $request->end_date]);
        }

        // Search by CAI number, invoice number, or customer name
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('cai_number', 'like', "%{$search}%")
                  ->orWhere('invoice_number', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%");
            });
        }

        $caiBillings = $query->paginate(15);

        return view('cai_billings.index', compact('caiBillings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::select('id', 'company_name')
                           ->orderBy('company_name')
                           ->get();
        
        $products = Item::with(['firstTax', 'secondTax'])
                          ->select('id', 'title', 'description', 'rate', 'tax_1_id', 'tax_2_id')
                          ->orderBy('title')
                          ->get();

        return view('cai_billings.create', compact('customers', 'products'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string|max:255',
            'customer_rtn' => 'nullable|string|max:20',
            'customer_address' => 'nullable|string',
            'issue_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:issue_date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:items,id',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.tax_rate' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'status' => 'required|in:draft,issued,paid,cancelled'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        try {
            DB::beginTransaction();

            // Create the main billing record
            $caiBilling = new CaiBilling();
            $caiBilling->company_name = $request->company_name;
            $caiBilling->customer_rtn = $request->customer_rtn;
            $caiBilling->customer_address = $request->customer_address;
            $caiBilling->issue_date = $request->issue_date;
            $caiBilling->due_date = $request->due_date;
            $caiBilling->discount_amount = $request->discount_amount ?? 0;
            $caiBilling->notes = $request->notes;
            $caiBilling->status = $request->status;
            
            // Calculate totals from items
            $subtotal = 0;
            $totalTax = 0;
            
            foreach ($request->items as $itemData) {
                $quantity = floatval($itemData['quantity']);
                $unitPrice = floatval($itemData['unit_price']);
                $taxRate = floatval($itemData['tax_rate'] ?? 15);
                
                $itemSubtotal = $quantity * $unitPrice;
                $itemTax = $itemSubtotal * ($taxRate / 100);
                
                $subtotal += $itemSubtotal;
                $totalTax += $itemTax;
            }
            
            $caiBilling->subtotal = $subtotal;
            $caiBilling->tax_amount = $totalTax;
            $caiBilling->total_amount = $subtotal + $totalTax - $caiBilling->discount_amount;
            $caiBilling->save();

            // Create billing items
            foreach ($request->items as $itemData) {
                $quantity = floatval($itemData['quantity']);
                $unitPrice = floatval($itemData['unit_price']);
                $taxRate = floatval($itemData['tax_rate'] ?? 15);
                
                $itemSubtotal = $quantity * $unitPrice;
                $itemTax = $itemSubtotal * ($taxRate / 100);
                $itemTotal = $itemSubtotal + $itemTax;
                
                CaiBillingItem::create([
                    'cai_billing_id' => $caiBilling->id,
                    'product_id' => $itemData['product_id'],
                    'description' => $itemData['description'],
                    'quantity' => $quantity,
                    'unit_price' => $unitPrice,
                    'tax_rate' => $taxRate,
                    'subtotal' => $itemSubtotal,
                    'tax_amount' => $itemTax,
                    'total' => $itemTotal
                ]);
            }

            DB::commit();

            return redirect()->route('cai-billings.show', $caiBilling)
                           ->with('success', 'Factura CAI creada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->with('error', 'Error al crear la factura: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(CaiBilling $caiBilling)
    {
        $caiBilling->load('customer');
        return view('cai_billings.show', compact('caiBilling'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(CaiBilling $caiBilling)
    {
        if ($caiBilling->status === 'paid') {
            return redirect()->route('cai-billings.show', $caiBilling)
                           ->with('error', 'No se puede editar una factura pagada.');
        }

        $customers = Customer::select('id', 'company_name')
                           ->orderBy('company_name')
                           ->get();
        
        $products = Item::with(['firstTax', 'secondTax'])
                          ->select('id', 'title', 'description', 'rate', 'tax_1_id', 'tax_2_id')
                          ->orderBy('title')
                          ->get();

        return view('cai_billings.edit', compact('caiBilling', 'customers', 'products'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, CaiBilling $caiBilling)
    {
        if ($caiBilling->status === 'paid') {
            return redirect()->route('cai-billings.show', $caiBilling)
                           ->with('error', 'No se puede editar una factura pagada.');
        }

        $validator = Validator::make($request->all(), [
            'company_name' => 'required|string|max:255',
            'customer_rtn' => 'nullable|string|max:20',
            'customer_address' => 'nullable|string',
            'issue_date' => 'required|date',
            'due_date' => 'nullable|date|after_or_equal:issue_date',
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:items,id',
            'items.*.description' => 'required|string|max:255',
            'items.*.quantity' => 'required|numeric|min:0.01',
            'items.*.unit_price' => 'required|numeric|min:0',
            'items.*.tax_rate' => 'nullable|numeric|min:0|max:100',
            'discount_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
            'status' => 'required|in:draft,issued,paid,cancelled'
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                           ->withErrors($validator)
                           ->withInput();
        }

        try {
            DB::beginTransaction();

            $caiBilling->fill($request->all());
            $caiBilling->discount_amount = $request->discount_amount ?? 0;
            
            // Recalculate totals
            $caiBilling->calculateTotals();
            $caiBilling->save();

            DB::commit();

            return redirect()->route('cai-billings.show', $caiBilling)
                           ->with('success', 'Factura CAI actualizada exitosamente.');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                           ->with('error', 'Error al actualizar la factura: ' . $e->getMessage())
                           ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(CaiBilling $caiBilling)
    {
        if ($caiBilling->status === 'paid') {
            return redirect()->route('cai-billings.index')
                           ->with('error', 'No se puede eliminar una factura pagada.');
        }

        try {
            $caiBilling->delete();
            return redirect()->route('cai-billings.index')
                           ->with('success', 'Factura CAI eliminada exitosamente.');
        } catch (\Exception $e) {
            return redirect()->route('cai-billings.index')
                           ->with('error', 'Error al eliminar la factura: ' . $e->getMessage());
        }
    }

    /**
     * Change status of the billing
     */
    public function changeStatus(Request $request, CaiBilling $caiBilling)
    {
        $validator = Validator::make($request->all(), [
            'status' => 'required|in:draft,issued,paid,cancelled',
            'payment_method' => 'required_if:status,paid|string|max:100',
            'payment_date' => 'required_if:status,paid|date'
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        try {
            $caiBilling->status = $request->status;
            
            if ($request->status === 'paid') {
                $caiBilling->payment_method = $request->payment_method;
                $caiBilling->payment_date = $request->payment_date;
            }
            
            $caiBilling->save();

            return response()->json([
                'success' => true,
                'message' => 'Estado actualizado exitosamente.',
                'status' => $caiBilling->status_label,
                'color' => $caiBilling->status_color
            ]);

        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al actualizar el estado.'], 500);
        }
    }

    /**
     * Get customer data for form
     */
    public function getCustomerData(Customer $customer)
    {
        return response()->json([
            'name' => $customer->company_name,
            'rtn' => $customer->vat ?? '',
            'address' => $customer->address ?? ''
        ]);
    }

    /**
     * Generate PDF for the billing
     */
    public function generatePdf(CaiBilling $caiBilling)
    {
        $caiBilling->load('customer');
        
        $pdf = app('dompdf.wrapper');
        $pdf->loadView('cai_billings.pdf', compact('caiBilling'));
        
        return $pdf->download('factura-' . $caiBilling->invoice_number . '.pdf');
    }
}
