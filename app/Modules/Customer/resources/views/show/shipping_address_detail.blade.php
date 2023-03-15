{!! Form::model($customer, ['route' => ['customer.customers.address_create'], 'method' => 'POST', 'id'=>'frmShippingAddress', 'data-parsley-validate'=>'true', 'autocomplete' => 'off' ]) !!}

    <input type="hidden" name="type" id="type" value="shipping">
    <input type="hidden" name="customer_id" id="customer_id" value="{{ $customer->id ?? null }}">

    <div class="row">
        <div class="form-group col-md-6">
            <label class="form-control-label">{{ __('Address Nickname') }}</label>
            {{ Form::text('shipping_address_nickname', null, [
                    'class' => 'form-control form-control-md',
                ])
            }}
        </div>
        <div class="form-group col-md-6">
            <label class="form-control-label">&nbsp;</label>
            <div class="form-group">
                <label class="form-control-label">
                    {{ Form::checkbox('shipping_is_primary', '1', null, ['class'=>'is_primary']) }}
                    {{ __('Primary Address') }}
                </label>
            </div>
        </div>
    </div>
    
    <div class="row">
        <div class="form-group col-md-6">
            <label class="form-control-label">{{ __('First Name') }} <span style="color: red;">*</span></label>
            {{ Form::text('shipping_firstname', null, [
                    'class' => 'form-control form-control-md',
                    "required",
                ])
            }}
        </div>
        <div class="form-group col-md-6">
            <label class="form-control-label">{{ __('Last Name') }} <span style="color: red;">*</span></label>
            {{ Form::text('shipping_lastname', null, [
                    'class' => 'form-control form-control-md',
                    "required",
                ])
            }}
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-12">
            <label class="form-control-label">{{ __('Address') }} <span style="color: red;">*</span></label>
            {{ Form::textarea('shipping_address', null, [
                    'class' => 'form-control form-control-md',
                    'rows'=>'5',
                    "required",
                ])
            }}
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-6">
            <label class="form-control-label">{{ __('City') }} <span style="color: red;">*</span></label>
            {{ Form::text('shipping_city', null, [
                    'class' => 'form-control form-control-md',
                    "required",
                ])
            }}
        </div>
        <div class="form-group col-md-6">
            <label class="form-control-label">{{ __('State') }}</label>
            {{ Form::text('shipping_state', null, [
                    'class' => 'form-control form-control-md',
                ])
            }}
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-6">
            <label class="form-control-label">{{ __('Country') }} <span style="color: red;">*</span></label>
            {{ Form::select('shipping_country_id', $countries, null, [
                    'class'=>'form-control' . ($errors->has('country_id') ? ' is-invalid' : ''),
                    "required",
                ])
            }}
        </div>
        <div class="form-group col-md-6">
            <label class="form-control-label">{{ __('Postal Code') }} <span style="color: red;">*</span></label>
            {{ Form::text('shipping_postalcode', null, [
                    'class' => 'form-control form-control-md',
                    "required",
                ])
            }}
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-6">
            <label class="form-control-label">{{ __('Mobile Phone') }}</label>
            {{ Form::text('shipping_daytime_phone', null, [
                    'class' => 'form-control form-control-md',
                ])
            }}
        </div>
        <div class="form-group col-md-6">
            <label class="form-control-label">{{ __('Daily Instructions') }}</label>
            {{ Form::text('shipping_delivery_instruction', null, [
                    'class' => 'form-control form-control-md',
                ])
            }}
        </div>
    </div>

    <div class="row text-center">
        <div class="form-group col-md-12">       
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button  >
            <button class="btn btn-primary" id="addressUpdate">Save</button>
        </div>
    </div>
{!! Form::close() !!}