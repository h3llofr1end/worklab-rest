<?php

namespace Tests\Unit;

use App\Product;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ProductTest extends TestCase
{
    use DatabaseMigrations;

    /** @test */
    function i_can_return_products_modified_by_last_5_minutes()
    {
        $productA = factory(Product::class)->create([
            'updated_at' => new Carbon('-30 minutes')
        ]);
        $productB = factory(Product::class)->create([
            'updated_at' => new Carbon('-5 minutes')
        ]);
        $productC = factory(Product::class)->create([
            'updated_at' => new Carbon('-15 minutes')
        ]);
        $productD = factory(Product::class)->create();
        $result = Product::getModifiedProducts(5);
        $this->assertEquals(count($result), 2);
        $this->assertFalse($result->contains($productA));
        $this->assertTrue($result->contains($productB));
        $this->assertFalse($result->contains($productC));
        $this->assertTrue($result->contains($productD));
    }
}
