<?php

namespace App\Imports;

use App\Models\Product;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Row;

class ProductImport implements OnEachRow
{
    /**
     * @param array $row
     *
     * @return Model|null
     */
    public function onRow(Row $row)
    {
        //TODO fix with heading row
        if (str_contains($row[1], 'SKU')) {
            return null;
        }

        //TODO use Maatwebsite\Excel\Concerns\ ToModel; WithMappedCells; WithHeadingRow;
        //TODO implementer Maatwebsite\Excel\Concerns\ ToModel; WithMappedCells; WithHeadingRow;
        return Product::create([
            'sku' => $row[1],
            'barcode' => $row[0],
            'name' => $row[27],
            'desc_taglia' => $row[4],
            'desc_colore' => $row[11],
            'description' => $row[29],
            'composition' => $row[17],
            'prezzo_ita' => $row[14],
        ]);
    }

    public function headingRow(): int
    {
        return 1;
    }
}
