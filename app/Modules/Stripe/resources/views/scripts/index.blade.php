@push('scripts')
    <script src="https://js.stripe.com/v3/"></script>
    <script>
        var stripe_key ='{!! setting('services.stripe.key') !!}';
    </script>
    <script src="{{ asset('js/admin/bootbox.min.js') }}"></script>
    <script src="{{ asset('stripe/js/main.js?v3') }}"></script>
    <script src="{{ asset('stripe/js/custom.js?v1.3') }}"></script>
@endpush