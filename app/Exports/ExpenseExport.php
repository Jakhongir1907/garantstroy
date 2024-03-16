<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
class ExpenseExport implements FromArray,ShouldAutoSize,WithHeadings
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
            'OBEKT NOMI','ISH BOSHQARUVCHI','IZOH',"SANA",'VALYUTA','KURS','SUMMA','JAMI'
        ];
    }
    public function map($row): array
    {
        return [
            $row['project'],
            $row['brigadier'],
            $row['comment'],
            $row['date'],
            $row['currency'],
            $row['currency_rate'],
            $row['summa'],
            $row['amount'],
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getStyle('A1:H1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14, // Adjust the size as needed
                    ],
                ]);
            },
        ];
    }
}
