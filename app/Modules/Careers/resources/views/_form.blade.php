<div class="row">
    <div class="col-md-4">
        <label>{{ __('Position') }}</label>
        <div class="form-group">
             {{ Form::text('position', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('position') ? ' is-invalid' : ''),
                    'placeholder' => __('Position')
                ])
            }}

            @if ($errors->has('position'))
                <div class="invalid-feedback">{{ $errors->first('position') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-4">
        <label>{{ __('Experience Level') }}</label>
        <div class="form-group">
            {{ Form::text('expreience_level', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('expreience_level') ? ' is-invalid' : ''),
                    'placeholder' => __('Experience Level')
                ])
            }}

            @if ($errors->has('expreience_level'))
                <div class="invalid-feedback">{{ $errors->first('expreience_level') }}</div>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-4">
        <label>{{ __('Post') }}</label>
        <div class="form-group">
             {{ Form::number('posts', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('posts') ? ' is-invalid' : ''),
                    'placeholder' => __('Post')
                ])
            }}

            @if ($errors->has('posts'))
                <div class="invalid-feedback">{{ $errors->first('posts') }}</div>
            @endif
        </div>
    </div>
</div>
<br>

<div class="row">
    <div class="form-group col-12 col-md-12 col-xl-12">
        <label class="form-control-label">{{ __('Select files to upload') }}</label>
        <div class="file-loading">
            <input id="careers_document" name="careers_document" type="file" id="policy_document" accept="application/pdf, .xls, .xlsx, text/plain, application/zip, .doc, .docx,.ppt, .pptx">
        </div>
        
        <input type="text" style="display: none;" name="hide_careers_doc_ids" id="hide_careers_doc_ids" value="{{ $hide_careers_doc_ids }}" data-parsley-required="false" data-parsley-errors-container="#error_customer_document_block" data-parsley-required-message="Please select and upload at least one Document!">
        <div id="error_customer_document_block" class="help-block"></div>
    </div>
</div>