@if(count($bidder_list) > 0)
    <div class="card">
        <div class="card-block">
            <div class="row">
                <div class="col-md-12">
                    <label class="form-control-label">{{ __('Bidders') }}</label>
                </div>
            </div>
            <div class="row">
                <div class="col-md-12">
                    <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th>{{ __('Reference') }}</th>
                            <th>{{ __('Customer Name') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Telephone') }}</th>
                            <th>{{ __('Registeration Date') }}</th>
                            <th>{{ __('Country') }}</th>
                        </tr>
                        </thead>

                        <tbody>
                            @foreach($bidder_list as $bidder)
                            @php
                                $customer = App\Modules\Customer\Models\Customer::where('email',$bidder['email'])->first();
                            @endphp
                            <tr>
                                <td>
                                    <div class="text-muted">
                                        @if(isset($customer))
                                            <a href="{{ route('customer.customers.show', $customer) }}">{{ $customer->ref_no }}</a>
                                        @else
                                            <a href="https://toolbox.globalauctionplatform.com/Customers/details/{{$bidder['customer_id']}}">{{ $bidder['reference'] }}</a>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="text-muted">
                                        {{ $bidder['first_name'] }} {{ $bidder['last_name'] }}
                                    </div>
                                </td>
                                <td>
                                    <div class="text-muted">
                                        {{ $bidder['email'] }}
                                    </div>
                                </td>
                                <td>
                                    <div class="text-muted">
                                        {{ $bidder['phone_number'] }}
                                    </div>
                                </td>
                                <td>
                                    <div class="text-muted">
                                        {{ $bidder['registration_date'] }}
                                    </div>
                                </td>
                                <td>
                                    @php
                                        $country = \App\Models\GapCountry::getCountryByCountryCode($bidder['country_code']);
                                    @endphp
                                    <div class="text-muted">
                                        {{ $country }}
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endif