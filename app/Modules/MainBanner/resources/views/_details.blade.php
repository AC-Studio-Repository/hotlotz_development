<div class="form-row">
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Main Title') }} <span style="color:red;">*</span></label>
        {{ Form::text('main_title', $main_banner->main_title ?? null, [
                'class' => 'form-control',
                'disabled'
            ])
        }}
    </div>
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Sub Title') }} <span style="color:red;">*</span></label>
        {{ Form::text('sub_title', $main_banner->sub_title ?? null, [
                'class' => 'form-control',
                'disabled'
            ])
        }}
    </div>
</div>

<div class="row">
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Link Name') }}</label>
        {{ Form::text('link_name', $main_banner->link_name ?? null, [
                'class' => 'form-control form-control-md',
                'disabled'
            ])
        }}
    </div>
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Link') }}</label>
        {{ Form::text('link', $main_banner->link ?? null, [
                'class' => 'form-control form-control-md',
                'disabled'
            ])
        }}
    </div>
</div>

<div class="row">
    <div class="col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Position') }} <span style="color:red;">*</span></label>
        <div class="form-group">
            <label class="radio-inline" for="position_left">
                {{ Form::radio('position', 'left', ($main_banner->position == 'left') ? true:false, ['class' => 'form-control position', 'id'=>'position_left', 'disabled']) }}
                Left
                &nbsp;
            </label>
            <label class="radio-inline" for="position_right">
                {{ Form::radio('position', 'right', ($main_banner->position == 'right') ? true:false, ['class' => 'form-control position', 'id'=>'position_right', 'disabled']) }}
                Right
                &nbsp;
            </label>
            <label class="radio-inline" for="position_hide">
                {{ Form::radio('position', 'hide', ($main_banner->position == 'hide') ? true:false, ['class' => 'form-control position', 'id'=>'position_hide', 'disabled']) }}
                Hide
                &nbsp;
            </label>
        </div>
    </div>

    <div class="col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Color') }}</label>
        <div class="form-group">
            <label class="radio-inline" for="color_navy" style="margin-right: 5px !important;">
                {{ Form::radio('color', 'navy', ($main_banner->color == 'navy') ? true:false, ['class' => 'form-control color', 'id'=>'color_navy', 'disabled']) }}
                <div style="background-color: #0E143E; padding: 20px 5px 5px 20px; float: right; border: 1px solid #000;"></div>
                &nbsp;
            </label>
            <label class="radio-inline" for="color_pink" style="margin-right: 5px !important;">
                {{ Form::radio('color', 'pink', ($main_banner->color == 'pink') ? true:false, ['class' => 'form-control color', 'id'=>'color_pink', 'disabled']) }}
                <div style="background-color: #E70C3B; padding: 20px 5px 5px 20px; float: right; border: 1px solid #000;"></div>
                &nbsp;
            </label>
            <label class="radio-inline" for="color_turquoise" style="margin-right: 5px !important;">
                {{ Form::radio('color', 'turquoise', ($main_banner->color == 'turquoise') ? true:false, ['class' => 'form-control color', 'id'=>'color_turquoise', 'disabled']) }}
                <div style="background-color: #61C4DD; padding: 20px 5px 5px 20px; float: right; border: 1px solid #000;"></div>
                &nbsp;
            </label>
        </div>
    </div>
</div>

<div class='row'>
    <div class="col-12 col-md-12 col-xl-12">
        <label>{{ __('Banner Image') }} (1920px x 1000px) <span style="color:red;">*</span> </label>
        <div class="form-group">            
            <img onclick="imagepreview(this)" lazyload="on" id="image_preview" src="{{ isset($main_banner->full_path)?$main_banner->full_path:'' }}" class="img-responsive" width="895px" height="240px">
        </div>
    </div>
</div>