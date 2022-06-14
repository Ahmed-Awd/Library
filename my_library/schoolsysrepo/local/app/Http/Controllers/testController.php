<?php

namespace App\Http\Controllers;

use App\testwesam;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;

class testController extends Controller
{
    public function importExport()
    {
        return view('testwesam');
    }
    public function downloadExcel($type)
    {
        $data = testwesam::get()->toArray();
        return Excel::create('itsolutionstuff_example', function($excel) use ($data) {
            $excel->sheet('mySheet', function($sheet) use ($data)
            {
                $sheet->fromArray($data);
            });
        })->download($type);
    }
    public function importExcel()
    {
        if(Input::hasFile('import_file')){
            $path = Input::file('import_file')->getRealPath();
            $data = Excel::load($path, function($reader) {
            })->get();
            if(!empty($data) && $data->count()){
                foreach ($data as $key => $value) {
                    $insert[] = ['title' => $value->title, 'description' => $value->description];
                }
                if(!empty($insert)){
                    DB::table('testwesam')->insert($insert);
                    dd('Insert Record successfully.');
                }
            }
        }
        return back();
    }
}
