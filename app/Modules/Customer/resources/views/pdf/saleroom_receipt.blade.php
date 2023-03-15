@extends('customer::pdf.master')

@section('title')
    @parent
    Genereate Saleroom Receipt
@stop

@push('styles')
    <style>
        .borderless td, .borderless th {
            border: none;
        }
        table {
            border-collapse: initial;
        }
        @page {
            size: A4;
        }
    </style>
@endpush

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-12 mb-4 mt-5">
                <img onclick="imagepreview(this)" lazyload="on" src="{{ asset('ecommerce/images/logo/logo.png') }}" class="img" width="auto" height="40px" style="margin-left:-2px"
                alt="logo">
            </div>
            <div class="col-6">
                <h2 style="margin-left:-2px">Receipt</h2>
                <br>
                <p>Paddle Number</p>
                <p>First Name</p>
                <p>Last Name</p>
                <p>Mobile Number</p>
                <p>Email</p>
                @if($customer->company_name != null)
                <p>Company</p>
                @endif
            </div>
            <div class="col-6 text-right">
                <h2> &nbsp; </h2>
                <br>
                <p>{{ $customer->ref_no }}</p>
                <p>{{ $customer->firstname ?? 'N/A' }}</p>
                <p>{{ $customer->lastname ?? 'N/A'}}</p>
                <p>{{ $customer->phone ?? 'N/A'}} </p>
                <p>{{ $customer->email }}</p>
                @if($customer->company_name != null)
                <p>{{ $customer->company_name ?? 'N/A' }}</p>
                @endif
            </div>
            <div class="col-12 mb-3 mt-3" style="border-bottom: 2px solid"></div>
            <div class="col-12">
                <table class="borderless" width="100%" cellpadding="3">
                    <tr>
                        <th width="1%">#</th>
                        <th width="5%">&nbsp;</th>
                        <th width="15%">Item Name</th>
                        <th width="15%" class="text-right">Item Code</th>
                    </tr>
                    </thead>
                      <tbody>
                        @php $i = 1; @endphp
                        @foreach($items as $item)
                        <tr>
                          <th scope="row">{{ $i }}</th>
                          <td>
                            @php
                                $photo = \App\Modules\Item\Models\ItemImage::where('item_id',$item->id)->select('file_name','full_path')->first();
                            @endphp

                            @if(isset($photo))
                                <img onclick="imagepreview(this)" lazyload="on" src="{{ $photo->full_path }}" alt="{{$photo->file_name}}" width="150px" height="auto">
                            @endif
                          </td>
                          <td>{{ $item->name }}</td>
                          <td class="text-right">{{ $item->item_number }}</td>
                        </tr>
                        @php $i++; @endphp
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div class="col-12 mb-3 mt-3" style="border-bottom: 2px solid"></div>
            <div class="col-12">
                @if($additional_note)
                <b>Additional Information</b>
                <p class="mt-3 mb-3">
                   {{ $additional_note }}
                </p>
                @endif
                <b>
                    Received into the saleroom by {{ $receiveBy }} on {{ $receiveDate }}
                </b>
            </div>
        </div>
    </div>
@endsection


@push('scripts')

@endpush