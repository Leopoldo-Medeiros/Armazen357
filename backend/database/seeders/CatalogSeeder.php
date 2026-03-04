<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Seeder;

class CatalogSeeder extends Seeder
{
    public function run(): void
    {
        $especiais = Category::updateOrCreate(
            ['slug' => 'cafes-especiais'],
            ['name' => 'Cafés Especiais', 'description' => 'Grãos com pontuação acima de 80 pela SCAA. Origem única, rastreabilidade completa.', 'is_active' => true]
        );

        $blends = Category::updateOrCreate(
            ['slug' => 'blends'],
            ['name' => 'Blends', 'description' => 'Combinações cuidadosamente equilibradas para consistência e complexidade de sabor.', 'is_active' => true]
        );

        $diario = Category::updateOrCreate(
            ['slug' => 'cafe-do-dia-a-dia'],
            ['name' => 'Café do Dia a Dia', 'description' => 'Grãos de alta qualidade com custo acessível para consumo diário.', 'is_active' => true]
        );

        $products = [
            // Especiais
            [
                'category_id'       => $especiais->id,
                'name'              => 'Bourbon Amarelo Chapada Diamantina',
                'slug'              => 'bourbon-amarelo-chapada-diamantina',
                'description'       => 'Variedade Bourbon Amarelo cultivada na Chapada Diamantina, BA. Notas de caramelo, amêndoa e frutas amarelas. Processo natural.',
                'origin'            => 'Chapada Diamantina, BA',
                'roast_level'       => 'light',
                'grind_type'        => 'whole_bean',
                'price_b2c'         => 89.90,
                'price_b2b'         => 72.00,
                'min_wholesale_qty' => 10,
                'stock_qty'         => 48,
                'is_active'         => true,
            ],
            [
                'category_id'       => $especiais->id,
                'name'              => 'Catuaí Vermelho Sul de Minas',
                'slug'              => 'catuai-vermelho-sul-de-minas',
                'description'       => 'Produzido na região do Sul de Minas Gerais, reconhecida mundialmente pela qualidade. Notas de chocolate ao leite, nozes e laranja.',
                'origin'            => 'Sul de Minas, MG',
                'roast_level'       => 'medium',
                'grind_type'        => 'whole_bean',
                'price_b2c'         => 79.90,
                'price_b2b'         => 63.00,
                'min_wholesale_qty' => 10,
                'stock_qty'         => 62,
                'is_active'         => true,
            ],
            [
                'category_id'       => $especiais->id,
                'name'              => 'Gesha Fazenda Vista Alegre',
                'slug'              => 'gesha-fazenda-vista-alegre',
                'description'       => 'Variedade Gesha, a mais rara e sofisticada do mundo. Produzida em altitude de 1.400m em Minas Gerais. Florido, chá de jasmim e bergamota.',
                'origin'            => 'Poços de Caldas, MG',
                'roast_level'       => 'light',
                'grind_type'        => 'whole_bean',
                'price_b2c'         => 189.90,
                'price_b2b'         => 155.00,
                'min_wholesale_qty' => 5,
                'stock_qty'         => 18,
                'is_active'         => true,
            ],
            // Blends
            [
                'category_id'       => $blends->id,
                'name'              => 'Blend Armazém 357 Signature',
                'slug'              => 'blend-armazem-357-signature',
                'description'       => 'Nossa combinação exclusiva: 60% Sul de Minas + 40% Cerrado Mineiro. Equilibrado, encorpado, com finalização longa de chocolate amargo.',
                'origin'            => 'Minas Gerais',
                'roast_level'       => 'medium',
                'grind_type'        => 'whole_bean',
                'price_b2c'         => 64.90,
                'price_b2b'         => 49.00,
                'min_wholesale_qty' => 12,
                'stock_qty'         => 120,
                'is_active'         => true,
            ],
            [
                'category_id'       => $blends->id,
                'name'              => 'Blend Espresso Intenso',
                'slug'              => 'blend-espresso-intenso',
                'description'       => 'Desenvolvido para máquinas espresso. 70% Robusta Conillon + 30% Arábica. Alta extração, crema densa, amargor equilibrado.',
                'origin'            => 'Espírito Santo / Minas Gerais',
                'roast_level'       => 'dark',
                'grind_type'        => 'whole_bean',
                'price_b2c'         => 54.90,
                'price_b2b'         => 41.00,
                'min_wholesale_qty' => 20,
                'stock_qty'         => 200,
                'is_active'         => true,
            ],
            // Dia a dia
            [
                'category_id'       => $diario->id,
                'name'              => 'Cerrado Mineiro Torrado e Moído',
                'slug'              => 'cerrado-mineiro-torrado-moido',
                'description'       => 'Café da região do Cerrado Mineiro, já torrado e moído na medida certa para coador ou cafeteira francesa. Notas de chocolate e caramelo.',
                'origin'            => 'Cerrado Mineiro, MG',
                'roast_level'       => 'medium',
                'grind_type'        => 'medium',
                'price_b2c'         => 39.90,
                'price_b2b'         => 28.00,
                'min_wholesale_qty' => 20,
                'stock_qty'         => 300,
                'is_active'         => true,
            ],
            [
                'category_id'       => $diario->id,
                'name'              => 'Saca de Café Arábica 60kg',
                'slug'              => 'saca-cafe-arabica-60kg',
                'description'       => 'Saca padrão de exportação, 60kg, grão verde (cru). Para torrefadoras, grandes empresas e compradores B2B de alto volume.',
                'origin'            => 'Sul de Minas, MG',
                'roast_level'       => 'light',
                'grind_type'        => 'whole_bean',
                'price_b2c'         => 1800.00,
                'price_b2b'         => 1500.00,
                'min_wholesale_qty' => 1,
                'stock_qty'         => 15,
                'is_active'         => true,
            ],
        ];

        foreach ($products as $data) {
            Product::updateOrCreate(
                ['slug' => $data['slug']],
                $data
            );
        }
    }
}
