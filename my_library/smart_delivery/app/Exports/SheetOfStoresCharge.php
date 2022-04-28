<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SheetOfStoresCharge implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
{
    use RegistersEventListeners;

    private $data;
    private $stores;
    private $days;
    private $totals;

    public function __construct($stores, $days, $data, $totals)
    {
        $this->data = $data;
        $this->stores = $stores;
        $this->days = $days;
        $this->totals = $totals;
    }


    public function headings(): array
    {
        $coulmns = ['0' => 'المطاعم/التاريخ',
            '1' => 'الإجمالي'];
        return array_merge($coulmns, $this->stores);
    }

    public function collection()
    {
        $rows = [];
        foreach ($this->data as $key => $value) {
            $row = [];
            $row['day'] = $key;
            $row['total'] = (string)($value['total'] / 100);
            unset($value["total"]);
            foreach ($value as $main => $minor) {
                array_push($row, (string)($minor / 100));
            }
            $rows[] = $row;
        }
        $row = [];
        $row['day'] = "المجموع";
        $row['total'] = (string)($this->totals["total"] / 100);
        $rows[] = $row;
        return collect($rows);
    }

    public static function afterSheet(AfterSheet $event)
    {
        $sheet = $event->sheet->getDelegate();
        $sheet->getStyle('1')->getFont()
            ->setSize(13)
            ->setBold(true)
            ->getColor()->setRGB('FF0000FF');
    }
}
