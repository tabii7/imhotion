<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PricingCategory;
use App\Models\PricingItem;

class PricingSeeder extends Seeder
{
    public function run(): void
    {
        // Categories
        $cat1 = PricingCategory::create(["name" => "Category Price per day", "slug" => "price-per-day", "description" => "For small projects or an extra day", "sort" => 0]);
        $cat2 = PricingCategory::create(["name" => "Our Packs", "slug" => "our-packs", "description" => "Save and get extra days", "sort" => 10]);
        $cat3 = PricingCategory::create(["name" => "Addons", "slug" => "addons", "description" => "Enhance your project with more options", "sort" => 20]);

        // Items: price per day
        PricingItem::create(["pricing_category_id" => $cat1->id, "title" => "Weekday", "price" => 549, "price_unit" => "per_day", "sort" => 0]);
        PricingItem::create(["pricing_category_id" => $cat1->id, "title" => "Weekend", "price" => 649, "price_unit" => "per_day", "sort" => 1]);

        // Our Packs
        PricingItem::create(["pricing_category_id" => $cat2->id, "title" => "5 Days", "price" => 519, "price_unit" => "per_day", "duration_years" => 1, "discount_percent" => 5, "has_gift_box" => false, "has_project_files" => false, "has_weekends_included" => false, "sort" => 0]);
        PricingItem::create(["pricing_category_id" => $cat2->id, "title" => "10 Days", "price" => 499, "price_unit" => "per_day", "duration_years" => 3, "discount_percent" => 10, "has_gift_box" => true, "has_project_files" => true, "has_weekends_included" => false, "sort" => 1]);
        PricingItem::create(["pricing_category_id" => $cat2->id, "title" => "20 Days", "price" => 469, "price_unit" => "per_day", "duration_years" => 5, "discount_percent" => 15, "has_gift_box" => true, "has_project_files" => true, "has_weekends_included" => true, "sort" => 2]);

        // Addons
        PricingItem::create(["pricing_category_id" => $cat3->id, "title" => "Extended Day", "price" => 79, "price_unit" => "per_hour", "sort" => 0]);
        PricingItem::create(["pricing_category_id" => $cat3->id, "title" => "Project Files", "price" => 99, "price_unit" => "per_year", "sort" => 1]);
        PricingItem::create(["pricing_category_id" => $cat3->id, "title" => "Camera Package", "price" => 249, "price_unit" => "per_day", "sort" => 2]);
        PricingItem::create(["pricing_category_id" => $cat3->id, "title" => "Website Content", "price" => 349, "price_unit" => "per_project", "sort" => 3]);
    }
}
