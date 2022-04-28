<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class UsersExport implements FromCollection, WithHeadings
{
    private $data;
    private $other;

    public function __construct(array $data, array $other)
    {
        $this->data = $data;
        $this->other = $other;
    }

    public function headings(): array
    {
        return array_merge($this->data, $this->other);
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        $user = User::role('customer')->select($this->data);
        if (in_array('total_amount', $this->other)) {
            $user = $user->withSum('orders', 'total_amount');
        }
        if (in_array('orders_count', $this->other)) {
            $user = $user->withCount('orders');
        }
        $user = $user->get();
        return $user;
    }
}
