@extends('appshell::layouts.default')

@section('title')
    {{ __('Email Logs') }}
@stop

@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')
            <div class="card-actionbar">

                <a href="/manage/email_logs" class="btn btn-sm btn-outline-success float-right">
                    <i class="zmdi zmdi-refresh"></i>
                    {{ __('Refresh') }}
                </a>

            </div>
        </div>

        <div class="card-block">
            <form action="" method="get" id="mainForm">
                <div class="row">
                    <div class="form-group col-md-2">
                        <label class="form-control-label">{{ __('Per page') }}</label>
                        <select class="form-control" name="per_page" id="per_page">
                            <option value="10" {{ $per_page == '10' ? 'selected' : ''}}>10</option>
                            <option value="10" {{ $per_page == '25' ? 'selected' : ''}}>25</option>
                            <option value="50" {{ $per_page == '50' ? 'selected' : ''}}>50</option>
                            <option value="50" {{ $per_page == '100' ? 'selected' : ''}}>100</option>
                            <option value="all" {{ $per_page == 'all' ? 'selected' : ''}}>All</option>
                        </select>
                    </div>
                     <div class="form-group col-md-2 ml-auto">
                        <label class="form-control-label">{{ __('Limit') }}</label>
                        <select class="form-control" name="take" id="limit">
                            <option value="100" {{ $take == '100' ? 'selected' : ''}}>100</option>
                            <option value="200" {{ $take == '200' ? 'selected' : ''}}>200</option>
                            <option value="500" {{ $take == '500' ? 'selected' : ''}}>500</option>
                            <option value="1000" {{ $take == '1000' ? 'selected' : ''}}>1000</option>
                        </select>
                    </div>
                </div>
            </form>
        </div>

        <div class="card-block">
            <div class="table-responsive" style="overflow-x: auto;">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th width="3%">No.</th>
                            <th width="15%">{{ __('To') }}</th>
                            <th width="20%">{{ __('Subject') }}</th>
                            <th width="10%">{{ __('Happened') }}</th>
                            <th width="5%">#</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach($entries as $key => $entry)
                            <tr>
                                <td> {{ $key + 1 }}</td>
                                <td>

                                    @if(isset($entry->content['user']))
                                        <a href="{{ route('customer.customers.show', $entry->content['user']['id']) }}" target="_blank">{{ $entry->content['user']['name'] }}</a>
                                    @else
                                        N/A
                                    @endif
                                    <pre>{{ array_key_first($entry->content['to']) }}</pre>

                                </td>
                                <td>{{ $entry->content['subject'] }}</td>
                                <td> {{ $entry->createdAt->diffForHumans() }}</td>
                                <td>
                                    <a href="{{ route('email_log.email_logs.show', $entry->id) }}" title="View Detail" target="_blank">
                                        <i class="zmdi zmdi-eye zmdi-hc-2x"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                {!! $entries->appends(request()->input())->links() !!}
            </div>
        </div>

    </div>

@stop

@section('scripts')
<script>
    $('#per_page, #limit').change(function(){
        $('#mainForm').submit();
    });
</script>
@stop
