<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\ProductModel;
use Illuminate\Database\Seeder;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        // ---- 1. CATEGORÍAS PADRE (parent_category_id = null) ----
        $hombre  = Category::create(['name' => 'Ropa de Hombre', 'slug' => 'ropa-de-hombre', 'description' => 'Moda masculina para toda ocasión.', 'parent_category_id' => null, 'is_active' => true]);
        $mujer   = Category::create(['name' => 'Ropa de Mujer',  'slug' => 'ropa-de-mujer',  'description' => 'Moda femenina y tendencias.',      'parent_category_id' => null, 'is_active' => true]);
        $calzado = Category::create(['name' => 'Calzado',        'slug' => 'calzado',        'description' => 'Zapatos y tenis para todos.',      'parent_category_id' => null, 'is_active' => true]);

        // ---- 2. SUBCATEGORÍAS (apuntan a su padre, nombres únicos) ----
        $camisas   = Category::create(['name' => 'Camisas',       'slug' => 'camisas',       'description' => 'Camisas de hombre.',    'parent_category_id' => $hombre->id,  'is_active' => true]);
        $pantHombre = Category::create(['name' => 'Pantalones Hombre', 'slug' => 'pantalones-hombre', 'description' => 'Pantalones de hombre.', 'parent_category_id' => $hombre->id, 'is_active' => true]);
        $blusas    = Category::create(['name' => 'Blusas',        'slug' => 'blusas',        'description' => 'Blusas de mujer.',      'parent_category_id' => $mujer->id,   'is_active' => true]);
        $vestidos  = Category::create(['name' => 'Vestidos',      'slug' => 'vestidos',      'description' => 'Vestidos de mujer.',    'parent_category_id' => $mujer->id,   'is_active' => true]);
        $zapatos   = Category::create(['name' => 'Zapatos',       'slug' => 'zapatos',       'description' => 'Zapatos formales.',     'parent_category_id' => $calzado->id, 'is_active' => true]);
        $tenis     = Category::create(['name' => 'Tenis',         'slug' => 'tenis',         'description' => 'Tenis urbanos.',        'parent_category_id' => $calzado->id, 'is_active' => true]);

        // ---- 3. PRODUCTOS (asociados a SUBCATEGORÍAS) ----
        $productos = [
            ['cat' => $camisas->id,    'name' => 'Camisa Azul Clásica',      'slug' => 'camisa-azul-clasica',   'price' => 149.99, 'offer' => 119.99],
            ['cat' => $camisas->id,    'name' => 'Camisa Blanca Formal',     'slug' => 'camisa-blanca-formal',  'price' => 159.99, 'offer' => null],
            ['cat' => $pantHombre->id, 'name' => 'Pantalón Negro de Vestir', 'slug' => 'pantalon-negro-vestir', 'price' => 199.99, 'offer' => 179.99],
            ['cat' => $pantHombre->id, 'name' => 'Jeans Azul Slim Fit',      'slug' => 'jeans-azul-slim-fit',   'price' => 219.99, 'offer' => null],
            ['cat' => $blusas->id,     'name' => 'Blusa Floral de Verano',   'slug' => 'blusa-floral-verano',   'price' => 129.99, 'offer' => 99.99],
            ['cat' => $blusas->id,     'name' => 'Blusa Blanca Elegante',    'slug' => 'blusa-blanca-elegante', 'price' => 139.99, 'offer' => null],
            ['cat' => $vestidos->id,   'name' => 'Vestido Rojo de Noche',    'slug' => 'vestido-rojo-noche',    'price' => 289.99, 'offer' => 249.99],
            ['cat' => $zapatos->id,    'name' => 'Zapatos Café de Cuero',    'slug' => 'zapatos-cafe-cuero',    'price' => 349.99, 'offer' => null],
            ['cat' => $tenis->id,      'name' => 'Tenis Blancos Urbanos',    'slug' => 'tenis-blancos-urbanos', 'price' => 289.99, 'offer' => 259.99],
            ['cat' => $tenis->id,      'name' => 'Tenis Negros Deportivos',  'slug' => 'tenis-negros-deportivos','price' => 269.99, 'offer' => null],
        ];

        foreach ($productos as $p) {
            ProductModel::create([
                'category_id' => $p['cat'],
                'name'        => $p['name'],
                'slug'        => $p['slug'],
                'description' => 'Producto de excelente calidad, ideal para cualquier ocasión.',
                'price'       => $p['price'],
                'offer_price' => $p['offer'],
                'sale_type'   => 'direct',
                'status'      => 'available',
            ]);
        }
    }
}