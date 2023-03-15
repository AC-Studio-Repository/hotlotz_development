<div class="form-group row">
    <div class="col-md-4">
        <label class="form-control-label">{{ __('GST Registered?') }}</label>
        <div class="input-group">
            <label class="radio-inline" for="buyer_gst_registered_true">
                {{ Form::radio('buyer_gst_registered', '1', true, ['id' => "buyer_gst_registered_true", 'disabled']) }}
                Yes
                &nbsp;
            </label>
            <label class="radio-inline" for="buyer_gst_registered_false">
                {{ Form::radio('buyer_gst_registered', '0', ($customer->buyer_gst_registered == '0')?true:false, ['id' => "buyer_gst_registered_false", 'disabled']) }}
                No
                &nbsp;
            </label>
        </div>

        @if ($errors->has('buyer_gst_registered'))
            <div class="invalid-feedback">{{ $errors->first('buyer_gst_registered') }}</div>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <label class="form-control-label">{{ __('Note To Appear On Invoice') }}</label>
        <div class="form-group">
            {{ Form::textarea('note_to_appear_on_invoice', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('note_to_appear_on_invoice') ? ' is-invalid' : ''),
                    'placeholder' => __('Dealers/Collectors Invoice'),
                    'rows' => 3
                ])
            }}

            @if ($errors->has('note_to_appear_on_invoice'))
                <div class="invalid-feedback">{{ $errors->first('note_to_appear_on_invoice') }}</div>
            @endif
        </div>
    </div>
</div>
