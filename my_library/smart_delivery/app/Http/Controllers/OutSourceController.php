<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreOutSourceRequest;
use App\Http\Requests\UpdateOutSourceRequest;
use App\Repositories\OutSourceRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Inertia\Inertia;
use App\Models\User;
use Illuminate\Support\Facades\Lang;

class OutSourceController extends Controller
{
    private OutSourceRepositoryInterface $outsourceRepository;

    public function __construct(
        OutSourceRepositoryInterface $outsourceRepository,
    ) {
        $this->outsourceRepository = $outsourceRepository;
    }


    public function index()
    {
        $outsources = $this->outsourceRepository->get();
        return Inertia::render('OutSource/Index', compact('outsources'));
    }

    public function create()
    {
        return Inertia::render('OutSource/Create');
    }

    public function store(StoreOutSourceRequest $request)
    {
        $validated = $request->validated();
        $this->outsourceRepository->store($validated);
        session()->flash('flash.banner', Lang::get('messages.record.create'));
        session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('outsources.index');
    }

    public function edit(User $outsource)
    {
        $outsource = $this->outsourceRepository->show($outsource);
        return Inertia::render('OutSource/Create', compact('outsource'));
    }

    public function update(UpdateOutSourceRequest $request, User $outsource)
    {
        $validated = $request->validated();
        $this->outsourceRepository->update($outsource, $validated);
        session()->flash('flash.banner', Lang::get('messages.record.update'));
        session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('outsources.index');
    }

    public function destroy(User $outsource)
    {
        $this->outsourceRepository->delete($outsource);
        session()->flash('flash.banner', Lang::get('messages.record.delete'));
        session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('outsources.index');
    }
}
