
@extends('marketplace::pdf.master')

@section('title')
    @parent
    Genereate Label
@stop

@push('styles')
    <style>
        @page {
            size: A4;
            margin-left: -5cm;
            margin-right: -5cm;
            margin-top:100px;
            margin-bottom:170px;
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
                <div class="col-md-6 mt-4">
                    <div class="card p-3">
                        <img onclick="imagepreview(this)" lazyload="on" src="{{ asset('ecommerce/images/logo/logo.png') }}" class="mb-3" width="225px" height="38px" alt="logo">
                        <h4 class="card-title">{{ $data[$i]['title'] }}</h4>
                        <p>{{ $data[$i]['category_name'] }}</p>
                        <div class="two-line-container">
                            <p>{{ \Illuminate\Support\Str::upper($data[$i]['item_title'] )}}</p>
                        </div>
                        <p class="card-text">BUY NOW PRICE | {{ $data[$i]['price'] }} <span class="float-right">{{ $data[$i]['item_number'] }}</span></p>

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