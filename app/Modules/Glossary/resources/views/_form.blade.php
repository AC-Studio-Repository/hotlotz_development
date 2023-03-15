<div class="row">
    <div class="col-md-4">
        <label>{{ __('Glossary Category*') }}</label>
        <div class="form-group">
            {{ Form::select('category', $glossary_categories, ($glossary->glossarycategory) ? $glossary->glossarycategory->id : null, array('class'=>'form-control'))}}

            @if ($errors->has('category'))
                <div class="invalid-feedback">{{ $errors->first('category') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-4">
        <label>{{ __('Term*') }}</label>
        <div class="form-group">
            {{ Form::text('question', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('question') ? ' is-invalid' : ''),
                    'placeholder' => __('Term*')
                ])
            }}

            @if ($errors->has('question'))
                <div class="invalid-feedback">{{ $errors->first('question') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-4">
        <label>{{ __('Explanation*') }}</label>
        <div class="form-group">
            {{ Form::textarea('answer', null,
                [
                    'class' => 'form-control form-control-md' . ($errors->has('answer') ? ' is-invalid' : ''),
                    'placeholder' => __('Explanation'),
                    'rows' => 3,
                    //'required',
                    'data-parsley-required-message'=>"This value is required.",
                ]
            ) }}

            @if ($errors->has('answer'))
                <div class="invalid-feedback">{{ $errors->first('answer') }}</div>
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