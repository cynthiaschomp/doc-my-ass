<?php
/**
 * Plugin Name: Doc My A$$
 * Plugin URI: https://securemyass.com/docs/
 * Description: Developer-first documentation system for the Whole A102434 Network. Features sub-millisecond reader, Cmd+K instant fuzzy search, Docs-as-Code Markdown synchronization, multi-tab code snippets, and dynamic scroll-spy navigation.
 * Version: 1.0.0
 * Author: Cynthia Schomp
 * Author URI: https://cynthiaschomp.com
 * License: Proprietary
 * Text Domain: doc-my-ass
 * Domain Path: /languages
 *
 * @package Doc_My_Ass
 */

if (!defined('ABSPATH')) {
    exit;
}

define('DMA_VERSION', '1.0.0');
define('DMA_FILE', __FILE__);
define('DMA_PATH', plugin_dir_path(__FILE__));
define('DMA_URL', plugin_dir_url(__FILE__));

// Require Core Subsystems
require_once DMA_PATH . 'includes/class-dma-post-type.php';
require_once DMA_PATH . 'includes/class-dma-markdown-engine.php';
require_once DMA_PATH . 'includes/class-dma-search-indexer.php';
require_once DMA_PATH . 'includes/class-dma-router.php';

// Bootstrap Lifecycle
function dma_init() {
    DMA_Post_Type::init();
    DMA_Search_Indexer::init();
    DMA_Router::init();
}
add_action('plugins_loaded', 'dma_init', 10);

// Register WP-CLI Commands
if (defined('WP_CLI') && WP_CLI) {
    require_once DMA_PATH . 'includes/class-dma-cli-command.php';
    WP_CLI::add_command('dma', 'DMA_CLI_Command');
    WP_CLI::add_command('dma list', ['DMA_CLI_Command', 'list_docs']);
}

// Activation hook to register rules and flush
register_activation_hook(__FILE__, function() {
    DMA_Post_Type::register_post_type();
    DMA_Post_Type::register_taxonomies();
    DMA_Post_Type::register_default_terms();
    DMA_Router::add_rewrite_rules();
    flush_rewrite_rules();
});

// Deactivation hook
register_deactivation_hook(__FILE__, function() {
    flush_rewrite_rules();
});
