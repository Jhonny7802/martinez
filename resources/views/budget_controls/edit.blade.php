@extends('layouts.app')

@section('title')
    Editar Control de Presupuesto
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h4>Editar Control de Presupuesto</h4>
            </div>
            <div class="card-body">
                @include('flash::message')
                @include('adminlte-templates::common.errors')

                {!! Form::model($budgetControl, ['route' => ['budget-controls.update', $budgetControl->id], 'method' => 'patch']) !!}
                <div class="row">
                    <div class="form-group col-md-6">
                        {!! Form::label('project_id', 'Proyecto:') !!}
                        <input type="text" class="form-control" value="{{ $budgetControl->project->name }}" disabled>
                    </div>

                    <div class="form-group col-md-6">
                        {!! Form::label('total_budget', 'Presupuesto Total:') !!}
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            {!! Form::number('total_budget', null, ['class' => 'form-control', 'step' => '0.01', 'min' => '0', 'required']) !!}
                        </div>
                        <small class="form-text text-muted">Cambiar este valor ajustará automáticamente el presupuesto restante.</small>
                    </div>

                    <div class="form-group col-md-6">
                        {!! Form::label('alert_threshold', 'Umbral de Alerta (%):') !!}
                        <div class="input-group">
                            {!! Form::number('alert_threshold', null, ['class' => 'form-control', 'min' => '1', 'max' => '100', 'required']) !!}
                            <div class="input-group-append">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <small class="form-text text-muted">Porcentaje del presupuesto que al ser alcanzado generará una alerta.</small>
                    </div>

                    <div class="form-group col-md-6">
                        {!! Form::label('current_spent', 'Gastado Actual:') !!}
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            <input type="text" class="form-control" value="{{ number_format($budgetControl->current_spent, 2) }}" disabled>
                        </div>
                        <small class="form-text text-muted">Este valor se actualiza automáticamente con los gastos registrados.</small>
                    </div>

                    <div class="form-group col-md-12">
                        {!! Form::label('notes', 'Notas:') !!}
                        {!! Form::textarea('notes', null, ['class' => 'form-control', 'rows' => 3]) !!}
                    </div>
                </div>

                <div class="card bg-light mt-3">
                    <div class="card-body">
                        <h5>Información de Estado</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>Estado actual:</strong> 
                                    <span class="badge {{ \App\Models\BudgetControl::STATUS_BADGE[$budgetControl->budget_status] }}">
                                        {{ \App\Models\BudgetControl::STATUS_TEXT[$budgetControl->budget_status] }}
                                    </span>
                                </p>
                                <p><strong>Porcentaje utilizado:</strong> {{ $budgetControl->percentage_spent }}%</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>Última actualización:</strong> {{ $budgetControl->last_updated ? $budgetControl->last_updated->format('d/m/Y H:i') : 'N/A' }}</p>
                                <p><strong>Presupuesto restante:</strong> ${{ number_format($budgetControl->remaining_budget, 2) }}</p>
                            </div>
                        </div>
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

                <div class="form-group mt-3">
                    <a href="{{ route('budget-controls.show', $budgetControl->id) }}" class="btn btn-secondary">Cancelar</a>
                    {!! Form::submit('Guardar Cambios', ['class' => 'btn btn-primary']) !!}
                </div>

                {!! Form::close() !!}
            </div>
        </div>
    </div>
@endsection
