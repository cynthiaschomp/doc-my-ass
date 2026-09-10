<?php
/**
 * Doc My A$$ - Markdown & Frontmatter Engine
 *
 * High-performance, zero-dependency Markdown parser with YAML frontmatter
 * extraction, GitHub alert callouts, multi-tab code fences, and automatic heading anchor generation.
 *
 * @package Doc_My_Ass
 */

if (!defined('ABSPATH')) {
    exit;
}

class DMA_Markdown_Engine {

    /**
     * Parse full markdown document with frontmatter.
     *
     * @param string $raw_content Raw markdown content with optional YAML frontmatter.
     * @return array {
     *     @type array  $frontmatter Extracted YAML metadata.
     *     @type string $html        Compiled semantic HTML.
     *     @type array  $toc         Extracted headings for Table of Contents.
     *     @type string $excerpt     Plaintext excerpt for search indexing.
     * }
     */
    public static function parse($raw_content) {
        $frontmatter = [];
        $markdown = $raw_content;

        // 1. Extract YAML Frontmatter
        if (preg_match('/^---\s*\r?\n(.*?)\r?\n---\s*\r?\n(.*)$/s', $raw_content, $matches)) {
            $frontmatter = self::parse_yaml_frontmatter($matches[1]);
            $markdown = $matches[2];
        }

        // 2. Pre-process Custom Components (Alerts, Multi-Tab Fences)
        $markdown = self::preprocess_alerts($markdown);
        $markdown = self::preprocess_tabs($markdown);

        // 3. Compile Markdown to HTML
        $headings = [];
        $html = self::markdown_to_html($markdown, $headings);

        // 4. Generate plain-text excerpt for search index
        $plain = strip_tags($html);
        $plain = preg_replace('/\s+/', ' ', $plain);
        $excerpt = mb_substr(trim($plain), 0, 240) . '...';

        return [
            'frontmatter' => $frontmatter,
            'html'        => $html,
            'toc'         => $headings,
            'excerpt'     => $excerpt,
        ];
    }

    /**
     * Parse simple YAML frontmatter key-value pairs and arrays.
     */
    public static function parse_yaml_frontmatter($yaml_string) {
        $data = [];
        $lines = explode("\n", str_replace("\r", "", $yaml_string));

        foreach ($lines as $line) {
            $line = trim($line);
            if (empty($line) || $line[0] === '#') {
                continue;
            }

            if (strpos($line, ':') !== false) {
                list($key, $value) = explode(':', $line, 2);
                $key = trim($key);
                $value = trim($value);

                // Check for JSON-style array: [item1, item2]
                if (preg_match('/^\[(.*)\]$/', $value, $m)) {
                    $items = array_map(function($item) {
                        return trim(trim($item), '"\'');
                    }, explode(',', $m[1]));
                    $data[$key] = array_filter($items);
                } elseif (is_numeric($value)) {
                    $data[$key] = $value + 0;
                } elseif (strtolower($value) === 'true') {
                    $data[$key] = true;
                } elseif (strtolower($value) === 'false') {
                    $data[$key] = false;
                } else {
                    $data[$key] = trim($value, '"\'');
                }
            }
        }

        return $data;
    }

    /**
     * Pre-process GitHub-style alert callouts:
     * > [!NOTE]
     * > [!TIP]
     * > [!IMPORTANT]
     * > [!WARNING]
     * > [!CAUTION]
     */
    private static function preprocess_alerts($text) {
        $pattern = '/^(?:>\s*\[!(NOTE|TIP|IMPORTANT|WARNING|CAUTION)\]\s*\r?\n)((?:>.*(?:\r?\n|$))+)/m';

        return preg_replace_callback($pattern, function($matches) {
            $type = strtoupper($matches[1]);
            $body_lines = explode("\n", $matches[2]);
            $clean_lines = [];

            foreach ($body_lines as $b_line) {
                $b_line = preg_replace('/^>\s?/', '', $b_line);
                $clean_lines[] = $b_line;
            }
            $inner = trim(implode("\n", $clean_lines));

            $icons = [
                'NOTE'      => '<svg class="dma-alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg>',
                'TIP'       => '<svg class="dma-alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 18h6"></path><path d="M10 22h4"></path><path d="M15.09 14c.18-.98.65-1.74 1.41-2.5A4.65 4.65 0 0 0 18 8 6 6 0 0 0 6 8c0 1 .23 2.23 1.5 3.5.76.76 1.23 1.52 1.41 2.5"></path></svg>',
                'IMPORTANT' => '<svg class="dma-alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>',
                'WARNING'   => '<svg class="dma-alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="m21.73 18-8-14a2 2 0 0 0-3.48 0l-8 14A2 2 0 0 0 4 21h16a2 2 0 0 0 1.73-3Z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>',
                'CAUTION'   => '<svg class="dma-alert-icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="7.86 2 16.14 2 22 7.86 22 16.14 16.14 22 7.86 22 2 16.14 2 7.86 7.86 2"></polygon><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>',
            ];

            $icon = $icons[$type] ?? $icons['NOTE'];
            $css_class = 'dma-alert dma-alert-' . strtolower($type);

            return "<!-- dma-raw-html -->\n<div class=\"{$css_class}\">\n<div class=\"dma-alert-header\">{$icon} <span class=\"dma-alert-title\">{$type}</span></div>\n<div class=\"dma-alert-body\">\n" . self::inline_markdown($inner) . "\n</div></div>\n<!-- /dma-raw-html -->\n";
        }, $text);
    }

    /**
     * Pre-process Multi-Tab Code Blocks:
     * :::tabs
     * @tab WP-CLI
     * ```bash
     * wp gma status
     * ```
     * @tab PHP
     * ```php
     * do_action('gma_check_ip');
     * ```
     * :::
     */
    private static function preprocess_tabs($text) {
        $pattern = '/:::tabs\s*\r?\n(.*?)\r?\n:::/s';

        return preg_replace_callback($pattern, function($matches) {
            $content = $matches[1];
            $tabs = preg_split('/^@tab\s+([^\r\n]+)\r?\n/m', $content, -1, PREG_SPLIT_DELIM_CAPTURE | PREG_SPLIT_NO_EMPTY);

            if (empty($tabs)) {
                return $matches[0];
            }

            $tab_nav = '';
            $tab_panes = '';
            $group_id = 'tab-group-' . wp_generate_password(8, false);

            for ($i = 0; $i < count($tabs); $i += 2) {
                $tab_title = trim($tabs[$i]);
                $tab_body = isset($tabs[$i + 1]) ? trim($tabs[$i + 1]) : '';
                $tab_id = $group_id . '-tab-' . ($i / 2);
                $is_active = ($i === 0) ? ' active' : '';

                $tab_nav .= "<button type=\"button\" class=\"dma-tab-btn{$is_active}\" data-tab=\"{$tab_id}\">" . esc_html($tab_title) . "</button>";
                $tab_panes .= "<div class=\"dma-tab-pane{$is_active}\" id=\"{$tab_id}\">" . self::render_code_fence_block($tab_body) . "</div>";
            }

            return "<!-- dma-raw-html -->\n<div class=\"dma-tab-container\">\n<div class=\"dma-tab-header\">{$tab_nav}</div>\n<div class=\"dma-tab-content\">{$tab_panes}</div>\n</div>\n<!-- /dma-raw-html -->\n";
        }, $text);
    }

    /**
     * Transform individual markdown code fences into interactive widgets with copy buttons.
     */
    private static function render_code_fence_block($markdown_snippet) {
        if (preg_match('/```([a-zA-Z0-9_\-\.\/]*)\s*(?:\[(.*?)\])?\r?\n(.*?)\r?\n```/s', $markdown_snippet, $m)) {
            $lang = !empty($m[1]) ? esc_attr($m[1]) : 'text';
            $file_label = !empty($m[2]) ? esc_html($m[2]) : '';
            $code = $m[3];
            $escaped_code = htmlspecialchars($code, ENT_QUOTES, 'UTF-8');
            $raw_code_attr = esc_attr($code);

            $label_html = $file_label ? "<span class=\"dma-code-filename\">{$file_label}</span>" : "<span class=\"dma-code-lang\">{$lang}</span>";

            return "<div class=\"dma-code-block\">
                <div class=\"dma-code-bar\">
                    {$label_html}
                    <button type=\"button\" class=\"dma-copy-btn\" data-code=\"{$raw_code_attr}\" title=\"Copy to clipboard\">
                        <svg class=\"dma-copy-icon\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\"><rect x=\"9\" y=\"9\" width=\"13\" height=\"13\" rx=\"2\" ry=\"2\"></rect><path d=\"M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1\"></path></svg>
                        <span class=\"dma-copy-text\">Copy</span>
                    </button>
                </div>
                <pre class=\"line-numbers\"><code class=\"language-{$lang}\">{$escaped_code}</code></pre>
            </div>";
        }
        return self::markdown_to_html($markdown_snippet, $dummy);
    }

    /**
     * Core Markdown to HTML parser.
     */
    public static function markdown_to_html($text, &$headings = []) {
        $lines = explode("\n", str_replace("\r", "", $text));
        $output = [];
        $in_raw_html = false;
        $in_code_block = false;
        $code_lang = '';
        $code_file = '';
        $code_buffer = [];
        $in_list = false;
        $list_type = 'ul';
        $in_table = false;
        $table_buffer = [];

        foreach ($lines as $line) {
            // Check for Raw HTML comment guards
            if (trim($line) === '<!-- dma-raw-html -->') {
                $in_raw_html = true;
                continue;
            }
            if (trim($line) === '<!-- /dma-raw-html -->') {
                $in_raw_html = false;
                continue;
            }
            if ($in_raw_html) {
                $output[] = $line;
                continue;
            }

            // Code Fences ```lang [filename]
            if (preg_match('/^```([a-zA-Z0-9_\-\.\/]*)\s*(?:\[(.*?)\])?$/', $line, $m)) {
                if ($in_code_block) {
                    // Close code block
                    $full_code = implode("\n", $code_buffer);
                    $escaped = htmlspecialchars($full_code, ENT_QUOTES, 'UTF-8');
                    $raw_attr = esc_attr($full_code);
                    $lang_class = $code_lang ? "language-{$code_lang}" : 'language-text';
                    $label = $code_file ? "<span class=\"dma-code-filename\">" . esc_html($code_file) . "</span>" : "<span class=\"dma-code-lang\">" . esc_html($code_lang ?: 'code') . "</span>";

                    $output[] = "<div class=\"dma-code-block\"><div class=\"dma-code-bar\">{$label}<button type=\"button\" class=\"dma-copy-btn\" data-code=\"{$raw_attr}\" title=\"Copy\"><svg class=\"dma-copy-icon\" viewBox=\"0 0 24 24\" fill=\"none\" stroke=\"currentColor\" stroke-width=\"2\"><rect x=\"9\" y=\"9\" width=\"13\" height=\"13\" rx=\"2\" ry=\"2\"></rect><path d=\"M5 15H4a2 2 0 0 1-2-2V4a2 2 0 0 1 2-2h9a2 2 0 0 1 2 2v1\"></path></svg><span class=\"dma-copy-text\">Copy</span></button></div><pre><code class=\"{$lang_class}\">{$escaped}</code></pre></div>";
                    $in_code_block = false;
                    $code_buffer = [];
                    $code_lang = '';
                    $code_file = '';
                } else {
                    $in_code_block = true;
                    $code_lang = !empty($m[1]) ? trim($m[1]) : '';
                    $code_file = !empty($m[2]) ? trim($m[2]) : '';
                    $code_buffer = [];
                }
                continue;
            }

            if ($in_code_block) {
                $code_buffer[] = $line;
                continue;
            }

            // Tables (| Column 1 | Column 2 |)
            if (preg_match('/^\|(.+)\|$/', trim($line))) {
                $in_table = true;
                $table_buffer[] = trim($line);
                continue;
            } elseif ($in_table) {
                $output[] = self::render_table($table_buffer);
                $table_buffer = [];
                $in_table = false;
            }

            // Headings (#, ##, ###, ####)
            if (preg_match('/^(#{1,4})\s+(.+)$/', $line, $hm)) {
                if ($in_list) {
                    $output[] = "</{$list_type}>";
                    $in_list = false;
                }
                $level = strlen($hm[1]);
                $heading_text = trim($hm[2]);
                $clean_text = strip_tags(self::inline_markdown($heading_text));
                $slug = sanitize_title($clean_text);

                // Collect for Table of Contents (h2 and h3)
                if ($level === 2 || $level === 3) {
                    $headings[] = [
                        'level' => $level,
                        'title' => $clean_text,
                        'id'    => $slug,
                    ];
                }

                $anchor_link = "<a href=\"#{$slug}\" class=\"dma-anchor-link\" aria-hidden=\"true\">#</a>";
                $output[] = "<h{$level} id=\"{$slug}\">" . self::inline_markdown($heading_text) . " {$anchor_link}</h{$level}>";
                continue;
            }

            // Horizontal Rules (--- or ***)
            if (preg_match('/^(\-{3,}|\*{3,})$/', trim($line))) {
                if ($in_list) {
                    $output[] = "</{$list_type}>";
                    $in_list = false;
                }
                $output[] = '<hr class="dma-hr">';
                continue;
            }

            // Unordered List (- or *)
            if (preg_match('/^[\*\-]\s+(.+)$/', trim($line), $lm)) {
                if (!$in_list || $list_type !== 'ul') {
                    if ($in_list) $output[] = "</{$list_type}>";
                    $output[] = '<ul class="dma-list">';
                    $in_list = true;
                    $list_type = 'ul';
                }
                $output[] = '<li>' . self::inline_markdown($lm[1]) . '</li>';
                continue;
            }

            // Ordered List (1. 2.)
            if (preg_match('/^\d+\.\s+(.+)$/', trim($line), $lm)) {
                if (!$in_list || $list_type !== 'ol') {
                    if ($in_list) $output[] = "</{$list_type}>";
                    $output[] = '<ol class="dma-list dma-list-ordered">';
                    $in_list = true;
                    $list_type = 'ol';
                }
                $output[] = '<li>' . self::inline_markdown($lm[1]) . '</li>';
                continue;
            }

            // End lists on empty or normal text
            if ($in_list && empty(trim($line))) {
                $output[] = "</{$list_type}>";
                $in_list = false;
                continue;
            }

            // Empty line
            if (empty(trim($line))) {
                continue;
            }

            // Blockquote (> text)
            if (preg_match('/^>\s?(.*)$/', $line, $bm)) {
                $output[] = '<blockquote class="dma-quote"><p>' . self::inline_markdown($bm[1]) . '</p></blockquote>';
                continue;
            }

            // Standard Paragraph
            $output[] = '<p>' . self::inline_markdown($line) . '</p>';
        }

        // Cleanup pending structures
        if ($in_table && !empty($table_buffer)) {
            $output[] = self::render_table($table_buffer);
        }
        if ($in_list) {
            $output[] = "</{$list_type}>";
        }

        return implode("\n", $output);
    }

    /**
     * Render markdown tables.
     */
    private static function render_table($lines) {
        if (count($lines) < 2) {
            return '';
        }

        $html = '<div class="dma-table-wrapper"><table class="dma-table"><thead><tr>';
        $headers = array_map('trim', explode('|', trim($lines[0], '|')));
        foreach ($headers as $h) {
            $html .= '<th>' . self::inline_markdown($h) . '</th>';
        }
        $html .= '</tr></thead><tbody>';

        for ($i = 2; $i < count($lines); $i++) {
            $cols = array_map('trim', explode('|', trim($lines[$i], '|')));
            $html .= '<tr>';
            foreach ($cols as $c) {
                $html .= '<td>' . self::inline_markdown($c) . '</td>';
            }
            $html .= '</tr>';
        }

        $html .= '</tbody></table></div>';
        return $html;
    }

    /**
     * Inline Markdown Formatting: bold, italic, inline code, links, badges.
     */
    public static function inline_markdown($text) {
        // Inline code `code`
        $text = preg_replace_callback('/`([^`]+)`/', function($m) {
            return '<code class="dma-inline-code">' . htmlspecialchars($m[1], ENT_QUOTES, 'UTF-8') . '</code>';
        }, $text);

        // Bold **text**
        $text = preg_replace('/\*\*([^*]+)\*\*/', '<strong>$1</strong>', $text);

        // Italic *text*
        $text = preg_replace('/\*([^*]+)\*/', '<em>$1</em>', $text);

        // Links [text](url)
        $text = preg_replace_callback('/\[([^\]]+)\]\(([^)]+)\)/', function($m) {
            $label = $m[1];
            $url = esc_url($m[2]);
            $is_external = (strpos($url, 'http') === 0 && strpos($url, 'securemyass.com') === false);
            $target = $is_external ? ' target="_blank" rel="noopener noreferrer"' : '';
            return "<a href=\"{$url}\" class=\"dma-link\"{$target}>{$label}</a>";
        }, $text);

        return $text;
    }
}
