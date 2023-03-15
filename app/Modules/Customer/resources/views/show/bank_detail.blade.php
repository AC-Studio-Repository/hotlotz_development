<div class="row">
    <div class="col-md-12 mb-3">
        <h5><strong>{{ __('Bank Details') }}</strong></h5>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <label class="form-control-label">{{ __('Country') }}</label>
        <div class="form-group">
            <label class="radio-inline" for="bank_country_sg">
                {{ Form::radio('bank_country', 'singapore', $customer->bank_country == 'singapore', ['class'=>'bank_country', 'id' => "bank_country_sg", 'disabled']) }}
                Singapore
                &nbsp;
            </label>
            <label class="radio-inline" for="bank_country_other">
                {{ Form::radio('bank_country', 'other', $customer->bank_country == 'other', ['class'=>'bank_country', 'id' => "bank_country_other", 'disabled']) }}
                Other Countries
                &nbsp;
            </label>
        </div>
    </div>
    @if($customer->bank_country == 'other')
    <div class="form-group col-md-4 divOther">
        <label class="form-control-label">{{ __('Other Country') }} <span style="color: red;">*</span></label>
        {{ Form::select('bank_country_id', [''=>'--- Select Country ---'] + $bank_countries, ($customer->bank_country_id != null)?$customer->bank_country_id:null, [
                'class'=>'form-control',
                'id'=>'bank_country_id',
                "disabled",
            ])
        }}
    </div>
    @endif
</div>

<div class="row">
    <div class="col-md-4">
        <label class="form-control-label">{{ __('Bank Name') }}</label>
        <div class="form-group">
            {{ Form::text('bank_name', $customer->bank_name, [
                    'class' => 'form-control form-control-md',
                    'disabled',
                ])
            }}
        </div>
    </div>
    <div class="col-md-4">
        <label class="form-control-label">{{ __('Account Name') }}</label>
        <div class="form-group">
            {{ Form::text('bank_account_name', $customer->bank_account_name, [
                    'class' => 'form-control form-control-md',
                    'disabled',
                ])
            }}
        </div>
    </div>
    <div class="col-md-4">
        <label class="form-control-label">{{ __('Account Number') }}</label>
        <div class="form-group">
            {{ Form::text('bank_account_number', $customer->bank_account_number, [
                    'class' => 'form-control form-control-md',
                    'disabled',
                ])
            }}
        </div>
    </div>
</div>

@if($customer->bank_country == 'other')
<div class="row divOther">
    <div class="col-md-4">
        <label class="form-control-label">{{ __('SWIFT Code') }}</label>
        <div class="form-group">
            {{ Form::text('swift', $customer->swift, [
                    'class' => 'form-control form-control-md',
                    'disabled',
                ])
            }}
        </div>
    </div>
    <div class="col-md-4">
        <label class="form-control-label">{{ __('Account Currency') }}</label>
        <div class="form-group">
            {{ Form::text('account_currency', $customer->account_currency, [
                    'class' => 'form-control form-control-md',
                    'disabled',
                ])
            }}
        </div>
    </div>
    <div class="col-md-4">
        <label class="form-control-label">{{ __('Additional Note') }}</label>
        <div class="form-group">
            {{ Form::text('bank_additional_note', $customer->bank_additional_note, [
                    'class' => 'form-control form-control-md',
                    'placeholder' => __('eg. IBAN Number'),
                    'disabled',
                ])
            }}
        </div>
    </div>
</div>
<div class="row divOther">
    <div class="col-md-12">
        <label class="form-control-label">{{ __('Bank Address') }}</label>
        <div class="form-group">
            {{ Form::textarea('bank_address', $customer->bank_address, [
                    'class' => 'form-control form-control-md',
                    'rows'=>3,
                    'disabled',
                ])
            }}
        </div>
    </div>
</div>
@endif