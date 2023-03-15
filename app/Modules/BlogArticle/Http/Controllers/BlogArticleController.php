<?php

namespace App\Modules\BlogArticle\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\BlogArticle\Http\Requests\StoreBlogArticleRequest;
use App\Modules\BlogArticle\Http\Requests\UpdateBlogArticleRequest;
use App\Modules\BlogArticle\Http\Repositories\BlogArticleRepository;
use App\Modules\BlogArticle\Models\BlogArticle;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use App\Helpers\NHelpers;
use DB;

class BlogArticleController extends Controller
{
    protected $blogArticleRepository;
    public function __construct(BlogArticleRepository $blogArticleRepository){
        $this->blogArticleRepository = $blogArticleRepository;
    }

    /**
     * Displays the blog_article index
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $blog_articles = $this->blogArticleRepository->all([], false, 10);

        return view('blog_article::index', [
            'blog_article' => app(BlogArticle::class),
            'blog_articles' => $blog_articles
        ]);
    }

    /**
     * Displays the create new blog_article view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('blog_article::create', [
            'blog_article' => app(BlogArticle::class),
        ]);
    }

    /**
     * @param CreateBlogArticle $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(StoreBlogArticleRequest $request)
    {
        DB::beginTransaction();
        try {

            $payload = $this->blogArticleRepository->packData($request);
            // dd($payload);
            $blog_article = $this->blogArticleRepository->create($payload);

            if ($blog_article) {

                if (isset($request->article_image)) {
                    $article_image = $request->article_image;
                    $file_path = Storage::put('blog_article/'.$blog_article->id, $article_image);
                    $file_name = $article_image->getClientOriginalName();
                    $full_path = Storage::url($file_path);

                    $image_data['file_name'] = $file_name;
                    $image_data['file_path'] = $file_path;
                    $image_data['full_path'] = $full_path;

                    $this->blogArticleRepository->update($blog_article->id, $image_data, true);
                }

                if (isset($request->article_file)) {
                    $article_file = $request->article_file;
                    $article_file_path = Storage::put('blog_article/'.$blog_article->id, $article_file);
                    $article_file_name = $article_file->getClientOriginalName();
                    $article_full_path = Storage::url($article_file_path);

                    $image_data['article_file_name'] = $article_file_name;
                    $image_data['article_file_path'] = $article_file_path;
                    $image_data['article_full_path'] = $article_full_path;

                    $this->blogArticleRepository->update($blog_article->id, $image_data, true);
                }

                DB::commit();
                flash()->success(__(':name has been created', ['name' => $blog_article->title]));
                return redirect(route('blog_article.blog_articles.show', ['blog_article' => $blog_article ]));
            }

            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => 'Blog Article Create Failed!']));
            return redirect()->back()->withInput();

        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            DB::rollback();
            return redirect()->back()->withInput();
        }        
    }

    /**
     * Show the blog_article
     *
     * @param BlogArticle $blog_article
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(BlogArticle $blog_article)
    {
        $blog_article = $this->blogArticleRepository->show('id', $blog_article->id, [], false);

        return view('blog_article::show', [
            'blog_article' => $blog_article,
        ]);
    }

    /**
     * @param BlogArticle $blog_article
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(BlogArticle $blog_article)
    {
        $blog_articles = $this->blogArticleRepository->show('id', $blog_article->id, [], false);

        return view('blog_article::edit', [
            'blog_article' => $blog_articles,
        ]);
    }

    /**
     * Saves updates to an existing blog_article
     *
     * @param BlogArticle       $blog_article
     * @param UpdateBlogArticle $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id, UpdateBlogArticleRequest $request)
    {
        DB::beginTransaction();
        try {

            // dd($request->all());
            $payload = $this->blogArticleRepository->packData($request);
            if (isset($request->article_image)) {
                $article_image = $request->article_image;
                $file_path = Storage::put('blog_article/'.$id, $article_image);
                $file_name = $article_image->getClientOriginalName();
                $full_path = Storage::url($file_path);

                $payload['file_name'] = $file_name;
                $payload['file_path'] = $file_path;
                $payload['full_path'] = $full_path;
            }
            if (isset($request->article_file)) {
                $article_file = $request->article_file;
                $article_file_path = Storage::put('blog_article/'.$id, $article_file);
                $article_file_name = $article_file->getClientOriginalName();
                $article_full_path = Storage::url($article_file_path);

                $payload['article_file_name'] = $article_file_name;
                $payload['article_file_path'] = $article_file_path;
                $payload['article_full_path'] = $article_full_path;
            }

            $updated = $this->blogArticleRepository->update($id, $payload, true);

            if ($updated) {
                DB::commit();
                $blog_article = BlogArticle::find($id);
                flash()->success(__(':name has been updated', ['name' => $blog_article->title]));
                return redirect(route('blog_article.blog_articles.show', ['blog_article' => $blog_article ]));
            }

            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => 'Blog Article Update Failed!']));
            return redirect()->back()->withInput();

        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            DB::rollback();
            return redirect()->back()->withInput();
        }
    }

    /**
     * Delete a blog_article
     *
     * @param BlogArticle $blog_article
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(BlogArticle $blog_article)
    {
        try {
            $title = $blog_article->title;
            $blog_article->delete();

            return response()->json([ 'status'=>'success', 'message' => $title.' has been deleted']);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([ 'status'=>'failed', 'message' => $e->getMessage()]);
        }
    }
}
