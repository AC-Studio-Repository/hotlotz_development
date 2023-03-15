@push('trix_css')
<link rel="stylesheet" type="text/css" href="{{ asset('plugins/trix/css/trix.css') }}">
<style>
    span.trix-button-group.trix-button-group--file-tools{
        display:none;
    }
    trix-editor:empty:not(:focus)::before {
        color: #C9D0D0
    }
    trix-editor ul {
        margin-left:10px;
    }
     trix-editor ol {
        margin-left:10px;
    }
</style>
@endpush
@push('trix_js')
<script type="text/javascript" src="{{ asset('plugins/trix/js/trix.js') }}"></script>
<script>
    $('input[name="href"]').removeAttr('required')
</script>
@endpush
<div class="form-row {{ $errors->has('type') ? ' has-danger' : '' }}">
    <label class="form-control-label col-md-2">{{ __('Auction type') }} <span style="color:red;">*</span></label>
    <div class="form-group col-md-4">
        @foreach($types as $key => $value)
            <label class="radio-inline" for="type_{{ $key }}">
                {{ Form::radio('type', $key,'time'==$key, ['class'=>'form-control', 'required', 'data-parsley-required-message'=>'This value is required.', 'data-parsley-errors-container'=>"#type_error"]) }}
                {{ $value }}
                &nbsp;
            </label>
        @endforeach

        @if ($errors->has('type'))
            <div class="form-control-feedback">{{ $errors->first('type') }}</div>
        @endif
        <div id="type_error"></div>
    </div>

    <!-- @if($auction->is_published == 'N')
        <div class="col-md-6" v-show="formType == 'edit'" >
            <div class="form-group">
                <button type="button" class="btn btn-md btn-success" id="btn_publish">{{ __('Publish') }}</button>
            </div>
        </div>
    @endif -->
</div>

<div class="row">
    <div class="col-md-4">
        <label>{{ __('Auction Title') }} <span style="color:red;">*</span></label>
        <div class="form-group">
            {{ Form::text('title', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('title') ? ' is-invalid' : ''),
                    'placeholder' => __('Auction Title'),
                    'required',
                    'id' => 'auctionTitle',
                    'data-parsley-required-message'=>"This value is required.",
                ])
            }}

            @if ($errors->has('title'))
                <div class="invalid-feedback">{{ $errors->first('title') }}</div>
            @endif
        </div>
    </div>
    <div class="col-md-4">
        <label class="form-control-label">{{ __('Buyers Premium') }} <span style="color:red;">*</span></label>
        <div class="form-group">
            <div class="input-group">
                {{ Form::text('buyers_premium', old('buyers_premium', (isset($auction->buyers_premium) && $auction->buyers_premium != null)?$auction->buyers_premium:24), [
                        'class' => 'form-control form-control-md' . ($errors->has('buyers_premium') ? ' is-invalid' : ''),
                        'data-parsley-type'=>'number',
                        'required',
                        'data-parsley-errors-container'=>'#error_buyers_premium',
                    ])
                }}
                <span class="input-group-addon">%</span>
            </div>
            <div id="error_buyers_premium"></div>

            @if ($errors->has('buyers_premium'))
                <div class="invalid-feedback">{{ $errors->first('buyers_premium') }}</div>
            @endif
        </div>
    </div>

    <div class="form-group col-12 col-md-4 col-xl-4">
        <label class="form-control-label">&nbsp;</label>
        <div class="input-group">
            <label class="checkbox-inline form-control-label" for="is_gst">
                {{ Form::checkbox('is_gst', 'Y', ($auction->is_gst == 'Y')?'checked':'', [
                        'id' => "is_gst",
                    ])
                }}
                GST
                &nbsp;
            </label>
        </div>

        @if ($errors->has('is_gst'))
            <input hidden class="form-control is-invalid">
            <div class="invalid-feedback">{{ $errors->first('is_gst') }}</div>
        @endif
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <label>{{ __('Confirmation Email') }} <span style="color:red;">*</span></label>
        <div class="form-group">
            {{ Form::email('confirmation_email', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('confirmation_email') ? ' is-invalid' : ''),
                    'placeholder' => __('Confirmation Email'),
                    'id' => 'confirmation_email',
                    'required',
                    'data-parsley-required-message'=>"This value is required.",
                ])
            }}

            @if ($errors->has('confirmation_email'))
                <div class="invalid-feedback">{{ $errors->first('confirmation_email') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-4">
        <label>{{ __('Registration Email') }} <span style="color:red;">*</span></label>
        <div class="form-group">
            {{ Form::email('registration_email', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('registration_email') ? ' is-invalid' : ''),
                    'placeholder' => __('Registration Email'),
                    'id' => 'registration_email',
                    'required',
                    'data-parsley-required-message'=>"This value is required.",
                ])
            }}

            @if ($errors->has('registration_email'))
                <div class="invalid-feedback">{{ $errors->first('registration_email') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-4">
        <label>{{ __('Payment Receive Email') }} <span style="color:red;">*</span></label>
        <div class="form-group">
            {{ Form::email('payment_receive_email', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('payment_receive_email') ? ' is-invalid' : ''),
                    'placeholder' => __('Payment Receive Email'),
                    'id' => 'payment_receive_email',
                    'required',
                    'data-parsley-required-message'=>"This value is required.",
                ])
            }}

            @if ($errors->has('payment_receive_email'))
                <div class="invalid-feedback">{{ $errors->first('payment_receive_email') }}</div>
            @endif
        </div>
    </div>
</div>

<div class='row'>
    <div class="col-md-4">
        <label>{{ __('Time Start') }} <span style="color:red;">*</span></label>
        <div class="form-group">
            {{ Form::text('timed_start', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('timed_start') ? ' is-invalid' : ''),
                    'placeholder' => __('Time Start'),
                    'id' => 'time_start',
                    'required',
                    'data-parsley-required-message'=>"This value is required.",
                ])
            }}
            {{
               Form::hidden('date',null,['id'=>'date'])
            }}
            {{
               Form::hidden('time',null,['id'=>'time'])
            }}
            @if ($errors->has('timed_start'))
                <div class="invalid-feedback">{{ $errors->first('timed_start') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-4">
        <label>{{ __('Time First Lot Ends') }} <span style="color:red;">*</span></label>
        <div class="form-group">
            {{ Form::text('timed_first_lot_ends', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('timed_first_lot_ends') ? ' is-invalid' : ''),
                    'placeholder' => __('Time First Lot Ends'),
                    'id' => 'time_first_lot_ends',
                    'required',
                    'data-parsley-required-message'=>"This value is required.",
                ])
            }}
            {{
               Form::hidden('lot_date',null,['id'=>'lot_date'])
            }}
            {{
               Form::hidden('lot_time',null,['id'=>'lot_time'])
            }}
            @if ($errors->has('timed_first_lot_ends'))
                <div class="invalid-feedback">{{ $errors->first('timed_first_lot_ends') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-4">
        <label>{{ __('Saleroom Category') }} <span style="color:red;">*</span></label>
        <div class="form-group">
            <select name="sr_category_name" class="form-control" required data-parsley-required-message="This value is required." >
                @foreach($saleroom_categories as $sr_category)
                    @if($sr_category['category_name'] == $auction->sr_category_name)
                        <option value="{{ $sr_category['category_name'] }}" selected="selected">{{ $sr_category['category_name'] }}</option>
                    @else
                        <option value="{{ $sr_category['category_name'] }}">{{ $sr_category['category_name'] }}</option>
                    @endif
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class='row'>
    <div class="col-md-4">
        <label>{{ __('Auction Image') }} (576px x 430px) <span style="color:red;">*</span> </label>
        <div class="form-group">
            <div style="height:0px;overflow:hidden;">
                <input name="auction_image" id="image_input" value="{{ old('auction_image',isset($auction->full_path)?$auction->full_path:'') }}" type="file" class="form-control" accept="image/*" onchange="readImage(this);" data-parsley-errors-container='#error_image_block' {{ !isset($auction->full_path)?'required':null }} />
            </div>
            <button type="button" class="btn btn-danger" style="opacity: 0.7; position: absolute; left: 15px;" onclick="chooseFile('#image_input');" data-placement="left" data-toggle="tooltip" title="Upload new image"><i class="fas fa-cloud-upload-alt"></i></button>
            <img onclick="imagepreview(this)" lazyload="on" id="image1" src="{{ isset($auction->full_path)?$auction->full_path:'' }}" class="img-responsive" width="300" height="200">
            <div id="error_image_block"></div>
        </div>
    </div>
    <div class="col-md-4">
        <label>{{ __('Sale Type') }} <span style="color:red;">*</span></label>
        <div class="form-group">
            {{ Form::select('sale_type', $sale_types, $auction->sale_type ?? null, [
                    'class' => 'form-control form-control-md' . ($errors->has('sale_type') ? ' is-invalid' : ''),
                    'required',
                ])
            }}

            @if ($errors->has('sale_type'))
                <div class="invalid-feedback">{{ $errors->first('sale_type') }}</div>
            @endif
        </div>
    </div>
</div>
<hr>

<h5 class="form-control-label mb-4">{{ __('Coming Auction') }}</h5>
<div class="row">
    <div class="col-md-4">
        <label>{{ __('Auction Title') }}</label>
        <div class="form-group">
            {{ Form::text('title', null, [
                    'class' => 'form-control form-control-md',
                    'placeholder' => __('Auction Title'),
                    'disabled',
                    'id' => 'auctionTitleComing'
                ])
            }}
        </div>
    </div>
    <div class="col-md-8">
        <label>{{ __('Viewing Date') }} </label>
        <div class="form-group">
            {{ Form::text('viewing_date_start', null, [
                    'class' => 'form-control form-control-md',
                    'placeholder' => __('Write Viewing Date'),
                ])
            }}
        </div>
    </div>

     <div class="col-md-12">
        <label>{{ __('Auction Details') }}</label>
        <div class="form-group">
            {{ Form::hidden('auction_detail', null, [
                        'id' => 'x'
                    ])
                }}
             <trix-editor v-pre input="x" placeholder="Type or copy/paste auction detail here" style="height:100px;"></trix-editor>
        </div>
    </div>

     <div class="col-md-12">
        <label>{{ __('About') }}</label>
        <div class="form-group">
            {{ Form::textarea('coming_auction_about', null,
                [
                    'class' => 'form-control',
                    'placeholder' => __('Type or copy/paste about here'),
                    'rows' => 4
                ]
            ) }}
        </div>
    </div>

    <div class="col-md-4">
        <label>{{ __('Consignment Deadline') }} </label>
        <div class="form-group">
            {{ Form::text('consignment_deadline', null, [
                    'class' => 'form-control form-control-md',
                    'placeholder' => __('Select Consignment Deadline'),
                    'id' => 'consignment_deadline_picker'
                ])
            }}
            {{
            Form::hidden('consignment_deadline_date',null,['id'=>'consignment_deadline_date'])
            }}
            {{
            Form::hidden('consignment_deadline_time',null,['id'=>'consignment_deadline_time'])
            }}
        </div>
        <div class="input-group">
            <label class="checkbox-inline form-control-label" for="coming_auction_tick">
                {{ Form::checkbox('coming_auction_tick', 1, ($auction->coming_auction_tick == 1 ) ? 'checked': '' )
                }}
                Private Collection
                &nbsp;
            </label>
        </div>
    </div>

    <div class="col-md-8">
        <label>{{ __('Consignment Information') }}</label>
        <div class="form-group">
            {{ Form::textarea('consignment_info', null,
                [
                    'class' => 'form-control',
                    'placeholder' => __('Type or copy/paste consignment information here'),
                    'rows' => 4
                ]
            ) }}
        </div>
    </div>

    <div class="col-md-4">
        <label>{{ __('Banner Image') }} (1920px x 480px) <span style="color:red;">*</span> </label>

        <div class="form-group">
            <div style="height:0px;overflow:hidden;">
                <input name="auction_banner" id="banner_input" value="{{ old('auction_banner',isset($auction->banner_full_path)?$auction->banner_full_path:'') }}" type="file" class="form-control" accept="image/*" onchange="readBanner(this);" data-parsley-errors-container='#error_banner_block' {{ !isset($auction->banner_full_path)?'required':null }} />
            </div>
            <button type="button" class="btn btn-danger" style="opacity: 0.7; position: absolute; left: 15px;" onclick="chooseFile('#banner_input');" data-placement="left" data-toggle="tooltip" title="Upload new banner"><i class="fas fa-cloud-upload-alt"></i></button>
            <img onclick="imagepreview(this)" lazyload="on" id="banner1" src="{{ isset($auction->banner_full_path)?$auction->banner_full_path:'' }}" class="img-responsive" width="895px" height="240px">
            <div id="error_banner_block"></div>
        </div>
        @if ($errors->has('auction_banner'))
            <div style="color:red;">{{ $errors->first('auction_banner') }}</div>
        @endif
    </div>

</div>
