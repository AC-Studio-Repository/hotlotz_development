<div class='row'>
    <div class="col-12 col-md-12 col-xl-12">
        <label>{{ __('Image') }} (576px x 576px) <span style="color:red">*</span> </label>
        <div class="form-group">            
            <img onclick="imagepreview(this)" lazyload="on" id="main_image_preview" src="{{ isset($what_we_sell->full_path)?$what_we_sell->full_path:'' }}" class="img-responsive" width="300" height="300">
        </div>
    </div>
</div>

<div class='row'>
    <div class="col-12 col-md-12 col-xl-12">
        <label>{{ __('Banner Image') }} (1920px x 480px) <span style="color:red">*</span> </label>
        <div class="form-group">            
            <img onclick="imagepreview(this)" lazyload="on" id="detail_banner_image_preview" src="{{ isset($what_we_sell->full_path)?$what_we_sell->detail_banner_full_path:'' }}" class="img-responsive" width="1000" height="250">
        </div>
    </div>
</div>

<div class="row">
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Category') }}</label>
        {{ Form::select('category_id', $categories, $what_we_sell->category_id, [
                'class' => 'form-control',
                'disabled',
            ])
        }}
    </div>
</div>
<div class="row">
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Caption') }} <span style="color:red">*</span></label>
        {{ Form::text('caption', $what_we_sell->caption, [
                'class' => 'form-control',
                'disabled'
            ])
        }}
    </div>
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">&nbsp;</label>
        <div class="input-group">
            <label class="checkbox-inline form-control-label" for="price_status">
                {{ Form::checkbox('price_status', 'Y', old('price_status', ($what_we_sell->price_status == 'Y')?true:false), [
                        'id' => 'price_status',
                        'disabled'
                    ])
                }}
                &nbsp;
                Sold
            </label>
        </div>
    </div>
</div>
<div class="row">
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Title') }} <span style="color:red">*</span></label>
        {{ Form::text('title', $what_we_sell->title, [
                'class' => 'form-control form-control-md',
                'disabled'
            ])
        }}
    </div>
</div>
<div class="row">
    <div class="form-group col-12 col-md-12 col-xl-12">
        <label class="form-control-label">{{ __('Title Blog') }} <span style="color:red">*</span></label>
        <label class="form-control disabled" style="background-color: #C9D0D0; opacity: 1; height: auto">
            {!! $what_we_sell->description !!}
        </label>
    </div>
</div>

<hr>
<div class="row">
    <div class="form-group col-12 col-md-12 col-xl-12">
        <label class="form-control-label">{{ __('Key Contact Detail') }}</label>
    </div>
</div>
<div class="row">
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Key Contact 1') }}</label>
        {{ Form::select('key_contact_1', [''=>'Select Key Contact']+$our_teams, $what_we_sell->key_contact_1, [
                'class' => 'form-control',
                'disabled'
            ])
        }}
    </div>
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Key Contact 2') }}</label>
        {{ Form::select('key_contact_2', [''=>'Select Key Contact']+$our_teams, $what_we_sell->key_contact_2, [
                'class' => 'form-control',
                'disabled'
            ])
        }}
    </div>
</div>