<?php

namespace Tests\Feature;

use App\Comment;
use App\Product;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CommentTest extends TestCase
{
    /**
     * @test
     *
     * A basic feature test example.
     *
     * @return void
     *
     */
    public function CreateUpdateDeleteComment(): void
    {
        $developer = User::where('id', '=', 3)->get()->first();
        $product = Product::get()->random();
        $body = 'rand = ' . str_pad(rand(0, 99999), 5);
        $new_body = 'update. ' . $body;


        // create
        $this->actingAs($developer)
            ->post(route('comments.store', $product), ['body' => $body,])
            ->assertStatus(302);
        $this->assertDatabaseHas('comments', ['body' => $body]);

        // update
        $comment = Comment::where('body', '=', $body)->get()->first();
        $this->actingAs($developer)
            ->patch(route('comments.update', $comment), ['body' => $new_body])
            ->assertStatus(302);
        $this->assertDatabaseHas('comments', ['body' => $new_body]);

        // delete
        $comment = Comment::where('body', '=', $new_body)->get()->first();
        $this->actingAs($developer)
            ->delete(route('comments.destroy', $comment))
            ->assertStatus(302);
        $this->assertDatabaseMissing('comments', ['body' => $new_body]);
    }
}
