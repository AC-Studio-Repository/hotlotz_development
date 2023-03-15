<form action="" id="filter">
    <div class="row">
        <div class="form-group col-12 col-md-4 col-xl-4">
            <label class="form-control-label">{{ __('Search by customer') }}</label>
            <input type="text" onChange="filter()" id="autocomplete" class="form-control" placeholder="Search.." name="search" value="{{ request('search') ?? null }}" style="height:29px;">

        </div>

        <div class="form-group col-12 col-md-4 col-xl-4">
            <label class="form-control-label">{{ __('Filter by from') }}</label>
            <select class="orderFrom form-control" name="from"></select>
        </div>
        <div class="form-group col-12 col-md-4 col-xl-4">
            <label class="form-control-label">Filter by type</label>
            <div class="input-group">
                <label class="radio-inline" for="bulk_true">
                    <input type="radio" onChange="filter()" name="orderType" value="ship" {{ request('orderType') == 'ship' ? 'checked' : '' }}>
                    Ship
                    &nbsp;&nbsp;
                </label>
                <label class="radio-inline" for="bulk_true">
                    <input type="radio" onChange="filter()" name="orderType" value="pickup" {{ request('orderType') == 'pickup' ? 'checked' : '' }}>
                    Pick up
                    &nbsp;&nbsp;
                </label>
            </div>
        </div>
        @if(request('status') !== 'complete')
        <div class="form-group col-12 col-md-8 col-xl-8">
            <label class="form-control-label">Filter by status</label>
            <div class="input-group">
                <label class="radio-inline" for="bulk_true">
                    <input type="radio" onChange="filter()" name="status" value="pending" {{ request('status') == 'pending' ? 'checked' : '' }}>
                    Pending
                    &nbsp;&nbsp;
                </label>
                <label class="radio-inline" for="bulk_true">
                    <input type="radio" onChange="filter()" name="status" value="paid" {{ request('status') == 'paid' ? 'checked' : '' }}>
                    Paid
                    &nbsp;&nbsp;
                </label>
                <label class="radio-inline" for="bulk_true">
                    <input type="radio" onChange="filter()" name="status" value="cancel" {{ request('status') == 'cancel' ? 'checked' : '' }}>
                    Cancelled
                    &nbsp;&nbsp;
                </label>
            </div>
        </div>
        @endif
    </div>


<div class="row">
    <div class="form-group col-12 col-md-12 col-xl-12 text-right">
        <button type="submit" class="btn btn-md btn-outline-primary" id="btnSearch">{{ __('Search') }}</button>
        <a href="{{ URL::current() }}"><button type="button" class="btn btn-md btn-outline-success float-right">Reset All</button></a>
    </div>
</div>

<br>
</form>
