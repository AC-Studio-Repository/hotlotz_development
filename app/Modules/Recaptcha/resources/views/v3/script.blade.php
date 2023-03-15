@if(setting('services.recaptcha.on_live'))
    <script src="https://www.google.com/recaptcha/api.js?render={!! setting('services.recaptcha.v3.site_key') !!}"></script>

    <script>
        grecaptcha.ready(function () {
            grecaptcha.execute('{!! setting('services.recaptcha.v3.site_key') !!}', { action: 'contact' }).then(function (token) {
                var recaptchaResponse = document.getElementById('recaptchaResponse');
                recaptchaResponse.value = token;
            });
        });
    </script>
@endif