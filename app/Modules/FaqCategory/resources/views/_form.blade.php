<div class="row">
    <div class="col-md-4">
        <label>{{ __('FAQ Category Name*') }}</label>
        <div class="form-group">
            {{ Form::text('name', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('name') ? ' is-invalid' : ''),
                    'placeholder' => __('FAQ Category Name *')
                ])
            }}

            @if ($errors->has('name'))
                <div class="invalid-feedback">{{ $errors->first('name') }}</div>
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