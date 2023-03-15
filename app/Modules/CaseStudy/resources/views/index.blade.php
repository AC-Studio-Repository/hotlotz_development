@extends('appshell::layouts.default')

@section('title')
    {{ __('Case Study') }}
@stop

@section('content')

    @if (Session::has('message'))
        <div class="alert alert-danger">
            {{ Session::get('message') }}
        </div>
    @endif

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')

            <div class="card-actionbar">
                <a href="{{ route('case_study.case_study.create') }}" class="btn btn-sm btn-outline-success float-right">
                    <i class="zmdi zmdi-plus"></i>
                    {{ __('Create New Case Study') }}
                </a>
            </div>
        </div>

        <div class="card-block">
            <div class="table-responsive" style="overflow-x:auto;">
                <table class="table table-striped table-hover">
                    <thead>
                    <tr>
                        <th>{{ __('Name') }}</th>
                        <th>{{ __('Abstract') }}</th>
                        <th>{{ __('Link') }}</th>
                        <th style="width: 10%">&nbsp;</th>
                    </tr>
                    </thead>

                    <tbody>
                    @foreach($case_studies as $case_study)
                        <tr>
                            <td>

                            @can('view content managements')
                            <span class="font-lg mb-3 font-weight-bold">
                                <a href="{{ route('case_study.case_study.detail', $case_study) }}">
                                    {{ $case_study->name }}
                                </a>
                            </span>
                            @else
                            <span class="font-lg mb-3 font-weight-bold">
                                {{ $case_study->name }}
                            </span>
                            @endcan
                            </td>
                            <td>
                            <span class="font-lg mb-3 font-weight-bold">
                                {{ $case_study->abstract }}
                            </span>
                            </td>
                            <td>
                            <span class="font-lg mb-3 font-weight-bold">
                                {{ $case_study->link }}
                            </span>
                            </td>
                            <td>
                                <div class="mt-2">
                                    <a href="{{route('case_study.case_study.edit',$case_study)}}"
                                       class="btn btn-xs btn-outline-primary btn-show-on-tr-hover float-right">{{ __('Edit') }}</a>

                                    {!! Form::open(['route' => ['case_study.case_study.destroy',$case_study],
                                                    'method' => 'DELETE',
                                                    'data-confirmation-text' => __('Are you sure to delete this :name?', ['name' => $case_study->name])
                                                    ])
                                            !!}
                                    <button class="btn btn-xs btn-outline-danger btn-show-on-tr-hover float-right">{{ __('Delete') }}</button>
                                    {!! Form::close() !!}
                                </div>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>

                </table>
            </div>
        </div>

    </div>

@stop

@section('scripts')

    <!-- Parsley CSS -->
    <link rel="stylesheet" href="{{asset('plugins/Parsley.js-2.9.1/src/parsley.css')}}">
    <!-- Parsley JS -->
    <script src="{{asset('plugins/Parsley.js-2.9.1/dist/parsley.min.js')}}"></script>

@stop
