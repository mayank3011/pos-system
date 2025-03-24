<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Collection;

class SalesReportExport implements FromCollection, WithHeadings
{
    protected $data;
    

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return collect($this->data);
    }

    public function headings(): array
    {
        return [
            'Category',
            'Total Sales',
            'Total Quantity Sold',
            'Payment Status',
            'Customer Name',
            'Total Orders'
        ];
    }
}