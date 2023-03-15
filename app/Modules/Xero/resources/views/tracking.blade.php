@extends('appshell::layouts.default')

@section('title')
    {{ __('Xero Tracking categories') }}
@stop

@section('styles')
@stop

@section('content')
    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')
        </div>

        <div class="card-block">
            <nav>
                <div class="nav nav-tabs" id="nav-tab" role="tablist">
                    <a class="nav-item nav-link active" id="business-tab" data-toggle="tab" href="#business" role="tab" aria-controls="business" aria-selected="false" data-tab_name="business">{{ __('Business') }}</a>

                    <a class="nav-item nav-link" id="category-tab" data-toggle="tab" href="#category" role="tab" aria-controls="category" aria-selected="true" data-tab_name="category">{{ __('Category') }}</a>

                </div>
            </nav>
            <div class="tab-content" id="nav-tabContent">
                <div class="tab-pane fade show active" id="business" role="tabpanel" aria-labelledby="business-tab">
                   <ul class="list-group">
                        @foreach($businesses as $business)
                        <form action="{{ route('xero.tracking.categories.update', $business->id) }}" method="POST">
                            @csrf
                        <li class="list-group-item">
                        <div class="input-group">
                            <input type="text" class="form-control" name="name" id="listInput{{ $business->id }}" disabled value="{{ $business->name }}">
                            @can('edit tracking')
                             <div class="input-group-append" id="listEditButton{{ $business->id }}">
                                <button class="btn btn-outline-warning" type="button" onclick="showUpdate('{{ $business->id }}')">Edit</button>
                            </div>
                            @endcan
                            <div class="input-group-append" id="listUpdateButton{{ $business->id }}" style="display:none;">
                                <button class="btn btn-outline-success" type="submit">Update</button>
                            </div>
                            <div class="input-group-append" id="listCancelButton{{ $business->id }}" style="display:none;">
                                <button class="btn btn-outline-danger" type="button" onclick="cancelUpdate('{{ $business->id }}')">x</button>
                            </div>
                            </div>
                        </li>

                        </form>
                        @endforeach
                    </ul>
                </div>
                 <div class="tab-pane fade" id="category" role="tabpanel" aria-labelledby="category-tab">
                    <ul class="list-group">
                        @foreach($categories as $category)
                        <form action="{{ route('xero.tracking.categories.update', $category->id) }}" method="POST">
                            @csrf
                        <li class="list-group-item">
                        <div class="input-group">
                            <input type="text" class="form-control" name="name" id="listInput{{ $category->id }}" disabled value="{{ $category->name }}">
                             @can('edit tracking')
                             <div class="input-group-append" id="listEditButton{{ $category->id }}">
                                <button class="btn btn-outline-warning" type="button" onclick="showUpdate('{{ $category->id }}')">Edit</button>
                            </div>
                            @endcan
                            <div class="input-group-append" id="listUpdateButton{{ $category->id }}" style="display:none;">
                                <button class="btn btn-outline-success" type="submit">Update</button>
                            </div>
                            <div class="input-group-append" id="listCancelButton{{ $category->id }}" style="display:none;">
                                <button class="btn btn-outline-danger" type="button" onclick="cancelUpdate('{{ $category->id }}')">x</button>
                            </div>
                        </div>
                        </li>
                        </form>
                        @endforeach
                    </ul>
                </div>
            </div>

        </div>

    </div>
@stop

@section('scripts')

<script>
    function showUpdate(id) {
        $('#listEditButton'+id).hide();
        $('#listUpdateButton'+id).show();
        $('#listCancelButton'+id).show();
        $('#listInput'+id).attr('disabled', false);
    }

    function cancelUpdate(id) {
        $('#listInput'+id).attr('disabled', true);
        $('#listEditButton'+id).show();
        $('#listUpdateButton'+id).hide();
        $('#listCancelButton'+id).hide();
    }
</script>

@stop