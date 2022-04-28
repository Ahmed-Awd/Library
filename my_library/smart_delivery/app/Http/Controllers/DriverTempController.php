<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DriverTemp;
use App\Models\User;
use App\Repositories\DriverTempRepositoryInterface;
use App\Repositories\AttachmentRepositoryInterface;
use App\Repositories\DriverRepositoryInterface;
use Illuminate\Support\Facades\Lang;
use Inertia\Inertia;

class DriverTempController extends Controller
{
    //
    private DriverTempRepositoryInterface $driverTempRepository;
    private AttachmentRepositoryInterface $attachmentRepository;
    private DriverRepositoryInterface $driverRepository;

    public function __construct(
        AttachmentRepositoryInterface $attachmentRepository,
        DriverRepositoryInterface $driverRepository,
        DriverTempRepositoryInterface $driverTempRepository
    ) {
        $this->attachmentRepository = $attachmentRepository;
        $this->driverRepository = $driverRepository;
        $this->driverTempRepository = $driverTempRepository;
    }

    public function index()
    {
        $drivers = $this->driverTempRepository->get();
        return Inertia::render('DriverTemp/Index', compact('drivers'));
    }

    public function show($drivertemp)
    {
        $driver = $this->driverTempRepository->show($drivertemp);
        return Inertia::render('DriverTemp/show', compact('driver'));
    }
    public function acceptUpdate($drivertemp)
    {
        $driver = DriverTemp::find($drivertemp);
        $user = User::find($driver->driver_id);
        $this->driverRepository->update($user, $driver);
        $this->attachmentRepository->update($user, $driver);
        $this->driverTempRepository->delete($driver);

        session()->flash('flash.banner',  Lang::get('messages.record.update'));
        session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('driver-temps.index');
    }
    public function destroy($drivertemp)
    {
        $driver = DriverTemp::find($drivertemp);
        $this->driverTempRepository->delete($driver);
        session()->flash('flash.banner',  Lang::get('messages.record.delete'));
        session()->flash('flash.bannerStyle', 'success');
        return redirect()->route('driver-temps.index');
    }
}
