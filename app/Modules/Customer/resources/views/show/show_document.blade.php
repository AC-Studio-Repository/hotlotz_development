<div class="form-row">
    <div class="form-group col-md-12">

        <h5><strong>{{ __('Document List') }}</strong></h5>
    </div>
</div>
<div class="form-row">
    <div class="form-group col-md-12">
        @foreach($customer_documents as $cust_document)
            <a href="{{ $cust_document->full_path }}" target="_blank" >{{ $cust_document->file_name }}</a>
            <br>
        @endforeach
    </div>
</div>