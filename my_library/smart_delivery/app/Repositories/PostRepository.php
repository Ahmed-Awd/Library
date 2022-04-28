<?php

namespace App\Repositories;

use App\Models\Post;
use App\Models\PostTranslation;
use Carbon\Carbon;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PostRepository implements PostRepositoryInterface
{
    private Post $post;


    public function __construct(Post $post)
    {
        $this->post = $post;
    }

    public function get()
    {
        return $this->post->join('post_translations', 'post_translations.post_id', 'posts.id')
        ->where('post_translations.language', app()->getLocale())->paginate(15);
    }

    public function show($post)
    {
        return $post->join('post_translations', 'post_translations.post_id', 'posts.id')
        ->where('post_translations.post_id', $post->id)
        ->where('post_translations.language', app()->getLocale())->first();
    }

    public function store()
    {
        return $this->post->create([]);
    }


    public function delete($post)
    {
        $post->delete();
    }
}
