<div class="row">
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Title') }}</label>
        {{ Form::text('title', null, [
                'class' => 'form-control' . ($errors->has('title') ? ' is-invalid' : ''),
            ])
        }}

        @if ($errors->has('title'))
            <input hidden class="form-control is-invalid">
            <div class="invalid-feedback">{{ $errors->first('title') }}</div>
        @endif
    </div>
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Publication Date') }}</label>
        {{ Form::text('publication_date', null, [
                'class' => 'form-control form-control-md' . ($errors->has('publication_date') ? ' is-invalid' : ''),
                'id' => 'datepicker',
                'placeholder'=>'yyyy-mm-dd'
            ])
        }}

        @if ($errors->has('publication_date'))
            <div class="invalid-feedback">{{ $errors->first('publication_date') }}</div>
        @endif
    </div>
</div>

<div class='row'>
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Article File') }} </label>
        <div>
            @php
                if($blog_article->type == null){
                    $blog_article->type = 'file';
                }
            @endphp
            <label class="radio-inline" for="type_file">
                {{ Form::radio('type', 'file', $blog_article->type == 'file', ['class'=>'type', 'id' => "type_file", 'v-model' =>
                'articleType']) }}
                File
                &nbsp;
            </label>
            <label class="radio-inline" for="type_url">
                {{ Form::radio('type', 'url', $blog_article->type == 'url', ['class'=>'type', 'id' => "type_url", 'v-model' =>
                'articleType']) }}
                URL
                &nbsp;
            </label>
        </div>
        <div class="form-group" id="fileDiv" v-show="articleType == 'file'">
            <input name="article_file" value="{{ old('article_file', $blog_article->article_full_path ?? '') }}" type="file" class="form-control" accept="application/pdf" data-parsley-errors-container='#error_article_file_block' />
            <div id="error_article_file_block"></div>
            @if(isset($blog_article->article_file_name))
                <label>{{ $blog_article->article_file_name }}</label>
            @endif
        </div>
        <div class="form-group" id="urlDiv" v-show="articleType == 'url'">
            {{ Form::text('article_url', null, [
                    'class' => 'form-control' . ($errors->has('article_url') ? ' is-invalid' : ''),
                ])
            }}

            @if ($errors->has('article_url'))
                <input hidden class="form-control is-invalid">
                <div class="invalid-feedback">{{ $errors->first('article_url') }}</div>
            @endif
        </div>
    </div>
</div>

<div class='row'>
    <div class="col-12 col-md-12 col-xl-12">
        <label class="form-control-label">{{ __('Article Logo') }} </label>
        <div class="form-group">
            <div style="height:0px;overflow:hidden;">
                <input name="article_image" id="image_input" value="{{ old('article_image', $blog_article->full_path ?? '') }}" type="file" class="form-control" accept="image/*" onchange="readImage(this);" data-parsley-errors-container='#error_image_block' />
            </div>
            <button type="button" class="btn btn-danger" style="opacity: 0.7; position: absolute; left: 15px;" onclick="chooseFile('#image_input');" data-placement="left" data-toggle="tooltip" title="Upload new image"><i class="fas fa-cloud-upload-alt"></i></button>
            <img onclick="imagepreview(this)" lazyload="on" id="image_preview" src="{{ isset($blog_article->full_path)?$blog_article->full_path:'' }}" class="img-responsive" width="300px" height="225px">
            <div id="error_image_block"></div>
        </div>
    </div>
</div>