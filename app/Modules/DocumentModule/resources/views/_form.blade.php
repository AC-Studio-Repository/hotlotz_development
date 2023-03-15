<div class="row">
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Title') }}</label>
        {{ Form::text('title', null, [
                'class' => 'form-control' . ($errors->has('title') ? ' is-invalid' : ''),
                'required' => 'required'
            ])
        }}

        @if ($errors->has('title'))
            <input hidden class="form-control is-invalid">
            <div class="invalid-feedback">{{ $errors->first('title') }}</div>
        @endif
    </div>
    <div class="form-group col-12 col-md-3 col-xl-3">
        <label class="form-control-label">{{ __('Publication Date') }}</label>
        {{ Form::date('publish_date', null, [
                'class' => 'form-control form-control-md' . ($errors->has('publish_date') ? ' is-invalid' : ''),
                'id' => 'datepicker',
                'placeholder'=>'yyyy-mm-dd',
                'required' => 'required'
            ])
        }}

        @if ($errors->has('publish_date'))
            <div class="invalid-feedback">{{ $errors->first('publish_date') }}</div>
        @endif
    </div>

    <div class="form-group col-12 col-md-3 col-xl-3">
        <label class="form-control-label">{{ __('Creator') }} <span style="color:red">*</span></label>
            {{ Form::select('created_by', $adminUsers, old('created_by', isset($document->created_by)? (integer)$document->created_by:Auth::user()->id), [
                    'class'=>'form-control',
                    'id' => 'created_by',
                    'required',
                ])
            }}

            @if ($errors->has('created_by'))
                <input hidden class="form-control is-invalid">
                <div class="invalid-feedback">{{ $errors->first('created_by') }}</div>
            @endif
    </div>
</div>

<div class='row'>
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('File or URL') }} </label>
        <div>
            @php
                if($document->type == null){
                    $document->type = 'file';
                }
            @endphp
            <label class="radio-inline" for="type_file">
                {{ Form::radio('type', 'file', $document->type == 'file', ['class'=>'type', 'id' => "type_file", 'v-model' =>
                'articleType']) }}
                File
                &nbsp;
            </label>
            <label class="radio-inline" for="type_url">
                {{ Form::radio('type', 'url', $document->type == 'url', ['class'=>'type', 'id' => "type_url", 'v-model' =>
                'articleType']) }}
                URL
                &nbsp;
            </label>
        </div>
        <div class="form-group" id="fileDiv" v-show="articleType == 'file'">
            <input name="document_file" value="{{ old('document_file', $document->full_path ?? '') }}" type="file" class="form-control" data-parsley-errors-container='#error_document_file_block' />
            <div id="error_document_file_block"></div>
            @if(isset($document->file_name))
                <label>{{ $document->file_name }}</label>
            @endif
        </div>
        <div class="form-group" id="urlDiv" v-show="articleType == 'url'" style="display:none;">
            {{ Form::text('document_url', null, [
                    'class' => 'form-control' . ($errors->has('document_url') ? ' is-invalid' : ''),
                ])
            }}

            @if ($errors->has('document_url'))
                <input hidden class="form-control is-invalid">
                <div class="invalid-feedback">{{ $errors->first('document_url') }}</div>
            @endif
        </div>
    </div>


</div>
