<div class="form-row">
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Title') }} <span style="color:red;">*</span></label>
        {{ Form::text('title', $whats_new_bid_barometer->title ?? null, [
                'class' => 'form-control',
                'disabled'
            ])
        }}
    </div>
</div>
<div class="row">
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Link') }} <span style="color:red;">*</span></label>
        {{ Form::text('link', $whats_new_bid_barometer->link ?? null, [
                'class' => 'form-control form-control-md',
                'disabled'
            ])
        }}
    </div>
</div>
<div class='row'>
    <div class="col-12 col-md-12 col-xl-12">
        <label>{{ __('Image') }} (576px x 412px) <span style="color:red;">*</span> </label>
        <div class="form-group">            
            <img onclick="imagepreview(this)" lazyload="on" id="image_preview" src="{{ isset($whats_new_bid_barometer->full_path)?$whats_new_bid_barometer->full_path:'' }}" class="img-responsive" width="300px" height="215px">
        </div>
    </div>
</div>