<?php

namespace Database\Seeders;

use App\Models\Partner;
use Illuminate\Database\Seeder;

class PartnerSeeder extends Seeder
{
    public function run(): void
    {
        $partners = [
            [
                'name' => 'Instituto Criar',
                'description' => 'Organização que promove educação e cultura nas periferias',
                'website_url' => 'https://institutocriar.org.br',
                'level' => 'gold',
                'order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Associação de Moradores de Osasco',
                'description' => 'Representando os interesses da comunidade local',
                'website_url' => 'https://example.com',
                'level' => 'gold',
                'order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Casa de Cultura de Osasco',
                'description' => 'Promovendo arte e cultura na região',
                'website_url' => 'https://example.com',
                'level' => 'silver',
                'order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Rede de Apoio Comunitário',
                'description' => 'Rede de apoio e solidariedade nas periferias',
                'website_url' => 'https://example.com',
                'level' => 'silver',
                'order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Movimento Periferia Viva',
                'description' => 'Movimento social pela valorização das periferias',
                'website_url' => 'https://example.com',
                'level' => 'bronze',
                'order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Coletivo de Jornalistas Periféricos',
                'description' => 'Coletivo de profissionais de comunicação das periferias',
                'website_url' => 'https://example.com',
                'level' => 'bronze',
                'order' => 6,
                'is_active' => true,
            ],
        ];

        foreach ($partners as $partner) {
            Partner::create($partner);
        }
    }
}
