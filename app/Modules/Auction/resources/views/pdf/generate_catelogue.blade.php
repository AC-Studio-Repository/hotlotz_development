
@extends('auction::pdf.master')

@section('title')
    @parent
    Genereate Catelogue
@stop

@push('styles')
    <style>
        /* scale 100 */

        @page {
            margin-left: -1cm;
            margin-right: -1cm;
            margin-bottom:1cm;
        }

        @media print {
          body {
            margin: 0;
            color: #000;
            background-color: #fff;
          }

        }

        .two-line-container {
            height: 65px;
        }
    </style>
@endpush

@section('content')
    <div class="container">

        @for ($i = 0; $i < sizeof($data['results']); $i++)
         @if($i == 0)
            <div class="row header">
                <div class="col-md-3 mt-2">
                    <img onclick="imagepreview(this)" lazyload="on" src="{{ asset('ecommerce/images/logo/logo.png') }}" class="row" width="225px" height="38px" alt="logo">
                </div>
                <div class="col-md-9 two-line-container hide-header-text" style="margin-top:20px;">
                    <b class="float-right" style="font-size:15px !important;">{{ $data['auction_info'] }}</b>
                </div>
            </div>
         @endif

        <div class="row mt-4">
            <div class="col-md-3 col-xs-4">
                <img onclick="imagepreview(this)" lazyload="on" src="{{ $data['results'][$i]['item_image'] }}" class="mx-auto d-block border" width="100%" height="auto" alt="...">
            </div>
            <div class="col-md-9 col-xs-8">
                <div class="row">
                    <div class="col-8 two-line-container">
                        <h4 class="inline" style="font-size:22.5px !important;">{{ $data['results'][$i]['item_name'] }}</h4>
                    </div>
                    <div class="col-4" style="font-size:15px !important;">
                        Lot # {{ $data['results'][$i]['lot_id'] }}
                    </div>
                    <div class="col-8">
                        <p class="text-justify truncate" style="font-size:15px !important;">
                        {{ \Illuminate\Support\Str::limit($data['results'][$i]['item_description'], 275, $end='...') }}
                        </p>
                         {{ $data['results'][$i]['dimension'] }}
                        <p class="text-justify" style="font-size:15px !important;">
                           {{ \Illuminate\Support\Str::limit($data['results'][$i]['item_condition'], 275, $end='...') }}
                        </p>
                    </div>
                    <div class="col-4" style="font-size:15px !important;">
                        <strong>Opening Bid:</strong>
                        <p>{{ $data['results'][$i]['starting_bid'] }}</p>
                        <strong>Estimate:</strong>
                        <p>{{ $data['results'][$i]['estimate'] }}</p>
                    </div>
                </div>

            </div>
        </div>
            @if($i >= 5 && ($i + 1) % 6 == 0)
                <div class="pagebreak"> </div>
            @endif
        @endfor
    </div>
@endsection


@push('scripts')

@endpush