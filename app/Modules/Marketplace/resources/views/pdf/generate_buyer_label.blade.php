
@extends('marketplace::pdf.master')

@section('title')
    @parent
    Genereate Buyer Label
@stop

@push('styles')
    <style>
        @page {
            size: A4;
            margin-left: -5cm;
            margin-right: -5cm;
            margin-top:8px;
            margin-bottom:8px;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <div class="row">
            @for ($i = 0; $i < sizeof($data); $i++)
                <div class="col-md-4 mt-5 mb-2 offset-md-1 bg-danger p-0">
                    <div class="card p-3" style=" border-radius: 0px 0px 170px 0px;">
                        <div class="row">
                            <div class="col-md-8">
                                <img onclick="imagepreview(this)" lazyload="on" src="{{ asset('ecommerce/images/logo/logo.png') }}" class="mb-3" width="200px" height="33px" alt="logo">
                            </div>
                            <div class="col-md-4">
                                <h4 class="text-center">{{ $data[$i]['customer_ref'] }}</h4>
                            </div>
                        </div>
                        <h1 class="card-title text-center mt-4">Thank you</h1>
                        <h3 class="card-title text-center">{{ $data[$i]['customer_fullname'] }}</h3>
                        <p class="text-center mt-2">{{ \Illuminate\Support\Str::upper($data[$i]['title'] )}}</p>
                        <p class="text-center"><b>AUCTIONS | MARKETPLACE | VALUATIONS</b></p>
                        <p class="text-center"><b>hotlotz.com | hello@hotlotz.com | 6254 7616</b></p>
                        <h6 class="text-center mb-3"><b>{{ $data[$i]['sale_date'] }} | # {{ $data[$i]['item_number'] }}</b></h6>

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