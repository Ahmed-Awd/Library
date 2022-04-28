<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\SpacesController;

class ExcelSettings extends Controller
{
    public $path = 'uploads/Excel/';
    public $excel_file;
    public $file_name;
    public $current_sheet;

    public function __construct()
    {
        $this->current_sheet = new \stdClass();
        $this->current_sheet->number = 0;
        $this->current_sheet->cols = 0;
        $this->current_sheet->rows = 0;
    }

    public function Upload(Request $request)
    {
        if ($request->hasFile('file')) {
            $file_name = $this->ProcessUpload($request);
            $this->file_name = $file_name;
            return $this->ParseExcel($file_name);
        }
        return json_encode(['state' => 'failed', 'desc' => 'upload_failed']);
    }

    public function check($extn)
    {
        if ($extn == 'xlsx') {
            return true;
        }
        return false;
    }

    public function ProcessUpload(Request $request)
    {
        $rand = rand(1, 200000);
        $file_name = "Excel" . '-' . $rand . '.' . $request->file->guessClientExtension();
        $request->file->move($this->path, $file_name);
        return $file_name;
    }

    public function ParseExcel($file)
    {
        $parser = new SimpleXLSX();
        $this->excel_file = $parser::parse($this->path . $file);

        return $file;
    }

    public function set_sheet($number)
    {
        $this->current_sheet->number = $number;
        $dim = $this->excel_file->dimension(1);
        $this->current_sheet->cols = $dim[0];
        $this->current_sheet->rows = $dim[1];
        return $dim;
    }

    public function CellValue($x, $y)
    {
        $data = $this->excel_file->rows($this->current_sheet->number);
        return $data[$x][$y];//row start at 15
    }

    public function sheetData()
    {
        $data = $this->excel_file->rows($this->current_sheet->number);
        return $data;//row start at 15
    }

    public function deleteFile()
    {
        $file = Delete_File($this->file_name, 'Excel');
        return $file;
    }
}
