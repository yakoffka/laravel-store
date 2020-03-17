<?php

namespace Tests\Feature;

use App\User;
use Maatwebsite\Excel\Facades\Excel;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProductsExportTest extends TestCase
{
    /**
     * @test
     *
     * @return void
     */
    public function simple_user_cannot_download_products_export(): void
    {
        $response = $this->actingAs($this->getSimpleUser())
            ->get(route('export.products'));

        $response->assertStatus(403);
    }

    /**
     * @test
     *
     * @return void
     */
    public function privileged_user_can_download_products_export(): void
    {
        $response = $this->actingAs($this->getPrivilegedUser())
            ->get(route('export.products'));

        $response->assertStatus(200);

        // Excel::assertDownloaded('export_products_\d{4}-\d{2}-\d{2}_\d{2}-\d{2}.xlsx',
        //     function(ProductsExport $export) {
        //         // Assert that the correct export is downloaded.
        //         dd($export);
        //         // return $export->query()->contains('#2018-01');
        //     });
        // dd($response);
        // Excel::matchByRegex();
    }

    /**
     * @return User
     */
    public function getSimpleUser(): User
    {
        return User::get()
            ->filter(static function (User $user) {
                return !$user->hasRole('system')
                    && !$user->hasRole('owner')
                    && !$user->hasRole('developer')
                    && !$user->hasRole('cmanager')
                    && !$user->hasRole('smanager')
                    && !$user->hasRole('admin');
            })
            ->random();
    }

    /**
     * @return User
     */
    public function getPrivilegedUser(): User
    {
        return User::get()
            ->filter(static function (User $user) {
                return $user->can('view_products');
            })
            ->random();
    }
}
