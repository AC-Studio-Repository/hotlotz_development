<div class="row">

    <div class="col-md-4">
        <label class="form-control-label">{{ __('Quote') }} <span style="color: red;">*</span></label>
        {{ Form::textarea('quote', null,
            [
                'class' => 'form-control' . ($errors->has('quote') ? ' is-invalid' : ''),
                'placeholder' => __('Quote'),
                'rows' => 3,
                'data-parsley-required'=>'true',
                'data-parsley-required-message'=>'This value is required.'
            ]
        ) }}

        @if ($errors->has('quote'))
            <div class="invalid-feedback">{{ $errors->first('quote') }}</div>
        @endif
    </div>

    <div class="col-md-4">
        <label>{{ __('Author Name*') }}</label>
        <div class="form-group">
            {{ Form::text('author', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('author') ? ' is-invalid' : ''),
                    'placeholder' => __('Author Name *')
                ])
            }}

            @if ($errors->has('author'))
                <div class="invalid-feedback">{{ $errors->first('author') }}</div>
            @endif
        </div>
    </div>
</div>
<hr>

<div class="form-group row">
    <div class="col-md-6 text-danger">
        <i class="zmdi zmdi-alert-circle-o zmdi-hc-fw"> </i> [ * ] This field should not be left blank .
    </div>
</div>

@section('scripts')
    <script src='https://code.jquery.com/jquery-1.12.4.js'></script>
    <script src='https://code.jquery.com/ui/1.12.1/jquery-ui.js'></script>
@stop