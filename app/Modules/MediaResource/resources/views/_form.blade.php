<div class="row">
    <div class="col-md-4">
        <label>{{ __('Date') }}</label>
        <div class="form-group">
             {{ Form::text('display_date', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('display_date') ? ' is-invalid' : ''),
                    'placeholder' => __('Date')
                ])
            }}

            @if ($errors->has('display_date'))
                <div class="invalid-feedback">{{ $errors->first('display_date') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-6">
        <label>{{ __('Title') }}</label>
        <div class="form-group">
            {{ Form::text('title', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('title') ? ' is-invalid' : ''),
                    'placeholder' => __('Title')
                ])
            }}

            @if ($errors->has('title'))
                <div class="invalid-feedback">{{ $errors->first('title') }}</div>
            @endif
        </div>
    </div>
</div>
<br>

<div class="row">
    <div class="form-group col-12 col-md-12 col-xl-12">
        <label class="form-control-label">{{ __('Select files to upload') }}</label>
        <div class="file-loading">
            <input id="media_resource_document" name="media_resource_document" type="file" accept="application/pdf, .xls, .xlsx, text/plain, application/zip, .doc, .docx,.ppt, .pptx">
        </div>
        
        <input type="text" style="display: none;" name="hide_media_resource_doc_ids" id="hide_media_resource_doc_ids" value="{{ $hide_media_resource_doc_ids }}" data-parsley-required="false" data-parsley-errors-container="#error_customer_document_block" data-parsley-required-message="Please select and upload at least one Document!">
        <div id="error_customer_document_block" class="help-block"></div>
    </div>
</div>