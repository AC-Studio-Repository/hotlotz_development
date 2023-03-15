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
                {{ Form::radio('bank_country', 'singapore', $customer->bank_country == 'singapore', ['class'=>'bank_country', 'id' => "bank_country_sg"]) }}
                Singapore
                &nbsp;
            </label>
            <label class="radio-inline" for="bank_country_other">
                {{ Form::radio('bank_country', 'other', $customer->bank_country == 'other', ['class'=>'bank_country', 'id' => "bank_country_other"]) }}
                Other Countries
                &nbsp;
            </label>
        </div>

        @if ($errors->has('bank_country'))
            <div class="form-control-feedback">{{ $errors->first('bank_country') }}</div>
        @endif
    </div>
    <div class="form-group col-md-4 divOther">
        <label class="form-control-label">{{ __('Other Country') }} <span style="color: red;">*</span></label>
        {{ Form::select('bank_country_id', [''=>'--- Select Country ---'] + $bank_countries, ($customer->bank_country_id != null)?$customer->bank_country_id:null, [
                'class'=>'form-control' . ($errors->has('bank_country_id') ? ' is-invalid' : ''),
                'id'=>'bank_country_id',
                "required",
            ])
        }}

        @if ($errors->has('bank_country_id'))
            <div class="invalid-feedback">{{ $errors->first('bank_country_id') }}</div>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <label class="form-control-label">{{ __('Bank Name') }}</label>
        <div class="form-group">
            {{ Form::text('bank_name', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('bank_name') ? ' is-invalid' : ''),
                ])
            }}

            @if ($errors->has('bank_name'))
                <div class="invalid-feedback">{{ $errors->first('bank_name') }}</div>
            @endif
        </div>
    </div>
    <div class="col-md-4">
        <label class="form-control-label">{{ __('Account Name') }}</label>
        <div class="form-group">
            {{ Form::text('bank_account_name', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('bank_account_name') ? ' is-invalid' : ''),
                ])
            }}

            @if ($errors->has('bank_account_name'))
                <div class="invalid-feedback">{{ $errors->first('bank_account_name') }}</div>
            @endif
        </div>
    </div>
    <div class="col-md-4">
        <label class="form-control-label">{{ __('Account Number') }}</label>
        <div class="form-group">
            {{ Form::text('bank_account_number', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('bank_account_number') ? ' is-invalid' : ''),
                    'placeholder' => __(''),
                ])
            }}

            @if ($errors->has('bank_account_number'))
                <div class="invalid-feedback">{{ $errors->first('bank_account_number') }}</div>
            @endif
        </div>
    </div>
</div>

<div class="row divOther">
    <div class="col-md-4">
        <label class="form-control-label">{{ __('SWIFT Code') }}</label>
        <div class="form-group">
            {{ Form::text('swift', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('swift') ? ' is-invalid' : ''),
                    'id'=>'swift'
                ])
            }}

            @if ($errors->has('swift'))
                <div class="invalid-feedback">{{ $errors->first('swift') }}</div>
            @endif
        </div>
    </div>
    <div class="col-md-4">
        <label class="form-control-label">{{ __('Account Currency') }}</label>
        <div class="form-group">
            {{ Form::text('account_currency', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('account_currency') ? ' is-invalid' : ''), 'id'=>'account_currency'
                ])
            }}

            @if ($errors->has('account_currency'))
                <div class="invalid-feedback">{{ $errors->first('account_currency') }}</div>
            @endif
        </div>
    </div>
    <div class="col-md-4">
        <label class="form-control-label">{{ __('Additional Note') }}</label>
        <div class="form-group">
            {{ Form::text('bank_additional_note', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('bank_additional_note') ? ' is-invalid' : ''),
                    'placeholder' => __('eg. IBAN Number'),
                    'id'=>'bank_additional_note'
                ])
            }}

            @if ($errors->has('bank_additional_note'))
                <div class="invalid-feedback">{{ $errors->first('bank_additional_note') }}</div>
            @endif
        </div>
    </div>
</div>
<div class="row divOther">
    <div class="col-md-12">
        <label class="form-control-label">{{ __('Bank Address') }}</label>
        <div class="form-group">
            {{ Form::textarea('bank_address', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('bank_address') ? ' is-invalid' : ''),
                    'rows'=>3,
                    'id'=>'bank_address'
                ])
            }}

            @if ($errors->has('bank_address'))
                <div class="invalid-feedback">{{ $errors->first('bank_address') }}</div>
            @endif
        </div>
    </div>
</div>

@section('scripts')
@parent
<script type="text/javascript">
    $(function(){
        checkBankCountry($('.bank_country:checked').val());
        $('.bank_country').click(function(){
            checkBankCountry($(this).val());
        });
    });
</script>
@stop