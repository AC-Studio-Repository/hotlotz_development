@extends('appshell::layouts.default')

@section('title')
    {{ __('Email Templates') }}
@stop

@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')
            @can('create email templates')
            <div class="card-actionbar">
                <a href="{{ route('email_template.email_templates.create') }}" class="btn btn-sm btn-outline-success float-right">
                    <i class="zmdi zmdi-plus"></i>
                    {{ __('Create Email Template') }}
                </a>
            </div>
            @endcan

        </div>

        <div class="card-block">
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>{{ __('Title') }}</th>
                    <th>{{ __('Description') }}</th>
                    <th style="width: 10%">&nbsp;</th>
                </tr>
                </thead>

                <tbody>
                @foreach($email_templates as $email_template)
                    <tr>
                        <td>
                            <div class="text-muted">
                                {{ $email_template->title }}
                            </div>
                        </td>
                        <td>
                            <div class="text-muted">
                                {{ $email_template->description }}
                            </div>
                        </td>
                        <td>
                            <div class="mt-2">

                                @can('edit email templates')
                                <a href="{{route('email_template.email_templates.edit',$email_template)}}"
                                       class="btn btn-xs btn-outline-primary btn-show-on-tr-hover float-right">{{ __('Edit') }}</a>
                                @endcan


                                @can('delete email templates')
                                {!! Form::open(['route' => ['email_template.email_templates.destroy',$email_template->id],
                                            'method' => 'DELETE',
                                            'data-confirmation-text' => __('Are you sure to delete :name?', ['name' => ''])
                                            ])
                                    !!}
                                <button class="btn btn-xs btn-outline-danger btn-show-on-tr-hover float-right">{{ __('Delete') }}</button>
                                {!! Form::close() !!}
                                @endcan
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>

@stop
