@if(count($winner_list) > 0)
    <div class="card">
        <div class="card-block">
            <div class="row">
                <div class="col-md-12">
                    <label class="form-control-label">{{ __('Winners') }}</label>
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
                            <th>{{ __('Total Lots Won') }}</th>
                            <th>{{ __('Country') }}</th>
                        </tr>
                        </thead>

                        <tbody>
                            @foreach($winner_list as $winner)
                            @php
                                $customer = App\Modules\Customer\Models\Customer::where('email',$winner['email'])->first();
                            @endphp
                            <tr>
                                <td>
                                    <div class="text-muted">
                                        @if(isset($customer))
                                            <a href="{{ route('customer.customers.show', $customer) }}">{{ $customer->ref_no }}</a>
                                        @else
                                            <a href="https://toolbox.globalauctionplatform.com/Customers/details/{{$winner['customer_id']}}">{{ $winner['reference'] }}</a>
                                        @endif
                                    </div>
                                </td>
                                <td>
                                    <div class="text-muted">
                                        {{ $winner['first_name'] }} {{ $winner['last_name'] }}
                                    </div>
                                </td>
                                <td>
                                    <div class="text-muted">
                                        {{ $winner['email'] }}
                                    </div>
                                </td>
                                <td>
                                    <div class="text-muted">
                                        {{ $winner['phone_number'] }}
                                    </div>
                                </td>
                                <td>
                                    <div class="text-muted">
                                        {{ $winner['number_of_lots_won'] }}
                                    </div>
                                </td>
                                <td>
                                    <div class="text-muted">
                                        {{ $winner['country'] }}
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