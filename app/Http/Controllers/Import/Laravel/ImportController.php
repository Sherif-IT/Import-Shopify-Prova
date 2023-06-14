<?php

namespace App\Http\Controllers\Import\Laravel;

use App\Http\Controllers\Controller;
use App\Imports\ProductImport;
use Illuminate\Http\JsonResponse;
use Maatwebsite\Excel\Facades\Excel;

class ImportController extends Controller
{

    /**
     * //TODO get files from system -> sort already processed files -> use file_import object in order to get excel files
     * //TODO implement config(['excel.import.startRow' => 2)]
     * //TODO create handle exception
     *
     * @return JsonResponse
     */
    public function importProductsFromSheet(): \Illuminate\Http\JsonResponse
    {
        Excel::import(new ProductImport, 'products.xlsx');

        return response()->json(["status"=>"ok"]);
    }
}
