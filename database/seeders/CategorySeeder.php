<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Pemrograman',
                'description' => 'Buku tentang bahasa pemrograman dan pengembangan perangkat lunak.',
            ],
            [
                'name' => 'Teknologi',
                'description' => 'Buku tentang perkembangan teknologi dan informasi.',
            ],
            [
                'name' => 'Jaringan Komputer',
                'description' => 'Buku tentang jaringan komputer dan infrastruktur jaringan.',
            ],
            [
                'name' => 'Database',
                'description' => 'Buku tentang pengelolaan database dan SQL.',
            ],
            [
                'name' => 'Sistem Operasi',
                'description' => 'Buku tentang sistem operasi komputer.',
            ],
            [
                'name' => 'Kecerdasan Buatan',
                'description' => 'Buku tentang AI, machine learning, dan deep learning.',
            ],
            [
                'name' => 'Sejarah',
                'description' => 'Buku tentang sejarah Indonesia dan dunia.',
            ],
            [
                'name' => 'Pendidikan',
                'description' => 'Buku tentang pembelajaran dan dunia pendidikan.',
            ],
            [
                'name' => 'Novel',
                'description' => 'Buku cerita fiksi dan karya sastra.',
            ],
            [
                'name' => 'Bisnis',
                'description' => 'Buku tentang bisnis, manajemen, dan kewirausahaan.',
            ],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(
                ['name' => $category['name']],
                ['description' => $category['description']]
            );
        }
    }
}
