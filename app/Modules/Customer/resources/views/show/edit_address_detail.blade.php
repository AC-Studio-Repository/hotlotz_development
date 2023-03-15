{{-- Form::model($address_detail, ['route' => ['customer.customers.address_update'], 'method' => 'POST', 'id'=>'frmAddress', 'data-parsley-validate'=>'true', 'autocomplete' => 'off' ]) --}}

    <input type="hidden" name="edit_type" id="edit_type" value="{{ $type }}">
    <input type="hidden" name="customer_id" id="customer_id" value="{{ $customer->id }}">
    <input type="hidden" name="address_id" id="address_id" value="{{ $address_detail->id ?? null }}">

    @if($type == 'shipping')    
    <div class="row">
        <div class="form-group col-md-6">
            <label class="form-control-label">{{ __('Address Nickname') }}</label>
            {{ Form::text('address_nickname', $address_detail->address_nickname ?? null, [
                    'class' => 'form-control form-control-md',
                    'id'=>'address_nickname',
                ])
            }}
        </div>
        <div class="form-group col-md-6">
            <label class="form-control-label">&nbsp;</label>
            <div class="form-group">
                <label class="form-control-label">
                    {{ Form::checkbox('is_primary', '1', (isset($address_detail->is_primary) && $address_detail->is_primary == 1)?true:false, ['class'=>'is_primary', 'id' => "is_primary"]) }}
                    {{ __('Primary Address') }}
                </label>
            </div>
        </div>
    </div>
    @endif
    
    <div class="row">
        @php
            $is_disabled = '';
            $firstname = $address_detail->firstname ?? null;
            $lastname = $address_detail->lastname ?? null;
            if(isset($type) && ($type == 'correspondence' || $type == 'kyc')){
                $is_disabled = 'disabled';
                $firstname = isset($customer->firstname)?$customer->firstname:null;
                $lastname = isset($customer->lastname)?$customer->lastname:null;
            }
        @endphp
        <div class="form-group col-md-6">
            <label class="form-control-label">{{ __('First Name') }} <span style="color: red;">*</span></label>
            {{ Form::text('firstname', $firstname, [
                    'class' => 'form-control form-control-md',
                    'id'=>'firstname',
                    "required",
                    $is_disabled
                ])
            }}
        </div>
        <div class="form-group col-md-6">
            <label class="form-control-label">{{ __('Last Name') }} <span style="color: red;">*</span></label>
            {{ Form::text('lastname', $lastname, [
                    'class' => 'form-control form-control-md',
                    'id'=>'lastname',
                    "required",
                    $is_disabled
                ])
            }}
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-12">
            <label class="form-control-label">{{ __('Address') }} <span style="color: red;">*</span></label>
            {{ Form::textarea('address', $address_detail->address ?? null, [
                    'class' => 'form-control form-control-md',
                    'id'=>'address',
                    'rows'=>'5',
                    "required",
                ])
            }}
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-6">
            <label class="form-control-label">{{ __('City') }} <span style="color: red;">*</span></label>
            {{ Form::text('city', $address_detail->city ?? null, [
                    'class' => 'form-control form-control-md',
                    'id'=>'city',
                    "required",
                ])
            }}
        </div>
        <div class="form-group col-md-6">
            <label class="form-control-label">{{ __('State') }}</label>
            {{ Form::text('state', $address_detail->state ?? null, [
                    'class' => 'form-control form-control-md',
                    'id'=>'state',
                ])
            }}
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-6">
            <label class="form-control-label">{{ __('Country') }} <span style="color: red;">*</span></label>
            {{ Form::select('country_id', $countries, $address_detail->country_id ?? 702, [
                    'class'=>'form-control' . ($errors->has('country_id') ? ' is-invalid' : ''),
                    'id'=>'country_id',
                    "required",
                ])
            }}
        </div>
        <div class="form-group col-md-6">
            <label class="form-control-label">{{ __('Postal Code') }} <span style="color: red;">*</span></label>
            {{ Form::text('postalcode', $address_detail->postalcode ?? null, [
                    'class' => 'form-control form-control-md',
                    'id'=>'postalcode',
                    "required",
                ])
            }}
        </div>
    </div>

    @if($type == 'shipping')
    <div class="row">
        <div class="form-group col-md-6">
            <label class="form-control-label">{{ __('Mobile Phone') }}</label>
            {{ Form::text('daytime_phone', $address_detail->daytime_phone ?? null, [
                    'class' => 'form-control form-control-md',
                    'id'=>'daytime_phone',
                ])
            }}
        </div>
        <div class="form-group col-md-6">
            <label class="form-control-label">{{ __('Daily Instructions') }}</label>
            {{ Form::text('delivery_instruction', $address_detail->delivery_instruction ?? null, [
                    'class' => 'form-control form-control-md',
                    'id'=>'delivery_instruction',
                ])
            }}
        </div>
    </div>
    @endif

    <div class="row text-center">
        <div class="form-group col-md-12">       
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button  >
            <button class="btn btn-primary" id="addressSave">Save</button>
        </div>
    </div>
{{-- Form::close() --}}