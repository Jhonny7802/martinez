<div id="addModal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('messages.products.new_product') }}</h5>
                <button type="button" aria-label="Close" class="close" data-dismiss="modal">×</button>
            </div>
            {{ Form::open(['id' => 'addNewForm']) }}
            <div class="modal-body">
                <div class="alert alert-danger d-none" id="validationErrorsBox"></div>
                <div class="row">
                    <div class="form-group col-sm-12">
                        {{ Form::label('title', __('messages.products.title').':') }}<span class="required">*</span>
                        {{ Form::text('title', null, ['class' => 'form-control', 'required','autocomplete' => 'off','placeholder'=>__('messages.products.title')]) }}
                    </div>
                    <div class="form-group col-sm-12 mb-0">
                        {{ Form::label('description', __('messages.common.description').':') }}
                        {{ Form::textarea('description', null, ['class' => 'form-control textarea-sizing', 'id' => 'productDescription']) }}
                    </div>
                    <div class="form-group col-sm-12 col-lg-6 col-md-12">
                        {{ Form::label('rate ', __('messages.products.rate').':') }}<span class="required">*</span>
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <div class="input-group-text">
                                    <i class="{{getCurrencyClass()}}"></i>
                                </div>
                            </div>
                            {{ Form::text('rate', null, ['class' => 'form-control price-input', 'required','autocomplete' => 'off','placeholder'=>__('messages.products.rate')]) }}
                        </div>
                    </div>
                    <div class="form-group col-sm-12 col-lg-6 col-md-12">
                        {{ Form::label('productGroup ', __('messages.products.product_group').':') }}<span
                                class="required">*</span>
                        <div class="input-group">
                            {{ Form::select('item_group_id', $data['itemGroups'], null, ['class' => 'form-control', 'id' => 'productGroup', 'required','placeholder' => __('messages.placeholder.select_product_group')]) }}
                            <div class="input-group-append plus-icon-height">
                                <div class="input-group-text">
                                    <a href="#" data-toggle="modal" data-target="#addProductGroupModal"><i
                                                class="fa fa-plus"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="form-group col-sm-12 col-lg-6 col-md-12">
                        {{ Form::label('tax_1 ', __('messages.products.tax').' 1:') }}
                        {{ Form::select('tax_1_id', $data['taxes'], null, ['class' => 'form-control', 'id' => 'taxSelectOne', 'placeholder' => __('messages.placeholder.select_tax1')]) }}
                    </div>
                    <div class="form-group col-sm-12 col-lg-6 col-md-12">
                        {{ Form::label('tax_2 ', __('messages.products.tax').' 2:') }}
                        {{ Form::select('tax_2_id', $data['taxes'], null, ['class' => 'form-control', 'id' => 'taxSelectTwo', 'placeholder' => __('messages.placeholder.select_tax2')]) }}
                    </div>
                </div>
                <div class="text-right">
                    {{ Form::button(__('messages.common.save'), ['type'=>'submit','class' => 'btn btn-primary','id'=>'btnSave','data-loading-text'=>"<span class='spinner-border spinner-border-sm'></span> Processing..."]) }}
                    <button type="button" id="btnCancel" class="btn btn-light ml-1"
                            data-dismiss="modal">{{ __('messages.common.cancel') }}</button>
                </div>
            </div>
            {{ Form::close() }}
        </div>
    </div>
</div>
