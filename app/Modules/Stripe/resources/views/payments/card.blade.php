@php
    $payment_methods = Auth::guard('customer')->user()->credit_cards;
@endphp

@if($payment_methods != null)
    @foreach($payment_methods as $key => $method)
        @php $brand = $method->card->brand @endphp
     <div class="form-group">
        <div class="form-check">
                <input class="form-check-input align-items-center" type="radio" id="payment_type" name="payment_type" data-payment={{ $brand }} value="{{$method->id}}" onchange="selectPayment('{{$method->id}}')"
            {{ old('payment_type') == '0' ? '' : 'checked' }}>

            <div class="d-flex breadcrumb mt-4 mb-custom_2 align-items-center py-2">

                <img lazyload="on" src="{{ asset("ecommerce/images/payments/$brand.png?v1.0") }}" alt="{{ $method->card->brand }}" width="40px">
                <p class="mb-0 ml-4 text_text_light_md pt-1">Ending {{ $method->card->last4 }}</p>
                <p class="mb-0 ml-4 text_text_light_md pt-1">{{ ucfirst($method->billing_details->name) ? ucfirst($method->billing_details->name) : 'No Nickname' }} </p>
                <p class="mb-0 ml-4 text_text_light_md pt-1">{{ $method->card->exp_month }} / {{ $method->card->exp_year }}</p>
                <p class="mb-0 ml-4 text_text_light_md pt-1 text-right">
                    @if( $method->billing_details->address->line1 != null &&
                    $method->billing_details->address->city != null &&
                    $method->billing_details->address->postal_code != null &&
                    $method->billing_details->address->country != null
                )
                &nbsp;	&nbsp;	&nbsp;	&nbsp;
                <a  data-toggle="collapse" href="#collapse{{$key}}" role="" aria-expanded="false" aria-controls="collapseExample">
                    View Billing Address
                </a>
                @endif
                </p>
                <div class="collapse" id="collapse{{$key}}">
                <div class="d-flex breadcrumb mt-1 ml-5 align-items-center py-2">
                    <div class="p-2"></div>
                    @php
                        $address = '';
                        if( $method->billing_details->address->line1 != null ||
                            $method->billing_details->address->line2 != null ||
                            $method->billing_details->address->city != null ||
                            $method->billing_details->address->postal_code != null ||
                            $method->billing_details->address->country != null
                        ){
                            $address .= $method->billing_details->address->line1;

                            if( $method->billing_details->address->line2 != null ){
                                $address .= ', ';
                            }
                            $address .= $method->billing_details->address->line2;

                            if( $method->billing_details->address->line2 != null ||
                            $method->billing_details->address->line1 != null ){
                                 $address .= ', ';
                            }
                            $address .= $method->billing_details->address->city;
                            if( $method->billing_details->address->postal_code != null ){
                                 $address .= ', ';
                            }
                            $address .= $method->billing_details->address->postal_code;
                            if( $method->billing_details->address->country != null ){
                                 $address .= ', ';
                            }
                                $address .= $stripeCountries[$method->billing_details->address->country] ;
                        }
                    @endphp
                    <p>
                    {{ $address }}
                    </p>
                </div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
@else

@endif
 <div class="form-group">
    <div class="form-check">
        <input class="form-check-input" type="radio" id="payment_type" name="payment_type" data-payment="other" value="new" onchange="selectPayment('new')"
        {{ $payment_methods == null ? 'checked' : '' }}>
        <label class="form-check-label js-bold font_18 ml-1" for="gridCheck">
            Pay with a New Card
        </label>
    </div>
</div>
