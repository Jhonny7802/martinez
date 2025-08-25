@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <div class="d-sm-flex justify-content-between align-items-center mb-4">
            <h1 class="h3 mb-0 text-gray-800">Editar Gasto #{{ $expense->id }}</h1>
            <a href="{{ route('budget-expenses.index') }}" class="btn btn-sm btn-secondary shadow-sm">
                <i class="fas fa-arrow-left fa-sm text-white-50"></i> Volver
            </a>
        </div>

        @include('flash::message')

        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Formulario de Edición</h6>
            </div>
            <div class="card-body">
                <form action="{{ route('budget-expenses.update', $expense->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Proyecto/Presupuesto:</label>
                                <input type="text" class="form-control" value="{{ $expense->budgetControl->project->project_name }}" readonly>
                                <small class="text-muted">No se puede cambiar el proyecto una vez creado el gasto.</small>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="category_id">Categoría:</label>
                                <select name="category_id" id="category_id" class="form-control @error('category_id') is-invalid @enderror" required>
                                    <option value="">Seleccione una categoría</option>
                                    @foreach($categories as $id => $name)
                                        <option value="{{ $id }}" {{ old('category_id', $expense->category_id) == $id ? 'selected' : '' }}>
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
                                <label>Monto ($):</label>
                                <input type="text" class="form-control" value="${{ number_format($expense->amount, 2) }}" readonly>
                                <small class="text-muted">No se puede cambiar el monto una vez creado el gasto.</small>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="expense_date">Fecha del Gasto:</label>
                                <input type="date" name="expense_date" id="expense_date" class="form-control @error('expense_date') is-invalid @enderror" 
                                       value="{{ old('expense_date', $expense->expense_date->format('Y-m-d')) }}">
                                @error('expense_date')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Descripción:</label>
                        <input type="text" name="description" id="description" class="form-control @error('description') is-invalid @enderror" 
                               value="{{ old('description', $expense->description) }}" required>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="receipt">Recibo/Factura (opcional):</label>
                        <input type="file" name="receipt" id="receipt" class="form-control-file @error('receipt') is-invalid @enderror">
                        <small class="text-muted">Formatos permitidos: JPG, PNG, PDF (máx. 2MB)</small>
                        @if($expense->receipt_path)
                            <div class="mt-2">
                                <p>Recibo actual: 
                                    <a href="{{ route('budget-expenses.download-media', $expense->id) }}" target="_blank">
                                        <i class="fas fa-download"></i> Descargar recibo
                                    </a>
                                </p>
                            </div>
                        @endif
                        @error('receipt')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group">
                        <label for="notes">Notas adicionales (opcional):</label>
                        <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes', $expense->notes) }}</textarea>
                        @error('notes')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <div class="form-group mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save"></i> Actualizar Gasto
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
