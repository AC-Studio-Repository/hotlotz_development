@extends('appshell::layouts.default')

@section('title')
    {{ __('Item Details') }}
@stop

@php
    use \App\Modules\Item\Models\Item;
    $tab_name = session()->has('tab_name')?session('tab_name'):'overview';
@endphp

@section('content')
    <div class="card">
        <div class="card-block">
            @can('edit items')
                <a href="{{ route('item.items.edit', $item) }}" class="btn btn-outline-info">{{ __('Edit Item') }}</a>

                @if( in_array($item->status, [Item::_PENDING_]) )
                    <button type="button" class="btn btn-outline-warning" id="btnDeclined" data-toggle="modal" data-target="#btnDeclinedItemModal">{{ __('Decline') }}</button>
                @endif

                @if( in_array($item->status, [Item::_IN_MARKETPLACE_]) )
                    <button type="button" class="btn btn-outline-info" id="btnWithdrawn" data-toggle="modal" data-target="#btnWithdrawnItemModal">{{ __('Withdraw') }}</button>

                    <button type="button" class="btn btn-outline-info" id="btnInternalWithdrawn" data-toggle="modal" data-target="#btnInternalWithdrawnItemModal">{{ __('Internal Withdraw') }}</button>
                @endif

                @if( in_array($item->status, [Item::_PAID_, Item::_SETTLED_, Item::_WITHDRAWN_]) )
                    <button type="button" class="btn btn-outline-info" id="btnDispatched" data-toggle="modal" data-target="#btnDispatchedItemModal">{{ __('Dispatch') }}</button>

                    @if($item->delivery_booked != 'Y')
                        <button type="button" class="btn btn-outline-info" id="btnDeliveryBooked" data-toggle="modal" data-target="#btnDeliveryBookedModal">{{ __('Delivery/pickup Booked') }}</button>
                    @endif
                @endif

                @if( $item->status == Item::_SOLD_ && $item->lifecycle_status == Item::_AUCTION_ )
                    <button type="button" class="btn btn-outline-info" id="btnCancelSale" data-toggle="modal" data-target="#btnCancelSaleModal">{{ __('Cancel Sale') }}</button>
                @endif

                @if( $item->permission_to_sell != 'Y' && $item->is_valuation_approved == 'Y' && $item->is_fee_structure_approved == 'Y' && $item->is_hotlotz_own_stock != 'Y' && $item->status == Item::_PENDING_ )
                    <a href="{{ route('item.items.request_for_permission', $item) }}" class="btn btn-outline-info">{{ __('Request for Permission')}}</a>
                @endif

            @endcan

            @can('delete items')
                @if( $item->status == Item::_PENDING_ && $item->lifecycle_status == null )
                    <button type="button" class="btn btn-outline-danger float-right" id="btnDeleteConfirm" data-id="{{ $item->id }}" data-name="{{ $item->name }}" >{{ __('Delete Item') }}</button>
                @endif
            @endcan

        </div>
    </div>
    <div class="card">
        <div class="card-header font-sm">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link {{ ($tab_name == 'overview')?'active':'' }}" id="overview-tab" data-toggle="tab" href="#overview" role="tab" aria-controls="overview" aria-selected="false" data-tab_name="overview">{{ __('Overview') }}</a>

                    <a class="nav-item nav-link {{ ($tab_name == 'cataloguing')?'active':'' }}" id="cataloguing-tab" data-toggle="tab" href="#cataloguing" role="tab" aria-controls="cataloguing" aria-selected="true" data-tab_name="cataloguing">{{ __('Cataloguing') }}</a>

                    <a class="nav-item nav-link {{ ($tab_name == 'item_lifecycle')?'active':'' }}" id="item_lifecycle-tab" data-toggle="tab" href="#item_lifecycle" role="tab" aria-controls="item_lifecycle" aria-selected="false" data-tab_name="item_lifecycle">Valuation & Lifecycle</a>

                    <a class="nav-item nav-link {{ ($tab_name == 'fee_structure')?'active':'' }}" id="fee_structure-tab" data-toggle="tab" href="#fee_structure" role="tab" aria-controls="fee_structure" aria-selected="false" data-tab_name="fee_structure">Fee Structure</a>

                    <a class="nav-item nav-link {{ ($tab_name == 'item_purchase')?'active':'' }}" id="item_purchase-tab" data-toggle="tab" href="#item_purchase" role="tab" aria-controls="item_purchase" aria-selected="false" data-tab_name="item_purchase">Purchase Details</a>

                    <a class="nav-item nav-link {{ ($tab_name == 'item_history')?'active':'' }}" id="item_history-tab" data-toggle="tab" href="#item_history" role="tab" aria-controls="item_history" aria-selected="false" data-tab_name="item_history">Item History</a>
                </div>
            </nav>
        </div>

        <div>
            <input type="hidden" name="item_id" id="item_id" value="{{$item->id}}">
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade {{ ($tab_name == 'overview')?'show active':'' }}" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                    @include('item::itemshow.show_overview')
                </div>
                <div class="tab-pane fade {{ ($tab_name == 'cataloguing')?'show active':'' }}" id="cataloguing" role="tabpanel" aria-labelledby="cataloguing-tab">
                    @include('item::itemshow.show_item_details')
                </div>
                <div class="tab-pane fade {{ ($tab_name == 'item_lifecycle')?'show active':'' }}" id="item_lifecycle" role="tabpanel" aria-labelledby="item_lifecycle-tab">
                    @include('item::itemshow.show_item_lifecycle')
                </div>

                <div class="tab-pane fade {{ ($tab_name == 'fee_structure')?'show active':'' }}" id="fee_structure" role="tabpanel" aria-labelledby="fee_structure-tab">
                    @include('item::itemshow.show_fee_structure')
                </div>
                <div class="tab-pane fade {{ ($tab_name == 'item_purchase')?'show active':'' }}" id="item_purchase" role="tabpanel" aria-labelledby="item_purchase-tab">
                    @include('item::itemshow.show_item_purchase')
                </div>
                <div class="tab-pane fade {{ ($tab_name == 'item_history')?'show active':'' }}" id="item_history" role="tabpanel" aria-labelledby="item_history-tab">
                    @include('item::itemshow.item_history')
                </div>
            </div>
            <input type="hidden" name="page_action" id="page_action" value="edit">
        </div>

        <div class="card-footer">

        </div>
    </div>

    @include('item::itemshow.button_modals')
    @include('item::customer_modal')

@stop

@section('scripts')

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

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

<style type="text/css">
    #btnEdit {
        margin-top: -1.6rem;
        margin-bottom: -0.5rem;
    }
</style>

<script type="text/javascript">

    new Vue({
        el: '#app',
        data: {
            psType: "{{ old('private_sale_type') ?: $item->private_sale_type }}"
        }
    });

    var _token = $('input[name="_token"]').val();
    var tab_name = {!! json_encode($tab_name) !!};
    checkTab(tab_name);

    $(function(){

        $('.nav-item').click(function(){
            tab_name = $(this).attr('data-tab_name');
            checkTab(tab_name);
        });

        $('#divPrivateSale').hide();
        $('#btnPrivateSale').click(function(){
            $('#divPrivateSale').toggle();
        });

    });

    function checkTab(tab_name) {
        $.ajax({
            url: "/manage/items/check_tab",
            type: 'post',
            data: "tab_name="+tab_name+"&_token="+_token,
            dataType: 'json',
            async: false,
            success: function(data) {
                //
            }
        });
    }

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

</script>

@include('item::itemshow.show_js')

@stop