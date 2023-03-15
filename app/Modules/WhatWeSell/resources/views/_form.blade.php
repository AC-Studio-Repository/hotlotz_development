<div class="row">
    <!-- <div class="col-md-6">
        <label>{{ __('Price *') }}</label>
        <div class="form-group">
            {{ Form::text('price', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('price') ? ' is-invalid' : ''),
                    'placeholder' => __('Price*')
                ])
            }}

            @if ($errors->has('price'))
                <div class="invalid-feedback">{{ $errors->first('price') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-6">
        <label>{{ __('Buyer Level *') }}</label>
        <div class="form-group">
            {{ Form::text('buyerlevel', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('buyerlevel') ? ' is-invalid' : ''),
                    'placeholder' => __('Buyer Level*')
                ])
            }}

            @if ($errors->has('buyerlevel'))
                <div class="invalid-feedback">{{ $errors->first('buyerlevel') }}</div>
            @endif
        </div>
    </div> -->

    <!-- What We Sell Image -->
    <div class="form-group col-12 col-md-12 col-xl-12">
        <label class="form-control-label">{{ __('Image *') }}</label>

        <div class="file-loading">
            <input id="whatwesell_image" name="whatwesell_image" type="file" accept="image/*">
        </div>

        {{ Form::hidden('hide_whatwesell_ids', ($hide_whatwesell_ids != '') ? $hide_whatwesell_ids : null, [
                'class' => 'form-control form-control-md' . ($errors->has('hide_whatwesell_ids') ? ' is-invalid' : ''),
                'id' => 'hide_whatwesell_ids'
            ])
        }}
        @if ($errors->has('hide_whatwesell_ids'))
            <div class="invalid-feedback">{{ $errors->first('hide_whatwesell_ids') }}</div>
        @endif
        <div id="error_uploaded_item_images_block" class="help-block"></div>
        <span style="color: #a70909;">Please provide Highlight Image with dimensions - 576 X 576px.</span>
    </div>

    <!-- What We Sell Banner Image -->
    <div class="form-group col-12 col-md-12 col-xl-12">
        <label class="form-control-label">{{ __('Detail Banner Image *') }}</label>

        <div class="file-loading">
            <input id="whatwesell_banner_image" name="whatwesell_banner_image" type="file" accept="image/*">
        </div>

        {{ Form::hidden('hide_whatwesell_banner_ids', ($hide_banner_whatwesell_ids != '') ? $hide_banner_whatwesell_ids : null, [
                'class' => 'form-control form-control-md' . ($errors->has('hide_whatwesell_banner_ids') ? ' is-invalid' : ''),
                'id' => 'hide_whatwesell_banner_ids'
            ])
        }}
        @if ($errors->has('hide_whatwesell_banner_ids'))
            <div class="invalid-feedback">{{ $errors->first('hide_whatwesell_banner_ids') }}</div>
        @endif
        <div id="error_uploaded_item_images_block" class="help-block"></div>
        <span style="color: #a70909;">Please provide Banner Image with dimensions - 1920px x  480px.</span>
    </div>
<div>

<div class="row">
    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">{{ __('Category') }}</label>
        {{ Form::select('category_id', $categories, null, [
                'class'=>'form-control' . ($errors->has('category_id') ? ' is-invalid' : ''),
                'id'=>'category_id',
            ])
        }}

        @if ($errors->has('category_id'))
            <input hidden class="form-control is-invalid">
            <div class="invalid-feedback">{{ $errors->first('category_id') }}</div>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <label>{{ __('Caption *') }}</label>
        <div class="form-group">
            {{ Form::text('caption', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('caption') ? ' is-invalid' : ''),
                    'placeholder' => __('Caption*')
                ])
            }}

            @if ($errors->has('caption'))
                <div class="invalid-feedback">{{ $errors->first('caption') }}</div>
            @endif
        </div>
    </div>

    <div class="form-group col-12 col-md-6 col-xl-6">
        <label class="form-control-label">&nbsp;</label>
        <div class="input-group">
            <label class="checkbox-inline" for="is_pro_photo_need">
                {{ Form::checkbox('price_status', 1, old('price_status', ($whatwesell->price_status)?true:false), [
                        'id' => "price_status",
                    ])
                }}
                Sold
                &nbsp;
            </label>
        </div>

        @if ($errors->has('detail_image_1_price_status'))
            <input hidden class="form-control is-invalid">
            <div class="invalid-feedback">{{ $errors->first('detail_image_1_price_status') }}</div>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <label>{{ __('Title Header*') }}</label>
        <div class="form-group">
            {{ Form::text('title', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('title') ? ' is-invalid' : ''),
                    'placeholder' => __('Title*')
                ])
            }}

            @if ($errors->has('title'))
                <div class="invalid-feedback">{{ $errors->first('title') }}</div>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <label>{{ __('Title Blog *') }}</label>
        <div class="form-group">
            {{ Form::textarea('description', null,
                    [
                        'class' => 'form-control' . ($errors->has('description') ? ' is-invalid' : ''),
                        'placeholder' => __('Title Blog'),
                        'rows' => 3,
                        'id' => 'description'
                    ]
            ) }}

            @if ($errors->has('description'))
                <div class="invalid-feedback">{{ $errors->first('description') }}</div>
            @endif
        </div>
    </div>
</div>
<br />

<!-- <div class="form-group row">
    <div class="col-md-6 text-danger">
        <i class="zmdi zmdi-alert-circle-o zmdi-hc-fw"> </i> [ * ] This field should not be left blank .
    </div>
</div> -->

<div class="dynamic-blog">
    @if($status == 'edit')
    @foreach($blogs as $key=>$blog)
    @php
        $index = $key + 1;
    @endphp
    <input type="hidden" id="hid_edit_id_{{ $blog->id }}" name="hid_edit_id_{{ $blog->id }}" value="{{ $blog->id }}" />
                <input type="hidden" id="hid_delete_id_{{ $blog->id }}" name="hid_delete_id_{{ $blog->id }}" value="0" />
    <div class="row" id="toggle_title_{{ $blog->id }}">
        <div class="col-md-4">
            <label>{{ __('Blog Header '.$index) }}</label>
            <div class="form-group">
                {{ Form::text('title_'.$blog->id,  $blog->title, [
                        'class' => 'form-control form-control-md' . ($errors->has('title_'.$blog->id) ? ' is-invalid' : ''),
                        'id' => 'title_'.$blog->id,
                        'placeholder' => __('Blog Header '.$index)
                    ])
                }}

                @if ($errors->has('title_'.$blog->id))
                    <div class="invalid-feedback">{{ $errors->first('title_'.$blog->id) }}</div>
                @endif
            </div>
        </div>
    </div>

    <div class="row" id="toggle_blog_{{ $blog->id }}">
        <div class="col-md-12">
            <label>{{ __('Blog '.$index) }}</label>
            <textarea id="blog_{{ $blog->id }}" name="blog_{{ $blog->id }}" class="summernote">{!! $blog->blog !!}</textarea>
        </div>
    </div>

    <a href="#" id="toggle_button_{{ $blog->id }}" onclick="remove_blog('{{ $blog->id }}');" class="btn btn-sm btn-outline-success">
        <i class="zmdi zmdi-minus"></i>
        {{ __('Remove Blog') }}
    </a>

    <hr>
    @endforeach
    @endif
</div>

<hr style="height: 1px; border:none; box-shadow: inset 0 12px 12px -12px rgb(173, 171, 171);" width="100%">
<br />

<div class="row">
    <div class="col-md-4">
        <label class="form-control-label">{{ __('Select Key Contact') }}
        </label>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <label>{{ __('Key Contact One') }}</label>
        <div class="form-group">
            <select class="form-control{{ $errors->has('key_contact_1') ? ' is-invalid' : '' }}" name="key_contact_1">
            <option value="">Select Key Contact</option>
            @foreach($ourteam as $team)
                <option value="{{ $team->id }}" {{ $team->id == $key_contact_1 ? 'selected' : '' }}>{{ $team->name }} ( {!! $team->position !!} )</option>
            @endforeach
            </select>

            @if ($errors->has('key_contact_1'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('key_contact_1') }}</strong>
                </span>
            @endif
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <label>{{ __('Key Contact Two') }}</label>
        <div class="form-group">
            <select class="form-control{{ $errors->has('key_contact_2') ? ' is-invalid' : '' }}" name="key_contact_2">
            <option value="">Select Key Contact</option>
            @foreach($ourteam as $team)
                <option value="{{ $team->id }}" {{ $team->id == $key_contact_2 ? 'selected' : '' }}>{{ $team->name }} ( {!! $team->position !!} )</option>
            @endforeach
            </select>

            @if ($errors->has('key_contact_2'))
                <span class="invalid-feedback" role="alert">
                    <strong>{{ $errors->first('key_contact_2') }}</strong>
                </span>
            @endif
        </div>
    </div>
</div>
<hr>


<div class="form-group row">
    <div class="col-md-6 card-header">
       What We Sell Highlights
    </div>
</div>

<div class="row">
   <!-- What We Sell Detail Image -->
    <div class="col-md-12">
        <label class="form-control-label">{{ __('Highlight Image 1') }}</label>
    </div>

    <div class="form-group col-12 col-md-12 col-xl-12">

        <div class="file-loading">
            <input id="whatwesell_detail_image_1" name="whatwesell_detail_image_1" type="file" accept="image/*">
        </div>

        <input type="text" style="display: none;" name="hide_whatwesell_detail_1_ids" id="hide_whatwesell_detail_1_ids" value="{{$hide_whatwesell_detail_1_ids}}" data-parsley-required="false" data-parsley-errors-container="#error_uploaded_item_images_block" data-parsley-required-message="Please select and upload banner image!">
        <div id="error_uploaded_item_images_block" class="help-block"></div>
        <span style="color: #a70909;">Please provide Banner Image with dimensions - 576 X 576px.</span>
    </div>

    <div class="col-md-6">
        <label>{{ __('Item Name ') }}</label>
        <div class="form-group">
            {{ Form::text('detail_image_1_title', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('detail_image_1_title') ? ' is-invalid' : ''),
                    'placeholder' => __('Title')
                ])
            }}

            @if ($errors->has('detail_image_1_title'))
                <div class="invalid-feedback">{{ $errors->first('detail_image_1_title') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-6">
        <label>{{ __('Price ') }}</label>
        <div class="form-group">
            {{ Form::text('detail_image_1_caption', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('detail_image_1_caption') ? ' is-invalid' : ''),
                    'placeholder' => __('Caption')
                ])
            }}

            @if ($errors->has('detail_image_1_caption'))
                <div class="invalid-feedback">{{ $errors->first('detail_image_1_caption') }}</div>
            @endif
        </div>
    </div>

    <br />
    <hr style="height: 1px; border:none; box-shadow: inset 0 12px 12px -12px rgb(173, 171, 171);" width="100%">
    <br />

    <div class="col-md-12">
        <label class="form-control-label">{{ __('Highlight Image 2') }}</label>
    </div>


    <div class="form-group col-12 col-md-12 col-xl-12">

        <div class="file-loading">
            <input id="whatwesell_detail_image_2" name="whatwesell_detail_image_2" type="file" accept="image/*">
        </div>

        <input type="text" style="display: none;" name="hide_whatwesell_detail_2_ids" id="hide_whatwesell_detail_2_ids" value="{{$hide_whatwesell_detail_2_ids}}" data-parsley-required="false" data-parsley-errors-container="#error_uploaded_item_images_block" data-parsley-required-message="Please select and upload banner image!">
        <div id="error_uploaded_item_images_block" class="help-block"></div>
        <span style="color: #a70909;">Please provide Banner Image with dimensions - 576 X 576px.</span>
    </div>

    <div class="col-md-6">
        <label>{{ __('Item Name ') }}</label>
        <div class="form-group">
            {{ Form::text('detail_image_2_title', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('detail_image_2_title') ? ' is-invalid' : ''),
                    'placeholder' => __('Title')
                ])
            }}

            @if ($errors->has('detail_image_2_title'))
                <div class="invalid-feedback">{{ $errors->first('detail_image_2_title') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-6">
        <label>{{ __('Price ') }}</label>
        <div class="form-group">
            {{ Form::text('detail_image_2_caption', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('detail_image_2_caption') ? ' is-invalid' : ''),
                    'placeholder' => __('Caption')
                ])
            }}

            @if ($errors->has('detail_image_2_caption'))
                <div class="invalid-feedback">{{ $errors->first('detail_image_2_caption') }}</div>
            @endif
        </div>
    </div>

    <br />
    <hr style="height: 1px; border:none; box-shadow: inset 0 12px 12px -12px rgb(173, 171, 171);" width="100%">
    <br />

    <div class="col-md-12">
        <label class="form-control-label">{{ __('Highlight Image 3') }}</label>
    </div>

    <div class="form-group col-12 col-md-12 col-xl-12">

        <div class="file-loading">
            <input id="whatwesell_detail_image_3" name="whatwesell_detail_image_3" type="file" accept="image/*">
        </div>

        <input type="text" style="display: none;" name="hide_whatwesell_detail_3_ids" id="hide_whatwesell_detail_3_ids" value="{{$hide_whatwesell_detail_3_ids}}" data-parsley-required="false" data-parsley-errors-container="#error_uploaded_item_images_block" data-parsley-required-message="Please select and upload banner image!">
        <div id="error_uploaded_item_images_block" class="help-block"></div>
        <span style="color: #a70909;">Please provide Banner Image with dimensions - 576 X 576px.</span>
    </div>

    <div class="col-md-6">
        <label>{{ __('Item Name ') }}</label>
        <div class="form-group">
            {{ Form::text('detail_image_3_title', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('detail_image_3_title') ? ' is-invalid' : ''),
                    'placeholder' => __('Title')
                ])
            }}

            @if ($errors->has('detail_image_3_title'))
                <div class="invalid-feedback">{{ $errors->first('detail_image_3_title') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-6">
        <label>{{ __('Price') }}</label>
        <div class="form-group">
            {{ Form::text('detail_image_3_caption', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('detail_image_3_caption') ? ' is-invalid' : ''),
                    'placeholder' => __('Caption')
                ])
            }}

            @if ($errors->has('detail_image_3_caption'))
                <div class="invalid-feedback">{{ $errors->first('detail_image_3_caption') }}</div>
            @endif
        </div>
    </div>

    <br />
    <hr style="height: 1px; border:none; box-shadow: inset 0 12px 12px -12px rgb(173, 171, 171);" width="100%">
    <br />

    <div class="col-md-12">
        <label class="form-control-label">{{ __('Highlight Image 4') }}</label>
    </div>

    <div class="form-group col-12 col-md-12 col-xl-12">

        <div class="file-loading">
            <input id="whatwesell_detail_image_4" name="whatwesell_detail_image_4" type="file" accept="image/*">
        </div>

        <input type="text" style="display: none;" name="hide_whatwesell_detail_4_ids" id="hide_whatwesell_detail_4_ids" value="{{$hide_whatwesell_detail_4_ids}}" data-parsley-required="false" data-parsley-errors-container="#error_uploaded_item_images_block" data-parsley-required-message="Please select and upload banner image!">
        <div id="error_uploaded_item_images_block" class="help-block"></div>
        <span style="color: #a70909;">Please provide Banner Image with dimensions - 576 X 576px.</span>
    </div>

    <div class="col-md-6">
        <label>{{ __('Item Name ') }}</label>
        <div class="form-group">
            {{ Form::text('detail_image_4_title', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('detail_image_4_title') ? ' is-invalid' : ''),
                    'placeholder' => __('Title')
                ])
            }}

            @if ($errors->has('detail_image_4_title'))
                <div class="invalid-feedback">{{ $errors->first('detail_image_4_title') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-6">
        <label>{{ __('Price') }}</label>
        <div class="form-group">
            {{ Form::text('detail_image_4_caption', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('detail_image_4_caption') ? ' is-invalid' : ''),
                    'placeholder' => __('Caption')
                ])
            }}

            @if ($errors->has('detail_image_4_caption'))
                <div class="invalid-feedback">{{ $errors->first('detail_image_4_caption') }}</div>
            @endif
        </div>
    </div>

    <br />
    <hr style="height: 1px; border:none; box-shadow: inset 0 12px 12px -12px rgb(173, 171, 171);" width="100%">
    <br />

    <div class="col-md-12">
        <label class="form-control-label">{{ __('Highlight Image 5') }}</label>
    </div>

    <div class="form-group col-12 col-md-12 col-xl-12">

        <div class="file-loading">
            <input id="whatwesell_detail_image_5" name="whatwesell_detail_image_5" type="file" accept="image/*">
        </div>

        <input type="text" style="display: none;" name="hide_whatwesell_detail_5_ids" id="hide_whatwesell_detail_5_ids" value="{{$hide_whatwesell_detail_5_ids}}" data-parsley-required="false" data-parsley-errors-container="#error_uploaded_item_images_block" data-parsley-required-message="Please select and upload banner image!">
        <div id="error_uploaded_item_images_block" class="help-block"></div>
        <span style="color: #a70909;">Please provide Banner Image with dimensions - 576 X 576px.</span>
    </div>

    <div class="col-md-6">
        <label>{{ __('Item Name ') }}</label>
        <div class="form-group">
            {{ Form::text('detail_image_5_title', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('detail_image_5_title') ? ' is-invalid' : ''),
                    'placeholder' => __('Title')
                ])
            }}

            @if ($errors->has('detail_image_5_title'))
                <div class="invalid-feedback">{{ $errors->first('detail_image_5_title') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-6">
        <label>{{ __('Price') }}</label>
        <div class="form-group">
            {{ Form::text('detail_image_5_caption', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('detail_image_5_caption') ? ' is-invalid' : ''),
                    'placeholder' => __('Caption')
                ])
            }}

            @if ($errors->has('detail_image_5_caption'))
                <div class="invalid-feedback">{{ $errors->first('detail_image_5_caption') }}</div>
            @endif
        </div>
    </div>

    <br />
    <hr style="height: 1px; border:none; box-shadow: inset 0 12px 12px -12px rgb(173, 171, 171);" width="100%">
    <br />

    <div class="col-md-12">
        <label class="form-control-label">{{ __('Highlight Image 6') }}</label>
    </div>

    <div class="form-group col-12 col-md-12 col-xl-12">

        <div class="file-loading">
            <input id="whatwesell_detail_image_6" name="whatwesell_detail_image_6" type="file" accept="image/*">
        </div>

        <input type="text" style="display: none;" name="hide_whatwesell_detail_6_ids" id="hide_whatwesell_detail_6_ids" value="{{$hide_whatwesell_detail_6_ids}}" data-parsley-required="false" data-parsley-errors-container="#error_uploaded_item_images_block" data-parsley-required-message="Please select and upload banner image!">
        <div id="error_uploaded_item_images_block" class="help-block"></div>
        <span style="color: #a70909;">Please provide Banner Image with dimensions - 576 X 576px.</span>
    </div>

    <div class="col-md-6">
        <label>{{ __('Item Name ') }}</label>
        <div class="form-group">
            {{ Form::text('detail_image_6_title', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('detail_image_6_title') ? ' is-invalid' : ''),
                    'placeholder' => __('Title')
                ])
            }}

            @if ($errors->has('detail_image_6_title'))
                <div class="invalid-feedback">{{ $errors->first('detail_image_6_title') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-6">
        <label>{{ __('Price') }}</label>
        <div class="form-group">
            {{ Form::text('detail_image_6_caption', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('detail_image_6_caption') ? ' is-invalid' : ''),
                    'placeholder' => __('Caption')
                ])
            }}

            @if ($errors->has('detail_image_6_caption'))
                <div class="invalid-feedback">{{ $errors->first('detail_image_6_caption') }}</div>
            @endif
        </div>
    </div>

    <br />
    <hr style="height: 1px; border:none; box-shadow: inset 0 12px 12px -12px rgb(173, 171, 171);" width="100%">
    <br />


    <div class="col-md-12">
        <label class="form-control-label">{{ __('Highlight Image 7') }}</label>
    </div>

    <div class="form-group col-12 col-md-12 col-xl-12">

        <div class="file-loading">
            <input id="whatwesell_detail_image_7" name="whatwesell_detail_image_7" type="file" accept="image/*">
        </div>

        <input type="text" style="display: none;" name="hide_whatwesell_detail_7_ids" id="hide_whatwesell_detail_7_ids" value="{{$hide_whatwesell_detail_7_ids}}" data-parsley-required="false" data-parsley-errors-container="#error_uploaded_item_images_block" data-parsley-required-message="Please select and upload banner image!">
        <div id="error_uploaded_item_images_block" class="help-block"></div>
        <span style="color: #a70909;">Please provide Banner Image with dimensions - 576 X 576px.</span>
    </div>

    <div class="col-md-6">
        <label>{{ __('Item Name ') }}</label>
        <div class="form-group">
            {{ Form::text('detail_image_7_title', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('detail_image_7_title') ? ' is-invalid' : ''),
                    'placeholder' => __('Title')
                ])
            }}

            @if ($errors->has('detail_image_7_title'))
                <div class="invalid-feedback">{{ $errors->first('detail_image_7_title') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-6">
        <label>{{ __('Price') }}</label>
        <div class="form-group">
            {{ Form::text('detail_image_7_caption', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('detail_image_7_caption') ? ' is-invalid' : ''),
                    'placeholder' => __('Caption')
                ])
            }}

            @if ($errors->has('detail_image_7_caption'))
                <div class="invalid-feedback">{{ $errors->first('detail_image_7_caption') }}</div>
            @endif
        </div>
    </div>

    <br />
    <hr style="height: 1px; border:none; box-shadow: inset 0 12px 12px -12px rgb(173, 171, 171);" width="100%">
    <br />


    <div class="col-md-12">
        <label class="form-control-label">{{ __('Highlight Image 8') }}</label>
    </div>

    <div class="form-group col-12 col-md-12 col-xl-12">

        <div class="file-loading">
            <input id="whatwesell_detail_image_8" name="whatwesell_detail_image_8" type="file" accept="image/*">
        </div>

        <input type="text" style="display: none;" name="hide_whatwesell_detail_8_ids" id="hide_whatwesell_detail_8_ids" value="{{$hide_whatwesell_detail_8_ids}}" data-parsley-required="false" data-parsley-errors-container="#error_uploaded_item_images_block" data-parsley-required-message="Please select and upload banner image!">
        <div id="error_uploaded_item_images_block" class="help-block"></div>
        <span style="color: #a70909;">Please provide Banner Image with dimensions - 576 X 576px.</span>
    </div>

    <div class="col-md-6">
        <label>{{ __('Item Name ') }}</label>
        <div class="form-group">
            {{ Form::text('detail_image_8_title', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('detail_image_8_title') ? ' is-invalid' : ''),
                    'placeholder' => __('Title')
                ])
            }}

            @if ($errors->has('detail_image_8_title'))
                <div class="invalid-feedback">{{ $errors->first('detail_image_8_title') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-6">
        <label>{{ __('Price') }}</label>
        <div class="form-group">
            {{ Form::text('detail_image_8_caption', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('detail_image_8_caption') ? ' is-invalid' : ''),
                    'placeholder' => __('Caption')
                ])
            }}

            @if ($errors->has('detail_image_8_caption'))
                <div class="invalid-feedback">{{ $errors->first('detail_image_8_caption') }}</div>
            @endif
        </div>
    </div>

    <br />
    <hr style="height: 1px; border:none; box-shadow: inset 0 12px 12px -12px rgb(173, 171, 171);" width="100%">
    <br />


    <div class="col-md-12">
        <label class="form-control-label">{{ __('Highlight Image 9') }}</label>
    </div>

    <div class="form-group col-12 col-md-12 col-xl-12">

        <div class="file-loading">
            <input id="whatwesell_detail_image_9" name="whatwesell_detail_image_9" type="file" accept="image/*">
        </div>

        <input type="text" style="display: none;" name="hide_whatwesell_detail_9_ids" id="hide_whatwesell_detail_9_ids" value="{{$hide_whatwesell_detail_9_ids}}" data-parsley-required="false" data-parsley-errors-container="#error_uploaded_item_images_block" data-parsley-required-message="Please select and upload banner image!">
        <div id="error_uploaded_item_images_block" class="help-block"></div>
        <span style="color: #a70909;">Please provide Banner Image with dimensions - 576 X 576px.</span>
    </div>

    <div class="col-md-6">
        <label>{{ __('Item Name ') }}</label>
        <div class="form-group">
            {{ Form::text('detail_image_9_title', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('detail_image_9_title') ? ' is-invalid' : ''),
                    'placeholder' => __('Title')
                ])
            }}

            @if ($errors->has('detail_image_9_title'))
                <div class="invalid-feedback">{{ $errors->first('detail_image_9_title') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-6">
        <label>{{ __('Price') }}</label>
        <div class="form-group">
            {{ Form::text('detail_image_9_caption', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('detail_image_9_caption') ? ' is-invalid' : ''),
                    'placeholder' => __('Caption')
                ])
            }}

            @if ($errors->has('detail_image_9_caption'))
                <div class="invalid-feedback">{{ $errors->first('detail_image_9_caption') }}</div>
            @endif
        </div>
    </div>

    <br />
    <hr style="height: 1px; border:none; box-shadow: inset 0 12px 12px -12px rgb(173, 171, 171);" width="100%">
    <br />


    <div class="col-md-12">
        <label class="form-control-label">{{ __('Highlight Image 10') }}</label>
    </div>

    <div class="form-group col-12 col-md-12 col-xl-12">

        <div class="file-loading">
            <input id="whatwesell_detail_image_10" name="whatwesell_detail_image_10" type="file" accept="image/*">
        </div>

        <input type="text" style="display: none;" name="hide_whatwesell_detail_10_ids" id="hide_whatwesell_detail_10_ids" value="{{$hide_whatwesell_detail_10_ids}}" data-parsley-required="false" data-parsley-errors-container="#error_uploaded_item_images_block" data-parsley-required-message="Please select and upload banner image!">
        <div id="error_uploaded_item_images_block" class="help-block"></div>
        <span style="color: #a70909;">Please provide Banner Image with dimensions - 576 X 576px.</span>
    </div>

    <div class="col-md-6">
        <label>{{ __('Item Name ') }}</label>
        <div class="form-group">
            {{ Form::text('detail_image_10_title', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('detail_image_10_title') ? ' is-invalid' : ''),
                    'placeholder' => __('Title')
                ])
            }}

            @if ($errors->has('detail_image_10_title'))
                <div class="invalid-feedback">{{ $errors->first('detail_image_10_title') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-6">
        <label>{{ __('Price') }}</label>
        <div class="form-group">
            {{ Form::text('detail_image_10_caption', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('detail_image_10_caption') ? ' is-invalid' : ''),
                    'placeholder' => __('Caption')
                ])
            }}

            @if ($errors->has('detail_image_10_caption'))
                <div class="invalid-feedback">{{ $errors->first('detail_image_10_caption') }}</div>
            @endif
        </div>
    </div>

    <br />
    <hr style="height: 1px; border:none; box-shadow: inset 0 12px 12px -12px rgb(173, 171, 171);" width="100%">
    <br />


    <div class="col-md-12">
        <label class="form-control-label">{{ __('Highlight Image 11') }}</label>
    </div>

    <div class="form-group col-12 col-md-12 col-xl-12">

        <div class="file-loading">
            <input id="whatwesell_detail_image_11" name="whatwesell_detail_image_11" type="file" accept="image/*">
        </div>

        <input type="text" style="display: none;" name="hide_whatwesell_detail_11_ids" id="hide_whatwesell_detail_11_ids" value="{{$hide_whatwesell_detail_11_ids}}" data-parsley-required="false" data-parsley-errors-container="#error_uploaded_item_images_block" data-parsley-required-message="Please select and upload banner image!">
        <div id="error_uploaded_item_images_block" class="help-block"></div>
        <span style="color: #a70909;">Please provide Banner Image with dimensions - 576 X 576px.</span>
    </div>

    <div class="col-md-6">
        <label>{{ __('Item Name ') }}</label>
        <div class="form-group">
            {{ Form::text('detail_image_11_title', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('detail_image_11_title') ? ' is-invalid' : ''),
                    'placeholder' => __('Title')
                ])
            }}

            @if ($errors->has('detail_image_11_title'))
                <div class="invalid-feedback">{{ $errors->first('detail_image_11_title') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-6">
        <label>{{ __('Price') }}</label>
        <div class="form-group">
            {{ Form::text('detail_image_11_caption', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('detail_image_11_caption') ? ' is-invalid' : ''),
                    'placeholder' => __('Caption')
                ])
            }}

            @if ($errors->has('detail_image_11_caption'))
                <div class="invalid-feedback">{{ $errors->first('detail_image_11_caption') }}</div>
            @endif
        </div>
    </div>

    <br />
    <hr style="height: 1px; border:none; box-shadow: inset 0 12px 12px -12px rgb(173, 171, 171);" width="100%">
    <br />


    <div class="col-md-12">
        <label class="form-control-label">{{ __('Highlight Image 12') }}</label>
    </div>

    <div class="form-group col-12 col-md-12 col-xl-12">

        <div class="file-loading">
            <input id="whatwesell_detail_image_12" name="whatwesell_detail_image_12" type="file" accept="image/*">
        </div>

        <input type="text" style="display: none;" name="hide_whatwesell_detail_12_ids" id="hide_whatwesell_detail_12_ids" value="{{$hide_whatwesell_detail_12_ids}}" data-parsley-required="false" data-parsley-errors-container="#error_uploaded_item_images_block" data-parsley-required-message="Please select and upload banner image!">
        <div id="error_uploaded_item_images_block" class="help-block"></div>
        <span style="color: #a70909;">Please provide Banner Image with dimensions - 576 X 576px.</span>
    </div>

    <div class="col-md-6">
        <label>{{ __('Item Name ') }}</label>
        <div class="form-group">
            {{ Form::text('detail_image_12_title', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('detail_image_12_title') ? ' is-invalid' : ''),
                    'placeholder' => __('Title')
                ])
            }}

            @if ($errors->has('detail_image_12_title'))
                <div class="invalid-feedback">{{ $errors->first('detail_image_12_title') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-6">
        <label>{{ __('Price') }}</label>
        <div class="form-group">
            {{ Form::text('detail_image_12_caption', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('detail_image_12_caption') ? ' is-invalid' : ''),
                    'placeholder' => __('Caption')
                ])
            }}

            @if ($errors->has('detail_image_12_caption'))
                <div class="invalid-feedback">{{ $errors->first('detail_image_12_caption') }}</div>
            @endif
        </div>
    </div>

</div>
