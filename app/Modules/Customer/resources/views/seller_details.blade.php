<div class="form-group row">
    <div class="col-md-4">
        <label class="form-control-label">{{ __('GST Registered?') }}</label>
        <div class="input-group">
            <label class="radio-inline" for="seller_gst_registered_true">
                {{ Form::radio('seller_gst_registered', 1, true, ['id' => "seller_gst_registered_true", 'v-model' =>
                'gstRegister']) }}
                Yes
                &nbsp;
            </label>
            <label class="radio-inline" for="seller_gst_registered_false">
                {{ Form::radio('seller_gst_registered', 0, ($customer->seller_gst_registered == 0)?true:false, ['id' => "seller_gst_registered_false", 'v-model' =>
                'gstRegister']) }}
                No
                &nbsp;
            </label>
        </div>

        @if ($errors->has('seller_gst_registered'))
            <div class="invalid-feedback">{{ $errors->first('seller_gst_registered') }}</div>
        @endif
    </div>

    <div class="col-md-4" v-show="gstRegister == '1'">
        <label class="form-control-label">{{ __('GST Number') }}</label>
        <div class="form-group">
            {{ Form::text('gst_number', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('gst_number') ? ' is-invalid' : ''),
                    'placeholder' => __('e.g 12345')
                ])
            }}

            @if ($errors->has('gst_number'))
                <div class="invalid-feedback">{{ $errors->first('gst_number') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-4">
        <label class="form-control-label">{{ __('Singapore UEN Number') }}</label>
        <div class="form-group">
            {{ Form::text('sg_uen_number', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('sg_uen_number') ? ' is-invalid' : ''),
                    'placeholder' => __('e.g 12345')
                ])
            }}

            @if ($errors->has('sg_uen_number'))
                <div class="invalid-feedback">{{ $errors->first('sg_uen_number') }}</div>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <label class="form-control-label">{{ __('Notes To Appear On Statement') }}</label>
        <div class="form-group">
            {{ Form::textarea('note_to_appear_on_statement', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('note_to_appear_on_statement') ? ' is-invalid' : ''),
                    'placeholder' => __(''),
                    'rows'=>3
                ])
            }}

            @if ($errors->has('note_to_appear_on_statement'))
                <div class="invalid-feedback">{{ $errors->first('note_to_appear_on_statement') }}</div>
            @endif
        </div>
    </div>
</div>