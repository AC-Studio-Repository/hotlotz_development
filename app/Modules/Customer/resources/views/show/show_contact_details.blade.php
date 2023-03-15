<div class="form-group row {{ $errors->has('type') ? ' has-danger' : '' }}">
    <label class="form-control-label col-md-2">{{ __('Client type') }}</label>
    <div class="col-md-10">
        @foreach($types as $key => $value)
            <label class="radio-inline" for="type_{{ $key }}">
                {{ Form::radio('type', $key, $customer->type->value() == $key, ['class'=>'customer_type', 'id' => "type_$key", 'disabled']) }}
                {{ ($value == 'Organization')?'Company':'Individual' }}
                &nbsp;
            </label>
        @endforeach
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        @php
            $required = '';
            $span_req = '';
            if($customer->type->value() == 'organization'){
                $required = 'required';
                $span_req = '*';
            }
        @endphp
        <label class="form-control-label">{{ __('Company Name') }} <span style="color: red;">{{$span_req}}</span></label>
        <div class="form-group">
            <div class="input-group">
                <span class="input-group-addon">
                    <i class="zmdi zmdi-group-work"></i>
                </span>
                {{ Form::text('company_name', $customer->company_name, [
                        'class' => 'form-control form-control-md',
                        'disabled',
                    ])
                }}
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <label class="form-control-label">{{ __('Client Reference') }} <span style="color: red;">*</span></label>
        <div class="form-group">
            {{ Form::text('ref_no', $customer->ref_no, [
                    'class' => 'form-control form-control-md',
                    'disabled'
                ])
            }}
        </div>
    </div>
    <div class="col-md-4">
        <label class="form-control-label">{{ __('Country of Residence') }} <span style="color: red;">*</span></label>
        <div class="form-group">
            {{ Form::select('country_of_residence', $countries, ($customer->country_of_residence > 0)?$customer->country_of_residence:null, [
                    'class' => 'form-control form-control-md',
                    'disabled'
                ])
            }}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <label class="form-control-label">{{ __('Title') }} <span style="color: red;">*</span></label>
        <div class="form-group">
            {{ Form::select('salutation', $salutations, $customer->salutation!=null?$customer->salutation:'Mr.', [
                    'class' => 'form-control form-control-md',
                    'disabled'
                ])
            }}
        </div>
    </div>

    <div class="col-md-4">
        <label class="form-control-label">{{ __('First Name') }} <span style="color: red;">*</span></label>
        <div class="form-group">
            {{ Form::text('firstname', $customer->firstname, [
                    'class' => 'form-control form-control-md',
                    'disabled'
                ])
            }}
        </div>
    </div>

    <div class="col-md-4">
        <label class="form-control-label">{{ __('Last Name') }} <span style="color: red;">*</span></label>
        <div class="form-group">
            {{ Form::text('lastname', $customer->lastname, [
                    'class' => 'form-control form-control-md',
                    'disabled'
                ])
            }}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <label class="form-control-label">{{ __('Email') }} <span style="color: red;">*</span></label>
        <div class="form-group">
            {{ Form::email('email', $customer->email, [
                    'class' => 'form-control form-control-md',
                    'disabled'
                ])
            }}
        </div>
    </div>

    <div class="col-md-2">
        <label class="form-control-label">{{ __('Country Code') }} <span style="color: red;">*</span></label>
        <div class="form-group">
            {{ Form::text('dialling_code', $customer->dialling_code, [
                    'class' => 'form-control form-control-md',
                    'disabled'
                ])
            }}
        </div>
    </div>

    <div class="col-md-4">
        <label class="form-control-label">{{ __('Mobile') }} <span style="color: red;">*</span></label>
        <div class="form-group">
            {{ Form::text('phone', $customer->phone, [
                    'class' => 'form-control form-control-md',
                    'disabled'
                ])
            }}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <label class="form-control-label">{{ __('Internal Comments') }} </label>
        <div class="form-group">
            {{ Form::textarea('internal_note', $customer->internal_note, [
                    'class' => 'form-control form-control-md',
                    'rows'=>3,
                    'disabled'
                ])
            }}
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <label class="form-control-label">{{ __('Main Client Contact') }} </label>
        <div class="form-group">
            {{ Form::select('main_client_contact', [null=>'-- Select Contact --'] + $admin_users, $customer->main_client_contact, [
                    'class' => 'form-control form-control-md',
                    'disabled'
                ])
            }}
        </div>
    </div>
    <div class="col-md-4">
        <label class="form-control-label">{{ __('Status') }} </label>
        <div class="form-group">
            <h5><span class="badge badge-{{ ($customer->is_active == 1) ? 'success' : 'danger' }}">{{ ($customer->is_active == 1) ? 'Active' : 'Blocked' }}</span></h5>
        </div>
    </div>
</div>

<!-- Client's Addresses -->
@include('customer::show.address')