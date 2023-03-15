@extends('appshell::layouts.default')

@section('title')
    {{ __('Split Settlement') }}
@stop

@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            Total {{ $items->count() }} Item(s) found
        </div>
         @php
            use \App\Modules\Item\Models\Item;
         @endphp
        <div class="card-block">

            <table class="table table-striped" width="100%">
                <tr>
                    @if($items->count() !== 1)
                    <th>
                        #
                    </th>
                    @endif
                    <th width="10%">Photo</th>
                    <th width="10%">Item Number</th>
                    <th width="10%">Item Name</th>
                    <th>Estimate</th>
                    <th>Reserve</th>
                    <th>Status</th>
                    <th>Hammer Price/Result Price</th>
                </tr>
                @foreach($items as $key => $item)
                    <tr>
                        @if($items->count() !== 1)
                        <td>
                            {{ Form::checkbox('item_id[]', $item->id, false, [
                                    'class' => "item_id",
                                ])
                            }}
                        </td>
                         @endif
                        <td>
                            @php
                                $photo = \App\Modules\Item\Models\ItemImage::where('item_id',$item->id)->select('file_name','full_path')->first();
                            @endphp

                            @if(isset($photo))
                                <img onclick="imagepreview(this)" lazyload="on" src="{{ $photo->full_path }}" alt="{{$photo->file_name}}" width="150px" height="auto">
                            @endif
                        </td>
                        <td>
                            <a href="{{ route('item.items.show_item', [$item,'overview']) }}" target="_blank">{{ $item->item_number }}</a>
                        </td>
                        <td>
                            <a href="{{ route('item.items.show_item', [$item,'cataloguing']) }}" target="_blank">{{ $item->name }}</a>
                        </td>
                        <td>
                            ${{ number_format($item->low_estimate) }}/${{ number_format($item->high_estimate) }}
                        </td>
                        <td>
                            ${{ ($item->is_reserve == 'Y' && $item->reserve != null)?number_format($item->reserve):0 }}
                        </td>
                        <td>
                            @if($item->status == Item::_SOLD_)
                                <span class="badge badge-pill badge-warning">{{ __($item->status) }}</span>
                            @elseif($item->status == Item::_PAID_)
                                <span class="badge badge-pill badge-info">{{ __($item->status) }}</span>
                            @elseif($item->status == Item::_SETTLED_)
                                <span class="badge badge-pill badge-secondary">{{ __($item->status) }}</span>
                            @else
                                <span class="badge badge-pill badge-success">{{ __($item->status) }}</span>
                            @endif
                        </td>
                        <td>
                            @if($item->buyer_id > 0)
                            <div class="mb-3">
                                Hammer Price <br>
                                ${{ ($item->sold_price != null)?number_format($item->sold_price):0.00 }}
                            </div>
                            <div>
                                Result Price <br>
                                ${{ (isset($item->total) && $item->total != null)?number_format($item->total,2,'.',','):0.00 }}
                            </div>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </table>

        </div>

         <div class="card-footer">
            <form action="{{ route('xero.splitSettlement', $invoice_id) }}" method="post"">
            @csrf
            <input type="hidden" name="ids" id="hidden_ids">
            @if($items->count() !== 1 && $items->count() > 1)
            <button class="btn btn-outline-success" type="submit" >{{ __('Split Item(s)') }}</button>
            @endif
            <a href="../"  class="btn btn-outline-danger">{{ __('Back') }}</a>
            </form>
        </div>
    </div>

@stop

@section('scripts')
<script>
    var hidden_id_list = [];

    $(document).on('click', '.item_id', function(){
        var item_value = $(this).val();
        if( $(this).is(":checked") ){
            hidden_id_list.push( $(this).val() );
        }else{
            var item_index = hidden_id_list.indexOf(item_value);
            if(item_index !== -1){
                hidden_id_list.splice(item_index, 1);
            }
        }
         $('#hidden_ids').val( JSON.stringify(hidden_id_list) );
    });
</script>
@stop
