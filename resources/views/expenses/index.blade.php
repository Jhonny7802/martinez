@extends('layouts.app')
@section('title')
    {{ __('messages.expenses') }}
@endsection
@section('page_css')
    <link rel="stylesheet" href="{{ mix('assets/css/expenses/expense.css') }}">
@endsection
@section('css')
    @livewireStyles
@endsection
@section('content')
    <section class="section">
        <div class="section-header expense-mbl-sec-hdr">
            <h1>{{ __('messages.expenses') }}</h1>
            <div class="section-header-breadcrumb">
                <div class="section-header-action mr-2 float-right">
                    {{ Form::select('expense_category',$expenseCategory,null,['id' => 'expenseCategory','class' => 'form-control','placeholder' => __('messages.placeholder.select_expanse_category')]) }}
                </div>
            </div>
            <div class="float-right">
                <a href="{{ route('expenses.expenseCategoryChart') }}"
                   class="btn btn-warning form-btn mr-2">{{ __('messages.expense.expense_category_by_chart') }}
                </a>
            </div>
            <div class="float-right">
                <a href="{{ route('expenses.create') }}"
                   class="btn btn-primary form-btn">{{ __('messages.common.add') }}
                    <i class="fas fa-plus"></i>
                </a>
            </div>
        </div>
        <div class="section-body">
            @include('flash::message')
            <div class="card">
                <div class="card-body">
                    @livewire('expenses')
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
    <script src="{{ asset('vendor/livewire/livewire.js') }}"></script>
    @include('livewire.livewire-turbo')
    <script>
        let expenseUrl = "{{ route('expenses.index') }}";
        let downloadAttachmentUrl = "{{ url('admin/expense-attachment-download') }}";
    </script>
    <script src="{{ mix('assets/js/expenses/expenses.js') }}"></script>
@endsection
