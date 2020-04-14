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
    public function getRequests200Test(): void
    {
        $category = Category::with(['parent', 'children', 'products'])
            ->get()
            ->where('id', '<>', 1)
            ->filter(static function (Category $value) {
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

        foreach ($getRequests200 as $route) {
            $response = $this->get($route);
            $response->assertStatus(200);
        }

        foreach ($getRequests302 as $route) {
            $response = $this->get($route);
            $response->assertStatus(302);
        }
    }

    /**
     * @test
     *
     * @return void
     * @throws Exception
     */
    public function getRequests404Test(): void
    {
        $getRequests404 = [];

        $categories = Category::with(['parent', 'children', 'products'])
            ->get()
            ->where('id', '<>', 1)
            ->filter(static function (Category $value) {
                return $value->hasProducts() && $value->isPublish();
            });
        if ($categories->count() > 0) {
            $category = $categories->random();

            $invisibleProducts3 = Product::where('category_id', '=', $category->id)
                ->get()
                ->where('publish', '=', false);
            if ($invisibleProducts3->count() > 0) {
                $invisibleProduct3 = $invisibleProducts3->random();
                $getRequests404[] = '/products/' . $invisibleProduct3->id;
            }
        }

        $invisibleCategories = Category::with(['parent', 'children', 'products'])
            ->get()
            ->where('id', '<>', 1)
            ->filter(static function (Category $value) {
                return $value->hasProducts() && !$value->parent->publish;
            });
        if ($invisibleCategories->count() > 0) {
            $invisibleCategory = $invisibleCategories->random();
            $getRequests404[] = '/categories/' . $invisibleCategory->id;

            $invisibleProducts1 = Product::where('category_id', '=', $invisibleCategory->id)
                ->get()
                ->where('publish', '=', true);
            if ($invisibleProducts1->count() > 0) {
                $invisibleProduct1 = $invisibleProducts1->random();
                $getRequests404[] = '/products/' . $invisibleProduct1->id;
            }

        }

        $invisibleSubCategories = Category::with(['parent', 'children', 'products'])
            ->get()
            ->where('id', '<>', 1)
            ->filter(static function (Category $value) {
                return $value->hasProducts() && !$value->publish;
            });
        if ($invisibleSubCategories->count() > 0) {
            $invisibleSubCategory = $invisibleSubCategories->random();

            $invisibleProducts2 = Product::where('category_id', '=', $invisibleSubCategory->id)
                ->get()
                ->where('publish', '=', true);
            if ($invisibleProducts2->count() > 0) {
                $invisibleProduct2 = $invisibleProducts2->random();
                $getRequests404[] = '/products/' . $invisibleProduct2->id;
            }
        }

        if (count($getRequests404) === 0) {
            $this->markTestSkipped(
                'Для успешного прохождения теста необходимо наличие скрытых директорий и/или товаров'
            );
        }

        echo "\n";
        foreach ($getRequests404 as $route) {
            $response = $this->get($route);
            $response->assertStatus(404);
        }
    }
}
