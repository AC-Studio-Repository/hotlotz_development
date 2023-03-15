@extends('appshell::layouts.default')

@section('title')
    {{ __('Media Resource') }}
@stop

@section('content')

    <div class="card card-accent-secondary">
        <div class="card-block">
                <table class="table table-hover" style="border: 1px solid #c9d0d0;">
                        <tr>
                            <td><a href="{{ route('media_resource.media_resources.index') }}" class="btn btn-outline-primary">{{ __('Media Resource') }}</a></td>
                        </tr>
                        <tr>
                            <td><a href="{{ route('media_resource.media_resources.infoIndex') }}" class="btn btn-outline-primary">{{ __('Media Resource Main Page') }}</a></td>
                        </tr>
                </table>
        </div>
    </div>

@stop
