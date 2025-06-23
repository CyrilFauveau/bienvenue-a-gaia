<?php
// Setup modules
// add_action('init', 'cpt_works');

// Module function
function cpt_works()
{
    $labels = [
        'name' => 'Réalisations',
        'singular_name' => 'Réalisation',
        'add_new' => 'Ajouter une réalisation',
        'add_new_item' => 'Ajouter une réalisation',
        'edit_item' => 'Editer une réalisation',
        'new_item' => 'Nouvelle réalisation',
        'view_item' => 'Afficher la réalisation',
        'search_items' => 'Rechercher une réalisation',
        'not_found' =>  'Aucune réalisation',
        'not_found_in_trash' => 'Aucun réalisation trouvé dans la corbeille',
        'parent_item_colon' => ''
    ];

    $args = [
        'labels' => $labels,
        'public' => true,
        'publicly_queryable' => true, // Hide this post type from being accessed directly
        'show_ui' => true,
        'show_in_menu' => true,
        'query_var' => true,
        'rewrite' => true, // Permalinks
        'capability_type' => 'post',
        'hierarchical' => false,
        'supports' => [
            'title',
            'editor',
            'thumbnail'
        ],
        'taxonomies' => [], // Taxonomies, add 'category' for WordPress categories
        'has_archive' => false,
        'menu_icon' => 'dashicons-plus',
    ];

    // Register the post type using the arguments we have setup above.
    register_post_type('works', $args);

    $args = [
        'label' => 'Catégories',
        'rewrite' => false,
        'labels' => [
            'name' => 'Catégories',
            'singular_name' => 'Catégorie',
            'all_items' => 'Toutes les catégories',
            'edit_item' => 'Éditer la catégorie',
            'view_item' => 'Voir la catégorie',
            'update_item' => 'Mettre à jour la catégorie',
            'add_new_item' => 'Ajouter une catégorie',
            'new_item_name' => 'Nouvelle catégorie',
            'search_items' => 'Rechercher une catégorie',
            'popular_items' => 'Catégories les plus utilisées',
        ],
        'hierarchical' => true
    ];

    register_taxonomy('categories_for_works', 'works', $args);
}
