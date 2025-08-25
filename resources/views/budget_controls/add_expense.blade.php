@extends('layouts.app')

@section('title')
    Agregar Gasto
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h4>Agregar Gasto a Presupuesto: {{ $budgetControl->project->name }}</h4>
            </div>
            <div class="card-body">
                @include('flash::message')
                @include('adminlte-templates::common.errors')

                {!! Form::open(['route' => ['budget-controls.store-expense', $budgetControl->id], 'files' => true]) !!}
                <div class="row">
                    <div class="col-md-6">
                        <div class="card bg-light mb-3">
                            <div class="card-header">Información del Presupuesto</div>
                            <div class="card-body">
                                <p><strong>Proyecto:</strong> {{ $budgetControl->project->name }}</p>
                                <p><strong>Presupuesto Total:</strong> ${{ number_format($budgetControl->total_budget, 2) }}</p>
                                <p><strong>Gastado Actual:</strong> ${{ number_format($budgetControl->current_spent, 2) }}</p>
                                <p><strong>Restante:</strong> ${{ number_format($budgetControl->remaining_budget, 2) }}</p>
                                <div class="progress mt-2" style="height: 20px;">
                                    <div class="progress-bar {{ $budgetControl->percentage_spent > 90 ? 'bg-danger' : ($budgetControl->percentage_spent > 75 ? 'bg-warning' : 'bg-success') }}" 
                                        role="progressbar" 
                                        style="width: {{ $budgetControl->percentage_spent }}%;" 
                                        aria-valuenow="{{ $budgetControl->percentage_spent }}" 
                                        aria-valuemin="0" 
                                        aria-valuemax="100">
                                        {{ $budgetControl->percentage_spent }}%
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            {!! Form::label('amount', 'Monto:') !!}
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text">$</span>
                                </div>
                                {!! Form::number('amount', null, ['class' => 'form-control', 'step' => '0.01', 'min' => '0.01', 'required', 'placeholder' => '0.00']) !!}
                            </div>
                        </div>

                        <div class="form-group">
                            {!! Form::label('description', 'Descripción:') !!}
                            {!! Form::text('description', null, ['class' => 'form-control', 'required', 'placeholder' => 'Ej: Compra de materiales']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('category_id', 'Categoría:') !!}
                            {!! Form::select('category_id', $categories, null, ['class' => 'form-control select2', 'placeholder' => 'Seleccione una categoría', 'required']) !!}
                        </div>

                        <div class="form-group">
                            {!! Form::label('expense_date', 'Fecha del Gasto:') !!}
                            {!! Form::date('expense_date', \Carbon\Carbon::now(), ['class' => 'form-control']) !!}
                        </div>
                    </div>

                    <div class="col-md-12">
                        <div class="form-group">
                            {!! Form::label('receipt', 'Recibo o Factura:') !!}
                            <div class="custom-file">
                                {!! Form::file('receipt', ['class' => 'custom-file-input', 'id' => 'receipt']) !!}
                                <label class="custom-file-label" for="receipt">Seleccionar archivo</label>
                            </div>
                            <small class="form-text text-muted">Formatos permitidos: JPG, PNG, PDF. Tamaño máximo: 2MB</small>
                        </div>

                        <div class="form-group">
                            {!! Form::label('notes', 'Notas Adicionales:') !!}
                            {!! Form::textarea('notes', null, ['class' => 'form-control', 'rows' => 3]) !!}
                        </div>
                    </div>
                </div>

                <div class="alert alert-warning mt-3">
                    <i class="fas fa-exclamation-triangle"></i> 
                    <strong>Importante:</strong> Al registrar este gasto, se actualizará automáticamente el presupuesto restante del proyecto.
                    @if($budgetControl->remaining_budget < 1000)
                        <br><strong>¡Atención!</strong> El presupuesto restante es bajo (${{ number_format($budgetControl->remaining_budget, 2) }}).
                    @endif
                </div>

                <div class="form-group mt-3">
                    <a href="{{ route('budget-controls.show', $budgetControl->id) }}" class="btn btn-secondary">Cancelar</a>
                    {!! Form::submit('Registrar Gasto', ['class' => 'btn btn-success']) !!}
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.select2').select2({
                theme: 'bootstrap4',
                width: '100%'
            });
            
            // Show file name when selected
            $('.custom-file-input').on('change', function() {
                var fileName = $(this).val().split('\\').pop();
                $(this).next('.custom-file-label').addClass("selected").html(fileName);
            });
        });
    </script>
@endpush
