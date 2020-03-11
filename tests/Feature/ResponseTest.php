<?php

namespace Tests\Feature;

use App\Category;
use App\Manufacturer;
use App\Product;
use App\User;
use Exception;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ResponseTest extends TestCase
{
    /*private User $developer;
    private User $user;

    protected function setUp(): void
    {
        // parent::setUp();
        $this->developer = User::where('id', '=', 3)->get()->first();
        $this->user = User::where('id', '=', 8)->get()->first();
    }*/

    /**
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
                return $value->hasProducts() && $value->isPublish();
            })
            ->random();

        $product = Product::where('category_id', '=', $category->id)
            ->get()
            ->where('publish', '=', true)
            ->random();

        $manufacturer = Manufacturer::get()->random();


        $getRequests200 = [
            '/',
            '/products/' . $product->id,
            '/categories',
            '/categories/' . $category->id,

            '/search?query=products',

            '/products?manufacturers[]=' . $manufacturer->id,
            '/products?&categories[' . $category->id . ']=' . $category->id,
            '/products?manufacturers[]=' . $manufacturer->id . '&categories[' . $category->id . ']=' . $category->id,

            '/cart',
        ];

        $getRequests302 = [
            '/cart/add/' . $product->id,
        ];

        // echo "\nReport from " . __CLASS__ . "\ni: Для успешного прохождения необходимо наличие скрытых директорий и товаров\n";
        // accessories -> unpublished; drums -> unpublished.

        foreach ($getRequests200 as $route) {
            $response = $this->get($route);
            // echo '    GET \'' . $route . '\': ';
            $response->assertStatus(200);
            // echo "200 - OK!\n";
        }

        foreach ($getRequests302 as $route) {
            $response = $this->get($route);
            // echo '    GET \'' . $route . '\': ';
            $response->assertStatus(302);
            // echo "302 - OK!\n";
        }
    }

    /**
     * @test
     *
     * @return void
     * @throws Exception
     */
    public function getUnauthorizedRequests404Test(): void
    {
        $category = Category::with(['parent', 'children', 'products'])
            ->get()
            ->where('id', '<>', 1)
            ->filter(static function ($value, $key) {
                return $value->hasProducts() && $value->isPublish();
            })
            ->random();

        $invisibleCategory = Category::with(['parent', 'children', 'products'])
            ->get()
            ->where('id', '<>', 1)
            ->filter(static function ($value, $key) {
                return $value->hasProducts() && !$value->parent->publish;
            })
            ->random();

        $invisibleSubCategory = Category::with(['parent', 'children', 'products'])
            ->get()
            ->where('id', '<>', 1)
            ->filter(static function ($value, $key) {
                return $value->hasProducts() && !$value->publish;
            })
            ->random();

        $invisibleProduct1 = Product::where('category_id', '=', $invisibleCategory->id)
            ->get()
            ->where('publish', '=', true)
            ->random();

        $invisibleProduct2 = Product::where('category_id', '=', $invisibleSubCategory->id)
            ->get()
            ->where('publish', '=', true)
            ->random();

        $invisibleProduct3 = Product::where('category_id', '=', $category->id)
            ->get()
            ->where('publish', '=', false)
            ->random();


        $getRequests404 = [
            '/products/' . $invisibleProduct1->id,
            '/products/' . $invisibleProduct2->id,
            '/products/' . $invisibleProduct3->id,
            '/categories/' . $invisibleCategory->id,
        ];

        echo "\n";
        foreach ($getRequests404 as $route) {
            $response = $this->get($route);
            // echo '    GET \'' . $route . '\': ';
            $response->assertStatus(404);
            // echo "404 - OK!\n";
        }
    }
}
