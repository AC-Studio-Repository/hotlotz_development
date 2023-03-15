@extends('appshell::layouts.default')

@section('title')
    {{ __('Editing') }} {{ $item->name }}
@stop

@section('content')
    <div class="col-12 col-lg-12 col-xl-12">
        <div class="card card-accent-secondary">
            <div class="card-header font-sm">
                <nav>
                    <div class="nav nav-tabs" id="nav-tab" role="tablist">
                        <a class="nav-item nav-link {{ ($tab_name == 'overview')?'active':'' }}" id="overview-tab" data-toggle="tab" href="{{ route('item.items.edit_item', [$item, 'overview'] ) }}" role="tab" aria-controls="overview" aria-selected="{{ ($tab_name == 'overview')?'true':'false' }}" data-tab_name="overview" onclick="clickTab(this)">{{ __('Overview') }}</a>

                        <a class="nav-item nav-link {{ ($tab_name == 'cataloguing')?'active':'' }}" id="cataloguing-tab" data-toggle="tab" href="{{ route('item.items.edit_item', [$item, 'cataloguing'] ) }}" role="tab" aria-controls="cataloguing" aria-selected="{{ ($tab_name == 'cataloguing')?'true':'false' }}" data-tab_name="cataloguing" onclick="clickTab(this)">{{ __('Cataloguing') }}</a>

                        <a class="nav-item nav-link {{ ($tab_name == 'item_lifecycle')?'active':'' }}" id="item_lifecycle-tab" data-toggle="tab" href="{{ route('item.items.edit_item', [$item, 'item_lifecycle'] ) }}" role="tab" aria-controls="item_lifecycle" aria-selected="{{ ($tab_name == 'item_lifecycle')?'true':'false' }}" data-tab_name="item_lifecycle" onclick="clickTab(this)">Valuation & Lifecycle</a>

                        <a class="nav-item nav-link {{ ($tab_name == 'fee_structure')?'active':'' }}" id="fee_structure-tab" data-toggle="tab" href="{{ route('item.items.edit_item', [$item, 'fee_structure'] ) }}" role="tab" aria-controls="fee_structure" aria-selected="{{ ($tab_name == 'fee_structure')?'true':'false' }}" data-tab_name="fee_structure" onclick="clickTab(this)">Fee Structure</a>

                        <a class="nav-item nav-link {{ ($tab_name == 'item_purchase')?'active':'' }}" id="item_purchase-tab" data-toggle="tab" href="{{ route('item.items.edit_item', [$item, 'item_purchase'] ) }}" role="tab" aria-controls="item_purchase" aria-selected="{{ ($tab_name == 'item_purchase')?'true':'false' }}" data-tab_name="item_purchase" onclick="clickTab(this)">Purchase Details</a>

                        <a class="nav-item nav-link {{ ($tab_name == 'item_history')?'active':'' }}" id="item_history-tab" data-toggle="tab" href="{{ route('item.items.edit_item', [$item, 'item_history'] ) }}" role="tab" aria-controls="item_history" aria-selected="{{ ($tab_name == 'item_history')?'true':'false' }}" data-tab_name="item_history" onclick="clickTab(this)">Item History</a>                        
                    </div>
                </nav>
            </div>

            <div>
                <input type="hidden" name="item_id" id="item_id" value="{{ $item->id }}">
                <input type="hidden" name="page_action" id="page_action" value="edit">
                <div class="tab-content" id="nav-tabContent">
                    <div class="tab-pane fade {{ ($tab_name == 'overview')?'show active':'' }}" id="overview" role="tabpanel" aria-labelledby="overview-tab">
                        @if($tab_name == 'overview')
                            @include('item::itemshow.show_overview')
                        @endif
                    </div>
                    <div class="tab-pane fade {{ ($tab_name == 'cataloguing')?'show active':'' }}" id="cataloguing" role="tabpanel" aria-labelledby="cataloguing-tab">
                        @if($tab_name == 'cataloguing')
                            @include('item::itemdetails.edit_item_details')
                        @endif
                    </div>
                    <div class="tab-pane fade {{ ($tab_name == 'item_lifecycle')?'show active':'' }}" id="item_lifecycle" role="tabpanel" aria-labelledby="item_lifecycle-tab">
                        @if($tab_name == 'item_lifecycle')
                            @include('item::itemlifecycle.item_lifecycle')
                        @endif
                    </div>

                    <div class="tab-pane fade {{ ($tab_name == 'fee_structure')?'show active':'' }}" id="fee_structure" role="tabpanel" aria-labelledby="fee_structure-tab">
                        @if($tab_name == 'fee_structure')
                            @include('item::itempackage.seller_package')
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
        </div>
    </div>
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

<!-- Handlebars JS -->
<script src="{{asset('custom/js/handlebars-v4.7.3.min.js')}}"></script>


<!-- Bootstrap Multiselect with Checkbox -->
<link rel="stylesheet" href="{{asset('plugins\bootstrap-multiselect-dropdown\css\bootstrap-multiselect.css')}}">
<script src="{{asset('plugins\bootstrap-multiselect-dropdown\js\bootstrap-multiselect.js')}}"></script>


<!-- Pickadate JS -->
<script src="{{asset('custom/js/pickadate/lib/picker.js')}}"></script>
<script src="{{asset('custom/js/pickadate/lib/picker.date.js')}}"></script>
<script src="{{asset('custom/js/pickadate/lib/picker.time.js')}}"></script>
<script src="{{asset('custom/js/pickadate/lib/legacy.js')}}"></script>

<!-- ### Fileinput ### -->

<link href="{{asset('plugins/bootstrap-fileinput-5.0.8/css/fileinput.min.css')}}" media="all" rel="stylesheet" type="text/css" />
<link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}" crossorigin="anonymous">
<script src="{{asset('plugins/bootstrap-fileinput-5.0.8/js/plugins/piexif.min.js')}}" type="text/javascript"></script>
<script src="{{asset('plugins/bootstrap-fileinput-5.0.8/js/plugins/sortable.min.js')}}" type="text/javascript"></script>
<script src="{{asset('plugins/bootstrap-fileinput-5.0.8/js/plugins/purify.min.js')}}" type="text/javascript"></script>
<script src="{{asset('custom/js/popper.min.js')}}"></script>
<script src="{{asset('custom/js/bootstrap.bundle.min.js')}}" crossorigin="anonymous"></script>
<!-- the main fileinput plugin file -->
<script src="{{asset('plugins/bootstrap-fileinput-5.0.8/js/fileinput.min.js')}}"></script>
<!-- following theme script is needed to use the Font Awesome 5.x theme (`fas`) -->
<script src="{{asset('plugins/bootstrap-fileinput-5.0.8/themes/fas/theme.min.js')}}"></script>

<script type="text/javascript">

    new Vue({
        el: '#app',
        data: {
            feeType: "{{ old('fee_type') ?: $item->fee_type }}"
        }
    });

    var _token = $('input[name="_token"]').val();
    var page_action = $('#page_action').val();
    var sub_category = {!! json_encode($item->sub_category) !!};
    var condition = {!! json_encode($item->condition) !!};

    function clickTab(obj){
        var aria_selected = $(obj).attr('aria-selected');
        if(aria_selected == 'false'){
            var url = $(obj).attr('href');
            location.href = url;
        }
    }

</script>
@include('item::commonjs')

@stop