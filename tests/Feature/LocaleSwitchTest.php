<?php

use App\Models\Banner;
use App\Models\Combo;
use App\Models\KidsItem;
use App\Models\MenuCategory;
use App\Models\MenuItem;
use App\Models\Promotion;
use App\Models\Recipe;
use App\Models\Restaurant;
use App\Models\Setting;
use App\Models\Tip;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('visitors can switch language and keep the choice on the next page', function () {
    $this->from(route('home'))
        ->post(route('locale.update'), ['locale' => 'en'])
        ->assertRedirect(route('home'))
        ->assertSessionHas('locale', 'en');

    $this->get(route('home'))
        ->assertOk()
        ->assertSee('<html lang="en">', false)
        ->assertSee('Home')
        ->assertSee('Our story')
        ->assertSee('Savour the taste of Japanese barbecue')
        ->assertSee('Sign up for offers')
        ->assertSee('aria-pressed="true">EN</button>', false);
});

test('the website defaults to Vietnamese', function () {
    $this->get(route('home'))
        ->assertOk()
        ->assertSee('<html lang="vi">', false)
        ->assertSee('Trang chủ')
        ->assertSee('aria-pressed="true">VI</button>', false);

    expect(localized_price(1234000))->toBe('1.234.000 đ');
});

test('visitors cannot select an unsupported language', function () {
    $this->from(route('home'))
        ->post(route('locale.update'), ['locale' => 'fr'])
        ->assertSessionHasErrors('locale')
        ->assertSessionMissing('locale');
});

test('offer signup responses use the selected language', function () {
    $this->withSession(['locale' => 'en'])
        ->postJson(route('leads.store'), [
            'salutation' => 'Ông',
            'name' => 'Alex Morgan',
            'email' => 'alex@example.com',
            'phone' => '0901234567',
        ])
        ->assertCreated()
        ->assertJsonPath('message', 'You have successfully signed up for offers.');
});

test('menu and booking pages use the selected language', function () {
    $this->withSession(['locale' => 'en'])
        ->get(route('menu.for-kids'))
        ->assertOk()
        ->assertSee('For Kids')
        ->assertSee('Category')
        ->assertSee('Food')
        ->assertSee('All categories')
        ->assertSee('Price: low to high');

    $this->get(route('booking.create'))
        ->assertOk()
        ->assertSee('Reservation details')
        ->assertSee('Full name')
        ->assertSee('Submit reservation request');
});

test('public pages use English content fields and translate legacy content', function () {
    Banner::create([
        'title' => 'Tiêu đề banner',
        'title_en' => 'English banner title',
        'type' => 'image',
        'image' => 'banners/hero.jpg',
        'status' => true,
    ]);

    $category = MenuCategory::create([
        'name' => 'Thịt bò',
        'slug' => 'beef',
        'name_en' => 'Beef',
        'description' => 'Các món thịt bò',
        'description_en' => 'Beef dishes',
        'status' => true,
    ]);

    MenuItem::create([
        'category_id' => $category->id,
        'name' => 'Wagyu',
        'slug' => 'wagyu',
        'name_en' => 'English Wagyu',
        'description' => 'Thịt bò Wagyu',
        'description_en' => 'Premium beef cut',
        'price' => 500,
        'is_must_try' => true,
        'status' => true,
    ]);

    Combo::create([
        'name' => 'Gói gia đình',
        'slug' => 'family-combo',
        'name_en' => 'Family combo',
        'description' => 'Bữa ăn gia đình',
        'description_en' => 'A meal for the whole family',
        'price' => 1000,
        'status' => true,
    ]);

    Promotion::create([
        'title' => 'Ưu đãi cuối tuần',
        'slug' => 'weekend-offer',
        'title_en' => 'Weekend offer',
        'short_description' => 'Giảm giá cuối tuần',
        'short_description_en' => 'Weekend savings',
        'description' => 'Ưu đãi dành cho gia đình',
        'description_en' => 'An offer for families',
        'status' => true,
    ]);

    KidsItem::create([
        'name' => 'Mì cho bé',
        'slug' => 'kids-noodles',
        'name_en' => 'Kids noodles',
        'type' => 'food',
        'description' => 'Mì dành cho trẻ em',
        'description_en' => 'Noodles made for little diners',
        'status' => true,
    ]);

    $recipe = Recipe::create([
        'title' => 'Công thức bò nướng',
        'slug' => 'grilled-beef-recipe',
        'title_en' => 'Grilled beef recipe',
        'short_description' => 'Mô tả công thức',
        'short_description_en' => 'Recipe summary',
        'content' => 'Nội dung công thức',
        'content_en' => 'Recipe instructions',
        'status' => true,
    ]);

    $tip = Tip::create([
        'title' => 'Bí quyết nướng ngon',
        'slug' => 'grilling-tip',
        'title_en' => 'Grilling tip',
        'short_description' => 'Mô tả bí quyết',
        'short_description_en' => 'Tip summary',
        'content' => 'Nội dung bí quyết',
        'content_en' => 'Tip instructions',
        'status' => true,
    ]);

    Restaurant::create([
        'name' => 'Nhà hàng Hà Nội',
        'address' => 'Hà Nội',
        'name_en' => 'Hanoi restaurant',
        'address_en' => 'Hanoi, Vietnam',
        'status' => true,
    ]);

    Setting::create(['key' => 'site_name', 'value' => 'NHÀ HÀNG USSINA AGING BEEF & BAR']);
    Setting::create(['key' => 'footer_address', 'value' => 'Tầng L77, Tòa nhà Landmark 81, 720A Điện Biên Phủ, phường Thạnh Mỹ Tây, Thành phố Hồ Chí Minh, Việt Nam']);
    Setting::create(['key' => 'footer_address_en', 'value' => 'English footer address']);
    Setting::create(['key' => 'footer_description', 'value' => 'Yakiniku King - Nhà hàng thịt nướng phong cách Nhật Bản.']);

    $this->withSession(['locale' => 'en'])
        ->get(route('home'))
        ->assertSee('English banner title')
        ->assertSee('English footer address')
        ->assertSee('USSINA AGING BEEF & BAR RESTAURANT')
        ->assertSee('Japanese-style barbecue restaurant.');

    $this->get(route('menu.index'))
        ->assertSee('Beef')
        ->assertSee('English Wagyu')
        ->assertSee('Premium beef cut')
        ->assertSee('500 VND');

    $this->get(route('menu.combos'))
        ->assertSee('Family combo')
        ->assertSee('A meal for the whole family')
        ->assertSee('1,000 VND');

    $this->get(route('menu.promotions'))
        ->assertSee('Weekend offer')
        ->assertSee('Weekend savings')
        ->assertSee('An offer for families');

    $this->get(route('menu.for-kids'))
        ->assertSee('Kids noodles')
        ->assertSee('Noodles made for little diners');

    $this->get(route('secret.recipes'))
        ->assertSee('Grilled beef recipe')
        ->assertSee('Recipe summary')
        ->assertSee('Recipe instructions');

    $this->get(route('secret.tip', $tip))
        ->assertSee('Grilling tip')
        ->assertSee('Tip instructions');

    $this->get(route('booking.create'))
        ->assertSee('Hanoi restaurant')
        ->assertSee('English Wagyu')
        ->assertSee('500 VND');

    expect(localized_text(MenuCategory::create([
        'name' => 'Thịt bò',
        'slug' => 'legacy-beef',
        'status' => true,
    ]), 'name'))->toBe('Beef');
});

test('admin updates persist English content fields', function () {
    $category = MenuCategory::create([
        'name' => 'Thịt bò',
        'slug' => 'beef',
        'status' => true,
    ]);

    $this->actingAs(User::factory()->create())
        ->put(route('admin.menu.categories.update', $category), [
            'name' => 'Thịt bò',
            'slug' => 'beef',
            'name_en' => 'Beef',
            'description_en' => 'Beef dishes',
        ])
        ->assertRedirect(route('admin.menu.categories.index'));

    $this->assertDatabaseHas('menu_categories', [
        'id' => $category->id,
        'name_en' => 'Beef',
        'description_en' => 'Beef dishes',
    ]);

    $this->actingAs(User::factory()->create())
        ->put(route('admin.menu.settings.update'), [
            'settings' => ['footer_address_en' => 'English address'],
        ])
        ->assertRedirect(route('admin.menu.settings.index'));

    $this->assertDatabaseHas('settings', [
        'key' => 'footer_address_en',
        'value' => 'English address',
    ]);
});

test('admin create actions persist English content for public records', function () {
    $user = User::factory()->create();
    $category = MenuCategory::create([
        'name' => 'Thịt bò',
        'slug' => 'beef',
        'status' => true,
    ]);

    $this->actingAs($user)
        ->post(route('admin.menu.banners.store'), [
            'type' => 'image',
            'title' => 'Banner',
            'title_en' => 'English banner',
        ])
        ->assertRedirect(route('admin.menu.banners.index'));

    $this->post(route('admin.menu.categories.store'), [
        'name' => 'Thịt heo',
        'slug' => 'pork',
        'name_en' => 'Pork',
        'description_en' => 'Pork dishes',
    ])->assertRedirect(route('admin.menu.categories.index'));

    $this->post(route('admin.menu.items.store'), [
        'category_id' => $category->id,
        'name' => 'Wagyu',
        'slug' => 'wagyu',
        'name_en' => 'Wagyu beef',
        'description_en' => 'Premium beef',
        'price' => 500,
    ])->assertRedirect(route('admin.menu.items.index'));

    $this->post(route('admin.menu.combos.store'), [
        'name' => 'Combo gia đình',
        'slug' => 'family-combo',
        'name_en' => 'Family combo',
        'description_en' => 'A meal for the family',
        'price' => 1000,
    ])->assertRedirect(route('admin.menu.combos.index'));

    $this->post(route('admin.menu.promotions.store'), [
        'title' => 'Ưu đãi',
        'slug' => 'offer',
        'title_en' => 'Special offer',
        'short_description_en' => 'Save on your meal',
        'description_en' => 'An English promotion description',
    ])->assertRedirect(route('admin.menu.promotions.index'));

    $this->post(route('admin.menu.recipes.store'), [
        'title' => 'Công thức',
        'slug' => 'recipe',
        'title_en' => 'Recipe',
        'short_description_en' => 'Recipe summary',
        'content' => 'Nội dung',
        'content_en' => 'Recipe instructions',
    ])->assertRedirect(route('admin.menu.recipes.index'));

    $this->post(route('admin.menu.tips.store'), [
        'title' => 'Bí kíp',
        'slug' => 'tip',
        'title_en' => 'Tip',
        'short_description_en' => 'Tip summary',
        'content' => 'Nội dung',
        'content_en' => 'Tip instructions',
    ])->assertRedirect(route('admin.menu.tips.index'));

    $this->post(route('admin.menu.restaurants.store'), [
        'name' => 'Nhà hàng Hà Nội',
        'address' => 'Hà Nội',
        'name_en' => 'Hanoi restaurant',
        'address_en' => 'Hanoi, Vietnam',
    ])->assertRedirect(route('admin.menu.restaurants.index'));

    $this->post(route('admin.kids-items.store'), [
        'name' => 'Mì cho bé',
        'slug' => 'kids-noodles',
        'name_en' => 'Kids noodles',
        'type' => 'food',
        'description_en' => 'Noodles for little diners',
    ])->assertRedirect(route('admin.kids-items.index'));

    $this->assertDatabaseHas('banners', ['title_en' => 'English banner']);
    $this->assertDatabaseHas('menu_categories', ['name_en' => 'Pork', 'description_en' => 'Pork dishes']);
    $this->assertDatabaseHas('menu_items', ['name_en' => 'Wagyu beef', 'description_en' => 'Premium beef']);
    $this->assertDatabaseHas('combos', ['name_en' => 'Family combo', 'description_en' => 'A meal for the family']);
    $this->assertDatabaseHas('promotions', ['title_en' => 'Special offer', 'short_description_en' => 'Save on your meal']);
    $this->assertDatabaseHas('recipes', ['title_en' => 'Recipe', 'content_en' => 'Recipe instructions']);
    $this->assertDatabaseHas('tips', ['title_en' => 'Tip', 'content_en' => 'Tip instructions']);
    $this->assertDatabaseHas('restaurants', ['name_en' => 'Hanoi restaurant', 'address_en' => 'Hanoi, Vietnam']);
    $this->assertDatabaseHas('kids_items', ['name_en' => 'Kids noodles', 'description_en' => 'Noodles for little diners']);
});

test('admin forms expose English fields for public content', function () {
    $user = User::factory()->create();
    $forms = [
        'admin.menu.banners.create' => ['title_en'],
        'admin.menu.categories.create' => ['name_en', 'description_en'],
        'admin.menu.items.create' => ['name_en', 'description_en'],
        'admin.menu.combos.create' => ['name_en', 'description_en'],
        'admin.menu.promotions.create' => ['title_en', 'short_description_en', 'description_en'],
        'admin.menu.recipes.create' => ['title_en', 'short_description_en', 'content_en'],
        'admin.menu.tips.create' => ['title_en', 'short_description_en', 'content_en'],
        'admin.menu.restaurants.create' => ['name_en', 'address_en'],
        'admin.kids-items.create' => ['name_en', 'description_en'],
        'admin.menu.settings.index' => ['settings[site_name_en]', 'settings[footer_address_en]', 'settings[footer_description_en]'],
    ];

    foreach ($forms as $routeName => $fieldNames) {
        $response = $this->actingAs($user)->get(route($routeName));

        if ($routeName === 'admin.menu.banners.create') {
            $response->assertSee('data-values=', false)
                ->assertSee('&quot;title_en&quot;', false);

            continue;
        }

        foreach ($fieldNames as $fieldName) {
            $response->assertSee('name="'.$fieldName.'"', false);
        }
    }
});
