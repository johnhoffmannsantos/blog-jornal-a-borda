<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JournalEditionSeeder extends Seeder
{
    public function run(): void
    {
        $editions = [
            [
                'title' => 'Edição 89 - Dezembro 2025',
                'slug' => 'edicao-89-dezembro-2025',
                'description' => 'Nesta edição, destacamos as principais conquistas da comunidade de Osasco ao longo de 2025, com reportagens especiais sobre educação, cultura e empreendedorismo periférico.',
                'pdf_file' => '/storage/journal-editions/pdf/placeholder.pdf',
                'published_date' => '2025-12-01',
                'edition_number' => 89,
                'is_featured' => true,
                'views' => 1250,
                'downloads' => 340,
            ],
            [
                'title' => 'Edição 88 - Novembro 2025',
                'slug' => 'edicao-88-novembro-2025',
                'description' => 'Reportagens sobre os desafios e conquistas da comunidade, com destaque para iniciativas de geração de renda e projetos culturais.',
                'pdf_file' => '/storage/journal-editions/pdf/placeholder.pdf',
                'published_date' => '2025-11-01',
                'edition_number' => 88,
                'is_featured' => false,
                'views' => 980,
                'downloads' => 280,
            ],
            [
                'title' => 'Edição 87 - Outubro 2025',
                'slug' => 'edicao-87-outubro-2025',
                'description' => 'Especial sobre o mês das crianças, com histórias de jovens talentos da periferia e projetos educacionais.',
                'pdf_file' => '/storage/journal-editions/pdf/placeholder.pdf',
                'published_date' => '2025-10-01',
                'edition_number' => 87,
                'is_featured' => false,
                'views' => 850,
                'downloads' => 220,
            ],
            [
                'title' => 'Edição 86 - Setembro 2025',
                'slug' => 'edicao-86-setembro-2025',
                'description' => 'Cobertura especial sobre a primavera e os eventos culturais que movimentam Osasco nesta época do ano.',
                'pdf_file' => '/storage/journal-editions/pdf/placeholder.pdf',
                'published_date' => '2025-09-01',
                'edition_number' => 86,
                'is_featured' => false,
                'views' => 720,
                'downloads' => 190,
            ],
        ];

        foreach ($editions as $editionData) {
            DB::table('journal_editions')->insert([
                'title' => $editionData['title'],
                'slug' => $editionData['slug'],
                'description' => $editionData['description'] ?? null,
                'pdf_file' => $editionData['pdf_file'],
                'published_date' => $editionData['published_date'],
                'edition_number' => $editionData['edition_number'] ?? null,
                'is_featured' => $editionData['is_featured'] ?? false,
                'views' => $editionData['views'] ?? 0,
                'downloads' => $editionData['downloads'] ?? 0,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
