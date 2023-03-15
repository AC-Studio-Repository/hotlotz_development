<table class="table table-striped table-hover table-responsive" style="overflow-x:auto;">
    <thead>
	    <tr>
	        <th width="5%">{{ __('Cover') }}</th>
	        <th width="15%">{{ __('Title') }}</th>
	        <th width="10%">{{ __('Dates') }}</th>
	        <th width="5%">{{ __('Status') }}</th>
	        <th width="5%">{{ __('TSR ID') }}</th>
			<th width="20%">{{ __('Totals') }}</th>
			<th>{{ __('Result') }}</th>
	        <th style="width: 5%">Actions</th>
	    </tr>
	</thead>
	<tbody>
	    @foreach($auctions as $auction)
	        <tr>
	            <td>
					<img onclick="imagepreview(this)" lazyload="on" src="{{ $auction->full_path }}" alt="{{ $auction->file_name }}" width="150px" height="auto">
	            </td>
	            <td>
					@can('view auctions')
						<span class="font-lg mb-3 font-weight-bold">
							<a href="{{ route('auction.auctions.show', $auction) }}">{{ $auction->title }}</a>
						</span>
					@else
						<div class="text-muted">
							{{ $auction->title }}
						</div>
					@endcan
	            </td>
	            <td>
	                <div class="mb-3">
						Published <br>
						{{ date_format(date_create($auction->timed_start), 'd/m/Y h:i') }}
					</div>
					 <div class="">
						Closes <br>
						{{ date_format(date_create($auction->timed_first_lot_ends), 'd/m/Y h:i') }}
					</div>
	            </td>
	            <td>
					<div class="mt-2">
						<span class="badge badge-pill badge-success">{{ $auction->status }}</span>
					</div>
	            </td>
					            <td>
					<div class="mt-2 font-md font-weight-bold">
						<a href="https://toolbox.globalauctionplatform.com/auction" target="_blank">{{ $auction->sr_reference }}</a>
					</div>
	            </td>
	            <td class="font-sm">
					<div class="row">
						<div class="col-5">
							Total Lots <br>
							{{ $auction->getAuctionResultByType($auction->id, 'total_lots') }}
						</div>
						<div class="col-7">
							Low Estimate <br>
							$ {{ number_format($auction->getAuctionResultByType($auction->id, 'low_estimate')) }}
						</div>
						<div class="col-5 mt-4">
							Total Bids <br>
							{{ $auction->getAuctionResultByType($auction->id, 'total_bids') }}
						</div>
						<div class="col-7 mt-4">
							High Estimate <br>
							$ {{ number_format($auction->getAuctionResultByType($auction->id, 'high_estimate')) }}
						</div>
					</div>
				</td>
	            <td>
					<div class="row">
						<div class="col-12">
							Hammer Total <br>
							$ {{ number_format($auction->getAuctionResultByType($auction->id, 'hammer_total')) }}
						</div>

						<div class="col-5 mt-4">
							Lots Sold <br>
							{{ $auction->getAuctionResultByType($auction->id, 'lots_sold') }}
						</div>
						<div class="col-7 mt-4">
							Percentage Sold <br>
							{{ $auction->getAuctionResultByType($auction->id, 'percentage_sold') }}
						</div>
					</div>
	            </td>
	            <td>
	                <div class="mt-2">
						@can('edit auctions')
		                    @if( $auction->is_closed != 'Y' )
		                        <a href="{{ route('auction.auctions.edit',$auction) }}"
		                           class="btn btn-xs btn-outline-primary btn-show-on-tr-hover mb-3">{{ __('Edit') }}</a>
								<br>
		                    @endif
						@endcan	                    

		                @can('delete auctions')
			                <button type="button" class="btn btn-xs btn-outline-danger" id="btnDeleteConfirm" data-id="{{ $auction->id }}" data-name="{{ $auction->title }}" >{{ __('Delete') }}</button>
			            @endcan
	                </div>
	            </td>
	        </tr>
	    @endforeach
	</tbody>
</table>

@if(count($auctions)>0)
    <hr>
    <nav>
        {!! $auctions->links() !!}
    </nav>
@endif