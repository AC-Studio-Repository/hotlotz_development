<div class="card-block">
	<div class="row">
	    <div class="form-group col-12 col-md-6 col-xl-6">
	        <label class="form-control-label">{{ __('Item Registration') }}</label>
	        {{ Form::text('registration_date', $item->registration_date ?? null, [
	                'class' => 'form-control',
	                'disabled'
	            ])
	        }}
	    </div>

	    <div class="form-group col-12 col-md-6 col-xl-6">
	        <label class="form-control-label">{{ __('Seller Agreement Signed') }}</label>
	        {{ Form::text('seller_agreement_signed_date', $item->seller_agreement_signed_date ?? null, [
	                'class' => 'form-control',
	                'disabled'
	            ])
	        }}
	    </div>
	</div>
    <div class="row">
	    <div class="col-12 col-md-12 col-xl-12">
	        <label class="form-control-label">{{ __('Auction History') }}</label>
	        <div class="form-group">
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
	<div class="row">
	    <div class="form-group col-12 col-md-6 col-xl-6">
	        <label class="form-control-label">{{ __('Entered Marketplace') }}</label>
	        {{ Form::text('entered_marketplace_date', $item->entered_marketplace_date ?? null, [
	                'class' => 'form-control',
	                'disabled'
	            ])
	        }}
	    </div>

	    <div class="form-group col-12 col-md-6 col-xl-6">
	        <label class="form-control-label">{{ __('Entered Clearance') }}</label>
	        {{ Form::text('entered_clearance_date', $item->entered_clearance_date ?? null, [
	                'class' => 'form-control',
	                'disabled'
	            ])
	        }}
	    </div>
	</div>
	<div class="row">
	    <div class="form-group col-12 col-md-6 col-xl-6">
	        <label class="form-control-label">{{ __('Sold') }}</label>
	        {{ Form::text('sold_date', $item->sold_date ?? null, [
	                'class' => 'form-control',
	                'disabled'
	            ])
	        }}
	    </div>

	    <div class="form-group col-12 col-md-6 col-xl-6">
	        <label class="form-control-label">{{ __('Settled') }}</label>
	        {{ Form::text('settled_date', $item->settled_date ?? null, [
	                'class' => 'form-control',
	                'disabled'
	            ])
	        }}
	    </div>
	</div>
	<div class="row">
	    <div class="form-group col-12 col-md-6 col-xl-6">
	        <label class="form-control-label">{{ __('Entered Storage') }}</label>
	        {{ Form::text('storage_date', $item->storage_date ?? null, [
	                'class' => 'form-control',
	                'disabled'
	            ])
	        }}
	    </div>

	    <div class="form-group col-12 col-md-6 col-xl-6">
	        <label class="form-control-label">{{ __('Storage Emails Sent') }}</label>        	
    		<p>
    			<span>{{ $item->storage_email1_date }}</span>
    			&nbsp;&nbsp; / &nbsp;&nbsp;
    			<span>{{ $item->storage_email2_date }}</span>
    		</p>
	    </div>
	</div>
	<div class="row">
	    <div class="form-group col-12 col-md-6 col-xl-6">
	        <label class="form-control-label">{{ __('Withdrawn') }}</label>
	        {{ Form::text('withdrawn_date', $item->withdrawn_date ?? null, [
	                'class' => 'form-control',
	                'disabled'
	            ])
	        }}
	    </div>
	    <div class="form-group col-12 col-md-6 col-xl-6">
	        <label class="form-control-label">{{ __('Decline') }}</label>
	        {{ Form::text('declined_date', $item->declined_date ?? null, [
	                'class' => 'form-control',
	                'disabled'
	            ])
	        }}
	    </div>
	</div>
	<div class="row">
	    <div class="form-group col-12 col-md-6 col-xl-6">
	        <label class="form-control-label">{{ __('Dispatched or Collected') }}</label>
	        {{ Form::text('dispatched_date', $item->dispatched_or_collected_date ?? null, [
	                'class' => 'form-control',
	                'disabled'
	            ])
	        }}
	    </div>

	    
	    <div class="form-group col-12 col-md-6 col-xl-6">
	        <label class="form-control-label">{{ __('Dispatched Person') }}</label>
	        {{ Form::text('dispatched_person', $item->dispatched_person ?? null, [
	                'class' => 'form-control',
	                'disabled'
	            ])
	        }}
	    </div>
	</div>
	<div class="row">
	    <div class="form-group col-12 col-md-6 col-xl-6">
	        <label class="form-control-label">{{ __('Dispatched Note') }}</label>
	        {{ Form::textarea('dispatched_remark', $item->dispatched_remark ?? null, [
	                'class' => 'form-control',
	                'rows' => 5,
	                'disabled'
	            ])
	        }}
	    </div>
	</div>
</div>