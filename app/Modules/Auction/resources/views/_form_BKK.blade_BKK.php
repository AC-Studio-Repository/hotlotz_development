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

    <!-- <div class="col-md-4">
        <label>{{ __('Address') }} <span style="color:red;">*</span></label>
        <div class="form-group">
            {{ Form::textarea('address1', null,
                [
                    'class' => 'form-control' . ($errors->has('address1') ? ' is-invalid' : ''),
                    'placeholder' => __('Address'),
                    'rows' => 3,
                    'required',
                    'data-parsley-required-message'=>"This value is required.",
                ]
            ) }}

            @if ($errors->has('address1'))
                <div class="invalid-feedback">{{ $errors->first('address1') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-4">
        <label>{{ __('Country') }}</label>
        <div class="form-group">
            {{ Form::select('country', $countries, $auction->country, array('class'=>'form-control'))}}

            @if ($errors->has('country'))
                <div class="invalid-feedback">{{ $errors->first('country') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-4">
        <label>{{ __('Country Code') }} <span style="color:red;">*</span></label>
        <div class="form-group">
            {{ Form::select('country_code', $countries_codes, $auction->country_code, array('class'=>'form-control', 'required',                 'data-parsley-required-message'=>"This value is required.",))}}

            @if ($errors->has('country_code'))
                <div class="invalid-feedback">{{ $errors->first('country_code') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-4">
        <label>{{ __('Currency') }} <span style="color:red;">*</span></label>
        <div class="form-group">
            {{ Form::select('currency', $currencies, $auction->currency, array('class'=>'form-control','required',                'data-parsley-required-message'=>"This value is required.",))}}

            @if ($errors->has('currency'))
                <div class="invalid-feedback">{{ $errors->first('currency') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-4">
        <label>{{ __('Country State Name') }}</label>
        <div class="form-group">
            {{ Form::text('contry_state_name', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('contry_state_name') ? ' is-invalid' : ''),
                    'placeholder' => __('Country State Name')
                ])
            }}

            @if ($errors->has('contry_state_name'))
                <div class="invalid-feedback">{{ $errors->first('contry_state_name') }}</div>
            @endif
        </div>
    </div>


    <div class="col-md-4">
        <label>{{ __('Post Code') }} <span style="color:red;">*</span></label>
        <div class="form-group">
            {{ Form::text('post_code', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('post_code') ? ' is-invalid' : ''),
                    'placeholder' => __('Post Code'),
                    'required',
                    'data-parsley-required-message'=>"This value is required.",
                ])
            }}

            @if ($errors->has('post_code'))
                <div class="invalid-feedback">{{ $errors->first('post_code') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-4">
        <label>{{ __('Town City') }} <span style="color:red;">*</span></label>
        <div class="form-group">
            {{ Form::text('town_city', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('town_city') ? ' is-invalid' : ''),
                    'placeholder' => __('Town City'),
                    'required',
                    'data-parsley-required-message'=>"This value is required.",
                ])
            }}

            @if ($errors->has('town_city'))
                <div class="invalid-feedback">{{ $errors->first('town_city') }}</div>
            @endif
        </div>
    </div> -->

    <div class="col-md-12">
        <label>{{ __('Important Information') }} <span style="color:red;">*</span></label>
        <div class="form-group">
            {{ Form::textarea('important_information', null,
                [
                    'class' => 'form-control' . ($errors->has('important_information') ? ' is-invalid' : ''),
                    'placeholder' => __('Type or copy/paste  important information here'),
                    'rows' => 3,
                    'required',
                    'data-parsley-required-message'=>"This value is required.",
                ]
            ) }}

            @if ($errors->has('important_information'))
                <div class="invalid-feedback">{{ $errors->first('important_information') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-12">
        <label>{{ __('Terms') }} <span style="color:red;">*</span></label>
        <div class="form-group">
            {{ Form::textarea('terms', null,
                [
                    'class' => 'form-control' . ($errors->has('terms') ? ' is-invalid' : ''),
                    'placeholder' => __('Type or copy/paste terms and info here'),
                    'rows' => 3,
                    'required',
                    'data-parsley-required-message'=>"This value is required.",
                ]
            ) }}

            @if ($errors->has('terms'))
                <div class="invalid-feedback">{{ $errors->first('terms') }}</div>
            @endif
        </div>
    </div>

    <!-- <div class="col-md-12">
        <label>{{ __('Shipping Info') }} <span style="color:red;">*</span></label>
        <div class="form-group">
            {{ Form::text('shipping_info', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('shipping_info') ? ' is-invalid' : ''),
                    'placeholder' => __('Shipping Info*')
                ])
            }}

            @if ($errors->has('shipping_info'))
                <div class="invalid-feedback">{{ $errors->first('shipping_info') }}</div>
            @endif
        </div>
    </div> -->

    <div class="col-md-4">
        <label>{{ __('Mobile Number') }} <span style="color:red;">*</span></label>
        <div class="form-group">
            {{ Form::text('telephone_number', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('telephone_number') ? ' is-invalid' : ''),
                    'placeholder' => __('Mobile Number'),
                    'required',
                    'data-parsley-required-message'=>"This value is required.",
                ])
            }}

            @if ($errors->has('telephone_number'))
                <div class="invalid-feedback">{{ $errors->first('telephone_number') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-4">
        <label>{{ __('Email') }} <span style="color:red;">*</span></label>
        <div class="form-group">
            {{ Form::email('email', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('email') ? ' is-invalid' : ''),
                    'placeholder' => __('Email'),
                    'id' => 'email',
                    'required',
                    'data-parsley-required-message'=>"This value is required.",
                ])
            }}
             <p style='display: none;cursor: pointer;' id='email_suggestion' >Copy this email to all email holder.</p>
            @if ($errors->has('email'))
                <div class="invalid-feedback">{{ $errors->first('email') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-4">
        <label>{{ __('Website') }} <span style="color:red;">*</span></label>
        <div class="form-group">
            {{ Form::text('website', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('website') ? ' is-invalid' : ''),
                    'placeholder' => __('Website'),
                    'required',
                    'data-parsley-required-message'=>"This value is required.",
                ])
            }}

            @if ($errors->has('website'))
                <div class="invalid-feedback">{{ $errors->first('website') }}</div>
            @endif
        </div>
    </div>

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

<!-- <div class='row'> -->
    <!-- <div class="col-md-4">
        <label>{{ __('Automatic Refund?') }}</label>
        <div class="form-group">
            Yes {{ Form::radio('automatic_refund', 1) }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            No {{ Form::radio('automatic_refund', 0, true) }}

            @if ($errors->has('automatic_refund'))
                <div class="invalid-feedback">{{ $errors->first('automatic_refund') }}</div>
            @endif
        </div>
    </div>

    <div class="col-md-4">
        <label>{{ __('Automatic deposite?') }}</label>
        <div class="form-group">
            Yes {{ Form::radio('automatic_deposite', 1) }}&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            No {{ Form::radio('automatic_deposite', 0, true) }}

            @if ($errors->has('automatic_deposite'))
                <div class="invalid-feedback">{{ $errors->first('automatic_deposite') }}</div>
            @endif
        </div>
    </div> -->

    <!-- <div class="col-md-4" id='minimun_deposite' style='display: none;'>
        <label>{{ __('Minimun deposite') }} <span style="color:red;">*</span></label>
        <div class="form-group">
            {{ Form::text('minimum_deposite', 0, [
                    'class' => 'form-control form-control-md' . ($errors->has('minimum_deposite') ? ' is-invalid' : ''),
                    'placeholder' => __('Minimun Deposite*'),
                ])
            }}

            @if ($errors->has('minimum_deposite'))
                <div class="invalid-feedback">{{ $errors->first('minimum_deposite') }}</div>
            @endif
        </div>
    </div> -->
<!-- </div> -->

<div class='row'>
    <div class="col-md-4">
        <label>{{ __('Increment Set Name') }} <span style="color:red;">*</span></label>
        <div class="form-group">
            {{ Form::text('increment_set_name', null, [
                    'class' => 'form-control form-control-md' . ($errors->has('increment_set_name') ? ' is-invalid' : ''),
                    'placeholder' => __('Increment Set Name'),
                    'required',
                    'data-parsley-required-message'=>"This value is required.",
                ])
            }}

            @if ($errors->has('increment_set_name'))
                <div class="invalid-feedback">{{ $errors->first('increment_set_name') }}</div>
            @endif
        </div>
    </div>

    <!-- <div class="col-md-12">
        <label>{{ __('Winner Notification Note') }}</label>
        <div class="form-group">
            {{ Form::textarea('winner_notification_note', null,
                [
                    'class' => 'form-control' . ($errors->has('winner_notification_note') ? ' is-invalid' : ''),
                    'placeholder' => __('Type or copy/paste  winner notification note here'),
                    'rows' => 3,
                ]
            ) }}

            @if ($errors->has('winner_notification_note'))
                <div class="invalid-feedback">{{ $errors->first('winner_notification_note') }}</div>
            @endif
        </div>
    </div> -->

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
        <label>{{ __('Hide venue address for lot locations?') }}</label>
        <div class="form-group">
            Yes {{ Form::radio('hide_venue_address_for_lot_locations', 1) }}
            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            No {{ Form::radio('hide_venue_address_for_lot_locations', 0, true) }}

            @if ($errors->has('hide_venue_address_for_lot_locations'))
                <div class="invalid-feedback">{{ $errors->first('hide_venue_address_for_lot_locations') }}</div>
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
            {{ Form::textarea('auction_detail', null,
                [
                    'class' => 'form-control',
                    'placeholder' => __('Type or copy/paste auction detail here'),
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