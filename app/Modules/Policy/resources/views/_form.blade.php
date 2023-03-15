<div class="row">
    <div class="col-md-4">
        <label>{{ __('Menu') }}</label>
        <div class="form-group">
            {{ Form::text('menu_name', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('menu_name') ? ' is-invalid' : ''),
                    'placeholder' => __('Menu')
                ])
            }}

            @if ($errors->has('menu_name'))
                <div class="invalid-feedback">{{ $errors->first('menu_name') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-8">
        <label>{{ __('Title Header') }}</label>
        <div class="form-group">
            {{ Form::text('title', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('title') ? ' is-invalid' : ''),
                    'placeholder' => __('Title Header')
                ])
            }}

            @if ($errors->has('title'))
                <div class="invalid-feedback">{{ $errors->first('title') }}</div>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <label>{{ __('Title Blog') }}</label>
        {{ Form::textarea('content', null,
            [
                'class' => 'form-control' . ($errors->has('content') ? ' is-invalid' : ''),
                'placeholder' => __('Title Blog'),
                'rows' => 3,
                'data-parsley-required-message'=>"This value is required.",
                'id' => 'summernote'
            ]
        ) }}

        @if ($errors->has('content'))
            <div class="invalid-feedback">{{ $errors->first('content') }}</div>
        @endif
    </div>
</div>
<br>

<div class="row">
    <div class="form-group col-12 col-md-12 col-xl-12">
        <label class="form-control-label">{{ __('Select files to upload') }}</label>
        <div class="file-loading">
            <input id="policy_document" name="policy_document" type="file" accept="application/pdf, .xls, .xlsx, text/plain, application/zip, .doc, .docx,.ppt, .pptx">
        </div>
        
        <input type="text" style="display: none;" name="lastest_id" id="lastest_id" value="{{ $lastest_id }}">
        <input type="text" style="display: none;" name="hide_file_path" id="hide_file_path" value="">
        <input type="text" style="display: none;" name="hide_full_path" id="hide_full_path" value="" data-parsley-errors-container="#error_documents_block" data-parsley-required-message="Please select and upload at least one Document!">
        <div id="error_documents_block" class="help-block"></div>
    </div>
</div>