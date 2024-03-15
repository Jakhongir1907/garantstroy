<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Events\AfterSheet;

class ContractFloorExport implements FromArray , WithHeadings , ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }
    public function array(): array
    {
        return $this->data;
    }
    public function headings(): array
    {
        return [
            'BLOK','ETAJ','VALYUTA','MAYDONI','NARXI','SUMMA'
        ];
    }
    public function map($row): array
    {
        return [
            $row['block'],
            $row['floor'],
            $row['currency'],
            $row['square'],
            $row['price'],
            $row['amount'],
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getStyle('A1:F1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14, // Adjust the size as needed
                    ],
                ]);
            },
        ];
    }
}
// $data[] = [
//            'project' =>  " ",
//            'name' => " ",
//            'date' =>  " ",
//            'currency' => " ",
//            'currency_rate' => " ",
//            'summa' => "JAMI:" ,
//            'amount' => $totalAmount
//        ];
