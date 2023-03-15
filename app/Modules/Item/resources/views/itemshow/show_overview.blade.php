@php
    use \App\Modules\Item\Models\Item;
@endphp
<div class="card-block">
    <div class="row">
	    <div class="form-group col-12 col-md-6 col-xl-6">
	        <label class="form-control-label">{{ __('Item Name') }}</label>
	        {{ Form::text('name', $item->name, [
	                'class' => 'form-control',
	                'disabled'
	            ])
	        }}
	    </div>
	    <div class="form-group col-12 col-md-6 col-xl-6">
	        <label class="form-control-label">{{ __('Item Status') }}</label>
	        <div class="form-group">
	        	<a class="btn btn-md btn-success" style="width:100%;" href="#">{{ $item->status }}</a>
		    </div>
	    </div>
	</div>
	<div class="row">
	    <div class="form-group col-12 col-md-6 col-xl-6">
	        <label class="form-control-label">{{ __('Item Number') }}</label>
	        {{ Form::text('item_number', $item->item_number, [
	                'class' => 'form-control',
	                'disabled'
	            ])
	        }}
	    </div>

	    <div class="form-group col-12 col-md-6 col-xl-6">
	        <label class="form-control-label">{{ __('Category') }}</label>
	        {{ Form::text('category', (isset($item->category) && isset($item->category_id))?$item->category->name:null, [
	                'class' => 'form-control',
	                'disabled'
	            ])
	        }}
	    </div>
	</div>
	<div class="row">
		<div class="form-group col-12 col-md-6 col-xl-6">
			<div class="row">
			    <div class="form-group col-12 col-md-12 col-xl-12">
			        <label class="form-control-label">{{ __('Description') }}</label>
			        {{ Form::textarea('long_description', $item->long_description,
			            [
			                'class' => 'form-control',
			                'rows' => 5,
			                'disabled'
			            ]
			        ) }}
			    </div>
			    <div class="form-group col-12 col-md-12 col-xl-12">
			        <label class="form-control-label">{{ __('Internal Notes') }}</label>
			        {{ Form::textarea('internal_notes', $item->internal_notes,
			            [
			                'class' => 'form-control',
			                'rows' => 5,
			                'disabled'
			            ]
			        ) }}
			    </div>
			</div>
	    </div>
	    <div class="form-group col-12 col-md-6 col-xl-6">
	        <label class="form-control-label">{{ __('Item Image') }}</label>
	        <div>
		        @php
	                $photo = \App\Modules\Item\Models\ItemImage::where('item_id',$item->id)->select('full_path')->first();
	            @endphp

	            @if(isset($photo))
			        <label style="width: 100%; height: auto; text-align: center;">
		            	<img onclick="imagepreview(this)" lazyload="on" src="{{ $photo->full_path }}" alt="{{ $photo->file_name }}" style="width:auto;height:auto;max-width:100%;max-height:100%;">
		            </label>
	            @endif
		    </div>
	    </div>
	</div>
	
	<div class="row">
	    <div class="col-12 col-md-6 col-xl-6">
	        <label class="form-control-label">{{ __('Seller') }}</label>
	        <div class="form-group">
	        	@if(isset($item->customer) && isset($item->customer_id))
		        	<a class="btn btn-md btn-success" style="width:100%;" href="{{ route('customer.customers.show', $item->customer) }}" target="_blank">{{ $item->customer->ref_no }}_{{ $item->customer->fullname }}</a>
		        @endif
		    </div>
	    </div>

	    <div class="form-group col-12 col-md-6 col-xl-6">
	        <label class="form-control-label">{{ __('Location') }}</label>
	        {{ Form::text('location', $item->location, [
	                'class' => 'form-control',
	                'disabled'
	            ])
	        }}
	    </div>
	</div>

	@if(in_array($item->status,[Item::_SOLD_,Item::_PAID_,Item::_SETTLED_]))
	<div class="row">
	    <div class="col-12 col-md-6 col-xl-6">
	        <label class="form-control-label">{{ __('Buyer') }}</label>
	        <div class="form-group">
	        	@if(isset($item->buyer) && $item->buyer_id>0)
		        	<a class="btn btn-md btn-success" style="width:100%;" href="{{ route('customer.customers.show', $item->buyer) }}" target="_blank">{{ $item->buyer->ref_no }}_{{ $item->buyer->fullname }}</a>
		        @endif
		    </div>
	    </div>

	    <div class="form-group col-12 col-md-6 col-xl-6">
	        <label class="form-control-label">{{ __('Auction / Marketplace') }}</label>
	        @php
	        	$where_sold = Item::_AUCTION_;
	        	if($item->lifecycle_status == Item::_MARKETPLACE_){
	        		$where_sold = Item::_MARKETPLACE_;
		        }
	        	if($item->lifecycle_status == Item::_CLEARANCE_){
	        		$where_sold = Item::_CLEARANCE_;
		        }
	        @endphp
	        {{ Form::text('where_sold', $where_sold, [
	                'class' => 'form-control',
	                'disabled'
	            ])
	        }}
	    </div>
	</div>
	<div class="row">
	    <div class="form-group col-12 col-md-6 col-xl-6">
	        <label class="form-control-label">{{ __('Sold Price') }}</label>
	        {{ Form::text('sold_price', number_format($item->sold_price), [
	                'class' => 'form-control',
	                'disabled'
	            ])
	        }}
	    </div>
	    <div class="form-group col-12 col-md-6 col-xl-6">
	        <label class="form-control-label">{{ __('Item History') }}</label>
	        <div>
	        	@foreach($auction_histories as $auction)
	        		<p>
	        			<span><a href="{{ route('auction.auctions.show', $auction['auction']) }}" title="Description">{{ $auction['name'] }}</a>
	        			</span>
	        			&nbsp;&nbsp; / &nbsp;&nbsp;
	        			<span>{{ $auction['entered_date'] }}</span>
	        			&nbsp;&nbsp; / &nbsp;&nbsp;
	        			<span><a href="{{ $auction['bidders_list'] }}" target="_blank" style="color: red;">{{ __('List of underbidders') }}</a></span>
	        		</p>
	        	@endforeach
	        </div>
	    </div>
	</div>
	@endif

	<div class="row">
	    <div class="form-group col-12 col-md-6 col-xl-6">
	        <label class="form-control-label">{{ __('Sales Contract Date') }}</label>
	        {{ Form::text('seller_agreement_signed_date', $item->seller_agreement_signed_date, [
	                'class' => 'form-control',
	                'disabled'
	            ])
	        }}
	    </div>
	    <div class="form-group col-12 col-md-6 col-xl-6">
	        <label class="form-control-label">{{ __('Reserve') }}</label>
	        {{ Form::text('reserve', ($item->reserve != null)?number_format($item->reserve):0, [
	                'class' => 'form-control',
	                'disabled'
	            ])
	        }}
	    </div>
	</div>
	<div class="row">
	    <div class="form-group col-12 col-md-6 col-xl-6">
	        <label class="form-control-label">{{ __('Low Estimate') }}</label>
	        {{ Form::text('low_estimate', ($item->low_estimate != null)?number_format($item->low_estimate):0, [
	                'class' => 'form-control',
	                'disabled'
	            ])
	        }}
	    </div>
	    <div class="form-group col-12 col-md-6 col-xl-6">
	        <label class="form-control-label">{{ __('High Estimate') }}</label>
	        {{ Form::text('high_estimate', ($item->high_estimate != null)?number_format($item->high_estimate):0, [
	                'class' => 'form-control',
	                'disabled'
	            ])
	        }}
	    </div>
	</div>
	@if($lot_number != null)
	<div class="row">
	    <div class="form-group col-12 col-md-6 col-xl-6">
	        <label class="form-control-label">{{ __('Lot Number') }}</label>
	        {{ Form::text('lot_number', $lot_number, [
	                'class' => 'form-control',
	                'disabled'
	            ])
	        }}
	    </div>
	</div>
	@endif
</div>