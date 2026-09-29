<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\CategoryTranslation;
use Illuminate\Database\QueryException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CategoryConstraintsTest extends TestCase
{
    use RefreshDatabase;

    public function test_category_cannot_have_two_translations_for_the_same_locale(): void
    {
        $category = Category::factory()->create();

        CategoryTranslation::factory()
            ->for($category)
            ->create([
                'locale' => 'es',
                'slug' => 'accesorios',
            ]);

        $this->expectException(UniqueConstraintViolationException::class);

        CategoryTranslation::factory()
            ->for($category)
            ->create([
                'locale' => 'es',
                'slug' => 'otros-accesorios',
            ]);
    }

    public function test_slug_must_be_unique_for_each_locale(): void
    {
        $firstCategory = Category::factory()->create();
        $secondCategory = Category::factory()->create();

        CategoryTranslation::factory()
            ->for($firstCategory)
            ->create([
                'locale' => 'es',
                'slug' => 'accesores',
            ]);

        $this->expectException(UniqueConstraintViolationException::class);

        CategoryTranslation::factory()
            ->for($secondCategory)
            ->create([
                'locale' => 'es',
                'slug' => 'accesores',
            ]);
    }

    public function test_same_slug_for_different_locales(): void
    {
        $category = Category::factory()->create();

        $spanish = CategoryTranslation::factory()
            ->for($category)
            ->create([
                'locale' => 'es',
                'slug' => 'manga',
            ]);

        $english = CategoryTranslation::factory()
            ->for($category)
            ->create([
                'locale' => 'en',
                'slug' => 'manga',
            ]);

        $this->assertDatabaseHas('category_translations', [
            'id' => $spanish->id,
            'category_id' => $category->id,
            'locale' => 'es',
            'slug' => 'manga',
        ]);

        $this->assertDatabaseHas('category_translations', [
            'id' => $english->id,
            'category_id' => $category->id,
            'locale' => 'en',
            'slug' => 'manga',
        ]);
    }

    public function test_deleting_category_deletes_its_translations(): void
    {
        $category = Category::factory()->create();

        $spanish = CategoryTranslation::factory()
            ->for($category)
            ->create(['locale' => 'es']);

        $english = CategoryTranslation::factory()
            ->for($category)
            ->create(['locale' => 'en']);

        $unrelated = CategoryTranslation::factory()->create();

        $category->delete();

        $this->assertDatabaseMissing('categories', [
            'id' => $category->id,
        ]);

        $this->assertDatabaseMissing('category_translations', [
            'id' => $spanish->id,
        ]);

        $this->assertDatabaseMissing('category_translations', [
            'id' => $english->id,
        ]);

        $this->assertDatabaseHas('category_translations', [
            'id' => $unrelated->id,
        ]);
    }

    public function test_category_with_children_cannot_be_deleted(): void
    {
        $parent = Category::factory()->create();

        Category::factory()
            ->for($parent, 'parent')
            ->create();

        $this->expectException(QueryException::class);

        $parent->delete();
    }

    public function test_deleting_translation_keeps_category_and_other_translations(): void
    {
        $category = Category::factory()->create();

        $spanish = CategoryTranslation::factory()
            ->for($category)
            ->create(['locale' => 'es']);

        $english = CategoryTranslation::factory()
            ->for($category)
            ->create(['locale' => 'en']);

        $spanish->delete();

        $this->assertDatabaseMissing('category_translations', [
            'id' => $spanish->id,
        ]);

        $this->assertDatabaseHas('categories', [
            'id' => $category->id,
        ]);

        $this->assertDatabaseHas('category_translations', [
            'id' => $english->id,
        ]);
    }
}
