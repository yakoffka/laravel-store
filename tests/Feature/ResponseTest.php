<?php

namespace Tests\Feature;

use App\Category;
use App\Manufacturer;
use App\Product;
use Exception;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ResponseTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @test
     *
     * @return void
     * @throws Exception
     */
    public function getUnauthorizedRequests200Test(): void
    {
        $category = Category::with(['parent', 'children', 'products'])
            ->get()
            ->where('id', '<>', 1)
            ->filter(static function ($value, $key) {
                return $value->hasProducts() && $value->fullSeeable();
            })
            ->random();

        $product = Product::where('category_id', '=', $category->id)
            ->get()
            ->where('seeable', '=', 'on')
            ->random();

        $manufacturer = Manufacturer::get()->random();


        $getRequests200 = [
            '/',
            '/products/' . $product->id,
            '/categories',
            '/categories/' . $category->id,

            '/search?query=products',

            '/products?manufacturers[]='.$manufacturer->id,
            '/products?&categories['.$category->id.']='.$category->id,
            '/products?manufacturers[]='.$manufacturer->id.'&categories['.$category->id.']='.$category->id,
        ];

        echo  "\nReport from " . __FILE__ . "\n";
        foreach ( $getRequests200 as $route ) {
            $response = $this->get($route);
            echo '    GET ' . $route;
            $response->assertStatus(200);
            echo  " - OK!\n";
        }
    }
}
