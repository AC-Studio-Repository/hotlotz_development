@extends('appshell::layouts.default')

@section('title')
    {{ __('Careers') }}
@stop

@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')

            <div class="card-actionbar">
                <a href="{{ route('careers.careerss.create') }}" class="btn btn-sm btn-outline-success float-right">
                    <i class="zmdi zmdi-plus"></i>
                    {{ __('Create Careers') }}
                </a>
            </div>

        </div>

        <div class="card-block">
            <div class="table-responsive" style="overflow-x:auto;">
            <table class="table table-striped table-hover">
                <thead>
                <tr>
                    <th>{{ __('Position') }}</th>
                    <th>{{ __('Experience Level') }}</th>
                    <th>{{ __('Posts') }}</th>
                    <th>{{ __('File') }}</th>
                    <th style="width: 10%">&nbsp;</th>
                </tr>
                </thead>

                <tbody>
                @foreach($careers as $career)
                    <tr>
                        <td>
                            <span class="font-lg mb-3 font-weight-bold">
                                <a href="{{ route('careers.careerss.show', $career) }}">{{ $career->position }}</a>
                            </span>
                        </td>
                        <td>
                            <span class="font-lg mb-3 font-weight-bold">
                                {{ $career->expreience_level }}
                            </span>
                        </td>
                        <td>
                            <span class="font-lg mb-3 font-weight-bold">
                                {{ $career->posts }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ $career->file_path }}" width="60px" height="60px">File</a>
                        </td>
                        <td>
                            <div class="mt-2">
                                @can('edit customers')
                                    <a href="{{route('careers.careerss.edit',$career)}}"
                                       class="btn btn-xs btn-outline-primary btn-show-on-tr-hover float-right">{{ __('Edit') }}</a>
                                @endcan

                                @can('delete customers')
                                    {!! Form::open(['route' => ['careers.careerss.destroy',$career->id],
                                                'method' => 'DELETE',
                                                'data-confirmation-text' => __('Are you sure to delete this :name?', ['name' => $career->position])
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
            @if($careers->hasPages())
            <hr>
            <nav>
                {{ $careers->links() }}
            </nav>
        @endif
        </div>
    </div>

@stop
