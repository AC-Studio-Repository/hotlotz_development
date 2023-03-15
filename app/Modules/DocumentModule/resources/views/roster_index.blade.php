@extends('appshell::layouts.default')

@section('title')
    {{ ucfirst($document_type) }}
@stop

@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            <div class="form-row">
                <div class="col-md-8">
                    <nav>
                        <div class="nav nav-tabs" id="nav-tab" role="tablist">
                            <a class="nav-item nav-link {{ app('request')->input('type') !== 'archive' ? 'active' : '' }}" id="nav-home-tab" href="roster" role="tab" aria-controls="nav-home" aria-selected="true">@yield('title')</a>
                            <a class="nav-item nav-link {{ app('request')->input('type') == 'archive' ? 'active' : '' }}" id="nav-profile-tab" href="?type=archive" role="tab" aria-controls="nav-profile" aria-selected="false">Archive</a>
                        </div>
                    </nav>
                </div>

                <div class="col-md-4">
                    <div class="card-actionbar">
                        <a href="{{ route('document.documents.create_document', $document_type ?? 'explainer') }}" class="btn btn-sm btn-outline-success float-right">
                            <i class="zmdi zmdi-plus"></i>
                            {{ __('Create New Document') }}
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="card-block" id="divBlogArticleList">
            <table class="table table-striped table-hover table-responsive" style="overflow-x:auto;">
                <thead>
                    <tr>
                        <th width="5%">#</th>
                        <th width="25%">{{ __('Title') }}</th>
                        <th>Document File</th>
                        <th width="15%">Created By</th>
                        <th width="15%">{{ __('Publication Date') }}</th>
                        <th width="10%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($documents as $key => $document)
                        <tr>
                            <td> {{ $key + 1 }}</td>
                            <td>
                                <div class="text-muted">
                                    {{ $document->title }}
                                </div>
                            </td>
                            <td>
                                <a href="{{ $document->full_path ?? '#' }}" target="{{ ($document->full_path)?'_blank':null }}">{{ 'View Document' }}</a>
                            </td>
                            <td>
                                <div class="text-muted">
                                    {{ ($document->user)?$document->user->name:null }}
                                </div>
                            </td>
                            <td>
                                <div class="mb-3">
                                    {{ date_format(date_create($document->publish_date), 'd M Y') }}
                                </div>
                            </td>
                            <td>
                                <div class="mt-2">
                                    @if($document->deleted_at == null)
                                        <a href="{{ route('document.documents.edit', $document) }}" class="btn btn-xs btn-outline-primary mb-3">{{ __('Edit') }}</a>
                                        <br>

                                        <button type="button" class="btn btn-xs btn-outline-warning btn-show-on-tr-hover float-left mb-1" id="btnDeleteConfirm" data-id="{{ $document->id }}" data-name="{{ $document->title }}" >{{ __('Archive') }}</button>
                                    @else
                                        <a href="{{ url('manage/documents/restore', $document->id) }}" class="btn btn-xs btn-outline-warning btn-show-on-tr-hover float-left mb-1">Restore</a>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if(count($documents)>0)
                <hr>
                <nav>
                    {!! $documents->links() !!}
                </nav>
            @endif
        </div>
    </div>

@stop

@section('scripts')

<script src="{{ asset('js/admin/bootbox.min.js?v1.0') }}"></script>
<link href="{{asset('plugins/jquery-ui-1.12.1/jquery-ui.min.css')}}" rel="stylesheet" />
<script src="{{asset('plugins/jquery-ui-1.12.1/jquery-ui.min.js')}}"></script>

<script type="text/javascript">
    var _token = $('input[name="_token"]').val();

    $(function(){

        $(document).on('click', '#btnDeleteConfirm', function(){
            var document_id = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            var content = 'Are you sure to archive '+name+'?';

            var response = confirm(content);
            if (response == true) {
                $.ajax({
                    url: '/manage/documents/'+document_id,
                    type: 'delete',
                    data: {
                        "id": document_id,
                        "_token": _token,
                    },
                    dataType: 'json',
                    async: false,
                    success: function(response) {
                        if(response.status == 'success') {
                            bootbox.alert(response.message, function(){
                                window.location.href = "{{ route('document.documents.get_documents', $document_type ?? '')}}";
                            });
                        }else {
                            bootbox.alert(response.message);
                            return false;
                        }
                    }
                });
            }
        });

    });
</script>
@stop