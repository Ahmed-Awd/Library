<?php

namespace App\Http\Controllers\Api;

use App\Models\Post;
use App\Models\PostTranslation;
use App\Repositories\PostRepositoryInterface;
use App\Http\Controllers\Controller;

class PostController extends Controller
{
    private PostRepositoryInterface $postRepository;

    public function __construct(
        PostRepositoryInterface $postRepository
    ) {
        $this->postRepository = $postRepository;
    }
    public function index()
    {
        $posts = $this->postRepository->get();
        return response()->json(['posts' => $posts->makeHidden(['deleted_at'])]);
    }
    public function show(Post $post)
    {
        $post = $this->postRepository->show($post);
        return response()->json(['post' => $post]);
    }
}
