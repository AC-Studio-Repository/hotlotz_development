@php
    $payment_methods = $customer->credit_cards;
@endphp

@if($payment_methods != null)
    <div class="row">

        @foreach($payment_methods as $key => $method)

            <div class="col-3">
                <div class="card">
                    @php $brand = $method->card->brand @endphp
                    <center>
                        <img onclick="imagepreview(this)" lazyload="on" src="{{ asset("ecommerce/images/payments/$brand.png?v1.0") }}" alt="{{ $method->card->brand }}" class="" style="width:80px!important;height:45px!important;margin-top:10px;">
                    </center>
                    <div class="card-body">
                    <h6 class="card-title">{{ ucfirst($method->billing_details->name) ? ucfirst($method->billing_details->name) : 'No Name' }}</h6>
                    @php
                        $monthNum  = $method->card->exp_month;
                        $dateObj   = DateTime::createFromFormat('!m', $monthNum);
                        $monthName = $dateObj->format('F');
                    @endphp
                    <p class="card-text"> **** {{ $method->card->last4 }}</span><br><span class="label label-warning">Exp - {{ $monthName }} {{ $method->card->exp_year }}</p>
                    @if( $method->billing_details->address->line1 != null ||
                        $method->billing_details->address->line2 != null ||
                        $method->billing_details->address->city != null ||
                        $method->billing_details->address->postal_code != null ||
                        $method->billing_details->address->country != null
                        )
                        <h6 class="card-title">Billing Detail</h6>
                        <p class="card-text">
                        {{ $method->billing_details->address->line1 }}
                        @if( $method->billing_details->address->line2 != null )
                        <br>
                        @endif
                        {{ $method->billing_details->address->line2 }}
                        @if( $method->billing_details->address->line2 != null ||
                            $method->billing_details->address->line1 != null )
                        <br>
                        @endif
                        {{ $method->billing_details->address->city }}
                        @if( $method->billing_details->address->postal_code != null )
                        ,
                        @endif
                        {{ $method->billing_details->address->postal_code }}
                        @if( $method->billing_details->address->country != null )
                        <br>
                        @endif
                        {{ $stripeCountries[$method->billing_details->address->country] }}
                        </p>
                    @endif
                    </div>
                    <div class="card-footer">
                        <form action="{{ route('stripe.delete.card') }}" method="post">
                            @csrf
                            <input type="hidden" name="source_card" value="{{ $method->id }}">
                            <center>
                                <button type="submit" class="btn btn-outline-danger" >Remove</button>
                            </center>
                        </form>

                    </div>
                </div>
            </div>
        @endforeach

    </div>
@else
    <p>No payment cards</p>
@endif
