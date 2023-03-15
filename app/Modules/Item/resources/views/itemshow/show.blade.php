@extends('appshell::layouts.default')

@section('title')
    {{ __('Item Details') }}
@stop

@php
    use \App\Modules\Item\Models\Item;
@endphp

@section('content')
    <div class="card">
        <div class="card-block">
            @can('edit items')
                <a href="{{ route('item.items.edit_item', [$item, $tab_name] ) }}" class="btn btn-outline-info">{{ __('Edit Item') }}</a>

                @if( in_array($item->status, [Item::_SWU_, Item::_PENDING_]) )
                    <button type="button" class="btn btn-outline-warning" id="btnDeclined" data-toggle="modal" data-target="#btnDeclinedItemModal">{{ __('Decline') }}</button>
                @endif

                @if( $item->status == Item::_SWU_ )
                    <button type="button" class="btn btn-outline-warning" id="btnPending" data-id="{{ $item->id }}">{{ __('Pending') }}</button>
                @endif

                @can('access withdraw')
                    @if( in_array($item->status, [Item::_IN_MARKETPLACE_, Item::_STORAGE_]) )
                        <button type="button" class="btn btn-outline-info" id="btnWithdrawn" data-toggle="modal" data-target="#btnWithdrawnItemModal">{{ __('Withdraw') }}</button>
                    @endif
                @endcan

                @can('access internal withdraw')
                    @if( (in_array($item->status, [Item::_PENDING_IN_AUCTION_, Item::_IN_AUCTION_, Item::_IN_MARKETPLACE_, Item::_DECLINED_, Item::_UNSOLD_]) || ($item->permission_to_sell == 'Y' && $item->is_cataloguing_approved != 'Y' && $item->status == Item::_PENDING_)) && $item->tag != 'dispatched' )
                        <button type="button" class="btn btn-outline-info" id="btnInternalWithdrawn" data-toggle="modal" data-target="#btnInternalWithdrawnItemModal">{{ __('Internal Withdraw') }}</button>
                    @endif
                @endcan

                @if( in_array($item->status, [Item::_PAID_, Item::_SETTLED_, Item::_WITHDRAWN_, Item::_UNSOLD_, Item::_ITEM_RETURNED_]) && $item->tag != 'dispatched')
                    <button type="button" class="btn btn-outline-info" id="btnDispatched" data-toggle="modal" data-target="#btnDispatchedItemModal">{{ __('Dispatch') }}</button>

                    @if($item->delivery_booked != 'Y')
                        <button type="button" class="btn btn-outline-info" id="btnDeliveryBooked" data-toggle="modal" data-target="#btnDeliveryBookedModal">{{ __('Delivery/pickup Booked') }}</button>
                    @endif
                @endif

                @can('access cancel sale')
                    @if( $item->status == Item::_SOLD_ && ($item->lifecycle_status == Item::_AUCTION_ || $item->lifecycle_status == Item::_PRIVATE_SALE_) && $item->is_credit_noted != 'Y' )
                        <button type="button" class="btn btn-outline-info" id="btnCancelSale" data-toggle="modal" data-target="#btnCancelSaleModal">{{ __('Cancel Sale') }}</button>
                    @endif
                @endcan

                @can('access credit note')
                    @if( in_array($item->status, [Item::_SOLD_, Item::_PAID_, Item::_SETTLED_]) && $item->is_credit_noted != 'Y' && $item->tag != 'dispatched' )
                        <button type="button" class="btn btn-outline-info" id="btnCreditNote" data-toggle="modal" data-target="#btnCreditNoteModal">{{ __('Credit Note') }}</button>
                    @endif
                @endcan

                @if( $item->permission_to_sell != 'Y' && $item->is_valuation_approved == 'Y' && $item->is_fee_structure_approved == 'Y' && $item->is_hotlotz_own_stock != 'Y' && $item->status == Item::_PENDING_ )
                    <a href="{{ route('item.items.request_for_permission', $item) }}" class="btn btn-outline-info">{{ __('Request for Permission')}}</a>
                @endif

                @can('access cancel dispatch')
                    @if( in_array($item->status, [Item::_PAID_, Item::_SETTLED_, Item::_WITHDRAWN_, Item::_UNSOLD_, Item::_ITEM_RETURNED_]) && $item->tag == 'dispatched' )
                        <button type="button" class="btn btn-outline-info" id="btnCancelDispatch" data-toggle="modal" data-target="#btnCancelDispatchModal">{{ __('Cancel Dispatch') }}</button>
                    @endif
                @endcan

            @endcan

            @can('delete items')
                @if( in_array($item->status, [Item::_SWU_, Item::_PENDING_]) && $item->lifecycle_status == null )
                    <button type="button" class="btn btn-outline-danger float-right" id="btnDeleteConfirm" data-id="{{ $item->id }}" data-name="{{ $item->name }}" >{{ __('Delete Item') }}</button>
                @endif
            @endcan

        </div>
    </div>
    <div class="card">
        <div class="card-header font-sm">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link {{ ($tab_name == 'overview')?'active':'' }}" id="overview-tab" data-toggle="tab" href="{{ route('item.items.show_item', [$item, 'overview'] ) }}" role="tab" aria-controls="overview" aria-selected="{{ ($tab_name == 'overview')?'true':'false' }}" data-tab_name="overview" onclick="clickTab(this)">{{ __('Overview') }}</a>

                    <a class="nav-item nav-link {{ ($tab_name == 'cataloguing')?'active':'' }}" id="cataloguing-tab" data-toggle="tab" href="{{ route('item.items.show_item', [$item, 'cataloguing'] ) }}" role="tab" aria-controls="cataloguing" aria-selected="{{ ($tab_name == 'cataloguing')?'true':'false' }}" data-tab_name="cataloguing" onclick="clickTab(this)">{{ __('Cataloguing') }}</a>

                    <a class="nav-item nav-link {{ ($tab_name == 'item_lifecycle')?'active':'' }}" id="item_lifecycle-tab" data-toggle="tab" href="{{ route('item.items.show_item', [$item, 'item_lifecycle'] ) }}" role="tab" aria-controls="item_lifecycle" aria-selected="{{ ($tab_name == 'item_lifecycle')?'true':'false' }}" data-tab_name="item_lifecycle" onclick="clickTab(this)">Valuation & Lifecycle</a>

                    <a class="nav-item nav-link {{ ($tab_name == 'fee_structure')?'active':'' }}" id="fee_structure-tab" data-toggle="tab" href="{{ route('item.items.show_item', [$item, 'fee_structure'] ) }}" role="tab" aria-controls="fee_structure" aria-selected="{{ ($tab_name == 'fee_structure')?'true':'false' }}" data-tab_name="fee_structure" onclick="clickTab(this)">Fee Structure</a>

                    <a class="nav-item nav-link {{ ($tab_name == 'item_purchase')?'active':'' }}" id="item_purchase-tab" data-toggle="tab" href="{{ route('item.items.show_item', [$item, 'item_purchase'] ) }}" role="tab" aria-controls="item_purchase" aria-selected="{{ ($tab_name == 'item_purchase')?'true':'false' }}" data-tab_name="item_purchase" onclick="clickTab(this)">Purchase Details</a>

                    <a class="nav-item nav-link {{ ($tab_name == 'item_history')?'active':'' }}" id="item_history-tab" data-toggle="tab" href="{{ route('item.items.show_item', [$item, 'item_history'] ) }}" role="tab" aria-controls="item_history" aria-selected="{{ ($tab_name == 'item_history')?'true':'false' }}" data-tab_name="item_history" onclick="clickTab(this)">Item History</a>
                </div>
            </nav>
        </div>

        <div>
            <input type="hidden" name="item_id" id="item_id" value="{{$item->id}}">
            <input type="hidden" name="page_action" id="page_action" value="edit">
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade {{ ($tab_name == 'overview')?'show active':'' }}" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                    @if($tab_name == 'overview')
                        @include('item::itemshow.show_overview')
                    @endif
                </div>
                <div class="tab-pane fade {{ ($tab_name == 'cataloguing')?'show active':'' }}" id="cataloguing" role="tabpanel" aria-labelledby="cataloguing-tab">
                    @if($tab_name == 'cataloguing')
                        @include('item::itemshow.show_item_details')
                    @endif
                </div>
                <div class="tab-pane fade {{ ($tab_name == 'item_lifecycle')?'show active':'' }}" id="item_lifecycle" role="tabpanel" aria-labelledby="item_lifecycle-tab">
                    @if($tab_name == 'item_lifecycle')
                        @include('item::itemshow.show_item_lifecycle')
                    @endif
                </div>

                <div class="tab-pane fade {{ ($tab_name == 'fee_structure')?'show active':'' }}" id="fee_structure" role="tabpanel" aria-labelledby="fee_structure-tab">
                    @if($tab_name == 'fee_structure')
                        @include('item::itemshow.show_fee_structure')
                    @endif
                </div>
                <div class="tab-pane fade {{ ($tab_name == 'item_purchase')?'show active':'' }}" id="item_purchase" role="tabpanel" aria-labelledby="item_purchase-tab">
                    @if($tab_name == 'item_purchase')
                        @include('item::itemshow.show_item_purchase')
                    @endif
                </div>
                <div class="tab-pane fade {{ ($tab_name == 'item_history')?'show active':'' }}" id="item_history" role="tabpanel" aria-labelledby="item_history-tab">
                    @if($tab_name == 'item_history')
                        @include('item::itemshow.item_history')
                    @endif
                </div>
            </div>
        </div>

        <div class="card-footer">

        </div>
    </div>

    @include('item::itemshow.button_modals')
    @include('item::customer_modal')

@stop

@section('scripts')


<!-- Select2 CSS -->
<link href="{{asset('plugins/select2-develop/dist/css/select2.min.css')}}" rel="stylesheet" />
<link href="{{asset('plugins/select2-bootstrap4-theme-master/dist/select2-bootstrap4.min.css')}}" rel="stylesheet" />
<!-- Select2 JS -->
<script src="{{asset('plugins/select2-develop/dist/js/select2.full.min.js')}}"></script>

<!-- Parsley CSS -->
<link rel="stylesheet" href="{{asset('plugins/Parsley.js-2.9.1/src/parsley.css')}}" />
<!-- Parsley JS -->
<script src="{{asset('plugins/Parsley.js-2.9.1/dist/parsley.min.js')}}"></script>

<script src="{{ asset('js/admin/bootbox.min.js?v1.0') }}"></script>

<!-- ### Additional JS ### -->
<script src="{{asset('custom/js/pickadate/lib/picker.js')}}"></script>
<script src="{{asset('custom/js/pickadate/lib/picker.date.js')}}"></script>
<script src="{{asset('custom/js/pickadate/lib/picker.time.js')}}"></script>
<script src="{{asset('custom/js/pickadate/lib/legacy.js')}}"></script>


<script type="text/javascript">

    new Vue({
        el: '#app',
        data: {
            psType: "{{ old('private_sale_type') ?: $item->private_sale_type }}"
        }
    });

    var _token = $('input[name="_token"]').val();
    var tab_name = {!! json_encode($tab_name) !!};
    var user_id = {!! json_encode($user_id) !!};

    $(function(){

        $(document).on('click', '#btnDeleteConfirm', function(){
            var item_id = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            var content = 'Are you sure to delete '+name+'?';

            var response = confirm(content);
            if (response == true) {
                $.ajax({
                    url: '/manage/items/'+item_id,
                    type: 'delete',
                    data: {
                        "id": item_id,
                        "_token": _token,
                    },
                    dataType: 'json',
                    async: false,
                    success: function(response) {
                        if(response.status == 'success') {
                            bootbox.alert(response.message, function(){
                                window.location.href = "{{ route('item.items.index')}}";
                            });
                        }else {
                            bootbox.alert(response.message);
                            return false;
                        }
                    }
                });
            }
        });

        $(document).on('click', '#btnPending', function(){
            var item_id = $(this).attr('data-id');
            $.ajax({
                url: '/manage/items/'+item_id+'/set_pending_status',
                type: 'post',
                data: {
                    "id": item_id,
                    "_token": _token,
                },
                dataType: 'json',
                async: false,
                success: function(response) {
                    if(response.status == 'success') {
                        bootbox.alert(response.message, function(){
                            location.reload();
                        });
                    }else {
                        bootbox.alert(response.message);
                        return false;
                    }
                }
            });
        });

    });

    function clickTab(obj){
        var aria_selected = $(obj).attr('aria-selected');
        if(aria_selected == 'false'){
            var url = $(obj).attr('href');
            location.href = url;
        }
    }

</script>

@include('item::itemshow.show_js')

@stop