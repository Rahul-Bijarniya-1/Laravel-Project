<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class LibrarySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        for ($i = 1; $i <= 10; $i++) {
            $bookId = $i;
            $bookName = "Book$i";
            

            // Inserting data into the books table
            DB::table('books')->insertOrIgnore([
                'id' => $bookId,
                'name' => $bookName
            ]);
        }

        \App\Models\User::factory(10)->create();
    }
}
