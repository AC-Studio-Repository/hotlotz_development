@extends('appshell::layouts.default')

@section('title')
    {{ __('Careers') }}
@stop

@section('content')

    <div class="card card-accent-secondary">
        <div class="card-block">
                <table class="table table-hover" style="border: 1px solid #c9d0d0;">
                        <tr>
                            <td><a href="{{ route('careers.careerss.index') }}" class="btn btn-outline-primary">{{ __('Careers') }}</a></td>
                        </tr>
                        <tr>
                            <td><a href="{{ route('careers.careerss.infoIndex') }}" class="btn btn-outline-primary">{{ __('Careers Main Page') }}</a></td>
                        </tr>
                </table>
        </div>
    </div>

@stop
