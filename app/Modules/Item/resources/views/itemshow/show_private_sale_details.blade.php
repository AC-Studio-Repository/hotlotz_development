<div class="row">
    <div class="form-group col-md-4">
        <label class="col-form-label">Type <span style="color:red">*</span></label>
        <div class="input-group">
            <label class="radio-inline" for="auction_type">
                {{ Form::radio('show_private_sale_type', 'auction', ($item->private_sale_type == 'auction')?true:null, [
                        'disabled',
                    ])
                }}
                Auction
                &nbsp;&nbsp;
            </label>
            <label class="radio-inline" for="privatesale_type">
                {{ Form::radio('show_private_sale_type', 'privatesale', ($item->private_sale_type == 'privatesale')?true:null, [
                        'disabled',
                    ])
                }}
                Private Sale
                &nbsp;&nbsp;
            </label>
        </div>
    </div>
    <div class="form-group col-md-4" v-show="psType == 'auction'" >
        <label class="col-form-label">Auction <span style="color:red">*</span></label>
        {{ Form::select('show_auction_id', $auctions, isset($item->private_sale_auction_id)?$item->private_sale_auction_id:null, [
                'class' => 'form-control form-control-md',
                'disabled',
            ])
        }}
    </div>
</div>
<div class="row">
    <div class="form-group col-md-4">
        <label class="col-form-label">Price <span style="color:red">*</span></label>
        {{ Form::text('show_price', isset($item->private_sale_price)?$item->private_sale_price:null, [
                'class' => 'form-control form-control-md',
                'disabled',
            ])
        }}
    </div>
    <div class="form-group col-md-4">
        <label class="col-form-label">Buyer Premium <span style="color:red">*</span></label>
        {{ Form::text('show_buyer_premium', isset($item->private_sale_buyer_premium)?$item->private_sale_buyer_premium:null, [
                'class' => 'form-control form-control-md',
                'disabled',
            ])
        }}
    </div>
    <div class="form-group col-md-4">
        <label class="col-form-label">Buyer <span style="color:red">*</span></label>
        {{ Form::text('show_buyer', isset($item->buyer_id)?($item->buyer->ref_no.'_'.$item->buyer->firstname.''.$item->buyer->lastname):null, [
                'class'=>'form-control',
                'disabled',
            ])
        }}
    </div>
</div>