<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\CategoryTranslation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryRelationshipTest extends TestCase
{
    use RefreshDatabase;

    /*
     * A basic feature test example.
     */
    public function test_category_has_translations(): void
    {
        $category = Category::factory()->create();

        $spanish = CategoryTranslation::factory()
            ->for($category)
            ->create(['locale' => 'es']);
        $english = CategoryTranslation::factory()
            ->for($category)
            ->create(['locale' => 'en']);

        CategoryTranslation::factory()->create();

        $translations = $category->translations;

        $this->assertCount(2, $translations);
        $this->assertTrue($translations->contains($spanish));
        $this->assertTrue($translations->contains($english));
    }

    public function test_translation_belongs_to_category(): void
    {
        $category = Category::factory()->create();
        $translation = CategoryTranslation::factory()
            ->for($category)
            ->create();

        $this->assertInstanceOf(Category::class, $translation->category);
        $this->assertTrue($translation->category->is($category));
    }

    public function test_category_belongs_to_parent(): void
    {
        $parent = Category::factory()->create();

        $child = Category::factory()
            ->for($parent, 'parent')
            ->create();

        $this->assertInstanceOf(Category::class, $child->parent);
        $this->assertTrue($child->parent->is($parent));
    }

    public function test_category_has_only_its_direct_children(): void
    {
        $parent = Category::factory()->create();

        $children = Category::factory()
            ->count(2)
            ->for($parent, 'parent')
            ->create();

        $grandchild = Category::factory()
            ->for($children->first(), 'parent')
            ->create();

        $unrelated = Category::factory()->create();

        $actualChildren = $parent->children;

        $this->assertCount(2, $actualChildren);

        foreach ($children as $child) {
            $this->assertTrue($actualChildren->contains($child));
        }

        $this->assertFalse($actualChildren->contains($grandchild));
        $this->assertFalse($actualChildren->contains($unrelated));
    }

    public function test_root_category_has_no_parent(): void
    {
        $category = Category::factory()->create([
            'parent_id' => null,
        ]);

        $this->assertNull($category->parent);
    }
}
