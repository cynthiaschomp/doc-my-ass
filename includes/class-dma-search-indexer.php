<?php
/**
 * Doc My A$$ - Client-Side Search Indexer
 *
 * Generates and serves a lightweight, high-performance JSON search index
 * for instant Cmd+K fuzzy searching directly in the browser.
 *
 * @package Doc_My_Ass
 */

if (!defined('ABSPATH')) {
    exit;
}

class DMA_Search_Indexer {

    const TRANSIENT_KEY = 'dma_search_index_json';
    const INDEX_FILENAME = 'dma-search-index.json';

    public static function init() {
        add_action('rest_api_init', [__CLASS__, 'register_rest_routes']);
        add_action('save_post_dma_doc', [__CLASS__, 'purge_index']);
    }

    /**
     * Register REST API endpoint: GET /wp-json/dma/v1/search-index
     */
    public static function register_rest_routes() {
        register_rest_route('dma/v1', '/search-index', [
            'methods'             => 'GET',
            'callback'            => [__CLASS__, 'get_search_index_endpoint'],
            'permission_callback' => '__return_true',
        ]);
    }

    /**
     * Endpoint handler.
     */
    public static function get_search_index_endpoint($request) {
        $index = self::get_or_build_index();
        return rest_ensure_response($index);
    }

    /**
     * Get cached search index or rebuild it.
     */
    public static function get_or_build_index($force_rebuild = false) {
        if (!$force_rebuild) {
            $cached = get_transient(self::TRANSIENT_KEY);
            if (!empty($cached) && is_array($cached)) {
                return $cached;
            }
        }

        $docs = get_posts([
            'post_type'      => 'dma_doc',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'orderby'        => 'menu_order',
            'order'          => 'ASC',
        ]);

        $index = [];

        foreach ($docs as $doc) {
            $product_terms = wp_get_post_terms($doc->ID, 'dma_product');
            $topic_terms   = wp_get_post_terms($doc->ID, 'dma_topic');

            $product_slug = !empty($product_terms) ? $product_terms[0]->slug : 'general';
            $product_name = !empty($product_terms) ? $product_terms[0]->name : 'General';
            $topic_name   = !empty($topic_terms)   ? $topic_terms[0]->name   : 'Guides';

            $badge = get_post_meta($doc->ID, '_dma_badge', true);
            $toc   = get_post_meta($doc->ID, '_dma_toc', true);
            if (!is_array($toc)) {
                $toc = [];
            }

            // Build canonical doc URL
            $url = home_url("/docs/{$product_slug}/{$doc->post_name}/");

            // Strip tags from content for searchable text
            $searchable = wp_strip_all_tags($doc->post_content);
            $searchable = preg_replace('/\s+/', ' ', $searchable);
            $excerpt    = mb_substr($searchable, 0, 180) . '...';

            $index[] = [
                'id'       => $doc->ID,
                'title'    => $doc->post_title,
                'slug'     => $doc->post_name,
                'url'      => $url,
                'product'  => [
                    'slug' => $product_slug,
                    'name' => $product_name,
                ],
                'topic'    => $topic_name,
                'badge'    => $badge ?: '',
                'excerpt'  => $excerpt,
                'headings' => array_slice($toc, 0, 8), // Include top headings for deep links
            ];
        }

        // Cache for 24 hours
        set_transient(self::TRANSIENT_KEY, $index, DAY_IN_SECONDS);

        // Also write static JSON to uploads directory for ultra-fast CDN/direct reading
        $upload_dir = wp_upload_dir();
        $target_file = trailingslashit($upload_dir['basedir']) . self::INDEX_FILENAME;
        @file_put_contents($target_file, json_encode($index, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));

        return $index;
    }

    /**
     * Purge search index cache.
     */
    public static function purge_index() {
        delete_transient(self::TRANSIENT_KEY);
    }
}
