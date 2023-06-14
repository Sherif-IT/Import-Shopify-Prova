<?php

namespace App\Http\Controllers\Import\Shopify;

use App\Http\Controllers\Controller;
use App\Models\ShopifyImport;
use Illuminate\Http\JsonResponse;


class ImportController extends Controller
{
    /**
     * @var ShopifyImport
     */
    private $shopifyImport;

    public function __construct(ShopifyImport $shopifyImport)
    {
        $this->shopifyImport = $shopifyImport;
    }

    /**
     * //TODO create handle exception
     * @return JsonResponse
     */
    public function importProductsToShopify(): \Illuminate\Http\JsonResponse
    {
        $status = null;
        $this->shopifyImport->importProductsAndVariantsToShopify();

        return response()->json(['status'=>'ok']);
    }
}
