@if(setting('services.recaptcha.on_live'))
    <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.v2.site_key') }}"></div>
@endif