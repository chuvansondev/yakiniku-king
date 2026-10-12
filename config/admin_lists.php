<?php

return [
    'Booking' => [
        'search' => ['booking_code', 'customer_name', 'phone', 'email'],
        'filters' => [
            'status' => ['options' => ['pending' => 'Pending', 'confirmed' => 'Confirmed', 'cancelled' => 'Cancelled', 'completed' => 'Completed']],
            'restaurant_id' => ['column' => 'restaurant_id', 'options_source' => 'restaurants'],
            'booking_date' => ['type' => 'date', 'label' => 'booking_date'],
        ],
        'sort' => ['id', 'booking_date', 'booking_time', 'customer_name', 'status', 'number_of_guests'],
        'per_page' => 20,
    ],
    'MenuItem' => [
        'search' => ['name', 'name_en', 'slug'],
        'filters' => [
            'category_id' => ['column' => 'category_id', 'options_source' => 'categories'],
            'status' => ['options' => ['1' => 'Active', '0' => 'Inactive']],
            'is_must_try' => ['options' => ['1' => 'Yes', '0' => 'No']],
        ],
        'sort' => ['id', 'name', 'price', 'sort_order', 'status', 'created_at'],
    ],
    'MenuCategory' => [
        'search' => ['name', 'name_en', 'slug'],
        'filters' => ['status' => ['options' => ['1' => 'Active', '0' => 'Inactive']]],
        'sort' => ['id', 'name', 'sort_order', 'status', 'created_at'],
    ],
    'Combo' => [
        'search' => ['name', 'name_en', 'slug'],
        'filters' => ['status' => ['options' => ['1' => 'Active', '0' => 'Inactive']]],
        'sort' => ['id', 'name', 'price', 'sort_order', 'status', 'created_at'],
    ],
    'Banner' => [
        'search' => ['title', 'title_en', 'link', 'video_url'],
        'filters' => [
            'type' => ['options' => ['image' => 'image', 'video' => 'video']],
            'status' => ['options' => ['1' => 'Active', '0' => 'Inactive']],
        ],
        'sort' => ['id', 'title', 'type', 'sort_order', 'status', 'created_at'],
    ],
    'Promotion' => [
        'search' => ['title', 'title_en', 'short_description', 'short_description_en', 'description', 'description_en'],
        'filters' => ['status' => ['options' => ['1' => 'Active', '0' => 'Inactive']]],
        'sort' => ['id', 'title', 'start_date', 'end_date', 'status', 'created_at'],
    ],
    'Recipe' => [
        'search' => ['title', 'title_en', 'slug', 'short_description', 'short_description_en'],
        'filters' => ['status' => ['options' => ['1' => 'Published', '0' => 'Hidden']]],
        'sort' => ['id', 'title', 'published_at', 'status', 'created_at'],
    ],
    'Tip' => [
        'search' => ['title', 'title_en', 'slug', 'short_description', 'short_description_en'],
        'filters' => ['status' => ['options' => ['1' => 'Published', '0' => 'Hidden']]],
        'sort' => ['id', 'title', 'published_at', 'status', 'created_at'],
    ],
    'Restaurant' => [
        'search' => ['name', 'name_en', 'address', 'address_en', 'phone'],
        'filters' => ['status' => ['options' => ['1' => 'Active', '0' => 'Inactive']]],
        'sort' => ['id', 'name', 'status', 'created_at'],
    ],
    'Lead' => [
        'search' => ['name', 'email', 'phone', 'message'],
        'filters' => ['status' => ['options' => ['new' => 'New', 'read' => 'Read', 'contacted' => 'Contacted']]],
        'sort' => ['id', 'name', 'status', 'created_at'],
    ],
    'KidsItem' => [
        'search' => ['name', 'name_en', 'slug'],
        'filters' => [
            'type' => ['options' => ['food' => 'Food', 'utensil' => 'Utensil', 'supply' => 'Supply']],
            'food_category' => ['options' => ['meat' => 'Meat', 'side_dish' => 'Side dish', 'vegetable' => 'Vegetable', 'soup' => 'Soup', 'rice_noodles' => 'Rice and noodles', 'dessert' => 'Dessert']],
            'status' => ['options' => ['1' => 'Active', '0' => 'Inactive']],
        ],
        'sort' => ['id', 'name', 'type', 'food_category', 'price', 'sort_order', 'status', 'created_at'],
    ],
];
