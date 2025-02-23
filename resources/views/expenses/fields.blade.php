<div class="row">
    <div class="form-group col-sm-4">
        {{ Form::label('name', __('messages.expense.name').':') }}<span class="required">*</span>
        {{ Form::text('name', null, ['class' => 'form-control','required','autocomplete' => 'off','placeholder'=> __('messages.expense.name')])}}
    </div>
    <div class="form-group col-sm-4">
        {{ Form::label('expense_category_id', __('messages.expense.expense_category').':') }}<span
                class="required">*</span>
        <div class="input-group">
            {{ Form::select('expense_category_id', $data['expenseCategories'],null, ['id'=>'expenseCategory','class' => 'form-control','placeholder' => __('messages.placeholder.select_expanse_category'),'required','placeholder'=> __('messages.placeholder.select_expanse_category')]) }}
            <div class="input-group-append plus-icon-height">
                <div class="input-group-text">
                    <a href="#" data-toggle="modal" data-target="#addExpenseCategoryModal"><i
                                class="fa fa-plus"></i></a>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group col-sm-4">
        {{ Form::label('customer_id', __('messages.expense.customer').':') }}
        {{ Form::select('customer_id', $data['customers'], isset($customerId) ? $customerId : null, ['id'=>'customers','class' => 'form-control','placeholder' => __('messages.placeholder.select_customer')]) }}
    </div>
    <div class="form-group col-sm-8 col-lg-4 col-md-8">
        {{ Form::label('expense_date', __('messages.expense.expense_date').':') }}<span class="required">*</span>
        <div class="input-group">
            <div class="input-group-prepend">
                <div class="input-group-text">
                    <i class="fas fa-calendar-alt"></i>
                </div>
            </div>
            {{ Form::text('expense_date', null, ['class' => 'form-control datepicker','required','placeholder'=>__('messages.expense.expense_date')]) }}
        </div>
    </div>
    <div class="form-group col-sm-4">
        {{ Form::label('amount', __('messages.expense.amount').':') }}<span class="required">*</span>
        <div class="input-group">
            <div class="input-group-prepend">
                <div class="input-group-text">
                    <i class="{{getCurrencyClass()}}"></i>
                </div>
            </div>
            {{ Form::text('amount', null, ['class' => 'form-control price-input', 'id' => 'amount','required','autocomplete' => 'off','placeholder'=>__('messages.expense.amount')]) }}
        </div>
    </div>
    <div class="form-group col-lg-3 col-md-6 col-sm-12 mb-3">
        <div class="row no-gutters">
            <div class="col-6">
                {{ Form::label('receipt_attachment', __('messages.expense.attachment').':',['class' => 'profile-label-color'] ) }}
                <label class="image__file-upload"> {{ __('messages.setting.choose') }}
                    {{ Form::file('receipt_attachment',['id'=>'attachment','class' => 'd-none']) }}
                </label>
            </div>
            <div class="col-2 mt-1">
                <img id='previewImage' class="img-thumbnail thumbnail-preview tPreview"
                     src="{{ asset('assets/img/infyom-logo.png') }}"/>
            </div>
        </div>
    </div>
    <div class="form-group col-sm-12">
        {{ Form::label('note', __('messages.expense.note').':') }}
        {{ Form::textarea('note', null, ['class' => 'form-control summernote-simple','id' => 'expenseNote']) }}
    </div>
</div>
<hr>
<div class="row">
    <div class="form-group col-sm-4">
        {{ Form::label('currency', __('messages.expense.currency').':') }}<span class="required">*</span>
        <select id="currency" data-show-content="true" class="form-control" name="currency" required>
            <option value="0" disabled="true" selected="true">{{ __('messages.placeholder.select_currency') }}</option>
            @foreach($data['currencies'] as $key => $currency)
                <option value="{{$key}}">
                    &#{{getCurrencyIcon($key)}}&nbsp;&nbsp;&nbsp; {{$currency}}
                </option>
            @endforeach
        </select>
    </div>
    <div class="form-group col-sm-4">
        {{ Form::label('payment_mode_id', __('messages.expense.payment_mode').':') }}
        <div class="input-group">
            {{ Form::select('payment_mode_id', $data['paymentModes'],null, ['id'=>'paymentMode','class' => 'form-control','placeholder' =>__('messages.placeholder.select_payment_mode')]) }}
            <div class="input-group-append plus-icon-height">
                <div class="input-group-text">
                    <a href="#" data-toggle="modal" data-target="#addCommonPaymentModeModal"><i class="fa fa-plus"></i></a>
                </div>
            </div>
        </div>
    </div>
    <div class="form-group col-sm-4">
        {{ Form::label('reference', __('messages.expense.reference').':') }}
        {{ Form::text('reference', null, ['class' => 'form-control','autocomplete' => 'off','placeholder'=>__('messages.expense.reference')]) }}
    </div>
    <div class="form-group col-sm-2">
        {{ Form::label('tax_1_id', __('messages.expense.tax_1').':') }}
        {{ Form::select('tax_1_id', $data['taxRates'],null, ['id'=>'tax1','class' => 'form-control','placeholder' =>__('messages.placeholder.select_tax1')]) }}
    </div>
    <div class="form-group col-sm-2">
        {{ Form::label('tax_2_id', __('messages.expense.tax_2').':') }}
        {{ Form::select('tax_2_id', $data['taxRates'],null, ['id'=>'tax2','class' => 'form-control','placeholder' => __('messages.placeholder.select_tax2')]) }}
    </div>
    <div class="form-group col-sm-8 d-none" id="isTaxApplied">
        {{ Form::label('tax_applied', __('messages.expense.apply_tax').':') }}
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="taxApplied" name="tax_applied">
            <label class="custom-control-label" for="taxApplied">{{ __('messages.expense.apply_tax_message') }}</label>
            (<span class="font-weight-bold" id="taxAmount"></span>)
        </div>
        <input type="hidden" name="tax_rate" id="taxRate">
    </div>
</div>
<div class="row">
    <div class="form-group col-sm-2">
        <div class="custom-control custom-checkbox">
            <input type="checkbox" class="custom-control-input" id="billable" name="billable" value="1">
            <label class="custom-control-label"
                   for="billable">{{__('messages.expense.billable')}}</label>
        </div>
    </div>
    <div class="col-sm-12">
        {{ Form::button(__('messages.common.save'), ['type'=>'submit','class' => 'btn btn-primary', 'id' => 'btnSave','data-loading-text'=>"<span class='spinner-border spinner-border-sm'></span> Processing..."]) }}
        <a href="{{ route('expenses.index') }}"
           class="btn btn-secondary text-dark">{{ __('messages.common.cancel') }}</a>
    </div>
</div>
