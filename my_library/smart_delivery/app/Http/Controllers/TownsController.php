<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTownRequest;
use App\Models\Town;
use App\Repositories\TownRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Inertia\Inertia;

class TownsController extends Controller
{
    private TownRepositoryInterface $townRepository;

    public function __construct(TownRepositoryInterface $townRepository)
    {
        $this->townRepository = $townRepository;
    }

    public function index()
    {
        $data['towns'] = $this->townRepository->all();
        return Inertia::render('Towns/Index', $data);
    }


    public function create()
    {
        return Inertia::render('Towns/Create');
    }


    public function store(StoreTownRequest $request)
    {
        $validated = $request->validated();
        $validated['name'] = translate($validated['name']);
        $this->townRepository->store($validated);
        session()->flash('flash.banner', Lang::get('messages.record.create'));
        session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('towns.index');
    }


    public function show(Town $town)
    {
      //
    }


    public function edit(Town $town)
    {
        $data['town'] = $this->townRepository->show($town);
        return Inertia::render('Towns/Create', $data);
    }


    public function update(StoreTownRequest $request, Town $town)
    {
        $validated = $request->validated();
        $validated['name'] = translate($validated['name']);
        $this->townRepository->update($town, $validated);
        session()->flash('flash.banner', Lang::get('messages.record.update'));
        session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('towns.index');
    }

    public function destroy(Town $town)
    {
        $this->townRepository->delete($town);
        session()->flash('flash.banner', Lang::get('messages.record.delete'));
        session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('towns.index');
    }

    public function switchStatus(Town $town)
    {
        $this->townRepository->changeStatus($town);
        session()->flash('flash.banner', "town status changed successfully");
        session()->flash('flash.bannerStyle', 'success');
        return back();
    }
}
