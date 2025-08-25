<?php

namespace App\Http\Controllers;

use App\Models\MaterialRequisition;
use App\Models\MaterialRequisitionItem;
use App\Models\Product;
use App\Models\Project;
use App\Models\User;
use App\Models\InventoryMovement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class MaterialRequisitionController extends AppBaseController
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $requisitions = MaterialRequisition::with(['project', 'requestedBy', 'approvedBy'])
                ->select('material_requisitions.*');

            return DataTables::of($requisitions)
                ->addColumn('project_name', function ($requisition) {
                    return $requisition->project->project_name ?? 'N/A';
                })
                ->addColumn('requested_by_name', function ($requisition) {
                    return $requisition->requestedBy->name ?? 'N/A';
                })
                ->addColumn('status_badge', function ($requisition) {
                    return '<span class="badge bg-' . $requisition->status_color . '">' . $requisition->status_label . '</span>';
                })
                ->addColumn('priority_badge', function ($requisition) {
                    return '<span class="badge bg-' . $requisition->priority_color . '">' . $requisition->priority_label . '</span>';
                })
                ->addColumn('total_cost_formatted', function ($requisition) {
                    return 'L. ' . number_format($requisition->total_cost, 2);
                })
                ->addColumn('actions', function ($requisition) {
                    return view('material_requisitions.actions', compact('requisition'))->render();
                })
                ->rawColumns(['status_badge', 'priority_badge', 'actions'])
                ->make(true);
        }

        return view('material_requisitions.index');
    }

    public function create()
    {
        $projects = Project::where('status', 'active')->get();
        $materials = Product::where('status', 'active')->get();
        
        return view('material_requisitions.create', compact('projects', 'materials'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'required_date' => 'required|date|after_or_equal:today',
            'priority' => 'required|in:low,medium,high,urgent',
            'purpose' => 'nullable|string',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity_requested' => 'required|integer|min:1',
            'items.*.specifications' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $requisition = MaterialRequisition::create([
                'requisition_number' => MaterialRequisition::generateRequisitionNumber(),
                'project_id' => $request->project_id,
                'requested_by' => Auth::id(),
                'status' => MaterialRequisition::STATUS_PENDING,
                'priority' => $request->priority,
                'required_date' => $request->required_date,
                'purpose' => $request->purpose,
                'notes' => $request->notes
            ]);

            foreach ($request->items as $itemData) {
                $product = Product::find($itemData['item_id']);
                MaterialRequisitionItem::create([
                    'requisition_id' => $requisition->id,
                    'item_id' => $itemData['item_id'],
                    'quantity_requested' => $itemData['quantity_requested'],
                    'unit_cost' => $product->cost_price ?? $product->rate,
                    'specifications' => $itemData['specifications'] ?? null
                ]);
            }

            DB::commit();

            activity()->performedOn($requisition)->causedBy(Auth::user())
                ->useLog('Material Requisition Created')
                ->log('Requisition ' . $requisition->requisition_number . ' created');

            return $this->sendSuccess('Requisición de materiales creada exitosamente');

        } catch (\Exception $e) {
            DB::rollback();
            return $this->sendError('Error al crear la requisición: ' . $e->getMessage());
        }
    }

    public function show(MaterialRequisition $materialRequisition)
    {
        $materialRequisition->load(['project', 'requestedBy', 'approvedBy', 'items.item']);
        return view('material_requisitions.show', compact('materialRequisition'));
    }

    public function edit(MaterialRequisition $materialRequisition)
    {
        if ($materialRequisition->status !== MaterialRequisition::STATUS_PENDING) {
            return redirect()->back()->with('error', 'Solo se pueden editar requisiciones pendientes');
        }

        $projects = Project::where('status', 'active')->get();
        $materials = Product::where('status', 'active')->get();
        $materialRequisition->load('items');
        
        return view('material_requisitions.edit', compact('materialRequisition', 'projects', 'materials'));
    }

    public function update(Request $request, MaterialRequisition $materialRequisition)
    {
        if ($materialRequisition->status !== MaterialRequisition::STATUS_PENDING) {
            return $this->sendError('Solo se pueden editar requisiciones pendientes');
        }

        $request->validate([
            'project_id' => 'required|exists:projects,id',
            'required_date' => 'required|date|after_or_equal:today',
            'priority' => 'required|in:low,medium,high,urgent',
            'purpose' => 'nullable|string',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.item_id' => 'required|exists:items,id',
            'items.*.quantity_requested' => 'required|integer|min:1',
            'items.*.specifications' => 'nullable|string'
        ]);

        DB::beginTransaction();
        try {
            $materialRequisition->update([
                'project_id' => $request->project_id,
                'priority' => $request->priority,
                'required_date' => $request->required_date,
                'purpose' => $request->purpose,
                'notes' => $request->notes
            ]);

            // Delete existing items and create new ones
            $materialRequisition->items()->delete();

            foreach ($request->items as $itemData) {
                $product = Product::find($itemData['item_id']);
                MaterialRequisitionItem::create([
                    'requisition_id' => $materialRequisition->id,
                    'item_id' => $itemData['item_id'],
                    'quantity_requested' => $itemData['quantity_requested'],
                    'unit_cost' => $product->cost_price ?? $product->rate,
                    'specifications' => $itemData['specifications'] ?? null
                ]);
            }

            DB::commit();

            activity()->performedOn($materialRequisition)->causedBy(Auth::user())
                ->useLog('Material Requisition Updated')
                ->log('Requisition ' . $materialRequisition->requisition_number . ' updated');

            return $this->sendSuccess('Requisición actualizada exitosamente');

        } catch (\Exception $e) {
            DB::rollback();
            return $this->sendError('Error al actualizar la requisición: ' . $e->getMessage());
        }
    }

    public function approve(Request $request, MaterialRequisition $materialRequisition)
    {
        if ($materialRequisition->status !== MaterialRequisition::STATUS_PENDING) {
            return $this->sendError('Solo se pueden aprobar requisiciones pendientes');
        }

        $request->validate([
            'items' => 'required|array',
            'items.*.quantity_approved' => 'required|integer|min:0'
        ]);

        DB::beginTransaction();
        try {
            foreach ($request->items as $itemId => $itemData) {
                $requisitionItem = MaterialRequisitionItem::where('requisition_id', $materialRequisition->id)
                    ->where('item_id', $itemId)
                    ->first();
                
                if ($requisitionItem) {
                    $requisitionItem->update([
                        'quantity_approved' => $itemData['quantity_approved']
                    ]);
                }
            }

            $materialRequisition->update([
                'status' => MaterialRequisition::STATUS_APPROVED,
                'approved_by' => Auth::id(),
                'approved_at' => now()
            ]);

            $materialRequisition->calculateTotalCost();

            DB::commit();

            activity()->performedOn($materialRequisition)->causedBy(Auth::user())
                ->useLog('Material Requisition Approved')
                ->log('Requisition ' . $materialRequisition->requisition_number . ' approved');

            return $this->sendSuccess('Requisición aprobada exitosamente');

        } catch (\Exception $e) {
            DB::rollback();
            return $this->sendError('Error al aprobar la requisición: ' . $e->getMessage());
        }
    }

    public function deliver(Request $request, MaterialRequisition $materialRequisition)
    {
        if ($materialRequisition->status !== MaterialRequisition::STATUS_APPROVED) {
            return $this->sendError('Solo se pueden entregar requisiciones aprobadas');
        }

        $request->validate([
            'items' => 'required|array',
            'items.*.quantity_delivered' => 'required|integer|min:0'
        ]);

        DB::beginTransaction();
        try {
            $allDelivered = true;

            foreach ($request->items as $itemId => $itemData) {
                $requisitionItem = MaterialRequisitionItem::where('requisition_id', $materialRequisition->id)
                    ->where('item_id', $itemId)
                    ->first();
                
                if ($requisitionItem) {
                    $newDelivered = $requisitionItem->quantity_delivered + $itemData['quantity_delivered'];
                    
                    // Check if we have enough stock
                    $product = Product::find($itemId);
                    if ($product->stock_quantity < $itemData['quantity_delivered']) {
                        throw new \Exception("Stock insuficiente para {$product->title}");
                    }

                    $requisitionItem->update([
                        'quantity_delivered' => $newDelivered
                    ]);

                    // Record inventory movement
                    InventoryMovement::recordMovement(
                        $itemId,
                        InventoryMovement::TYPE_OUT,
                        $itemData['quantity_delivered'],
                        Auth::id(),
                        "Entrega para requisición {$materialRequisition->requisition_number}",
                        $requisitionItem->unit_cost,
                        'requisition',
                        $materialRequisition->id
                    );

                    if ($newDelivered < $requisitionItem->quantity_approved) {
                        $allDelivered = false;
                    }
                }
            }

            if ($allDelivered) {
                $materialRequisition->update([
                    'status' => MaterialRequisition::STATUS_DELIVERED,
                    'delivered_at' => now()
                ]);
            }

            DB::commit();

            activity()->performedOn($materialRequisition)->causedBy(Auth::user())
                ->useLog('Material Requisition Delivered')
                ->log('Materials delivered for requisition ' . $materialRequisition->requisition_number);

            return $this->sendSuccess('Materiales entregados exitosamente');

        } catch (\Exception $e) {
            DB::rollback();
            return $this->sendError('Error al entregar materiales: ' . $e->getMessage());
        }
    }

    public function reject(Request $request, MaterialRequisition $materialRequisition)
    {
        if ($materialRequisition->status !== MaterialRequisition::STATUS_PENDING) {
            return $this->sendError('Solo se pueden rechazar requisiciones pendientes');
        }

        $request->validate([
            'rejection_reason' => 'required|string'
        ]);

        $materialRequisition->update([
            'status' => MaterialRequisition::STATUS_REJECTED,
            'notes' => ($materialRequisition->notes ? $materialRequisition->notes . "\n\n" : '') . 
                      "RECHAZADO: " . $request->rejection_reason
        ]);

        activity()->performedOn($materialRequisition)->causedBy(Auth::user())
            ->useLog('Material Requisition Rejected')
            ->log('Requisition ' . $materialRequisition->requisition_number . ' rejected: ' . $request->rejection_reason);

        return $this->sendSuccess('Requisición rechazada');
    }

    public function destroy(MaterialRequisition $materialRequisition)
    {
        if ($materialRequisition->status === MaterialRequisition::STATUS_DELIVERED) {
            return $this->sendError('No se pueden eliminar requisiciones entregadas');
        }

        activity()->performedOn($materialRequisition)->causedBy(Auth::user())
            ->useLog('Material Requisition Deleted')
            ->log('Requisition ' . $materialRequisition->requisition_number . ' deleted');

        $materialRequisition->delete();

        return $this->sendSuccess('Requisición eliminada exitosamente');
    }

    /**
     * Get requisition items for modals
     */
    public function getItems(MaterialRequisition $materialRequisition)
    {
        $items = $materialRequisition->items()->with('item')->get();
        return response()->json(['items' => $items]);
    }

    /**
     * Get statistics for dashboard
     */
    public function getStats()
    {
        $stats = [
            'pending' => MaterialRequisition::where('status', MaterialRequisition::STATUS_PENDING)->count(),
            'approved' => MaterialRequisition::where('status', MaterialRequisition::STATUS_APPROVED)->count(),
            'delivered' => MaterialRequisition::where('status', MaterialRequisition::STATUS_DELIVERED)->count(),
            'total_month' => MaterialRequisition::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('total_cost')
        ];

        return response()->json($stats);
    }

    /**
     * Get recent requisitions
     */
    public function getRecent()
    {
        $recent = MaterialRequisition::with(['project'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($req) {
                return [
                    'requisition_number' => $req->requisition_number,
                    'project_name' => $req->project->project_name ?? 'N/A',
                    'status_label' => $req->status_label,
                    'status_color' => $req->status_color
                ];
            });

        return response()->json($recent);
    }
}
