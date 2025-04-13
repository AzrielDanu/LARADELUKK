<?php

namespace App\Exports;

use App\Models\Purchase;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExcelExport implements FromCollection, WithHeadings, WithMapping

{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return Purchase::with(['member', 'user', 'detail_purchase'])->get(); 
    }
    

    public function headings(): array
    {
        return [
            "Customer Name", "Number Phone", "Point", "Product", "Total Price", "Total Payment", "Change", "Purchase Date"
        ];
    }

    public function map($item): array
    {
        // $dataPurchase = '';
        // foreach ($item->purchase as $value) {
        //     $format = $value['name'] . " ( qty " . $value['qty'] . " : Rp. " . number_format($value['price']) . "), ";
        //     $dataPurchase .= $format;
        // }

        return[
            $item->member->name ?? 'Non-Member',
            $item->member->phone ?? '-',            
            $item->point ?? '-',
            $item->purchase_product,
            'Rp. ' . number_format($item->total_price, 0, ',', '.'),
            'Rp. ' . number_format($item->total_payment, 0, ',', '.'),
            'Rp. ' . number_format($item->change, 0, ',', '.'),
            $item->created_at->format('d-m-Y')
        ];
    }
}
