<div class="row">
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Title') }}</label>
        {{ Form::text('title', $blog_article->title ?? null, [
                'class' => 'form-control',
                'disabled'
            ])
        }}
    </div>
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Publication Date') }}</label>
        {{ Form::text('publication_date', $blog_article->publication_date ?? null, [
                'class' => 'form-control',
                'disabled'
            ])
        }}
    </div>
</div>

<div class='row'>
    <div class="col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Article File') }} </label>
        <div class="form-group">
            {{ $blog_article->article_file_name }}
        </div>
    </div>
    <div class="col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Article Logo') }} </label>
        <div class="form-group">
            <img onclick="imagepreview(this)" lazyload="on" id="image_preview" src="{{ $blog_article->full_path ?? '' }}" class="img-responsive" width="200px" height="135px">
        </div>
    </div>
</div>