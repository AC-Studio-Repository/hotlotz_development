@extends('appshell::layouts.default')

@section('title')
    {{ __('Press Coverage Details') }}
@stop

@section('content')
    <div class="card">
        <div class="card-block">
            <a href="{{ route('blog_article.blog_articles.edit', $blog_article) }}" class="btn btn-outline-info">{{ __('Edit Press Coverage') }}</a>

            <button type="button" class="btn btn-outline-danger float-right" id="btnDeleteConfirm" data-id="{{ $blog_article->id }}" data-title="{{ $blog_article->title }}" >{{ __('Delete Press Coverage') }}</button>
        </div>
    </div>
    <div class="card">
        <div class="card-block">
            @include('blog_article::_details')
        </div>
    </div>

@stop

@section('scripts')

<script src="{{ asset('js/admin/bootbox.min.js?v1.0') }}"></script>

<script type="text/javascript">
    var _token = $('input[name="_token"]').val();

    $(function(){

        $(document).on('click', '#btnDeleteConfirm', function(){
            var blog_article_id = $(this).attr('data-id');
            var name = $(this).attr('data-title');
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
