<div class="row">
    <input type="hidden" name="group" value="{{\App\Models\Setting::COMPANY_INFORMATION}}">
    <div class="form-group col-sm-6">
        {{ Form::label('company', __('messages.company.company').':') }}<span
                class="required">*</span>
        {{ Form::text('company', $settings['company'], ['class' => 'form-control','id' => 'companyNameId', 'required','placeholder'=> __('messages.company.company')]) }}
    </div>
    <div class="form-group col-sm-6">
        {{ Form::label('website', __('messages.company.website').':') }}<span
                class="required">*</span>
        {{ Form::text('website', $settings['website'], ['class' => 'form-control', 'required','autocomplete' => 'off','placeholder'=>__('messages.company.website')]) }}
    </div>
</div>
<div class="row">
    <div class="form-group col-sm-6">
        {{ Form::label('vat_number', __('messages.company.vat_number').':') }}
        {{ Form::text('vat_number', $settings['vat_number'], ['class' => 'form-control', 'minLength' => '4', 'maxLength' => '15','placeholder'=>__('messages.company.vat_number')]) }}
    </div>
    <div class="form-group col-sm-6">
        {{ Form::label('current_currency', __('messages.company.currency').':') }}<span class="required">*</span>
        <select id="mySelect" data-show-content="true" class="form-control" name="current_currency" required>
            @foreach(\App\Models\Setting::CURRENCIES as $key => $currency)
                <option data-content="<i class='{{getCurrencyClass($key)}} text-black-50'></i> {{$currency}}"
                        value="{{$key}}" {{getCurrentCurrency() == $key ? 'selected' : ''}}>
                    &#{{ getCurrencyForPDF($key) }}  {{$currency}}
                </option>
            @endforeach
        </select>
    </div>
</div>
<div class="row">
    <div class="form-group col-sm-6">
        {{ Form::label('city',__('messages.company.city').':') }}
        {{ Form::text('city', $settings['city'], ['class' => 'form-control','placeholder'=> __('messages.company.city')]) }}
    </div>
    <div class="form-group col-sm-6">
        {{ Form::label('state', __('messages.company.state').':') }}
        {{ Form::text('state', $settings['state'], ['class' => 'form-control','placeholder'=>__('messages.company.state')]) }}
    </div>
</div>
<div class="row">
    <div class="form-group col-sm-6">
        {{ Form::label('country_code', __('messages.company.country_code').':') }}
        {{ Form::text('country_code', $settings['country_code'], ['class' => 'form-control','placeholder'=>__('messages.company.country_code')]) }}
    </div>
    <div class="form-group col-sm-6">
        {{ Form::label('zip_code', __('messages.company.zip_code').':') }}
        {{ Form::text('zip_code', $settings['zip_code'], ['class' => 'form-control', 'onkeyup' => 'if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,"")','maxLength' => '6','minLength' => '6','placeholder'=> __('messages.company.zip_code')]) }}
    </div>
</div>
<div class="row">
    <div class="form-group col-sm-6">
        {{ Form::label('phone', __('messages.company.phone').(':')) }}<br>
        {{ Form::tel('phone', $settings['phone'], ['class' => 'form-control','id' => 'phoneNumber', 'onkeyup' => 'if (/\D/g.test(this.value)) this.value = this.value.replace(/\D/g,"")']) }}
        {{ Form::hidden('prefix_code',old('prefix_code'),['id'=>'prefix_code']) }}
        <span id="valid-msg" class="hide">{{ __('messages.placeholder.valid_number') }}</span>
        <span id="error-msg" class="hide"></span>
    </div>
</div>
<div class="row">
    <div class="form-group col-sm-12">
        {{ Form::label('address', __('messages.company.address').':') }}<span
                class="required">*</span>
        {{ Form::textarea('address', $settings['address'], ['class' => 'form-control textarea-sizing', 'id' => 'addressId', 'required', 'rows' => '5','placeholder'=>__('messages.company.address')]) }}
    </div>
</div>
<div class="row">
    <!-- Submit Field -->
    <div class="form-group col-sm-12">
        {{ Form::submit(__('messages.common.save'), ['class' => 'btn btn-primary']) }}
    </div>
</div>
