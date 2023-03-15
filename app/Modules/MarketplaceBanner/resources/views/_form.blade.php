<div class='row'>
    <div class="col-12 col-md-12 col-xl-12">
        <label>{{ __('Banner Image') }} (1920px x 556px) <span style="color:red;">*</span> </label>
        <div class="form-group">
            <div style="height:0px;overflow:hidden;">
                <input name="banner_image" id="image_input" value="{{ old('banner_image',isset($marketplace_banner->full_path)?$marketplace_banner->full_path:'') }}" type="file" class="form-control" accept="image/*" onchange="readImage(this);" data-parsley-errors-container='#error_image_block' {{ !isset($marketplace_banner->full_path)?'required':null }} />
            </div>
            <button type="button" class="btn btn-danger" style="opacity: 0.7; position: absolute; left: 15px;" onclick="chooseFile('#image_input');" data-placement="left" data-toggle="tooltip" title="Upload new image"><i class="fas fa-cloud-upload-alt"></i></button>
            <img onclick="imagepreview(this)" lazyload="on" id="image_preview" src="{{ isset($marketplace_banner->full_path)?$marketplace_banner->full_path:'' }}" class="img-responsive" width="895px" height="240px">
            <div id="error_image_block"></div>
        </div>
    </div>
</div>