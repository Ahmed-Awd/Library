<?php

namespace App\Repositories;

use App\Models\Post;
use App\Models\PostTranslation;
use Carbon\Carbon;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class PostTranslationRepository implements PostTranslationRepositoryInterface
{
    private PostTranslation $postTranslation;


    public function __construct(PostTranslation $postTranslation)
    {
        $this->postTranslation = $postTranslation;
    }

    public function get()
    {
        return $this->postTranslation->get();
    }

    public function store($post, $data)
    {
        $post->translations()->createMany($data);
    }

    public function update($post, $data, $lang)
    {
        $postTrans = PostTranslation::where('post_id', $post->id)
            ->where('language', $lang)->first();
        if ($postTrans) {
            $postTrans->title = $data['title'];
            $postTrans->content = $data['content'];
            $postTrans->save();
        }
    }

    public function delete($postTranslation)
    {
        $postTranslation->delete();
    }
}
