<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Post;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use App\Repositories\PostRepositoryInterface;
use App\Repositories\PostTranslationRepositoryInterface;
use Illuminate\Support\Facades\Lang;
use Inertia\Inertia;

class PostController extends Controller
{
    private PostRepositoryInterface $postRepository;
    private PostTranslationRepositoryInterface $postTranslationRepository;

    public function __construct(
        PostRepositoryInterface $postRepository,
        PostTranslationRepositoryInterface $postTranslationRepository
    ) {
        $this->postRepository = $postRepository;
        $this->postTranslationRepository = $postTranslationRepository;
    }


    public function index()
    {
        $data['posts'] = $this->postRepository->get();
        return Inertia::render('Posts/Index', $data);
    }

    public function create()
    {
        return Inertia::render('Posts/Create');
    }

    public function store(StorePostRequest $request)
    {
        $validated = $request->validated();
        $post = $this->postRepository->store();
        $this->postTranslationRepository->store($post, $validated['translations']);

        session()->flash('flash.banner',Lang::get('messages.record.create'));
        session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('posts.index');
    }

    public function edit(Post $post)
    {
        $data['post'] = $post->load('translations');
        $post = $data['post'];
        return Inertia::render('Posts/Create', $data);
    }

    public function update(UpdatePostRequest $request, Post $post)
    {
        $translations = $request->validated()['translations'];
        foreach ($translations as $key => $translation) {
            $this->postTranslationRepository->update($post, $translation, $key);
        }

        session()->flash('flash.banner', Lang::get('messages.record.update'));
        session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('posts.index');
    }

    public function destroy(Post $post)
    {
        $this->postRepository->delete($post);
        session()->flash('flash.banner', Lang::get('messages.record.delete'));
        session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('posts.index');
    }

    public function show(post $post)
    {
        $data['post'] = $this->postRepository->show($post);
        $data['canLogin'] = Route::has('login');
        $data['canRegister'] = Route::has('register');
        $data['laravelVersion'] = Application::VERSION;
        $data ['phpVersion'] = PHP_VERSION;
        $data['social'] = \App\Models\Setting::social();
        $data['apps'] = \App\Models\Setting::apps();
        return Inertia::render('Post', $data);
    }
}
