<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Tag;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class BlogSeeder extends Seeder
{
    public function run(): void
    {
        // Criar usuários (autores)
        $authors = [
            User::create([
                'name' => 'Fernanda Matos Oliveira',
                'email' => 'fernanda@jornalaborda.com.br',
                'password' => bcrypt('password'),
            ]),
            User::create([
                'name' => 'Carol Magalhães',
                'email' => 'carol@jornalaborda.com.br',
                'password' => bcrypt('password'),
            ]),
            User::create([
                'name' => 'Barbara Marques',
                'email' => 'barbara@jornalaborda.com.br',
                'password' => bcrypt('password'),
            ]),
            User::create([
                'name' => 'Luís Novais',
                'email' => 'luis@jornalaborda.com.br',
                'password' => bcrypt('password'),
            ]),
            User::create([
                'name' => 'Ellen L. Pedrosa',
                'email' => 'ellen@jornalaborda.com.br',
                'password' => bcrypt('password'),
            ]),
        ];

        // Criar categorias
        $categories = [
            Category::create([
                'name' => 'Empreendedorismo',
                'slug' => 'empreendedorismo',
                'description' => 'Notícias sobre empreendedorismo e negócios',
            ]),
            Category::create([
                'name' => 'Cultura',
                'slug' => 'cultura',
                'description' => 'Notícias sobre cultura e arte',
            ]),
            Category::create([
                'name' => 'Educação',
                'slug' => 'educacao',
                'description' => 'Notícias sobre educação',
            ]),
            Category::create([
                'name' => 'Esporte',
                'slug' => 'esporte',
                'description' => 'Notícias sobre esportes',
            ]),
            Category::create([
                'name' => 'Política',
                'slug' => 'politica',
                'description' => 'Notícias sobre política',
            ]),
            Category::create([
                'name' => 'Saúde',
                'slug' => 'saude',
                'description' => 'Notícias sobre saúde',
            ]),
            Category::create([
                'name' => 'Últimas Notícias',
                'slug' => 'ultimasnoticias',
                'description' => 'Últimas notícias',
            ]),
        ];

        // Criar tags
        $tags = [
            Tag::create(['name' => 'economia', 'slug' => 'economia']),
            Tag::create(['name' => 'favela', 'slug' => 'favela']),
            Tag::create(['name' => 'cultura', 'slug' => 'cultura']),
            Tag::create(['name' => 'osasco', 'slug' => 'osasco']),
            Tag::create(['name' => 'educacao', 'slug' => 'educacao']),
            Tag::create(['name' => 'politica', 'slug' => 'politica']),
            Tag::create(['name' => 'saude', 'slug' => 'saude']),
            Tag::create(['name' => 'empreendedorismo', 'slug' => 'empreendedorismo']),
            Tag::create(['name' => 'rapnacional', 'slug' => 'rapnacional']),
            Tag::create(['name' => 'batalha-de-rima', 'slug' => 'batalha-de-rima']),
        ];

        // Criar posts
        $posts = [
            [
                'title' => 'Empreendedorismo nas favelas cresce após crise da pandemia',
                'slug' => 'empreendedorismo-nas-favelas-pandemia',
                'excerpt' => 'Pesquisa aponta que moradores transformaram necessidade em alternativa de renda diante da crise provocada pela pandemia',
                'content' => "A pandemia de COVID-19 impulsionou a criação de milhares de novos negócios nas favelas brasileiras. Levantamento do Data Favela, ligado à Central Única das Favelas (CUFA), revela que 56% dos empreendimentos atualmente ativos nesses territórios foram abertos a partir de 2020, no auge da crise sanitária e econômica.\n\nCrescimento expressivo após 2020\n\nSegundo o estudo, mais da metade dos negócios existentes nas favelas surgiu após o início da pandemia, considerando o período entre fevereiro de 2020 e o fim do estado de emergência em saúde no país.\n\nEmpreender virou alternativa diante da crise\n\nA pandemia provocou demissões, queda na renda e redução das oportunidades formais de trabalho. Diante desse cenário, muitos moradores das comunidades transformaram habilidades pessoais e atividades informais em fonte de renda.",
                'category_id' => $categories[0]->id,
                'author_id' => $authors[0]->id,
                'featured_image' => 'https://via.placeholder.com/1200x600/ff2d20/ffffff?text=Empreendedorismo+nas+favelas',
                'published_at' => now()->subDays(4),
            ],
            [
                'title' => 'Verba federal para cultura em Osasco gera debate sobre transparência',
                'slug' => 'verba-federal-cultura-osasco',
                'excerpt' => 'A participação de Osasco na Política Nacional Aldir Blanc (PNAB) entrou no centro do debate cultural do município',
                'content' => "A participação de Osasco na Política Nacional Aldir Blanc (PNAB) entrou no centro do debate cultural do município após a viralização, nas redes sociais, de um vídeo de uma reunião sobre o tema.\n\nO debate sobre a transparência na aplicação de recursos públicos para cultura tem mobilizado artistas e gestores culturais da cidade.",
                'category_id' => $categories[1]->id,
                'author_id' => $authors[1]->id,
                'featured_image' => 'https://via.placeholder.com/1200x600/667eea/ffffff?text=Cultura+Osasco',
                'published_at' => now()->subDays(5),
            ],
            [
                'title' => 'Vestibular: direito ou privilégio?',
                'slug' => 'vestibular-direito-ou-privilegio',
                'excerpt' => 'Cursinhos populares têm um papel importante na ampliação do acesso ao ensino superior',
                'content' => "Cursinhos populares têm um papel importante na ampliação do acesso ao ensino superior para estudantes de escolas públicas e de baixa renda. Com aulas gratuitas ou a preços acessíveis, essas iniciativas democratizam o conhecimento e oferecem oportunidades de preparação para os vestibulares.\n\nO acesso ao ensino superior ainda é um desafio para muitos jovens brasileiros, especialmente aqueles que vêm de famílias de baixa renda.",
                'category_id' => $categories[2]->id,
                'author_id' => $authors[2]->id,
                'featured_image' => 'https://via.placeholder.com/1200x600/764ba2/ffffff?text=Educacao',
                'published_at' => now()->subDays(6),
            ],
            [
                'title' => 'Jotapê propõe royalties nas batalhas de rima e reacende debate',
                'slug' => 'royalties-nas-batalhas-de-rima-jotape-freestyle',
                'excerpt' => 'Rapper questiona a ausência de registro autoral nas batalhas e afirma que artistas deixam de receber grande parte da receita',
                'content' => "O rapper Jotapê anunciou, em suas redes sociais, uma proposta para implementar royalties nas batalhas de rima. A iniciativa reacende o debate sobre valorização do freestyle e direitos autorais na cultura hip-hop.\n\nO artista questiona a ausência de registro autoral nas batalhas e afirma que muitos artistas deixam de receber grande parte da receita gerada pelo próprio improviso.",
                'category_id' => $categories[0]->id,
                'author_id' => $authors[3]->id,
                'featured_image' => 'https://via.placeholder.com/1200x600/ff2d20/ffffff?text=Batalhas+de+Rima',
                'published_at' => now()->subDays(11),
            ],
            [
                'title' => 'Batalhas de rima como escolas políticas da periferia',
                'slug' => 'batalhas-de-rima-como-escolas-politicas-da-periferia',
                'excerpt' => 'Das ruas para os palcos, as batalhas de rima ganharam cada vez mais espaço',
                'content' => "Das ruas para os palcos, saindo de rodas em praças e calçadas para as multidões, as batalhas de rima ganharam cada vez mais espaço com o passar dos anos.\n\nAs batalhas funcionam como espaços de aprendizado político e social, onde jovens da periferia encontram voz e expressão para suas realidades.",
                'category_id' => $categories[1]->id,
                'author_id' => $authors[4]->id,
                'featured_image' => 'https://via.placeholder.com/1200x600/667eea/ffffff?text=Cultura+Hip+Hop',
                'published_at' => now()->subDays(12),
            ],
            [
                'title' => 'Por que esperamos a grande felicidade?',
                'slug' => 'significado-da-vida-e-proposito',
                'excerpt' => 'A busca pelo significado da vida é uma necessidade humana fundamental',
                'content' => "A busca pelo significado da vida é uma necessidade humana fundamental e está diretamente ligada ao bem-estar psicológico, à saúde física e à construção da identidade. Estudos em psicologia mostram que experiências intensas — tanto positivas quanto negativas — podem nos ajudar a encontrar propósito.\n\nMuitas vezes, esperamos por momentos grandiosos de felicidade, quando na verdade a felicidade pode ser encontrada nas pequenas coisas do dia a dia.",
                'category_id' => $categories[5]->id,
                'author_id' => $authors[0]->id,
                'featured_image' => 'https://via.placeholder.com/1200x600/764ba2/ffffff?text=Saude+Mental',
                'published_at' => now()->subDays(12),
            ],
            [
                'title' => 'Pró-Sangue intensifica campanha para doação no Carnaval',
                'slug' => 'pro-sangue-intensifica-campanha-para-doacao-no-carnaval',
                'excerpt' => 'Com a aproximação do Carnaval, período em que historicamente ocorre redução no número de doações',
                'content' => "Com a aproximação do Carnaval, período em que historicamente ocorre redução no número de doações, a Pró-Sangue intensifica a campanha 'Antes da Folia, Doe Sangue', voltada à conscientização da importância da doação.\n\nA campanha busca garantir estoques adequados de sangue durante o período de festas, quando tradicionalmente há queda nas doações.",
                'category_id' => $categories[4]->id,
                'author_id' => $authors[1]->id,
                'featured_image' => 'https://via.placeholder.com/1200x600/ff2d20/ffffff?text=Doacao+de+Sangue',
                'published_at' => now()->subDays(14),
            ],
            [
                'title' => 'Por que designers, babás e outros profissionais seguem desvalorizados no Brasil',
                'slug' => 'por-que-designers-babas-e-outros-profissionais-seguem-desvalorizados-no-brasil',
                'excerpt' => 'A desvalorização de profissões como designers, trabalhadores domésticos, babás, cuidadores',
                'content' => "A desvalorização de profissões como designers, trabalhadores domésticos, babás, cuidadores e outros serviços essenciais não é um fenômeno recente — e tampouco simples. No Brasil, esse processo tem raízes históricas e estruturais que precisam ser discutidas.\n\nEssas profissões, apesar de essenciais para o funcionamento da sociedade, frequentemente recebem salários baixos e não têm o reconhecimento adequado.",
                'category_id' => $categories[0]->id,
                'author_id' => $authors[0]->id,
                'featured_image' => 'https://via.placeholder.com/1200x600/667eea/ffffff?text=Trabalho',
                'published_at' => now()->subDays(15),
            ],
        ];

        foreach ($posts as $postData) {
            $post = Post::create($postData);
            
            // Associar tags aleatórias
            $tagsCollection = collect($tags);
            $randomTags = $tagsCollection->random(rand(1, 3));
            if (is_array($randomTags)) {
                $post->tags()->attach($randomTags);
            } else {
                $post->tags()->attach($randomTags->id);
            }
        }
    }
}
