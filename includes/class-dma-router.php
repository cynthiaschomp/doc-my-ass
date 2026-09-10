<?php
/**
 * Doc My A$$ - Documentation Router & Template Interceptor
 *
 * Provides clean /docs/{product}/{slug}/ routing and injects our
 * ultra-fast, Divi-independent documentation template.
 *
 * @package Doc_My_Ass
 */

if (!defined('ABSPATH')) {
    exit;
}

class DMA_Router {

    public static function init() {
        add_action('init', [__CLASS__, 'add_rewrite_rules'], 10);
        add_filter('query_vars', [__CLASS__, 'register_query_vars']);
        add_filter('template_include', [__CLASS__, 'intercept_template'], 9999);
    }

    /**
     * Add clean rewrite rules for documentation URLs.
     */
    public static function add_rewrite_rules() {
        // /docs/ -> Root docs hub
        add_rewrite_rule(
            '^docs/?$',
            'index.php?dma_docs_root=1',
            'top'
        );

        // /docs/{product}/ -> Product landing
        add_rewrite_rule(
            '^docs/([a-zA-Z0-9_\-]+)/?$',
            'index.php?dma_product_slug=$matches[1]',
            'top'
        );

        // /docs/{product}/{slug}/ -> Specific doc page
        add_rewrite_rule(
            '^docs/([a-zA-Z0-9_\-]+)/([a-zA-Z0-9_\-]+)/?$',
            'index.php?dma_product_slug=$matches[1]&dma_doc_slug=$matches[2]',
            'top'
        );
    }

    /**
     * Register query variables with WordPress.
     */
    public static function register_query_vars($vars) {
        $vars[] = 'dma_docs_root';
        $vars[] = 'dma_product_slug';
        $vars[] = 'dma_doc_slug';
        return $vars;
    }

    /**
     * Intercept template loader and render template-docs.php directly.
     */
    public static function intercept_template($template) {
        $is_root    = get_query_var('dma_docs_root');
        $prod_slug  = get_query_var('dma_product_slug');
        $doc_slug   = get_query_var('dma_doc_slug');

        if (!empty($is_root) || !empty($prod_slug) || !empty($doc_slug)) {
            // Prevent 404 status
            global $wp_query;
            $wp_query->is_404 = false;
            status_header(200);

            $custom_template = DMA_PATH . 'templates/template-docs.php';
            if (file_exists($custom_template)) {
                return $custom_template;
            }
        }

        return $template;
    }
}
