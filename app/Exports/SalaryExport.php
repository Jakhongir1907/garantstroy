<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Events\AfterSheet;
class SalaryExport implements FromArray,ShouldAutoSize,WithHeadings
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
// 'project' => " " ,
//           'name' => " " ,
//           'work_days' => " " ,
//           'day_offs' => " " ,
//           'salary_rate' => " " ,
//           'advance_payments' => " " ,
//           'amount' => " " ,
//           'from' => "JAMI SUMMA:" ,
//           'to' => $totalAmount ,
    public function headings(): array
    {
        return [
            'OBEKT NOMI','ISHCHI (F.I.O)','ISHLAGAN KUNLARI',"DAM OLISH",'KUNLIK MAOSHI','AVANS','JAMI','DAN','GACHA'
        ];
    }
    public function map($row): array
    {
        return [
            $row['project'],
            $row['name'],
            $row['work_days'],
            $row['day_offs'],
            $row['salary_rate'],
            $row['advance_payments'],
            $row['amount'],
            $row['from'],
            $row['to'],
        ];
    }
    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                $event->sheet->getStyle('A1:L1')->applyFromArray([
                    'font' => [
                        'bold' => true,
                        'size' => 14, // Adjust the size as needed
                    ],
                ]);
            },
        ];
    }
}
