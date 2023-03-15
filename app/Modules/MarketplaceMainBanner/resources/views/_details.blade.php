<div class="row">
    <div class="col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Caption') }}</label>
        <div class="form-group">
            {{ Form::text('caption', $marketplace_main_banner->caption ?? null, [
                    'class' => 'form-control form-control-md',
                    'disabled'
                ])
            }}
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-md-12 col-xl-12">
        <label class="form-control-label">{{ __('Banner') }} (1920px x 556px)</label>
        <div class="form-group">            
            <img onclick="imagepreview(this)" lazyload="on" id="image_preview" src="{{ $marketplace_main_banner->full_path ?? '' }}" class="img-responsive" width="1020" height="300">
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Learn More') }}</label>
        <div class="form-group">
            {{ Form::text('learn_more', $marketplace_main_banner->learn_more ?? null, [
                    'class' => 'form-control form-control-md',
                    'disabled'
                ])
            }}
        </div>
    </div>
</div>