@extends('layouts.app')

@section('title')
    Crear Control de Presupuesto
@endsection

@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h4>Crear Control de Presupuesto</h4>
            </div>
            <div class="card-body">
                @include('flash::message')
                
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <strong>¡Ups! Algo salió mal.</strong>
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {!! Form::open(['route' => 'budget-controls.store']) !!}
                <div class="row">
                    <div class="form-group col-md-6">
                        {!! Form::label('project_id', 'Proyecto:') !!}
                        {!! Form::select('project_id', $projects, null, ['class' => 'form-control select2', 'placeholder' => 'Seleccione un proyecto', 'required']) !!}
                    </div>

                    <div class="form-group col-md-6">
                        {!! Form::label('total_budget', 'Presupuesto Total:') !!}
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text">$</span>
                            </div>
                            {!! Form::number('total_budget', null, ['class' => 'form-control', 'step' => '0.01', 'min' => '0', 'required']) !!}
                        </div>
                    </div>

                    <div class="form-group col-md-6">
                        {!! Form::label('alert_threshold', 'Umbral de Alerta (%):') !!}
                        <div class="input-group">
                            {!! Form::number('alert_threshold', 80, ['class' => 'form-control', 'min' => '1', 'max' => '100', 'required']) !!}
                            <div class="input-group-append">
                                <span class="input-group-text">%</span>
                            </div>
                        </div>
                        <small class="form-text text-muted">Porcentaje del presupuesto que al ser alcanzado generará una alerta.</small>
                    </div>

                    <div class="form-group col-md-12">
                        {!! Form::label('notes', 'Notas:') !!}
                        {!! Form::textarea('notes', null, ['class' => 'form-control', 'rows' => 3]) !!}
                    </div>
                </div>

                <div class="card bg-light mt-3">
                    <div class="card-body">
                        <h5>Información Importante</h5>
                        <ul>
                            <li>El presupuesto se asignará al proyecto seleccionado.</li>
                            <li>Se generarán alertas automáticas cuando el gasto supere el umbral establecido.</li>
                            <li>Podrá agregar gastos y monitorear el presupuesto en tiempo real.</li>
                        </ul>
                    </div>
                </div>

                <div class="form-group mt-3">
                    <a href="{{ route('budget-controls.index') }}" class="btn btn-secondary">Cancelar</a>
                    {!! Form::submit('Guardar', ['class' => 'btn btn-primary']) !!}
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
        });
    </script>
@endpush
