
@extends('auction::pdf.master')

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
        a {
        color: #6c757d;
        }
        a:link {
            text-decoration: none;
        }

        a:visited {
            text-decoration: none;
        }

        a:hover {
            text-decoration: none;
        }

        a:active {
            text-decoration: none;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <div class="row header">
            <div class="col-md-4 m-4">
                <img onclick="imagepreview(this)" lazyload="on" src="{{ asset('ecommerce/images/logo/logo.png') }}" class="row" width="225px" height="38px"
                    alt="logo">
            </div>
        </div>
        <div class="row">
           @include('auction::details.sale_report_table')
        </div>
    </div>
@endsection


@push('scripts')
        <script>
            window.print();
        </script>
@endpush