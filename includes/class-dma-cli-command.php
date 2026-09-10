<?php
/**
 * Doc My A$$ - WP-CLI Management Commands
 *
 * Provides 'wp dma sync', 'wp dma build-index', and 'wp dma list' commands.
 *
 * @package Doc_My_Ass
 */

if (!defined('ABSPATH')) {
    exit;
}

class DMA_CLI_Command {

    /**
     * Synchronize markdown documentation from disk into WordPress.
     *
     * ## OPTIONS
     *
     * [--dir=<dir>]
     * : Directory containing markdown documentation files. Defaults to plugin docs directory.
     *
     * ## EXAMPLES
     *
     *     wp dma sync
     *     wp dma sync --dir=/var/www/html/wp-content/plugins/doc-my-ass/docs
     *
     * @when after_wp_load
     */
    public function sync($args, $assoc_args) {
        $docs_dir = isset($assoc_args['dir']) ? $assoc_args['dir'] : DMA_PATH . 'docs';

        if (!is_dir($docs_dir)) {
            WP_CLI::error("Documentation directory not found: {$docs_dir}");
            return;
        }

        WP_CLI::log(WP_CLI::colorize("%C=== Syncing Doc My A$$ from Disk ===%n"));
        WP_CLI::log("Source: {$docs_dir}");

        // Find all .md files recursively
        $files = self::rglob($docs_dir . '/*.md');

        if (empty($files)) {
            WP_CLI::warning("No .md files found in {$docs_dir}");
            return;
        }

        $synced_count = 0;
        $updated_count = 0;

        foreach ($files as $file_path) {
            $raw_content = file_get_contents($file_path);
            if (empty($raw_content)) {
                continue;
            }

            $parsed = DMA_Markdown_Engine::parse($raw_content);
            $fm = $parsed['frontmatter'];

            // Infer product from parent directory if not set in frontmatter
            $rel_path = str_replace($docs_dir . '/', '', $file_path);
            $path_parts = explode('/', $rel_path);
            $inferred_product = (count($path_parts) > 1) ? $path_parts[0] : 'guard-my-ass';

            $product_slug = !empty($fm['product']) ? sanitize_title($fm['product']) : $inferred_product;
            $topic_name   = !empty($fm['topic'])   ? sanitize_text_field($fm['topic']) : 'General';
            $title        = !empty($fm['title'])   ? sanitize_text_field($fm['title']) : basename($file_path, '.md');
            $slug         = !empty($fm['slug'])    ? sanitize_title($fm['slug']) : basename($file_path, '.md');
            $order        = isset($fm['order'])    ? intval($fm['order']) : 10;
            $badge        = !empty($fm['badge'])   ? sanitize_text_field($fm['badge']) : '';
            $version      = !empty($fm['version']) ? sanitize_text_field($fm['version']) : 'v1.0';

            // Ensure taxonomy terms exist
            if (!term_exists($product_slug, 'dma_product')) {
                wp_insert_term(ucwords(str_replace('-', ' ', $product_slug)), 'dma_product', ['slug' => $product_slug]);
            }
            if (!term_exists($topic_name, 'dma_topic')) {
                wp_insert_term($topic_name, 'dma_topic');
            }

            // Check if post already exists by slug and product
            $existing_posts = get_posts([
                'post_type'   => 'dma_doc',
                'name'        => $slug,
                'post_status' => 'any',
                'numberposts' => 1,
                'tax_query'   => [
                    [
                        'taxonomy' => 'dma_product',
                        'field'    => 'slug',
                        'terms'    => $product_slug,
                    ],
                ],
            ]);

            $post_data = [
                'post_title'   => $title,
                'post_name'    => $slug,
                'post_content' => $parsed['html'],
                'post_status'  => 'publish',
                'post_type'    => 'dma_doc',
                'menu_order'   => $order,
            ];

            if (!empty($existing_posts)) {
                $post_id = $existing_posts[0]->ID;
                $post_data['ID'] = $post_id;
                wp_update_post($post_data);
                $updated_count++;
                $action_label = WP_CLI::colorize("%y[UPDATED]%n");
            } else {
                $post_id = wp_insert_post($post_data);
                $synced_count++;
                $action_label = WP_CLI::colorize("%g[CREATED]%n");
            }

            if ($post_id && !is_wp_error($post_id)) {
                // Assign taxonomy terms
                wp_set_object_terms($post_id, [$product_slug], 'dma_product');
                wp_set_object_terms($post_id, [$topic_name], 'dma_topic');

                // Update metadata
                update_post_meta($post_id, '_dma_badge', $badge);
                update_post_meta($post_id, '_dma_version', $version);
                update_post_meta($post_id, '_dma_toc', $parsed['toc']);
                update_post_meta($post_id, '_dma_file_path', $rel_path);

                WP_CLI::log("  {$action_label} [{$product_slug}] {$title} (Slug: /{$product_slug}/{$slug}/)");
            }
        }

        // Rebuild client search index
        WP_CLI::log(WP_CLI::colorize("\n%CRebuilding client-side Cmd+K search index...%n"));
        DMA_Search_Indexer::get_or_build_index(true);

        WP_CLI::success("Sync completed! Created: {$synced_count}, Updated: {$updated_count}");
    }

    /**
     * Rebuild the client-side search index.
     *
     * ## EXAMPLES
     *
     *     wp dma build-index
     *
     * @when after_wp_load
     */
    public function build_index($args, $assoc_args) {
        WP_CLI::log("Compiling search index for all published Sentinel docs...");
        $index = DMA_Search_Indexer::get_or_build_index(true);
        $count = count($index);
        WP_CLI::success("Search index rebuilt with {$count} documents.");
    }

    /**
     * List all published documentation articles.
     *
     * ## EXAMPLES
     *
     *     wp dma list
     *
     * @when after_wp_load
     */
    public function list_docs($args, $assoc_args) {
        $docs = get_posts([
            'post_type'      => 'dma_doc',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'orderby'        => 'menu_order',
            'order'          => 'ASC',
        ]);

        if (empty($docs)) {
            WP_CLI::warning("No documents found. Run 'wp dma sync' to load docs.");
            return;
        }

        $items = [];
        foreach ($docs as $doc) {
            $products = wp_get_post_terms($doc->ID, 'dma_product', ['fields' => 'names']);
            $topics   = wp_get_post_terms($doc->ID, 'dma_topic', ['fields' => 'names']);
            $badge    = get_post_meta($doc->ID, '_dma_badge', true);

            $items[] = [
                'ID'      => $doc->ID,
                'Product' => !empty($products) ? implode(', ', $products) : '-',
                'Topic'   => !empty($topics)   ? implode(', ', $topics)   : '-',
                'Title'   => $doc->post_title,
                'Slug'    => $doc->post_name,
                'Order'   => $doc->menu_order,
                'Badge'   => $badge ?: '-',
            ];
        }

        WP_CLI\Utils\format_items('table', $items, ['ID', 'Product', 'Topic', 'Title', 'Slug', 'Order', 'Badge']);
    }

    /**
     * Helper to recursively find files matching a glob pattern.
     */
    private static function rglob($pattern, $flags = 0) {
        $files = glob($pattern, $flags);
        foreach (glob(dirname($pattern) . '/*', GLOB_ONLYDIR | GLOB_NOSORT) as $dir) {
            $files = array_merge($files, self::rglob($dir . '/' . basename($pattern), $flags));
        }
        return $files;
    }
}
