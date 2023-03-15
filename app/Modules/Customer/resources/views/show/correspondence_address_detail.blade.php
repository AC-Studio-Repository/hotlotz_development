{!! Form::model($customer, ['route' => ['customer.customers.address_create'], 'method' => 'POST', 'id'=>'frmCorrespondenceAddress', 'data-parsley-validate'=>'true', 'autocomplete' => 'off' ]) !!}

    <input type="hidden" name="type" id="type" value="correspondence">
    <input type="hidden" name="customer_id" id="customer_id" value="{{ $customer->id ?? null }}">
    
    <div class="row">
        <div class="form-group col-md-6">
            <label class="form-control-label">{{ __('First Name') }} <span style="color: red;">*</span></label>
            {{ Form::text('firstname', isset($customer->firstname)?$customer->firstname:null, [
                    'class' => 'form-control form-control-md',
                    "disabled",
                ])
            }}
        </div>
        <div class="form-group col-md-6">
            <label class="form-control-label">{{ __('Last Name') }} <span style="color: red;">*</span></label>
            {{ Form::text('lastname', isset($customer->lastname)?$customer->lastname:null, [
                    'class' => 'form-control form-control-md',
                    "disabled",
                ])
            }}
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-12">
            <label class="form-control-label">{{ __('Address') }} <span style="color: red;">*</span></label>
            {{ Form::textarea('correspondence_address', null, [
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
            {{ Form::text('correspondence_city', null, [
                    'class' => 'form-control form-control-md',
                    "required",
                ])
            }}
        </div>
        <div class="form-group col-md-6">
            <label class="form-control-label">{{ __('State') }}</label>
            {{ Form::text('correspondence_state', null, [
                    'class' => 'form-control form-control-md',
                ])
            }}
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-6">
            <label class="form-control-label">{{ __('Country') }} <span style="color: red;">*</span></label>
            {{ Form::select('correspondence_country_id', $countries, null, [
                    'class'=>'form-control' . ($errors->has('country_id') ? ' is-invalid' : ''),
                    "required",
                ])
            }}
        </div>
        <div class="form-group col-md-6">
            <label class="form-control-label">{{ __('Postal Code') }} <span style="color: red;">*</span></label>
            {{ Form::text('correspondence_postalcode', null, [
                    'class' => 'form-control form-control-md',
                    "required",
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