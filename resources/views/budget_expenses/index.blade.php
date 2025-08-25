@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Gastos de Presupuesto</h1>
            <a href="{{ route('budget-expenses.create') }}" class="btn btn-sm btn-primary shadow-sm">
                <i class="fas fa-plus fa-sm text-white-50"></i> Nuevo Gasto
            </a>
        </div>

        @include('flash::message')

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Listado de Gastos</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Proyecto</th>
                                <th>Último Gasto</th>
                                <th>Descripción</th>
                                <th>Fecha</th>
                                <th>Total Gastos</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($projects as $project)
                                <tr>
                                    <td>{{ $project->id }}</td>
                                    <td>
                                        <a href="{{ route('projects.show', $project->id) }}">
                                            {{ $project->project_name }}
                                        </a>
                                    </td>
                                    <td>${{ number_format($project->last_expense_amount, 2) }}</td>
                                    <td>{{ Str::limit($project->last_expense_description ?? 'Sin descripción', 50) }}</td>
                                    <td>{{ $project->last_expense_date ? date('d/m/Y', strtotime($project->last_expense_date)) : 'N/A' }}</td>
                                    <td>${{ number_format($project->total_expenses ?? 0, 2) }}</td>
                                    <td>
                                        <div class="btn-group">
                                            <a href="{{ route('projects.show', $project->id) }}" 
                                               class="btn btn-sm btn-primary">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('budget-expenses.create', ['project_id' => $project->id]) }}" 
                                               class="btn btn-sm btn-success">
                                                <i class="fas fa-plus"></i> Nuevo Gasto
                                            </a>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center">
                    {{ $projects->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection

@push('page_scripts')
<script>
    $(document).ready(function() {
        $('#dataTable').DataTable({
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.24/i18n/Spanish.json"
            },
            "pageLength": 15,
            "order": [[ 5, "desc" ]],
            "columnDefs": [
                { "orderable": false, "targets": 6 }
            ]
        });
    });
</script>
@endpush
