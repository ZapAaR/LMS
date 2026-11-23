<?php

namespace Database\Seeders;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use RuntimeException;

class BookSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categoryIds = Category::pluck('id');

        if ($categoryIds->isEmpty()) {
            throw new RuntimeException('Kategori belum tersedia. Jalankan CategorySeeder terlebih dahulu.');
        }

        $faker = fake('id_ID');

        for ($i = 1; $i <= 50; $i++) {

            $stock = $faker->numberBetween(0, 50);

            Book::create([
                'category_id' => $categoryIds->random(),
                'book_code' => 'BK-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'title' => $faker->sentence(3),
                'author' => $faker->name(),
                'publisher' => $faker->company(),
                'publication_year' => $faker->numberBetween(2010, 2026),
                'isbn' => $faker->unique()->isbn13(),
                'stock' => $stock,
                'available_stock' => $stock,
            ]);
        }
    }
}
