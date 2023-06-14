<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\JsonResponse;
use Shopify\Clients\Graphql;
use Shopify\Exception\HttpRequestException;
use Shopify\Exception\MissingArgumentException;

class ShopifyImport extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * //TODO installl php 8 pour caster enums status
     * ready, processing, error, closed
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'status',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date_start' => 'datetime',
        'date_end' => 'datetime',
    ];


    /**
     * //TODO persist class and optimize stuffs on this class
     * @expectedException
     * @return JsonResponse|string|void
     */
    public function importProductsAndVariantsToShopify()
    {
        $products = $this->getProducts();

        echo "processing products start";

        foreach ($products as $productSku => $product)
        {
            echo "<br>";
            echo "-------------------------------------";
            echo "<br>";
            echo "processing product:". $productSku.". start";
            echo "<br>";
            echo "-------------------------------------";
            echo "<br>";

            $variants['variants'] = [];

            foreach ($product['variants'] as $variant) {
                $p = isset($variant['prezzo_ita']) ? number_format($variant['prezzo_ita'], 2) : '';
                $variants['variants'][] =
                    [
                        "sku" => $variant['sku'] ?? '',
                        "barcode" => $variant['barcode'] ?? '',
                        "title" => $variant['name'] ?? '',
                        "options" => [$variant['desc_taglia'] ?? '', $variant['desc_colore'] ?? ''],
                        "price" => (int)$p,
                        "metafields" => [
                            [
                                "namespace" => "description",
                                "key" => "description",
                                "type" => "single_line_text_field",
                                "value" => $variant['description'] ?? ''
                            ]
                        ],
                    ];
            }

            try {
                //TODO singleton
                $client = new Graphql(config('shopify.store_url'), config('shopify.api.token'));
            } catch (MissingArgumentException $e) {
                return $e . '. graphql error trying connecting store ' . config('shopify.store_url');
            }

            $queryString = '
                        mutation productVariantCreate($input: ProductInput!) {
                            productCreate(input: $input) {
                              product {
                                id
                                title
                                    variants (first:3){
                                      edges {
                                        node {
                                            createdAt
                                            id
                                            title
                                            sku
                                            price
                                        }
                                      }
                                }
                              }
                              userErrors {
                                message
                                field
                              }
                            }
                        }
            ';

            $variables = [
                "input" => [
                    "title" => $product['name'],
                    "tags" => ["prova"],
                    "descriptionHtml" => "<div>" . $product['description'] ." "."<b>".$product['composition']."</b>". "</div>",
                    "options" => ['taglia', 'colore'],
                    "variants" => $variants['variants']
                ]
            ];

            try {
                $query = $client->query(['query' => $queryString, "variables" => $variables]);
            } catch (HttpRequestException|MissingArgumentException $e) {
                return response()->json($e. ' please contact IT service for support: it.dev@tech.web');
            }

            echo "<br>";
            echo "-------------------------------------";
            echo "<br>";
            echo "processing product:". $productSku." end.";
            echo "<br>";
            echo $query->getBody()->getContents();
            echo "<br>";
            echo "-------------------------------------";
            echo "<br>";

            die();
        }

        echo "<br>";
        echo "-------------------------------------";
        echo "<br>";
        echo "processing products end.";
        echo "<br>";
        echo "-------------------------------------";
        echo "<br>";

        return response()->json("import: items: " . count($products) . " ok");
    }


    private function initBaseParentSku(&$baseSku, $variantSku)
    {
        return $baseSku = $variantSku;
    }

    /**
     * @return array
     * //TODO implement recourceCollection
     */
    private function getProducts(): array
    {
        $output = [];
        $separator = '-';
        $baseParentSku = null;

        $products = Product::whereIn('is_imported_sp', [0, null])->get();

        foreach ($products as $product)
        {
            $variantSku = $product->sku;
            $parentSku = substr($variantSku, 0, strpos($variantSku, $separator));

            $variant = [
                'sku' => $variantSku,
                'name' => $product->name,
                'barcode' => $product->barcode,
                'desc_taglia' => $product->desc_taglia,
                'desc_colore' => $product->desc_colore,
                'description' => $product->description,
                'prezzo_ita' => $product->prezzo_ita
            ];

            if ($baseParentSku != $parentSku) {
                $output[$parentSku] = [
                    'name' => $product->name, //TODO merge name dei prodotti semplici
                    'description' => $product->description,
                    'composition' => $product->composition,
                    'variants' => [
                        $variant
                    ]
                ];
            } else
            {
                $output[$parentSku]['variants'][] = $variant;
            }

            $this->initBaseParentSku($baseParentSku, $parentSku);
        }

        return $output;
    }

}
