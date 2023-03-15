<div class="row {{ $errors->has('type') ? ' has-danger' : '' }}">
    <label class="form-control-label col-md-2">{{ __('Client type') }}</label>
    <div class="col-md-10">
        @php
            if($form_type == 'create'){
                $customer->type = 'individual';
            }
        @endphp
        @foreach($types as $key => $value)
            <label class="radio-inline" for="type_{{ $key }}">
                {{ Form::radio('type', $key, $customer->type->value() == $key, ['class'=>'customer_type', 'id' => "type_$key", 'v-model' =>
                'customerType', ($is_admin_role === 'no')?'disabled':null]) }}
                {{ ($value == 'Organization')?'Company':'Individual' }}
                &nbsp;
            </label>
        @endforeach

        @if ($errors->has('type'))
            <div class="form-control-feedback">{{ $errors->first('type') }}</div>
        @endif
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <label class="form-control-label">{{ __('Company Name') }} <span class="company_name_span" style="color: red;">&nbsp;</span></label>
        <div class="form-group">
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="zmdi zmdi-group-work"></i>
                </span>
                {{ Form::text('company_name', null, [
                        'class' => 'form-control form-control-md' . ($errors->has('company_name') ? ' is-invalid' : ''),
                        'placeholder' => __('Company name'),
                        'id' => 'company_name',
                        'data-parsley-errors-container'=>'#error_company_name',
                        ($is_admin_role === 'no')?'disabled':null
                    ])
                }}
                @if ($errors->has('company_name'))
                    <div class="invalid-tooltip" style="width: auto">{{ $errors->first('company_name') }}</div>
                @endif
            </div>
            <div id="error_company_name"></div>
        </div>
    </div>

    <div class="col-md-4">
        <label class="form-control-label">{{ __('Client Reference') }} <span style="color: red;">*</span></label>
        <div class="form-group">
            {{ Form::text('ref_no', $ref_no, [
                    'class' => 'form-control form-control-md' . ($errors->has('ref_no') ? ' is-invalid' : ''),
                    'placeholder' => __('Paddle No.'),
                    'disabled'=>'true'
                ])
            }}

            @if ($errors->has('ref_no'))
                <div class="invalid-feedback">{{ $errors->first('ref_no') }}</div>
            @endif
        </div>
    </div>
    <div class="col-md-4">
        <label class="form-control-label">{{ __('Country of Residence') }} <span style="color: red;">*</span></label>
        <div class="form-group">
            {{ Form::select('country_of_residence', $countries, $customer->country_of_residence ?? 702, [
                    'class' => 'form-control form-control-md' . ($errors->has('country_of_residence') ? ' is-invalid' : ''),
                    'required',
                    ($is_admin_role === 'no')?'disabled':null
                ])
            }}

            @if ($errors->has('country_of_residence'))
                <div class="invalid-feedback">{{ $errors->first('country_of_residence') }}</div>
            @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <label class="form-control-label">{{ __('Title') }} <span style="color: red;">*</span></label>
        <div class="form-group">
            {{ Form::select('salutation', $salutations, $customer->salutation!=null?$customer->salutation:'Mr.', [
                    'class' => 'form-control form-control-md' . ($errors->has('salutation') ? ' is-invalid' : ''),
                    'required',
                    ($is_admin_role === 'no')?'disabled':null
                ])
            }}

            @if ($errors->has('salutation'))
                <div class="invalid-feedback">{{ $errors->first('salutation') }}</div>
            @endif
        </div>
    </div>
    <div class="col-md-4">
        <label class="form-control-label">{{ __('First Name') }} <span style="color: red;">*</span></label>
        <div class="form-group">
            {{ Form::text('firstname', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('firstname') ? ' is-invalid' : ''),
                    'placeholder' => __('First name *'),
                    'required',
                    ($is_admin_role === 'no')?'disabled':null
                ])
            }}

            @if ($errors->has('firstname'))
                <div class="invalid-feedback">{{ $errors->first('firstname') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-4">
        <label class="form-control-label">{{ __('Last Name') }} <span style="color: red;">*</span></label>
        <div class="form-group">
            {{ Form::text('lastname', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('lastname') ? ' is-invalid' : ''),
                    'placeholder' => __('Last name *'),
                    'required',
                    ($is_admin_role === 'no')?'disabled':null
                ])
            }}

            @if ($errors->has('lastname'))
                <div class="invalid-feedback">{{ $errors->first('lastname') }}</div>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <label class="form-control-label">{{ __('Email') }} <span style="color: red;">*</span></label>
        <div class="form-group">
            {{ Form::email('email', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('email') ? ' is-invalid' : ''),
                    'placeholder' => __('Email *'),
                    'required',
                ])
            }}

            @if ($errors->has('email'))
                <div class="invalid-feedback">{{ $errors->first('email') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md13">
        <label class="form-control-label">{{ __('Country Code') }}</label>
        <div class="form-group">
            {{ Form::select('dialling_code', $country_codes, $customer->dialling_code!=null?$customer->dialling_code:null, [
                    'class' => 'form-control form-control-md' . ($errors->has('dialling_code') ? ' is-invalid' : ''),
                ])
            }}

            @if ($errors->has('dialling_code'))
                <div class="invalid-feedback">{{ $errors->first('dialling_code') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-4">
        <label class="form-control-label">{{ __('Mobile') }} <span style="color: red;">*</span></label>
        <div class="form-group">
            {{ Form::text('phone', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('phone') ? ' is-invalid' : ''),
                    'placeholder' => __('Primary Phone'),
                    'required',
                ])
            }}

            @if ($errors->has('phone'))
                <div class="invalid-feedback">{{ $errors->first('phone') }}</div>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <label class="form-control-label">{{ __('Internal Comments') }} </label>
        <div class="form-group">
            {{ Form::textarea('internal_note', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('internal_note') ? ' is-invalid' : ''),
                    'placeholder' => __('Internal Comments'),
                    'rows'=>3,
                    ($is_admin_role === 'no')?'disabled':null
                ])
            }}

            @if ($errors->has('internal_note'))
                <div class="invalid-feedback">{{ $errors->first('internal_note') }}</div>
            @endif
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <label class="form-control-label">{{ __('Main Client Contact') }} </label>
        <div class="form-group">
            {{ Form::select('main_client_contact', [null=>'-- Select Contact --'] + $admin_users, $customer->main_client_contact, [
                    'class' => 'form-control form-control-md',
                    'id'=>'main_client_contact',
                    ($is_admin_role === 'no')?'disabled':null
                ])
            }}

            @if ($errors->has('main_client_contact'))
                <div class="invalid-feedback">{{ $errors->first('main_client_contact') }}</div>
            @endif
        </div>
    </div>
    
    @if($form_type == 'edit')
        @can('blocking client')
        <div class="col-md-4">
            <label class="form-control-label">{{ __('Status') }}</label>
            <div class="form-group">
                {{ Form::hidden('is_active', 0) }}
                <label class="switch switch-icon switch-pill switch-primary">
                    {{ Form::checkbox('is_active', 1, old('is_active', ($customer->is_active == 1)?true:false), ['class' => 'switch-input','id'=>'is_active', ($is_admin_role === 'no')?'disabled':null]) }}
                    <span class="switch-label" data-on="&#xf26b;" data-off="&#xf136;"></span>
                    <span class="switch-handle"></span>
                </label>
                <label id="statusActive">Active</label>
                <label id="statusBlocked">Blocked</label>

                @if ($errors->has('is_active'))
                    <input type="text" hidden class="form-control is-invalid">
                    <div class="invalid-feedback">{{ $errors->first('is_active') }}</div>
                @endif
            </div>
        </div>
        @endcan
    @endif
</div>
