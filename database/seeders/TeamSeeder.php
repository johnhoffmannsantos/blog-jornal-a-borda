<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class TeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $teamMembers = [
            // Administração
            [
                'name' => 'Noellen Assis',
                'email' => 'noellen@jornalaborda.com.br',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'position' => 'Fundadora',
                'bio' => 'Fundadora do Jornal Aborda, dedicada a dar voz às comunidades periféricas de Osasco.',
            ],
            [
                'name' => 'Daniel Martins',
                'email' => 'daniel@jornalaborda.com.br',
                'password' => Hash::make('password'),
                'role' => 'editor',
                'position' => 'Editor Chefe',
                'bio' => 'Editor Chefe comprometido com a qualidade e relevância do conteúdo.',
            ],
            [
                'name' => 'Valter Cichini Jr.',
                'email' => 'valter@jornalaborda.com.br',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'position' => 'Gestor de TI',
                'bio' => 'Gestor de Tecnologia da Informação, responsável pela infraestrutura digital.',
            ],
            
            // Editores
            [
                'name' => 'Ana Carolina Bernardes',
                'email' => 'ana@jornalaborda.com.br',
                'password' => Hash::make('password'),
                'role' => 'editor',
                'position' => 'Editora',
                'bio' => 'Editora apaixonada por comunicação e comprometida com conteúdo de qualidade.',
            ],
            
            // Redatores
            [
                'name' => 'Vivian Mattos',
                'email' => 'vivian@jornalaborda.com.br',
                'password' => Hash::make('password'),
                'role' => 'author',
                'position' => 'Redatora',
                'bio' => 'Redatora dedicada a trazer as histórias das periferias de Osasco.',
            ],
            [
                'name' => 'Pedro Ferro',
                'email' => 'pedro@jornalaborda.com.br',
                'password' => Hash::make('password'),
                'role' => 'author',
                'position' => 'Redator',
                'bio' => 'Redator comprometido com o jornalismo de qualidade.',
            ],
            [
                'name' => 'Tais Pereira',
                'email' => 'tais@jornalaborda.com.br',
                'password' => Hash::make('password'),
                'role' => 'author',
                'position' => 'Redatora',
                'bio' => 'Redatora apaixonada por contar histórias relevantes.',
            ],
            [
                'name' => 'Aline Augustinho',
                'email' => 'aline@jornalaborda.com.br',
                'password' => Hash::make('password'),
                'role' => 'author',
                'position' => 'Redatora',
                'bio' => 'Redatora dedicada a dar voz às comunidades.',
            ],
            [
                'name' => 'Nahiana Marano',
                'email' => 'nahiana@jornalaborda.com.br',
                'password' => Hash::make('password'),
                'role' => 'author',
                'position' => 'Redatora',
                'bio' => 'Redatora comprometida com o jornalismo social.',
            ],
            [
                'name' => 'Édina Schimitz',
                'email' => 'edina@jornalaborda.com.br',
                'password' => Hash::make('password'),
                'role' => 'author',
                'position' => 'Redatora',
                'bio' => 'Redatora especializada em saúde e bem-estar.',
            ],
            [
                'name' => 'Kamila Moraes',
                'email' => 'kamila@jornalaborda.com.br',
                'password' => Hash::make('password'),
                'role' => 'author',
                'position' => 'Redatora',
                'bio' => 'Redatora focada em educação e cultura.',
            ],
            [
                'name' => 'Renata Bottiglia',
                'email' => 'renata@jornalaborda.com.br',
                'password' => Hash::make('password'),
                'role' => 'author',
                'position' => 'Redatora',
                'bio' => 'Redatora dedicada a temas sociais e comunitários.',
            ],
            [
                'name' => 'Letícia Lima',
                'email' => 'leticia@jornalaborda.com.br',
                'password' => Hash::make('password'),
                'role' => 'author',
                'position' => 'Redatora',
                'bio' => 'Redatora comprometida com informação de qualidade.',
            ],
            [
                'name' => 'Fernanda Matos',
                'email' => 'fernanda@jornalaborda.com.br',
                'password' => Hash::make('password'),
                'role' => 'author',
                'position' => 'Redatora',
                'bio' => 'Redatora apaixonada por jornalismo comunitário.',
            ],
            
            // Revisores
            [
                'name' => 'Elaine Bispo',
                'email' => 'elaine@jornalaborda.com.br',
                'password' => Hash::make('password'),
                'role' => 'author',
                'position' => 'Revisora de Texto',
                'bio' => 'Revisora dedicada à qualidade textual.',
            ],
            [
                'name' => 'Malu Neves',
                'email' => 'malu@jornalaborda.com.br',
                'password' => Hash::make('password'),
                'role' => 'author',
                'position' => 'Revisora de Texto',
                'bio' => 'Revisora comprometida com a excelência editorial.',
            ],
            [
                'name' => 'Elenna Abreu',
                'email' => 'elenna@jornalaborda.com.br',
                'password' => Hash::make('password'),
                'role' => 'author',
                'position' => 'Revisora de Texto',
                'bio' => 'Revisora especializada em revisão e edição.',
            ],
            [
                'name' => 'Van Lee',
                'email' => 'vanlee@jornalaborda.com.br',
                'password' => Hash::make('password'),
                'role' => 'author',
                'position' => 'Revisora de Texto',
                'bio' => 'Revisora dedicada à qualidade e clareza textual.',
            ],
            [
                'name' => 'Niara Murati',
                'email' => 'niara@jornalaborda.com.br',
                'password' => Hash::make('password'),
                'role' => 'author',
                'position' => 'Revisora de Texto',
                'bio' => 'Revisora comprometida com a excelência editorial.',
            ],
            
            // Social Media e Comunicação
            [
                'name' => 'Nicole Brum',
                'email' => 'nicole@jornalaborda.com.br',
                'password' => Hash::make('password'),
                'role' => 'author',
                'position' => 'Social Media',
                'bio' => 'Especialista em mídias sociais e comunicação digital.',
            ],
            [
                'name' => 'Lilian Penha',
                'email' => 'lilian@jornalaborda.com.br',
                'password' => Hash::make('password'),
                'role' => 'author',
                'position' => 'Social Media',
                'bio' => 'Gestora de redes sociais e comunicação.',
            ],
            [
                'name' => 'Jéssica Lima Barros',
                'email' => 'jessica@jornalaborda.com.br',
                'password' => Hash::make('password'),
                'role' => 'author',
                'position' => 'Comunicação',
                'bio' => 'Especialista em comunicação e relações públicas.',
            ],
            
            // Design
            [
                'name' => 'Viviane Rocha',
                'email' => 'viviane@jornalaborda.com.br',
                'password' => Hash::make('password'),
                'role' => 'author',
                'position' => 'Designer',
                'bio' => 'Designer gráfica responsável pela identidade visual.',
            ],
        ];

        foreach ($teamMembers as $member) {
            User::updateOrCreate(
                ['email' => $member['email']],
                $member
            );
        }
    }
}
