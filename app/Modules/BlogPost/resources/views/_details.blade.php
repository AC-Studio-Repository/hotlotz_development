<div class="form-row">
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Title') }}</label>
        {{ Form::text('title', $blog_post->title ?? null, [
                'class' => 'form-control',
                'disabled'
            ])
        }}
    </div>
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Post Date') }}</label>
        {{ Form::text('post_date', $blog_post->post_date ?? null, [
                'class' => 'form-control',
                'disabled'
            ])
        }}
    </div>
</div>

<div class="row">
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Link Name') }}</label>
        {{ Form::text('link_name', $blog_post->link_name ?? null, [
                'class' => 'form-control form-control-md',
                'disabled'
            ])
        }}
    </div>
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Link') }}</label>
        {{ Form::text('link', $blog_post->link ?? null, [
                'class' => 'form-control form-control-md',
                'disabled'
            ])
        }}
    </div>
</div>

<div class='row'>
    <div class="col-12 col-md-12 col-xl-12">
        <label class="form-control-label">{{ __('Banner Image') }} (576px x 430px) </label>
        <div class="form-group">            
            <img onclick="imagepreview(this)" lazyload="on" id="image_preview" src="{{ $blog_post->full_path ?? '' }}" class="img-responsive" width="300px" height="225px">
        </div>
    </div>
</div>