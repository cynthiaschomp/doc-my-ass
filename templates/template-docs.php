<?php
/**
 * Doc My A$$ - High-Velocity Documentation Template
 *
 * Standalone, ultra-fast 3-column developer documentation reader.
 * Bypasses page builders for maximum performance and typography precision.
 *
 * @package Doc_My_Ass
 */

if (!defined('ABSPATH')) {
    exit;
}

// 1. Resolve Active Context
$req_product_slug = get_query_var('dma_product_slug');
$req_doc_slug     = get_query_var('dma_doc_slug');
$is_root          = get_query_var('dma_docs_root');

// Fetch all available products
$products = get_terms([
    'taxonomy'   => 'dma_product',
    'hide_empty' => false,
]);

// If no product requested, default to guard-my-ass
if (empty($req_product_slug)) {
    $req_product_slug = !empty($products) ? $products[0]->slug : 'guard-my-ass';
}

$active_product_term = get_term_by('slug', $req_product_slug, 'dma_product');
$active_product_name = $active_product_term ? $active_product_term->name : 'Sentinel Documentation';
$active_product_desc = $active_product_term ? $active_product_term->description : '';

// 2. Fetch all docs for current product, ordered by menu_order
$product_docs = get_posts([
    'post_type'      => 'dma_doc',
    'post_status'    => 'publish',
    'posts_per_page' => -1,
    'orderby'        => 'menu_order',
    'order'          => 'ASC',
    'tax_query'      => [
        [
            'taxonomy' => 'dma_product',
            'field'    => 'slug',
            'terms'    => $req_product_slug,
        ],
    ],
]);

// 3. Resolve Active Document
$current_doc = null;
if (!empty($req_doc_slug)) {
    foreach ($product_docs as $doc) {
        if ($doc->post_name === $req_doc_slug) {
            $current_doc = $doc;
            break;
        }
    }
}

// Default to first doc if requested slug was empty or not found
if (!$current_doc && !empty($product_docs)) {
    $current_doc = $product_docs[0];
}

// Resolve Prev / Next Articles for pagination
$prev_doc = null;
$next_doc = null;
if ($current_doc && !empty($product_docs)) {
    for ($i = 0; $i < count($product_docs); $i++) {
        if ($product_docs[$i]->ID === $current_doc->ID) {
            $prev_doc = ($i > 0) ? $product_docs[$i - 1] : null;
            $next_doc = ($i < count($product_docs) - 1) ? $product_docs[$i + 1] : null;
            break;
        }
    }
}

// Metadata for active doc
$doc_title    = $current_doc ? $current_doc->post_title : 'Documentation Overview';
$doc_content  = $current_doc ? $current_doc->post_content : '<p>No documentation found for this section yet.</p>';
$doc_badge    = $current_doc ? get_post_meta($current_doc->ID, '_dma_badge', true) : '';
$doc_version  = $current_doc ? get_post_meta($current_doc->ID, '_dma_version', true) : 'v1.0';
$doc_toc      = $current_doc ? get_post_meta($current_doc->ID, '_dma_toc', true) : [];
if (!is_array($doc_toc)) {
    $doc_toc = [];
}

// Group product docs by topic
$docs_by_topic = [];
foreach ($product_docs as $d) {
    $topic_terms = wp_get_post_terms($d->ID, 'dma_topic');
    $topic_name  = !empty($topic_terms) ? $topic_terms[0]->name : 'Guides';
    if (!isset($docs_by_topic[$topic_name])) {
        $docs_by_topic[$topic_name] = [];
    }
    $docs_by_topic[$topic_name][] = $d;
}

// Product list metadata
$product_meta_list = [
    'guard-my-ass'    => ['name' => 'Guard My Ass',    'badge' => 'WAF / Edge',     'icon' => '🛡️'],
    'back-my-ass-up'  => ['name' => 'Back My A$$ Up',  'badge' => 'Vault & Backup', 'icon' => '💾'],
    'secure-my-ass'   => ['name' => 'Secure My Ass',   'badge' => 'Core Endpoint',  'icon' => '🔐'],
    'speed-my-ass-up' => ['name' => 'Speed My A$$ Up', 'badge' => 'Cache & CWV',   'icon' => '⚡'],
    'compress-my-ass' => ['name' => 'Compress My A$$', 'badge' => 'Image Engine',  'icon' => '🗜️'],
    'sentinel-api'    => ['name' => 'Sentinel API',    'badge' => 'Swarm & Cloud',  'icon' => '🌐'],
];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo esc_html($doc_title); ?> — Secure My Ass Developer Docs</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?php echo esc_url(DMA_URL . 'assets/css/docs.css?v=' . DMA_VERSION); ?>">
</head>
<body class="dma-body">

    <!-- Global Top Navigation Header -->
    <header class="dma-top-header">
        <div class="dma-top-container">
            <div class="dma-brand-group">
                <button type="button" class="dma-mobile-toggle" id="dmaMobileNavToggle" aria-label="Toggle Navigation">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg>
                </button>
                <a href="https://securemyass.com" class="dma-logo-link">
                    <span class="dma-logo-shield">🛡️</span>
                    <span class="dma-logo-text">SecureMyAss<span class="dma-logo-accent">.com</span></span>
                </a>
                <span class="dma-divider">/</span>
                <span class="dma-badge-docs">DEVELOPER DOCS</span>
            </div>

            <!-- Global Search Trigger -->
            <div class="dma-header-search">
                <button type="button" class="dma-search-trigger-btn" id="dmaOpenSearchBtn">
                    <svg class="dma-search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                    <span class="dma-search-placeholder">Quick search documentation...</span>
                    <kbd class="dma-kbd">⌘K</kbd>
                </button>
            </div>

            <div class="dma-header-actions">
                <a href="https://securemyass.com" class="dma-nav-back-link">Return to Site →</a>
            </div>
        </div>
    </header>

    <!-- Main 3-Column Layout Container -->
    <div class="dma-docs-layout">

        <!-- Column 1: Left Navigation Sidebar -->
        <aside class="dma-sidebar" id="dmaSidebar">
            <!-- Product Selector Dropdown -->
            <div class="dma-product-switcher">
                <label class="dma-switcher-label">SUITE PRODUCT</label>
                <div class="dma-custom-select-wrapper">
                    <select id="dmaProductSelect" class="dma-product-select" onchange="window.location.href=this.value;">
                        <?php foreach ($product_meta_list as $pslug => $pmeta): ?>
                            <option value="<?php echo esc_url(home_url("/docs/{$pslug}/")); ?>" <?php selected($pslug, $req_product_slug); ?>>
                                <?php echo esc_html($pmeta['icon'] . ' ' . $pmeta['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <!-- Product Summary Card -->
            <div class="dma-product-card">
                <div class="dma-prod-badge"><?php echo esc_html($product_meta_list[$req_product_slug]['badge'] ?? 'Plugin'); ?></div>
                <h3 class="dma-prod-title"><?php echo esc_html($active_product_name); ?></h3>
                <p class="dma-prod-desc"><?php echo esc_html($active_product_desc); ?></p>
            </div>

            <!-- Documentation Tree Navigation -->
            <nav class="dma-nav-tree">
                <?php foreach ($docs_by_topic as $topic_name => $topic_docs): ?>
                    <div class="dma-nav-group">
                        <div class="dma-nav-group-title"><?php echo esc_html(strtoupper($topic_name)); ?></div>
                        <ul class="dma-nav-list">
                            <?php foreach ($topic_docs as $item): 
                                $is_active = ($current_doc && $current_doc->ID === $item->ID);
                                $item_badge = get_post_meta($item->ID, '_dma_badge', true);
                            ?>
                                <li class="dma-nav-item<?php echo $is_active ? ' active' : ''; ?>">
                                    <a href="<?php echo esc_url(home_url("/docs/{$req_product_slug}/{$item->post_name}/")); ?>" class="dma-nav-link">
                                        <span class="dma-nav-text"><?php echo esc_html($item->post_title); ?></span>
                                        <?php if (!empty($item_badge)): ?>
                                            <span class="dma-nav-badge"><?php echo esc_html($item_badge); ?></span>
                                        <?php endif; ?>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endforeach; ?>
            </nav>
        </aside>

        <!-- Column 2: Center Content Reader -->
        <main class="dma-main-content">
            <div class="dma-content-wrapper">
                
                <!-- Breadcrumb Bar -->
                <nav class="dma-breadcrumbs" aria-label="Breadcrumbs">
                    <a href="<?php echo esc_url(home_url('/docs/')); ?>">Docs</a>
                    <span class="dma-crumb-sep">/</span>
                    <a href="<?php echo esc_url(home_url("/docs/{$req_product_slug}/")); ?>"><?php echo esc_html($active_product_name); ?></a>
                    <span class="dma-crumb-sep">/</span>
                    <span class="dma-crumb-current"><?php echo esc_html($doc_title); ?></span>
                </nav>

                <!-- Article Header -->
                <header class="dma-article-header">
                    <div class="dma-meta-badges">
                        <span class="dma-version-tag"><?php echo esc_html($doc_version); ?></span>
                        <?php if (!empty($doc_badge)): ?>
                            <span class="dma-feature-tag"><?php echo esc_html($doc_badge); ?></span>
                        <?php endif; ?>
                    </div>
                    <h1 class="dma-article-title"><?php echo esc_html($doc_title); ?></h1>
                </header>

                <!-- Article Body Content -->
                <article class="dma-prose">
                    <?php echo $doc_content; // Raw safe HTML compiled by DMA_Markdown_Engine ?>
                </article>

                <!-- Prev / Next Pagination -->
                <footer class="dma-pagination-footer">
                    <?php if ($prev_doc): ?>
                        <a href="<?php echo esc_url(home_url("/docs/{$req_product_slug}/{$prev_doc->post_name}/")); ?>" class="dma-page-btn dma-prev-btn">
                            <span class="dma-btn-sub">← Previous Article</span>
                            <span class="dma-btn-main"><?php echo esc_html($prev_doc->post_title); ?></span>
                        </a>
                    <?php else: ?>
                        <div class="dma-page-spacer"></div>
                    <?php endif; ?>

                    <?php if ($next_doc): ?>
                        <a href="<?php echo esc_url(home_url("/docs/{$req_product_slug}/{$next_doc->post_name}/")); ?>" class="dma-page-btn dma-next-btn">
                            <span class="dma-btn-sub">Next Article →</span>
                            <span class="dma-btn-main"><?php echo esc_html($next_doc->post_title); ?></span>
                        </a>
                    <?php endif; ?>
                </footer>

            </div>
        </main>

        <!-- Column 3: Right "On This Page" TOC Sidebar -->
        <aside class="dma-toc-sidebar">
            <div class="dma-toc-container">
                <div class="dma-toc-header">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="8" y1="6" x2="21" y2="6"></line><line x1="8" y1="12" x2="21" y2="12"></line><line x1="8" y1="18" x2="21" y2="18"></line><line x1="3" y1="6" x2="3.01" y2="6"></line><line x1="3" y1="12" x2="3.01" y2="12"></line><line x1="3" y1="18" x2="3.01" y2="18"></line></svg>
                    <span>ON THIS PAGE</span>
                </div>
                <?php if (!empty($doc_toc)): ?>
                    <ul class="dma-toc-list" id="dmaTocList">
                        <?php foreach ($doc_toc as $heading): 
                            $indent_class = ($heading['level'] === 3) ? ' dma-toc-h3' : ' dma-toc-h2';
                        ?>
                            <li class="dma-toc-item<?php echo $indent_class; ?>">
                                <a href="#<?php echo esc_attr($heading['id']); ?>" class="dma-toc-link">
                                    <?php echo esc_html($heading['title']); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="dma-toc-empty">Overview</p>
                <?php endif; ?>

                <!-- Back to Top Button -->
                <div class="dma-toc-footer">
                    <button type="button" class="dma-back-to-top" onclick="window.scrollTo({top: 0, behavior: 'smooth'});">
                        ↑ Back to Top
                    </button>
                </div>
            </div>
        </aside>

    </div>

    <!-- Command+K Search Modal -->
    <div class="dma-modal-backdrop" id="dmaSearchModal" style="display: none;">
        <div class="dma-search-modal">
            <div class="dma-modal-input-bar">
                <svg class="dma-modal-search-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                <input type="text" id="dmaSearchInput" class="dma-modal-input" placeholder="Search docs by keyword, CLI command, or feature..." autocomplete="off">
                <button type="button" class="dma-modal-close" id="dmaCloseSearchBtn">ESC</button>
            </div>
            <div class="dma-search-results" id="dmaSearchResults">
                <div class="dma-search-hint">Type a query to search across the entire Security Sentinel Suite...</div>
            </div>
            <div class="dma-modal-footer">
                <span class="dma-footer-tip"><kbd>↑</kbd><kbd>↓</kbd> Navigate</span>
                <span class="dma-footer-tip"><kbd>Enter</kbd> Select</span>
                <span class="dma-footer-tip"><kbd>Esc</kbd> Close</span>
            </div>
        </div>
    </div>

    <script>
        window.DMA_CONFIG = {
            searchIndexUrl: "<?php echo esc_url(rest_url('dma/v1/search-index')); ?>",
            currentProduct: "<?php echo esc_js($req_product_slug); ?>"
        };
    </script>
    <script src="<?php echo esc_url(DMA_URL . 'assets/js/docs.js?v=' . DMA_VERSION); ?>"></script>
</body>
</html>
