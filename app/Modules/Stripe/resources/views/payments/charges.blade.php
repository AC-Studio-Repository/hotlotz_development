@php
    $payment_methods = $customer->credit_cards;
@endphp

@if($payment_methods != null)
    @foreach($payment_methods as $key => $method)
    <div class="form-group mb-custom_2">
        <div class="form-check">
            <input class="form-check-input" type="radio" id="payment_type" name="payment_type" value="{{$key}}" onchange="selectPayment({{$key}})"
            {{ old('payment_type') == '0' ? '' : 'checked' }}>
            @php $brand = $method->card->brand @endphp
            <img onclick="imagepreview(this)" lazyload="on" src="{{ asset("ecommerce/images/payments/$brand.png?v1.0") }}" alt="{{ $method->card->brand }}" width="50px">
            <label class="form-check-label js-bold font_18 ml-1" for="gridCheck">
                {{ ucfirst($method->billing_details->name) ? ucfirst($method->billing_details->name) : 'No Name' }} **** {{ $method->card->last4 }}
            </label>
        </div>
        <p class="mb-0 ml-4 text_text_light_md pt-1">Expires {{ $method->card->exp_month }} {{ $method->card->exp_year }}</p>
    </div>
    @endforeach
@else
    <p>No payment cards</p>
@endif