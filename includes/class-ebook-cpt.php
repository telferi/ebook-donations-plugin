<?php
if (!defined('ABSPATH')) {
    exit;
}

class EBook_CPT {

    // eBook CPT regisztráció
    public static function register_ebook_cpt() {
        $labels = [
            'name'               => __('eBooks', 'ebook-donation'),
            'singular_name'      => __('eBook', 'ebook-donation'),
            'add_new'            => __('Új eBook feltöltése', 'ebook-donation'),
            'all_items'          => __('Összes eBook', 'ebook-donation'),
            'add_new_item'       => __('Új eBook hozzáadása', 'ebook-donation'),
            'edit_item'          => __('eBook szerkesztése', 'ebook-donation'),
            'new_item'           => __('Új eBook', 'ebook-donation'),
            'view_item'          => __('eBook megtekintése', 'ebook-donation'),
            'search_items'       => __('Keresés eBookok között', 'ebook-donation'),
            'not_found'          => __('Nem található eBook', 'ebook-donation'),
            'not_found_in_trash' => __('Nincs eBook a kukában', 'ebook-donation'),
            'menu_name'          => __('eBooks', 'ebook-donation'),
        ];

        $args = [
            'labels'             => $labels,
            'public'             => true,
            'has_archive'        => true,
            'menu_icon'          => 'dashicons-book',
            'supports'           => ['title', 'editor', 'thumbnail', 'custom-fields'],
            'capability_type'    => 'post',
            'rewrite'            => ['slug' => 'ebooks'],
            'show_in_rest'       => true,
        ];

        register_post_type('ebook', $args);
    }
}
