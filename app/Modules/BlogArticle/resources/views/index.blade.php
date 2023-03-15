@extends('appshell::layouts.default')

@section('title')
    {{ __('Press Coverage') }}
@stop

@section('content')

    <div class="card card-accent-secondary">

        <div class="card-header">
            @yield('title')

            <div class="card-actionbar">
                <a href="{{ route('blog_article.blog_articles.create') }}" class="btn btn-sm btn-outline-success float-right">
                    <i class="zmdi zmdi-plus"></i>
                    {{ __('Create Article') }}
                </a>
            </div>
        </div>

        <div class="card-block" id="divBlogArticleList">
            <table class="table table-striped table-hover table-responsive" style="overflow-x:auto;">
                <thead>
                    <tr>
                        <th width="15%">{{ __('Image') }}</th>
                        <th width="15%">{{ __('Title') }}</th>
                        <th width="15%">{{ __('Publication Date') }}</th>
                        <th width="5%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($blog_articles as $blog_article)
                        <tr>
                            <td>
                                <img onclick="imagepreview(this)" lazyload="on" src="{{ $blog_article->full_path }}" alt="{{ $blog_article->file_name }}" width="150px" height="auto">
                            </td>
                            <td>
                                @can('view blog_articles')
                                    <span class="font-lg mb-3 font-weight-bold">
                                        <a href="{{ route('blog_article.blog_articles.show', $blog_article) }}">{{ $blog_article->title }}</a>
                                    </span>
                                @else
                                    <div class="text-muted">
                                        {{ $blog_article->title }}
                                    </div>
                                @endcan
                            </td>
                            <td>
                                <div class="mb-3">
                                    {{ date_format(date_create($blog_article->publication_date), 'd M Y') }}
                                </div>
                            </td>
                            <td>
                                <div class="mt-2">
                                    <a href="{{ route('blog_article.blog_articles.edit',$blog_article) }}"
                                       class="btn btn-xs btn-outline-primary mb-3">{{ __('Edit') }}</a>
                                    <br>

                                    <button type="button" class="btn btn-xs btn-outline-danger" id="btnDeleteConfirm" data-id="{{ $blog_article->id }}" data-name="{{ $blog_article->title }}" >{{ __('Delete') }}</button>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            @if(count($blog_articles)>0)
                <hr>
                <nav>
                    {!! $blog_articles->links() !!}
                </nav>
            @endif
        </div>
        <input type="hidden" name="hidden_page" id="hidden_page" value="1" />
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
            var blog_article_id = $(this).attr('data-id');
            var name = $(this).attr('data-name');
            var content = 'Are you sure to delete '+name+'?';

            var response = confirm(content);
            if (response == true) {
                $.ajax({
                    url: '/manage/blog_articles/'+blog_article_id,
                    type: 'delete',
                    data: {
                        "id": blog_article_id,
                        "_token": _token,
                    },
                    dataType: 'json',
                    async: false,
                    success: function(response) {
                        if(response.status == 'success') {
                            bootbox.alert(response.message, function(){
                                window.location.href = "{{ route('blog_article.blog_articles.index')}}";
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