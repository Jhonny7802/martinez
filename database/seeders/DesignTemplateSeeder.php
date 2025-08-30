<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DesignTemplate;

class DesignTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $templates = [
            [
                'name' => 'Casa Moderna Minimalista',
                'category' => 'residential',
                'description' => 'Diseño moderno con líneas limpias y espacios abiertos',
                'dimensions' => '1200x800',
                'default_elements' => json_encode([
                    'house_style' => 'modern',
                    'roof_type' => 'flat',
                    'windows' => 'large',
                    'garage' => true
                ]),
                'is_active' => true,
                'created_by' => 1
            ],
            [
                'name' => 'Casa Tradicional Familiar',
                'category' => 'residential',
                'description' => 'Casa tradicional perfecta para familias grandes',
                'dimensions' => '1400x900',
                'default_elements' => json_encode([
                    'house_style' => 'traditional',
                    'roof_type' => 'gabled',
                    'windows' => 'standard',
                    'garage' => true,
                    'garden' => true
                ]),
                'is_active' => true,
                'created_by' => 1
            ],
            [
                'name' => 'Casa de Campo Rústica',
                'category' => 'residential',
                'description' => 'Diseño rústico con materiales naturales',
                'dimensions' => '1000x700',
                'default_elements' => json_encode([
                    'house_style' => 'rustic',
                    'roof_type' => 'gabled',
                    'windows' => 'wooden',
                    'porch' => true
                ]),
                'is_active' => true,
                'created_by' => 1
            ],
            [
                'name' => 'Villa Mediterránea',
                'category' => 'residential',
                'description' => 'Estilo mediterráneo con patio y jardín',
                'dimensions' => '1600x1000',
                'default_elements' => json_encode([
                    'house_style' => 'mediterranean',
                    'roof_type' => 'tile',
                    'windows' => 'arched',
                    'patio' => true,
                    'garden' => true
                ]),
                'is_active' => true,
                'created_by' => 1
            ],
            [
                'name' => 'Apartamento Urbano',
                'category' => 'residential',
                'description' => 'Diseño compacto para espacios urbanos',
                'dimensions' => '800x600',
                'default_elements' => json_encode([
                    'house_style' => 'urban',
                    'roof_type' => 'flat',
                    'windows' => 'modern',
                    'balcony' => true
                ]),
                'is_active' => true,
                'created_by' => 1
            ]
        ];

        foreach ($templates as $template) {
            DesignTemplate::create($template);
        }
    }
}
