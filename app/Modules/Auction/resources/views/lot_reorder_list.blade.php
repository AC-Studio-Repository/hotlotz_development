@extends('appshell::layouts.default')

@section('title')
    {{ __('Auction Details') }}
@stop

@section('content')
<div class="card card-accent-secondary">

    <div class="card-header">
        {{ __('Lot Reordering') }}
    </div>

    {!! Form::model($auction, ['route' => ['auction.auctions.lot_reordering',$auction], 'data-parsley-validate'=>'true', 'autocomplete' => 'off']) !!}

        <div class="card-block">
            <div class="row">
                <div class="form-group col-md-12">
                    <div class="btn-group">
                        <a href="#" id="grid" class="btn btn-default btn-sm active"><i class="fas fa-th-large fa-2x"></i></a>
                        <a href="#" id="list" class="btn btn-default btn-sm"><i class="fas fa-bars fa-2x"></i></a>
                    </div>
                </div>
            </div>

            <div class="row">
                <div id="lots" class="grid-column form-group">
                    @foreach($lots as $key => $lot)
                        <div class="lot grid-square" data-id="{{ $lot['id'] }}">
                            <img onclick="imagepreview(this)" lazyload="on" class="grid_wh" src="{{ $lot['item_image'] }}">
                            &nbsp; &nbsp;

                            <input type="hidden" name="auction_item[]" value="{{ $lot['id'] }}" >

                            <span class="lot-description">
                                {{ $lot['item_name'] }}
                                &nbsp;|&nbsp;
                                {{ $lot['low_estimate'] }}
                                 -
                                {{ $lot['high_estimate'] }}
                                &nbsp;|&nbsp;
                                {{ $lot['starting_bid'] }}
                            </span>
                        </div>
                    @endforeach
                </div>
            </div>

            <!-- <ul id="lots">
              <li data-id="1">1</li>
              <li data-id="2">2</li>
              <li data-id="3">3</li>
            </ul> -->

            <!-- <div class="form-row">&nbsp;</div>
            <div class="form-row">
                <div class="col-md-12">
                    <input class="form-control form-control-md" type="text" name="hidden_lots" value="" id="hidden_lots">
                </div>
            </div> -->
        </div>

        <div class="card-footer">
            <button class="btn btn-success" id="btnLotReorder">{{ __('Reorder') }}</button>
            <a href="{{ route('auction.auctions.show',['auction'=>$auction])}}" class="btn btn-link text-muted">{{ __('Cancel') }}</a>
        </div>

    {!! Form::close() !!}
</div>
@stop

@section('scripts')

<link rel="stylesheet" href="{{ asset('fontawesome/css/all.min.css') }}" crossorigin="anonymous">


<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>
<!-- <script src="https://cdn.jsdelivr.net/npm/jquery-sortablejs@latest/jquery-sortable.js"></script> -->

<style type="text/css">
    .btn-group > .btn.active{
        background-color: #ccc;
    }

    .grid_wh {
        width: 100%;
        height: 100%;
    }
    .list_wh {
        width: 150px;
        height: 150px;
    }
</style>


<script>
    $(function(){

        /* Optional: Add active class to the current button (highlight it) */
        $(document).on( "click", ".btn-group .btn", function(e){
            e.preventDefault();
            $('.btn-group .btn').removeClass('active');
            $(this).addClass('active');
        });

        $('#lots .lot-description').hide();

        $('#grid').click(function(event){
            event.preventDefault();
            $('#lots .lot').addClass('grid-square');
            $('#lots .lot img').addClass('grid_wh');
            $('#lots .lot img').removeClass('list_wh');
            $('#lots .lot-description').hide();
        });
        $('#list').click(function(event){
            event.preventDefault();
            $('#lots .lot').removeClass('grid-square');
            $('#lots .lot img').addClass('list_wh');
            $('#lots .lot img').removeClass('grid_wh');
            $('#lots .lot-description').show();
        });

        // var order = $('#lots').sortable();

        var sortable = new Sortable(lots,{
            group: "lots",
            multiDrag: true,
            selectedClass: 'selected',
            animation: 150,
            // store: {
            //     /**
            //      * Get the order of elements. Called once during initialization.
            //      * @param   {Sortable}  sortable
            //      * @returns {Array}
            //      */
            //     // get: function (sortable) {
            //     //     var order = localStorage.getItem(sortable.options.group.name);
            //     //     return order ? order.split(",") : [];
            //     // },

            //     /**
            //      * Save the order of elements. Called onEnd (when the item is dropped).
            //      * @param {Sortable}  sortable
            //      */
            //     set: function (sortable) {
            //         var order = sortable.toArray();
            //         localStorage.setItem(sortable.options.group.name, order.join(","));
            //         $('#hidden_lots').val(localStorage.getItem('lots'));
            //     },
            // },
        });

    });
</script>
@stop
