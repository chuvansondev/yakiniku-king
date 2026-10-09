<?php

use App\Models\Tip;
use App\Models\User;

test('an administrator can update a tip', function () {
    $administrator = User::factory()->create(['is_admin' => true]);
    $tip = Tip::create([
        'title' => 'Original title',
        'slug' => 'original-title',
        'content' => 'Original content',
    ]);

    $response = $this->actingAs($administrator)->put(route('admin.menu.tips.update', $tip), [
        'title' => 'Updated title',
        'content' => 'Updated content',
    ]);

    $response->assertRedirect(route('admin.menu.tips.index'));

    expect($tip->fresh())
        ->title->toBe('Updated title')
        ->content->toBe('Updated content');
});
