<?php

namespace App\Imports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\OnEachRow;
use Maatwebsite\Excel\Row;

class ProductImportOld implements OnEachRow //WithMappedCells,
{

//    public function mapping(): array
//    {
//        return [
//            'barcode' => 'A1',
//            'sku'  => 'B1',
//            'desc_taglia' => 'E1',
//            'prezzo_ita' => 'N1',
//            'name' => 'Z1',
//            'description' => 'AB1',
//            'desc_colore' => 'L1',
//        ];
//    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function onRow(Row $row)
    {
        //TODO fix with heading row
        if (str_contains($row[1], 'SKU'))
        {
            return null;
        }

        //TODO changer logique
        return Product::create([
            'sku' => $row[1],
            'barcode' => $row[0],
            'name' => $row[27],
            'desc_taglia' => $row[4],
            'desc_colore' => $row[11],
            'description' => $row[29],
            'prezzo_ita' => $row[14],
        ]);
    }
    //    public function model(array $row)
    //    {
    //        //TODO fix with heading row
    //        if (str_contains($row['sku'], 'SKU')) {
    //            return null;
    //        }
    //        return new Product([
    //            'sku' => $row['sku'],
    //            'barcode' => $row['barcode'],
    //            'name' => $row['name'],
    //            'desc_taglia' => $row['desc_taglia'],
    //            'desc_colore' => $row['desc_colore'],
    //            'description' => $row['description'],
    //            'prezzo_ita' => $row['prezzo_ita'],
    //        ]);
    //    }
    public function headingRow(): int
    {
        return 1;
    }

    public function matchInputsFromFile(array $row)
    {

    }
}
