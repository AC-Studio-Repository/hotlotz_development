<?php

namespace App\Modules\BlogPost\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Modules\BlogPost\Http\Requests\StoreBlogPostRequest;
use App\Modules\BlogPost\Http\Requests\UpdateBlogPostRequest;
use App\Modules\BlogPost\Http\Repositories\BlogPostRepository;
use App\Modules\BlogPost\Models\BlogPost;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Response;
use App\Helpers\NHelpers;
use DB;

class BlogPostController extends Controller
{
    protected $blogPostRepository;
    public function __construct(BlogPostRepository $blogPostRepository)
    {
        $this->blogPostRepository = $blogPostRepository;
    }

    /**
     * Displays the blog_post index
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index()
    {
        $blog_posts = $this->blogPostRepository->all([], false);

        return view('blog_post::index', [
            'blog_post' => app(BlogPost::class),
            'blog_posts' => $blog_posts
        ]);
    }

    public function blogPostReordering(Request $request)
    {
        // dd($request->all());
        DB::beginTransaction();
        try {
            $inputs = $request->all();
            foreach ($inputs['blogpost_id'] as $key => $blog_post_id) {
                $sequence_number = $key + 1;

                $this->blogPostRepository->update($blog_post_id, ['order'=>$sequence_number], true);
            }
            DB::commit();

            flash()->success(__('Blog Post are reordered Successfully!'));
            return redirect()->route('blog_post.blog_posts.index');
        } catch (\Exception $e) {
            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => 'Blog Post are reordered Failed!']));
            return redirect()->route('blog_post.blog_posts.index')->with('fail', 'Blog Post are reordered Failed!');
        }
    }

    /**
     * Displays the create new blog_post view
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function create()
    {
        return view('blog_post::create', [
            'blog_post' => app(BlogPost::class),
        ]);
    }

    /**
     * @param CreateBlogPost $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(StoreBlogPostRequest $request)
    {
        DB::beginTransaction();
        try {
            $payload = $this->blogPostRepository->packData($request);
            // dd($payload);
            $blog_post = $this->blogPostRepository->create($payload);

            if ($blog_post) {
                $this->blogPostRepository->update($blog_post->id, ['order'=>$blog_post->id], true);

                if (isset($request->post_image)) {
                    $post_image = $request->post_image;
                    $file_path = Storage::put('blog_post/'.$blog_post->id, $post_image);
                    $file_name = $post_image->getClientOriginalName();
                    $full_path = Storage::url($file_path);

                    $image_data['file_name'] = $file_name;
                    $image_data['file_path'] = $file_path;
                    $image_data['full_path'] = $full_path;

                    $this->blogPostRepository->update($blog_post->id, $image_data, true);
                }

                DB::commit();
                flash()->success(__(':name has been created', ['name' => $blog_post->title]));
                return redirect(route('blog_post.blog_posts.show', ['blog_post' => $blog_post ]));
            }

            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => 'Blog Post Create Failed!']));
            return redirect()->back()->withInput();
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            DB::rollback();
            return redirect()->back()->withInput();
        }
    }

    /**
     * Show the blog_post
     *
     * @param BlogPost $blog_post
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function show(BlogPost $blog_post)
    {
        $blog_post = $this->blogPostRepository->show('id', $blog_post->id, [], false);

        return view('blog_post::show', [
            'blog_post' => $blog_post,
        ]);
    }

    /**
     * @param BlogPost $blog_post
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function edit(BlogPost $blog_post)
    {
        $blog_posts = $this->blogPostRepository->show('id', $blog_post->id, [], false);

        return view('blog_post::edit', [
            'blog_post' => $blog_posts,
        ]);
    }

    /**
     * Saves updates to an existing blog_post
     *
     * @param BlogPost       $blog_post
     * @param UpdateBlogPost $request
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update($id, UpdateBlogPostRequest $request)
    {
        DB::beginTransaction();
        try {

            // dd($request->all());
            $payload = $this->blogPostRepository->packData($request);
            if (isset($request->post_image)) {
                $post_image = $request->post_image;
                $file_path = Storage::put('blog_post/'.$id, $post_image);
                $file_name = $post_image->getClientOriginalName();
                $full_path = Storage::url($file_path);

                $payload['file_name'] = $file_name;
                $payload['file_path'] = $file_path;
                $payload['full_path'] = $full_path;
            }

            $updated = $this->blogPostRepository->update($id, $payload, true);

            if ($updated) {
                DB::commit();
                $blog_post = BlogPost::find($id);
                flash()->success(__(':name has been updated', ['name' => $blog_post->title]));
                return redirect(route('blog_post.blog_posts.show', ['blog_post' => $blog_post ]));
            }

            DB::rollback();
            flash()->error(__('Error: :msg', ['msg' => 'Blog Post Update Failed!']));
            return redirect()->back()->withInput();
        } catch (\Exception $e) {
            flash()->error(__('Error: :msg', ['msg' => $e->getMessage()]));
            DB::rollback();
            return redirect()->back()->withInput();
        }
    }

    /**
     * Delete a blog_post
     *
     * @param BlogPost $blog_post
     *
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function destroy(BlogPost $blog_post)
    {
        try {
            $name = $blog_post->name;
            $blog_post->delete();

            return response()->json([ 'status'=>'success', 'message' => $name.' has been deleted']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([ 'status'=>'failed', 'message' => $e->getMessage()]);
        }
    }
}
