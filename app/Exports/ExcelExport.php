<?php

namespace App\Exports;

use App\Models\Card;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromCollection;

class ExcelExport implements FromCollection
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return DB::table('card_credit_info')->groupBy(['card_number'])->get();
    }
}
