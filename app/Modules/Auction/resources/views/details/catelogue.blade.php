<div class="form-group row{{ $errors->has('type') ? ' has-danger' : '' }}">
    <label class="form-control-label col-md-2">{{ __('Auction type') }}</label>
    <div class="col-md-10">
        @foreach($types as $key => $value)
            <label class="radio-inline" for="type_{{ $key }}">
                {{ Form::radio('type', $key, ($auction->type == $key)?true:false, ['disabled']) }}
                {{ $value }}
                &nbsp;
            </label>
        @endforeach
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <label class="form-control-label">{{ __('Auction Title') }} <span style="color:red;">*</span></label>
        <div class="form-group">
            {{ Form::text('title', $auction->title, [
                    'class' => 'form-control form-control-md',
                    'disabled',
                ])
            }}
        </div>
    </div>
    <div class="col-md-4">
        <label class="form-control-label">{{ __('Buyers Premium') }} <span style="color:red;">*</span></label>
        <div class="form-group">
            <div class="input-group">
                {{ Form::text('buyers_premium', $auction->buyers_premium, [
                        'class' => 'form-control form-control-md',
                        'disabled'
                    ])
                }}
                <span class="input-group-addon">%</span>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <label class="form-control-label">&nbsp;</label>
        <div class="input-group">
            <label class="checkbox-inline form-control-label" for="is_gst">
                {{ Form::checkbox('is_gst', 'Y', ($auction->is_gst == 'Y')?'checked':'', [
                        'disabled'
                    ])
                }}
                GST
                &nbsp;
            </label>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <label class="form-control-label">{{ __('Confirmation Email') }} <span style="color:red;">*</span></label>
        <div class="form-group">
            {{ Form::email('confirmation_email', $auction->confirmation_email, [
                    'class' => 'form-control form-control-md',
                    'id' => 'confirmation_email',
                    'disabled'
                ])
            }}
        </div>
    </div>

    <div class="col-md-4">
        <label class="form-control-label">{{ __('Registration Email') }} <span style="color:red;">*</span></label>
        <div class="form-group">
            {{ Form::email('registration_email', $auction->registration_email, [
                    'class' => 'form-control form-control-md',
                    'id' => 'registration_email',
                    'disabled'
                ])
            }}
        </div>
    </div>

    <div class="col-md-4">
        <label class="form-control-label">{{ __('Payment Receive Email') }} <span style="color:red;">*</span></label>
        <div class="form-group">
            {{ Form::email('payment_receive_email', $auction->payment_receive_email, [
                    'class' => 'form-control form-control-md',
                    'id' => 'payment_receive_email',
                    'disabled'
                ])
            }}
        </div>
    </div>
</div>

<div class='row'>
    <div class="col-md-4">
        <label class="form-control-label">{{ __('Time Start') }} <span style="color:red;">*</span></label>
        <div class="form-group">
            {{ Form::text('timed_start', $auction->timed_start, [
                    'class' => 'form-control form-control-md',
                    'id' => 'time_start',
                    'disabled'
                ])
            }}
        </div>
    </div>
    <div class="col-md-4">
        <label class="form-control-label">{{ __('Time First Lot Ends') }} <span style="color:red;">*</span></label>
        <div class="form-group">
            {{ Form::text('timed_first_lot_ends', $auction->timed_first_lot_ends, [
                    'class' => 'form-control form-control-md',
                    'id' => 'time_first_lot_ends',
                    'disabled'
                ])
            }}
        </div>
    </div>
    <div class="col-md-4">
        <label>{{ __('Saleroom Category') }} <span style="color:red;">*</span></label>
        <div class="form-group">
            {{ Form::text('sr_category_name', $auction->sr_category_name ?? null, [
                    'class' => 'form-control form-control-md',
                    'id' => 'sr_category_name',
                    'disabled'
                ])
            }}
        </div>
    </div>
</div>

<div class='row'>
    <div class="col-md-4">
        <label>{{ __('Auction Image') }} (576px x 430px) <span style="color:red;">*</span> </label>
        <div class="form-group">
            <img onclick="imagepreview(this)" lazyload="on" id="auction_image" src="{{ isset($auction->full_path)?$auction->full_path:'' }}" class="img-responsive" width="300" height="200">
        </div>
    </div>
    <div class="col-md-4">
        <label>{{ __('Sale Type') }} <span style="color:red;">*</span></label>
        <div class="form-group">
            {{ Form::text('sale_type', $sale_types[$auction->sale_type] ?? null, [
                    'class' => 'form-control form-control-md',
                    'disabled',
                ])
            }}
        </div>
    </div>
</div>

<hr>

<h5 class="form-control-label mb-4">{{ __('Coming Auction') }}</h5>
<div class="row">

    <div class="col-md-4">
        <label>{{ __('Auction Title') }}</label>
        <div class="form-group">
            {{ Form::text('title',  ($auction->title)?$auction->title:null, [
                    'class' => 'form-control form-control-md',
                    'placeholder' => __('Auction Title'),
                    'disabled',
                ])
            }}
        </div>
    </div>
    <div class="col-md-4">
        <label>{{ __('Viewing Date Start') }} </label>
        <div class="form-group">
            {{ Form::text('viewing_date_start', ($auction->viewing_date_start)?$auction->viewing_date_start:null, [
                    'class' => 'form-control form-control-md',
                    'placeholder' => __('Write Viewing Date Start'),
                    'disabled'
                ])
            }}
        </div>
    </div>

    <div class="col-md-4">
        <label>{{ __('Viewing Date Ends') }} </label>
        <div class="form-group">
            {{ Form::text('viewing_date_end', ($auction->viewing_date_end)?$auction->viewing_date_end:null, [
                    'class' => 'form-control form-control-md',
                    'placeholder' => __('Write Viewing Date Ends'),
                     'disabled'
                ])
            }}

        </div>
    </div>

     <div class="col-md-12">
        <label>{{ __('Auction Details') }}</label>
        <div class="form-group">
            {{ Form::textarea('auction_detail', ($auction->auction_detail)?$auction->auction_detail:null,
                [
                    'class' => 'form-control',
                    'placeholder' => __('Type or copy/paste auction detail here'),
                    'rows' => 4,
                    'disabled'
                ]
            ) }}
        </div>
    </div>

    <div class="col-md-4">
        <label>{{ __('Consignment Deadline') }} </label>
        <div class="form-group">
            {{ Form::text('consignment_deadline', ($auction->consignment_deadline)?$auction->consignment_deadline:null, [
                    'class' => 'form-control form-control-md',
                    'placeholder' => __('Select Consignment Deadline'),
                    'disabled'
                ])
            }}

        </div>
    </div>

    <div class="col-md-8">
        <label>{{ __('Consignment Information') }}</label>
        <div class="form-group">
            {{ Form::textarea('consignment_info', ($auction->consignment_info)?$auction->consignment_info:null,
                [
                    'class' => 'form-control',
                    'placeholder' => __('Type or copy/paste consignment information here'),
                    'rows' => 4,
                    'disabled'
                ]
            ) }}
        </div>
    </div>

    <div class="col-md-4">
        <label>{{ __('Banner Image') }} (1920px x 480px) <span style="color:red;">*</span> </label>

        <div class="form-group">

            <img onclick="imagepreview(this)" lazyload="on" id="banner1" src="{{ isset($auction->banner_full_path)?$auction->banner_full_path:'' }}" class="img-responsive" width="895px" height="240px">
        </div>
    </div>

</div>
