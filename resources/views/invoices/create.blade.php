@extends('layouts.app')
@section('title')
    {{ __('messages.invoice.invoice') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/select2.min.css') }}">
    <link href="{{ asset('css/bootstrap-datetimepicker.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('assets/css/@fortawesome/fontawesome-free/css/all.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ mix('assets/css/invoices/invoices.css') }}">
@endsection
@section('content')
    <section class="section">
        <div class="section-header">
            <h1>{{ __('messages.invoice.new_invoice') }}</h1>
            <div class="section-header-breadcrumb">
                <a href="{{ url()->previous() }}" class="btn btn-primary form-btn float-right-mobile">
                    {{ __('messages.common.back') }}
                </a>
            </div>
        </div>
        <div class="section-body">
            @include('layouts.errors')
            <div class="card">
                {{ Form::open(['route' => 'invoices.store', 'validated' => false, 'id' => 'invoiceForm']) }}
                @include('invoices.address_modal')
                @include('invoices.fields')
                {{ Form::close() }}
            </div>
        </div>
    </section>
    @include('invoices.templates.templates')
    @include('tags.common_tag_modal')
    @include('payment_modes.common_payment_mode')
@endsection
@section('page_scripts')
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ mix('assets/js/custom/custom-datatable.js') }}"></script>
    <script src="{{ mix('assets/js/bs4-summernote/summernote-bs4.js') }}"></script>
    <script src="{{ asset('js/bootstrap-datetimepicker.min.js') }}"></script>
    <script src="{{ mix('assets/js/select2.min.js') }}"></script>
@endsection
@section('scripts')
    <script>
        let taxData = JSON.parse('@json($data['taxes'])');
        let invoiceUrl = "{{ route('invoices.index') }}";
        let isCreate = true;
        let createData = true;
        let createInvoiceAddress = true;
        let customerURL = "{{ route('get.customer.address') }}";
        let editData = false;
     </script>
    <script src="{{ mix('assets/js/custom/input-price-format.js') }}"></script>
    <script src="{{ mix('assets/js/sales/sales.js') }}"></script>
    <script src="{{ mix('assets/js/invoices/invoices.js') }}"></script>
@endsection
