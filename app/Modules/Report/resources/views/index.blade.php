@extends('appshell::layouts.default')

@section('title')
    {{ __('Reports') }}
@stop

@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')
            <!-- @can('create reports')
            <div class="card-actionbar">
                <a href="{{ route('report.reports.create') }}" class="btn btn-sm btn-outline-success float-right">
                    <i class="zmdi zmdi-plus"></i>
                    {{ __('Create Report') }}
                </a>
            </div>
            @endcan -->

        </div>

        <div class="card-block">
            <table class="table table-striped table-hover">
                <tr>
                    <td><a href="{{ route('report.reports.unsold_post_auction') }}" class="btn btn-outline-primary">{{ __('Post Auction Report (Unsold)') }}</a></td>
                </tr>

            </table>

        </div>
    </div>

@stop
