<div class='row'>
    <div class="col-12 col-md-12 col-xl-12">
        <label>{{ __('Banner Image') }} (1920px x 556px) <span style="color:red;">*</span> </label>
        <div class="form-group">            
            <img onclick="imagepreview(this)" lazyload="on" id="image_preview" src="{{ isset($marketplace_banner->full_path)?$marketplace_banner->full_path:'' }}" class="img-responsive" width="895px" height="240px">
        </div>
    </div>
</div>