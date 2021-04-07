<?php

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // make the foreign key checks null
        DB::statement('SET FOREIGN_KEY_CHECKS = 0');

        // dropping table data if present
        User::truncate();
        Post::truncate();

        // flush Event incase of mailing and other third party usage
        User::flushEventListeners();
        Post::flushEventListeners();

        // quantity that should be created
        $userQuantity = 200;
        $postQuantity = 300;

        // creation
        factory(User::class, $userQuantity)->create();
        factory(Post::class, $postQuantity)->create();
    }
}
