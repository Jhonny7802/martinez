@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Crear Nuevo Gasto</h1>
            <a href="{{ route('budget-expenses.index') }}" class="btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Volver
            </a>
        </div>

        @include('flash::message')

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Formulario de Gasto</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('budget-expenses.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="budget_control_id">Proyecto/Presupuesto:</label>
                                <select name="project_id" id="project_id" class="form-control @error('project_id') is-invalid @enderror" required>
                                    <option value="">Seleccione un proyecto</option>
                                    @foreach($projects as $id => $name)
                                        <option value="{{ $id }}" {{ old('project_id', $selectedProjectId) == $id ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('project_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="category_id">Categoría:</label>
                                <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                                    <option value="">Seleccione una categoría</option>
                                    @foreach($categories as $id => $name)
                                        <option value="{{ $id }}" {{ old('category_id') == $id ? 'selected' : '' }}>
                                            {{ $name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('category_id')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="amount">Monto ($):</label>
                                <input type="number" name="amount" id="amount" class="form-control @error('amount') is-invalid @enderror" 
                                       value="{{ old('amount') }}" step="0.01" min="0.01" required>
                                @error('amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="expense_date">Fecha del Gasto:</label>
                                <input type="date" name="expense_date" id="expense_date" class="form-control @error('expense_date') is-invalid @enderror" 
                                       value="{{ old('expense_date', date('Y-m-d')) }}">
                                @error('expense_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Descripción:</label>
                        <input type="text" name="description" id="description" class="form-control @error('description') is-invalid @enderror" 
                               value="{{ old('description') }}" required>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="receipt">Recibo/Factura (opcional):</label>
                        <input type="file" name="receipt" id="receipt" class="form-control-file @error('receipt') is-invalid @enderror">
                        <small class="text-muted">Formatos permitidos: JPG, PNG, PDF (máx. 2MB)</small>
                        @error('receipt')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="notes">Notas adicionales (opcional):</label>
                        <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes') }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Guardar Gasto
                        </button>
                        <a href="{{ route('budget-expenses.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
