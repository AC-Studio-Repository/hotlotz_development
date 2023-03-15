@extends('appshell::layouts.default')

@section('title')
    {{ __('Email Log Detail') }}
@stop

@section('content')
    <div class="card">
        <div class="card-header">
             @yield('title')
        </div>
        <div class="card-block">
            <dl class="row">
                <dt class="col-sm-3">To</dt>
                <dt class="col-sm-1">:</dt>
                <dd class="col-sm-8">
                    @if(isset($entry->content['user']))
                        <a href="{{ route('customer.customers.show', $entry->content['user']['id']) }}" target="_blank">{{ $entry->content['user']['name'] }}</a>
                    @else
                        N/A
                    @endif
                    <pre>{{ array_key_first($entry->content['to']) }}</pre>
                </dd>

                <dt class="col-sm-3">From</dt>
                <dt class="col-sm-1">:</dt>
                <dd class="col-sm-8">{{ array_key_first($entry->content['from']) }}</dd>

                <dt class="col-sm-3">Subject</dt>
                <dt class="col-sm-1">:</dt>
                <dd class="col-sm-8">{{ $entry->content['subject'] }}</dd>

                <dt class="col-sm-3">Happened </dt>
                <dt class="col-sm-1">:</dt>
                <dd class="col-sm-8">{{ $entry->createdAt->format('Y-m-d H:i:s') }}</dd>

                <dt class="col-sm-3">Time Zone</dt>
                <dt class="col-sm-1">:</dt>
                <dd class="col-sm-8">{{ $entry->createdAt->format('e') }}</dd>
            </dl>
        </div>

        <hr>
        <div class="card-block">
            <samp>{!! $entry->content['html'] !!}</samp>
        </div>
    </div>
@stop
