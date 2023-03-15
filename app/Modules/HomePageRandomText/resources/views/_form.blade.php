<div class="row">
    <div class="col-md-4">
        <label>{{ __('Title*') }}</label>
        <div class="form-group">
            {{ Form::text('title', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('title') ? ' is-invalid' : ''),
                    'placeholder' => __('Title *')
                ])
            }}

            @if ($errors->has('title'))
                <div class="invalid-feedback">{{ $errors->first('title') }}</div>
            @endif
        </div>
    </div>

     <div class="col-md-4">
        <label class="form-control-label">{{ __('Description') }} <span style="color: red;">*</span></label>
        {{ Form::textarea('description', null,
            [
                'class' => 'form-control' . ($errors->has('description') ? ' is-invalid' : ''),
                'placeholder' => __('Random Text'),
                'rows' => 3,
                'data-parsley-required'=>'true',
                'data-parsley-required-message'=>'This value is required.'
            ]
        ) }}

        @if ($errors->has('description'))
            <div class="invalid-feedback">{{ $errors->first('description') }}</div>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <label>{{ __('Link Url') }}</label>
        <div class="form-group">
            {{ Form::text('link_url', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('link_url') ? ' is-invalid' : ''),
                    'placeholder' => __('Link Url')
                ])
            }}

            @if ($errors->has('link_url'))
                <div class="invalid-feedback">{{ $errors->first('link_url') }}</div>
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