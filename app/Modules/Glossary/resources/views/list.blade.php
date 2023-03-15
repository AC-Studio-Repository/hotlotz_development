@extends('appshell::layouts.default')

@section('title')
    {{ __('Glossary') }}
@stop

@section('content')

    <div class="card card-accent-secondary">
        <div class="card-block">
                <table class="table table-hover" style="border: 1px solid #c9d0d0;">
                        <tr>
                            <td><a href="{{ route('glossary.glossarys.index') }}" class="btn btn-outline-primary">{{ __('Glossary') }}</a></td>
                        </tr>
                        <tr>
                            <td><a href="{{ route('glossary.glossarys.infopage') }}" class="btn btn-outline-primary">{{ __('Glossary Main Page') }}</a></td>
                        </tr>
                </table>
        </div>
    </div>

@stop