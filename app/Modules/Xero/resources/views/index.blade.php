<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    @include('recaptcha::v3.script')
</head>
<body>
    <form action="{{ route('xero.invoice') }}" method="post">
        @csrf
        <div class="form-group">
            <label for="exampleFormControlInput1">Auction ID </label>
            <input type="text" name="auction_id" class="form-control" id="exampleFormControlInput1">
        </div>
         <div class="form-group">
            <label for="exampleFormControlInput1">Hammer Price</label>
            <input type="text" name="hammer_price" class="form-control" id="exampleFormControlInput1">
        </div>
         <div class="form-group">
            <label for="exampleFormControlInput1">Item
            @php $items = \App\Modules\Item\Models\Item::all(); @endphp
            <select name="item_id" class="form-control">
            @foreach($items as $item)
                <option value="{{ $item->id }}">{{ $item->name }}</option>
            @endforeach
            </select>
        </div>
         <div class="form-group">
            <label for="exampleFormControlInput1">Buyer</label>
            @php $customers = \App\Modules\Customer\Models\Customer::all(); @endphp
            <select name="buyer_id" class="form-control">
            @foreach($customers as $customer)
                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
            @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="exampleFormControlInput1">Seller</label>
            <select name="seller_id" class="form-control">
            @foreach($customers as $customer)
                <option value="{{ $customer->id }}">{{ $customer->name }}</option>
            @endforeach
            </select>
        </div>
        @include('recaptcha::v3.session')
        <button type="submit">Submit</button>
    </form>
</body>
</html>
