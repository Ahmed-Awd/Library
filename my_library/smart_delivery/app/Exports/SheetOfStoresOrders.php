<?php


namespace App\Exports;


use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;

class SheetOfStoresOrders implements FromCollection, WithHeadings, ShouldAutoSize, WithEvents
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
            $row['total'] = (string)($value['total']);
            unset($value["total"]);
            foreach ($value as $main => $minor) {
                array_push($row, (string)($minor));
            }
            $rows[] = $row;
        }
        $row = [];
        $row['day'] = "المجموع";
        $row['total'] = (string)($this->totals["total"]);
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
