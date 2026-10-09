<?php

use App\Models\Banner;
use App\Models\Combo;
use App\Models\KidsItem;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Promotion;
use App\Models\Recipe;
use App\Models\Restaurant;
use App\Models\Tip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\ParallelTesting;
use Illuminate\Support\Facades\Storage;

uses(RefreshDatabase::class);

function fakeAdminMenuUploadStorage(): void
{
    ParallelTesting::resolveTokenUsing(fn (): string => (string) getmypid());
    Storage::fake('public');
}

test('category creation stores an uploaded image', function () {
    fakeAdminMenuUploadStorage();

    $response = $this->actingAs(User::factory()->create())
        ->post(route('admin.menu.categories.store'), [
            'name' => 'Grill',
            'slug' => 'grill',
            'image' => UploadedFile::fake()->create('grill.jpg', 100, 'image/jpeg'),
        ]);

    $response->assertRedirect(route('admin.menu.categories.index'));

    $category = MenuCategory::firstOrFail();

    expect($category->image)->toStartWith('menu/categories/');
    Storage::disk('public')->assertExists($category->image);
});

test('category update replaces its uploaded image', function () {
    fakeAdminMenuUploadStorage();
    $oldImagePath = 'menu/categories/old.jpg';
    Storage::disk('public')->put($oldImagePath, 'old image');
    $category = MenuCategory::create([
        'name' => 'Grill',
        'slug' => 'grill',
        'image' => $oldImagePath,
    ]);

    $response = $this->actingAs(User::factory()->create())
        ->put(route('admin.menu.categories.update', $category), [
            'name' => 'Grill Updated',
            'slug' => 'grill-updated',
            'image' => UploadedFile::fake()->create('grill-updated.jpg', 100, 'image/jpeg'),
        ]);

    $response->assertRedirect(route('admin.menu.categories.index'));

    $category->refresh();

    expect($category->image)->toStartWith('menu/categories/');
    Storage::disk('public')->assertExists($category->image);
    Storage::disk('public')->assertMissing($oldImagePath);
});

test('category update removes its current image when requested', function () {
    fakeAdminMenuUploadStorage();
    $imagePath = 'menu/categories/current.jpg';
    Storage::disk('public')->put($imagePath, 'current image');
    $category = MenuCategory::create([
        'name' => 'Grill',
        'slug' => 'grill',
        'image' => $imagePath,
    ]);

    $this->actingAs(User::factory()->create())
        ->get(route('admin.menu.categories.edit', $category))
        ->assertOk()
        ->assertSee('data-image-remove-toggle', false);

    $response = $this->actingAs(User::factory()->create())
        ->put(route('admin.menu.categories.update', $category), [
            'name' => 'Grill',
            'slug' => 'grill',
            'remove_image' => '1',
        ]);

    $response->assertRedirect(route('admin.menu.categories.index'));

    expect($category->fresh()->image)->toBeNull();
    Storage::disk('public')->assertMissing($imagePath);
});

test('menu item update removes its current image when requested', function () {
    fakeAdminMenuUploadStorage();
    $category = MenuCategory::create(['name' => 'Grill', 'slug' => 'grill']);
    $imagePath = 'menu/items/current.jpg';
    Storage::disk('public')->put($imagePath, 'current image');
    $item = MenuItem::create([
        'category_id' => $category->id,
        'name' => 'Beef',
        'slug' => 'beef',
        'price' => 100,
        'image' => $imagePath,
    ]);

    $this->actingAs(User::factory()->create())
        ->get(route('admin.menu.items.edit', $item))
        ->assertOk()
        ->assertSee('data-image-remove-toggle', false);

    $this->actingAs(User::factory()->create())
        ->put(route('admin.menu.items.update', $item), [
            'category_id' => $category->id,
            'name' => 'Beef',
            'slug' => 'beef',
            'price' => 100,
            'remove_image' => '1',
        ])
        ->assertRedirect(route('admin.menu.items.index'));

    expect($item->fresh()->image)->toBeNull();
    Storage::disk('public')->assertMissing($imagePath);
});

test('menu item creation stores an uploaded image', function () {
    fakeAdminMenuUploadStorage();
    $category = MenuCategory::create([
        'name' => 'Grill',
        'slug' => 'grill',
    ]);

    $response = $this->actingAs(User::factory()->create())
        ->post(route('admin.menu.items.store'), [
            'category_id' => $category->id,
            'name' => 'Beef',
            'slug' => 'beef',
            'price' => 100,
            'image' => UploadedFile::fake()->create('beef.jpg', 100, 'image/jpeg'),
        ]);

    $response->assertRedirect(route('admin.menu.items.index'));

    $item = MenuItem::firstOrFail();

    expect($item->image)->toStartWith('menu/items/');
    Storage::disk('public')->assertExists($item->image);
});

test('menu item update replaces its uploaded image', function () {
    fakeAdminMenuUploadStorage();
    $category = MenuCategory::create([
        'name' => 'Grill',
        'slug' => 'grill',
    ]);
    $oldImagePath = 'menu/items/old.jpg';
    Storage::disk('public')->put($oldImagePath, 'old image');
    $item = MenuItem::create([
        'category_id' => $category->id,
        'name' => 'Beef',
        'slug' => 'beef',
        'price' => 100,
        'image' => $oldImagePath,
    ]);

    $response = $this->actingAs(User::factory()->create())
        ->put(route('admin.menu.items.update', $item), [
            'category_id' => $category->id,
            'name' => 'Beef Updated',
            'slug' => 'beef-updated',
            'price' => 120,
            'image' => UploadedFile::fake()->create('beef-updated.jpg', 100, 'image/jpeg'),
        ]);

    $response->assertRedirect(route('admin.menu.items.index'));

    $item->refresh();

    expect($item->image)->toStartWith('menu/items/');
    Storage::disk('public')->assertExists($item->image);
    Storage::disk('public')->assertMissing($oldImagePath);
});

test('combo creation stores an uploaded image', function () {
    fakeAdminMenuUploadStorage();

    $response = $this->actingAs(User::factory()->create())
        ->post(route('admin.menu.combos.store'), [
            'name' => 'Family Set',
            'slug' => 'family-set',
            'price' => 500,
            'image' => UploadedFile::fake()->create('family-set.jpg', 100, 'image/jpeg'),
        ]);

    $response->assertRedirect(route('admin.menu.combos.index'));

    $combo = Combo::firstOrFail();

    expect($combo->image)->toStartWith('menu/combos/');
    Storage::disk('public')->assertExists($combo->image);
});

test('combo update replaces its uploaded image', function () {
    fakeAdminMenuUploadStorage();
    $oldImagePath = 'menu/combos/old.jpg';
    Storage::disk('public')->put($oldImagePath, 'old image');
    $combo = Combo::create([
        'name' => 'Family Set',
        'slug' => 'family-set',
        'price' => 500,
        'image' => $oldImagePath,
    ]);

    $response = $this->actingAs(User::factory()->create())
        ->put(route('admin.menu.combos.update', $combo), [
            'name' => 'Family Set Updated',
            'slug' => 'family-set-updated',
            'price' => 550,
            'image' => UploadedFile::fake()->create('family-set-updated.jpg', 100, 'image/jpeg'),
        ]);

    $response->assertRedirect(route('admin.menu.combos.index'));

    $combo->refresh();

    expect($combo->image)->toStartWith('menu/combos/');
    Storage::disk('public')->assertExists($combo->image);
    Storage::disk('public')->assertMissing($oldImagePath);
});

test('combo update removes its current image when requested', function () {
    fakeAdminMenuUploadStorage();
    $imagePath = 'menu/combos/current.jpg';
    Storage::disk('public')->put($imagePath, 'current image');
    $combo = Combo::create([
        'name' => 'Family Set',
        'slug' => 'family-set',
        'price' => 500,
        'image' => $imagePath,
    ]);

    $this->actingAs(User::factory()->create())
        ->get(route('admin.menu.combos.edit', $combo))
        ->assertOk()
        ->assertSee('data-image-remove-toggle', false);

    $this->actingAs(User::factory()->create())
        ->put(route('admin.menu.combos.update', $combo), [
            'name' => 'Family Set',
            'slug' => 'family-set',
            'price' => 500,
            'remove_image' => '1',
        ])
        ->assertRedirect(route('admin.menu.combos.index'));

    expect($combo->fresh()->image)->toBeNull();
    Storage::disk('public')->assertMissing($imagePath);
});

test('kids item update removes its current image when requested', function () {
    fakeAdminMenuUploadStorage();
    $imagePath = 'kids/items/current.jpg';
    Storage::disk('public')->put($imagePath, 'current image');
    $kidsItem = KidsItem::create([
        'name' => 'Kids Meal',
        'slug' => 'kids-meal',
        'type' => 'food',
        'image' => $imagePath,
    ]);

    $this->actingAs(User::factory()->create())
        ->get(route('admin.kids-items.edit', $kidsItem))
        ->assertOk()
        ->assertSee('data-image-remove-toggle', false);

    $this->actingAs(User::factory()->create())
        ->put(route('admin.kids-items.update', $kidsItem), [
            'name' => 'Kids Meal',
            'slug' => 'kids-meal',
            'type' => 'food',
            'remove_image' => '1',
        ])
        ->assertRedirect(route('admin.kids-items.index'));

    expect($kidsItem->fresh()->image)->toBeNull();
    Storage::disk('public')->assertMissing($imagePath);
});

test('banner update removes its current image when requested', function () {
    fakeAdminMenuUploadStorage();
    $imagePath = 'banners/current.jpg';
    Storage::disk('public')->put($imagePath, 'current image');
    $banner = Banner::create(['type' => 'image', 'image' => $imagePath]);

    $this->actingAs(User::factory()->create())
        ->get(route('admin.menu.banners.edit', $banner))
        ->assertOk()
        ->assertSee('data-vue-banner-form', false)
        ->assertSee('banners\\/current.jpg', false);

    $this->actingAs(User::factory()->create())
        ->put(route('admin.menu.banners.update', $banner), [
            'type' => 'image',
            'remove_image' => '1',
        ])
        ->assertRedirect(route('admin.menu.banners.index'));

    expect($banner->fresh()->image)->toBeNull();
    Storage::disk('public')->assertMissing($imagePath);
});

test('promotion update removes its current image when requested', function () {
    fakeAdminMenuUploadStorage();
    $imagePath = 'promotions/current.jpg';
    Storage::disk('public')->put($imagePath, 'current image');
    $promotion = Promotion::create([
        'title' => 'Summer',
        'slug' => 'summer',
        'image' => $imagePath,
    ]);

    $this->actingAs(User::factory()->create())
        ->get(route('admin.menu.promotions.edit', $promotion))
        ->assertOk()
        ->assertSee('data-image-remove-toggle', false);

    $this->actingAs(User::factory()->create())
        ->put(route('admin.menu.promotions.update', $promotion), [
            'title' => 'Summer',
            'slug' => 'summer',
            'remove_image' => '1',
        ])
        ->assertRedirect(route('admin.menu.promotions.index'));

    expect($promotion->fresh()->image)->toBeNull();
    Storage::disk('public')->assertMissing($imagePath);
});

test('recipe update removes its current image when requested', function () {
    fakeAdminMenuUploadStorage();
    $imagePath = 'recipes/current.jpg';
    Storage::disk('public')->put($imagePath, 'current image');
    $recipe = Recipe::create([
        'title' => 'Grilled Beef',
        'slug' => 'grilled-beef',
        'content' => 'Grill the beef until cooked.',
        'image' => $imagePath,
    ]);

    $this->actingAs(User::factory()->create())
        ->get(route('admin.menu.recipes.edit', $recipe))
        ->assertOk()
        ->assertSee('data-vue-image-field', false)
        ->assertSee('data-image-path="recipes/current.jpg"', false);

    $this->actingAs(User::factory()->create())
        ->put(route('admin.menu.recipes.update', $recipe), [
            'title' => 'Grilled Beef',
            'slug' => 'grilled-beef',
            'content' => 'Grill the beef until cooked.',
            'remove_image' => '1',
        ])
        ->assertRedirect(route('admin.menu.recipes.index'));

    expect($recipe->fresh()->image)->toBeNull();
    Storage::disk('public')->assertMissing($imagePath);
});

test('tip update removes its current image when requested', function () {
    fakeAdminMenuUploadStorage();
    $imagePath = 'tips/current.jpg';
    Storage::disk('public')->put($imagePath, 'current image');
    $tip = Tip::create([
        'title' => 'Grilling tips',
        'slug' => 'grilling-tips',
        'content' => 'Keep the grill at a steady temperature.',
        'image' => $imagePath,
    ]);

    $this->actingAs(User::factory()->create())
        ->get(route('admin.menu.tips.edit', $tip))
        ->assertOk()
        ->assertSee('data-vue-image-field', false)
        ->assertSee('data-image-path="tips/current.jpg"', false);

    $this->actingAs(User::factory()->create())
        ->put(route('admin.menu.tips.update', $tip), [
            'title' => 'Grilling tips',
            'slug' => 'grilling-tips',
            'content' => 'Keep the grill at a steady temperature.',
            'remove_image' => '1',
        ])
        ->assertRedirect(route('admin.menu.tips.index'));

    expect($tip->fresh()->image)->toBeNull();
    Storage::disk('public')->assertMissing($imagePath);
});

test('restaurant update removes its current image when requested', function () {
    fakeAdminMenuUploadStorage();
    $imagePath = 'restaurants/current.jpg';
    Storage::disk('public')->put($imagePath, 'current image');
    $restaurant = Restaurant::create([
        'name' => 'Downtown',
        'address' => 'Main Street',
        'image' => $imagePath,
    ]);

    $this->actingAs(User::factory()->create())
        ->get(route('admin.menu.restaurants.edit', $restaurant))
        ->assertOk()
        ->assertSee('data-image-remove-toggle', false);

    $this->actingAs(User::factory()->create())
        ->put(route('admin.menu.restaurants.update', $restaurant), [
            'name' => 'Downtown',
            'address' => 'Main Street',
            'remove_image' => '1',
        ])
        ->assertRedirect(route('admin.menu.restaurants.index'));

    expect($restaurant->fresh()->image)->toBeNull();
    Storage::disk('public')->assertMissing($imagePath);
});

test('updates without a new image keep the current images', function () {
    fakeAdminMenuUploadStorage();
    $categoryImagePath = 'menu/categories/current.jpg';
    $itemImagePath = 'menu/items/current.jpg';
    $comboImagePath = 'menu/combos/current.jpg';
    Storage::disk('public')->put($categoryImagePath, 'category image');
    Storage::disk('public')->put($itemImagePath, 'item image');
    Storage::disk('public')->put($comboImagePath, 'combo image');

    $category = MenuCategory::create([
        'name' => 'Grill',
        'slug' => 'grill',
        'image' => $categoryImagePath,
    ]);
    $item = MenuItem::create([
        'category_id' => $category->id,
        'name' => 'Beef',
        'slug' => 'beef',
        'price' => 100,
        'image' => $itemImagePath,
    ]);
    $combo = Combo::create([
        'name' => 'Family Set',
        'slug' => 'family-set',
        'price' => 500,
        'image' => $comboImagePath,
    ]);
    $user = User::factory()->create();

    $this->actingAs($user)
        ->put(route('admin.menu.categories.update', $category), [
            'name' => 'Grill Updated',
            'slug' => 'grill-updated',
        ])
        ->assertRedirect(route('admin.menu.categories.index'));

    $this->put(route('admin.menu.items.update', $item), [
        'category_id' => $category->id,
        'name' => 'Beef Updated',
        'slug' => 'beef-updated',
        'price' => 120,
    ])->assertRedirect(route('admin.menu.items.index'));

    $this->put(route('admin.menu.combos.update', $combo), [
        'name' => 'Family Set Updated',
        'slug' => 'family-set-updated',
        'price' => 550,
    ])->assertRedirect(route('admin.menu.combos.index'));

    expect($category->fresh()->image)->toBe($categoryImagePath)
        ->and($item->fresh()->image)->toBe($itemImagePath)
        ->and($combo->fresh()->image)->toBe($comboImagePath);

    Storage::disk('public')->assertExists($categoryImagePath);
    Storage::disk('public')->assertExists($itemImagePath);
    Storage::disk('public')->assertExists($comboImagePath);
});
