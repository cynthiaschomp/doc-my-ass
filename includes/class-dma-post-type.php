<?php
/**
 * Doc My A$$ - Post Type & Taxonomy Registration
 *
 * Registers the 'dma_doc' custom post type and custom taxonomies
 * ('dma_product', 'dma_topic') for documentation management.
 *
 * @package Doc_My_Ass
 */

if (!defined('ABSPATH')) {
    exit;
}

class DMA_Post_Type {

    /**
     * Initialize post type and taxonomies.
     */
    public static function init() {
        add_action('init', [__CLASS__, 'register_post_type'], 5);
        add_action('init', [__CLASS__, 'register_taxonomies'], 5);
        add_action('init', [__CLASS__, 'register_default_terms'], 10);
    }

    /**
     * Register 'dma_doc' custom post type.
     */
    public static function register_post_type() {
        $labels = [
            'name'                  => _x('Doc My A$$', 'Post Type General Name', 'doc-my-ass'),
            'singular_name'         => _x('Doc My A$$ Article', 'Post Type Singular Name', 'doc-my-ass'),
            'menu_name'             => __('Doc My A$$', 'doc-my-ass'),
            'name_admin_bar'        => __('Doc My A$$', 'doc-my-ass'),
            'archives'              => __('Doc Archives', 'doc-my-ass'),
            'attributes'            => __('Doc Attributes', 'doc-my-ass'),
            'parent_item_colon'     => __('Parent Doc:', 'doc-my-ass'),
            'all_items'             => __('All Docs', 'doc-my-ass'),
            'add_new_item'          => __('Add New Doc', 'doc-my-ass'),
            'add_new'               => __('Add New', 'doc-my-ass'),
            'new_item'              => __('New Doc', 'doc-my-ass'),
            'edit_item'             => __('Edit Doc', 'doc-my-ass'),
            'update_item'           => __('Update Doc', 'doc-my-ass'),
            'view_item'             => __('View Doc', 'doc-my-ass'),
            'view_items'            => __('View Docs', 'doc-my-ass'),
            'search_items'          => __('Search Docs', 'doc-my-ass'),
        ];

        $args = [
            'label'                 => __('Doc My A$$', 'doc-my-ass'),
            'description'           => __('Technical documentation for Doc My A$$ and the Whole A$$ Network', 'doc-my-ass'),
            'labels'                => $labels,
            'supports'              => ['title', 'editor', 'author', 'revisions', 'page-attributes', 'custom-fields'],
            'taxonomies'            => ['dma_product', 'dma_topic'],
            'hierarchical'          => true,
            'public'                => true,
            'show_ui'               => true,
            'show_in_menu'          => true,
            'menu_position'         => 26,
            'menu_icon'             => 'dashicons-book-alt',
            'show_in_admin_bar'     => true,
            'show_in_nav_menus'     => true,
            'can_export'            => true,
            'has_archive'           => false,
            'exclude_from_search'   => false,
            'publicly_queryable'    => true,
            'capability_type'       => 'post',
            'show_in_rest'          => true,
            'rewrite'               => false, // Handled by custom rewrite rules in DMA_Router
        ];

        register_post_type('dma_doc', $args);
    }

    /**
     * Register product and topic taxonomies.
     */
    public static function register_taxonomies() {
        // Product Taxonomy (e.g. guard-my-ass, back-my-ass-up)
        register_taxonomy('dma_product', ['dma_doc'], [
            'labels' => [
                'name'              => _x('Products', 'taxonomy general name', 'doc-my-ass'),
                'singular_name'     => _x('Product', 'taxonomy singular name', 'doc-my-ass'),
                'search_items'      => __('Search Products', 'doc-my-ass'),
                'all_items'         => __('All Products', 'doc-my-ass'),
                'edit_item'         => __('Edit Product', 'doc-my-ass'),
                'update_item'       => __('Update Product', 'doc-my-ass'),
                'add_new_item'      => __('Add New Product', 'doc-my-ass'),
                'new_item_name'     => __('New Product Name', 'doc-my-ass'),
                'menu_name'         => __('Products', 'doc-my-ass'),
            ],
            'hierarchical'          => true,
            'show_ui'               => true,
            'show_admin_column'     => true,
            'query_var'             => true,
            'show_in_rest'          => true,
            'rewrite'               => false,
        ]);

        // Topic/Category Taxonomy
        register_taxonomy('dma_topic', ['dma_doc'], [
            'labels' => [
                'name'              => _x('Topics', 'taxonomy general name', 'doc-my-ass'),
                'singular_name'     => _x('Topic', 'taxonomy singular name', 'doc-my-ass'),
                'search_items'      => __('Search Topics', 'doc-my-ass'),
                'all_items'         => __('All Topics', 'doc-my-ass'),
                'edit_item'         => __('Edit Topic', 'doc-my-ass'),
                'update_item'       => __('Update Topic', 'doc-my-ass'),
                'add_new_item'      => __('Add New Topic', 'doc-my-ass'),
                'new_item_name'     => __('New Topic Name', 'doc-my-ass'),
                'menu_name'         => __('Topics', 'doc-my-ass'),
            ],
            'hierarchical'          => true,
            'show_ui'               => true,
            'show_admin_column'     => true,
            'query_var'             => true,
            'show_in_rest'          => true,
            'rewrite'               => false,
        ]);
    }

    /**
     * Register initial default terms for products.
     */
    public static function register_default_terms() {
        $products = [
            'guard-my-ass'    => [
                'name'        => 'Guard My Ass',
                'description' => 'Real-Time Web Application Firewall, Shannon Entropy & Edge Traefik Shield',
                'icon'        => 'shield',
                'badge'       => 'WAF / Edge'
            ],
            'back-my-ass-up'  => [
                'name'        => 'Back My A$$ Up',
                'description' => 'Air-Gapped Cryptographic Backups, Cloud Vault (R2/S3) & Standalone Rescue',
                'icon'        => 'archive',
                'badge'       => 'Vault / Backup'
            ],
            'secure-my-ass'   => [
                'name'        => 'Secure My Ass',
                'description' => 'Zero-Day AST Scanner, Web Shell Quarantine Vault & A$$ AI SOC Analyst',
                'icon'        => 'lock',
                'badge'       => 'Core Endpoint'
            ],
            'speed-my-ass-up' => [
                'name'        => 'Speed My A$$ Up',
                'description' => 'Sub-0.5ms Pre-Boot Static HTML Caching, Speculation Rules & BYOK AI CSS Scanner',
                'icon'        => 'zap',
                'badge'       => 'Performance'
            ],
            'compress-my-ass' => [
                'name'        => 'Compress My A$$',
                'description' => 'Zero-SaaS Local Media Compression, WebP/AVIF Hyper-Engine & Non-Destructive Vault',
                'icon'        => 'image',
                'badge'       => 'Media / WebP'
            ],
            'whole-ass-network'    => [
                'name'        => 'Whole A$$ API & Network',
                'description' => 'Swarm Threat Intelligence Telemetry Ingestion & Licensing Architecture',
                'icon'        => 'code',
                'badge'       => 'API / Swarm'
            ],
        ];

        foreach ($products as $slug => $meta) {
            if (!term_exists($slug, 'dma_product')) {
                $term = wp_insert_term($meta['name'], 'dma_product', [
                    'slug'        => $slug,
                    'description' => $meta['description'],
                ]);
                if (!is_wp_error($term)) {
                    update_term_meta($term['term_id'], 'dma_icon', $meta['icon']);
                    update_term_meta($term['term_id'], 'dma_badge', $meta['badge']);
                }
            }
        }
    }
}
