
@extends('auction::pdf.master')

@section('title')
    @parent
    Genereate Label
@stop

@push('styles')
    <style>
        /* scale 70 */
        @page {
            size: A4;
            margin-left: -5cm;
            margin-right: -5cm;
            margin-bottom: 1.7cm;
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
        <div class="row">
            @for ($i = 0; $i < sizeof($data); $i++)
                <div class="col-md-6" style="margin-top:8px;margin-bottom:8px">
                    <div class="card p-3">
                        <img onclick="imagepreview(this)" lazyload="on" src="{{ asset('ecommerce/images/logo/logo.png') }}" class="mb-3" width="225px" height="38px" alt="logo">
                        <h4 class="two-line-container" style="font-size:22.5px !important;">{{ $data[$i]['title'] }}</h4>
                        <p style="font-size:15px !important;">Closing {{ $data[$i]['close_date'] }}</p>
                        <h5 style="font-size:18.75px !important;">Lot {{ $data[$i]['lot_number'] }}</h5>
                        <div class="two-line-container">
                            <p style="font-size:15px !important;">{{ \Illuminate\Support\Str::upper($data[$i]['item_title'] )}}</p>
                        </div>
                        <p class="card-text" style="font-size:15px !important;">Estimate | {{ $data[$i]['estimate'] }}</p>
                        <p class="card-text" style="font-size:15px !important;">Opening Bid | {{ $data[$i]['starting_bid'] }} <span class="float-right">{{ $data[$i]['item_number'] }}</span></p>

                    </div>
                </div>
                @if($i >= 7 && ($i + 1) % 8 == 0)
                <div class="pagebreak"> </div>
                @endif
            @endfor
        </div>
    </div>
@endsection


@push('scripts')

@endpush