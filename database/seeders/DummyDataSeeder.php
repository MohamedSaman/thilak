<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Brand;
use App\Models\ProductCategory;
use App\Models\ProductDetail;
use App\Models\ProductStock;
use App\Models\Customer;
use Faker\Factory as Faker;

class DummyDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();

        // 1. Seed Categories
        $categories = [];
        $categoryNames = ['Plumbing', 'Electrical', 'Hardware', 'Paints', 'Tools', 'Building Materials', 'Sanitary Ware', 'Roofing', 'Tiles', 'Safety Gear'];
        
        foreach ($categoryNames as $name) {
            $categories[] = ProductCategory::create([
                'name' => $name,
                'description' => $faker->sentence,
            ]);
        }
        $this->command->info('Categories seeded.');

        // 2. Seed Brands
        $brands = [];
        $brandNames = ['Dulux', 'Orange Electric', 'Tokyo Cement', 'Hikoki', 'Anton', 'S-Lon', 'Lanka Tiles', 'Rocell', 'Nippon Paint', 'Makita'];

        foreach ($brandNames as $name) {
            $brands[] = Brand::create([
                'brand_name' => $name,
                'notes' => $faker->sentence,
            ]);
        }
        $this->command->info('Brands seeded.');

        // 3. Seed Customers
        for ($i = 0; $i < 20; $i++) {
            Customer::create([
                'name' => $faker->name,
                'phone' => $faker->phoneNumber,
                'email' => $faker->unique()->safeEmail,
                'type' => $faker->randomElement(['Wholesale', 'Retail']),
                'address' => $faker->address,
                'business_name' => $faker->company,
                'notes' => $faker->sentence,
            ]);
        }
        $this->command->info('Customers seeded.');

        // 4. Seed Products
        for ($i = 0; $i < 50; $i++) {
            $supplierPrice = $faker->randomFloat(2, 100, 5000);
            $sellingPrice = $supplierPrice * 1.5; // 50% margin
            $mrpPrice = $sellingPrice * 1.2; // slightly higher MRP
            $stockQty = $faker->numberBetween(0, 500);
            $damageQty = $faker->numberBetween(0, 50);

            $product = ProductDetail::create([
                'product_code' => 'P' . $faker->unique()->numberBetween(1000, 9999),
                'category_id' => $faker->randomElement($categories)->id,
                'brand_id' => $faker->randomElement($brands)->id,
                'product_name' => $faker->words(3, true),
                'image_url' => null, // Or use a placeholder if needed
                'supplier_price' => $supplierPrice,
                'selling_price' => $sellingPrice,
                'mrp_price' => $mrpPrice,
                'stock_quantity' => $stockQty,
                'damage_quantity' => $damageQty,
                'sold' => $faker->numberBetween(0, 200),
                'status' => $faker->randomElement(['Available', 'Unavailable']),
                'customer_field' => json_encode([]), 
            ]);

            // Sync with ProductStock table
            ProductStock::create([
                'product_id' => $product->id,
                'total_stock' => $stockQty,
                'damage_stock' => $damageQty,
            ]);
        }
        $this->command->info('Products seeded.');
    }
}
