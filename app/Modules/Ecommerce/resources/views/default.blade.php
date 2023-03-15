@extends('ecommerce::layouts.master')

@push('styles')
<style>
 p {
     color:red;
 }
</style>
@endpush

@section('content')
    <div class="row">
        <div class="col-sm-5">2</div>
        <div class="col-sm-5">2</div>
    </div>
     <p>Hello Default</p>
@endsection

@push('scripts')
<script>
    alert('Hello Default');
</script>
@endpush