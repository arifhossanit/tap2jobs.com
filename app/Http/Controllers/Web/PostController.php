<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\AppBaseController;
use App\Http\Requests\CreateBlogCommentRequest;
use App\Models\Post;
use App\Models\PostCategory;
use App\Models\PostComment;
use App\Repositories\PostRepository;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class PostController extends AppBaseController
{
    /** @var PostRepository */
    private $postRepository;

    public function __construct(PostRepository $postRepository)
    {
        $this->postRepository = $postRepository;
    }

    /**
     * @return Application|Factory|View
     */
    public function getBlogLists(): View
    {
        $data = $this->postRepository->getBlogLists();

        return view('front_web.blogs.index')->with($data);
    }

    /**
     * @return Application|Factory|View
     */
    public function getBlogDetails(Post $post): View
    {
        $data = $this->postRepository->getBlogDetails($post);
        $url = [
            'gmail' => 'https://plus.google.com/share?url='.url()->current(),
            'twitter' => 'https://twitter.com/intent/tweet?url='.url()->current(),
            'facebook' => 'https://www.facebook.com/sharer/sharer.php?u='.url()->current(),
            'pinterest' => 'http://pinterest.com/pin/create/button/?url='.url()->current(),
            'linkedin' => 'https://www.linkedin.com/shareArticle/?url='.url()->current(),
        ];

        return view('front_web.blogs.blogs_details', compact('url'))->with($data);
    }

    /**
     * @return Application|Factory|View
     */
    public function getBlogDetailsByCategory(PostCategory $postCategory): View
    {
        $data = $this->postRepository->getBlogDetailsByCategory($postCategory);

        return view('front_web.blogs.blogs_based_on_category')->with($data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function blogCommentStore(int $blogId, CreateBlogCommentRequest $request): JsonResponse
    {
        $input = $request->all();
        $comment = $this->postRepository->createComment($blogId, $input);
        $comment = PostComment::with('user')->find($comment->id);

        return $this->sendResponse($comment, __('messages.flash.comment_saved'));
    }

    public function blogCommentDelete(int $id)
    {
        $commentId = PostComment::where('id', $id);
        $commentId->delete();

        return $this->sendResponse($commentId, __('messages.flash.comment_deleted'));
    }

    /**
     * @return mixed
     */
    public function blogCommentEdit(PostComment $postComment)
    {
        return $this->sendResponse($postComment, __('messages.flash.comment_edit'));
    }

    /**
     * @return mixed
     */
    public function blogCommentUpdate(CreateBlogCommentRequest $request, int $id)
    {
        $input = $request->except(['_token', 'comment-id']);
        $comment = PostComment::where('id', $id);
        $comment->update($input);

        return $this->sendResponse($input, __('messages.flash.comment_updated'));
    }
}
