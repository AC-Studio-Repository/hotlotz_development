@include('stripe::forms.__form')
@php
\Stripe\Stripe::setApiKey(setting('services.stripe.secret'));

$intent = \Stripe\SetupIntent::create([
'customer' => Auth::guard('customer')->user()->stripe_customer_id
]);
@endphp

<div class="row">
    <div class="col">
        <button id="card-button" type="button" onclick="cardButton('{{ $intent->client_secret }}')"
            class="btn btn-lg btn-fixed btn-active text-uppercase my-2 my-sm-0 font_16 text_white">Save</button>
        <a href="" class="btn btn-lg btn-fixed btn-outline-active text-uppercase my-2 my-sm-0 font_16">Cancel</a>
    </div>
</div>
