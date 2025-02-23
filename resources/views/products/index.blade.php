@extends('layouts.app')
@section('title')
    {{ __('messages.products.products') }}
@endsection
@section('page_css')
    <link href="{{ asset('assets/css/jquery.dataTables.min.css') }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/css/select2.min.css') }}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="{{ asset('assets/css/bs4-summernote/summernote-bs4.css') }}">
@endsection
@section('content')
    <section class="section">
        <div class="section-header item-align-right">
            <h1>{{ __('messages.products.products') }}</h1>
            <div class="section-header-breadcrumb float-right">
                <div class="card-header-action mr-3 select2-mobile-margin">
                    {{ Form::select('group', $data['itemGroups'], null, ['id' => 'filter_group', 'class' => 'form-control', 'placeholder' => __('messages.placeholder.select_product_group')]) }}
                </div>
            </div>
            <div class="float-right">
                <a href="#" class="btn btn-primary form-btn" data-toggle="modal"
                   data-target="#addModal">{{ __('messages.common.add') }} <i class="fas fa-plus"></i></a>
            </div>
        </div>
        <div class="section-body">
            <div class="card">
                <div class="card-body">
                    @include('products.table')
                </div>
            </div>
        </div>
    </section>
    @include('products.templates.templates')
    @include('products.add_modal')
    @include('products.edit_modal')
    @include('products.product_group_modal')
    @include('products.edit_product_group_modal')
@endsection
@section('page_scripts')
    <script src="{{ asset('assets/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ mix('assets/js/custom/custom-datatable.js') }}"></script>
    <script src="{{ mix('assets/js/bs4-summernote/summernote-bs4.js') }}"></script>
    <script src="{{ mix('assets/js/select2.min.js')}}"></script>
@endsection
@section('scripts')
    <script src="{{ mix('assets/js/custom/input-price-format.js')}}"></script>
    <script src="{{ mix('assets/js/products/products.js')}}"></script>
@endsection
