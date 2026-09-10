/**
 * Doc My A$$ - Developer Documentation Client Interactivity
 *
 * Provides instant 1-click clipboard copying, multi-tab code switching,
 * dynamic scroll-spy TOC, and Cmd+K client-side fuzzy search.
 */

(function () {
    'use strict';

    // 1. One-Click Clipboard Copying
    function initCodeCopy() {
        document.querySelectorAll('.dma-copy-btn').forEach(function (btn) {
            btn.addEventListener('click', function () {
                var code = btn.getAttribute('data-code');
                if (!code) {
                    var pre = btn.closest('.dma-code-block').querySelector('code');
                    code = pre ? pre.innerText : '';
                }

                if (!code) return;

                navigator.clipboard.writeText(code).then(function () {
                    var originalText = btn.querySelector('.dma-copy-text');
                    if (originalText) originalText.textContent = 'Copied!';
                    btn.classList.add('copied');

                    setTimeout(function () {
                        if (originalText) originalText.textContent = 'Copy';
                        btn.classList.remove('copied');
                    }, 2000);
                }).catch(function (err) {
                    console.error('Clipboard copy failed:', err);
                });
            });
        });
    }

    // 2. Multi-Tab Code Fences Switcher
    function initTabSwitchers() {
        document.querySelectorAll('.dma-tab-container').forEach(function (container) {
            var buttons = container.querySelectorAll('.dma-tab-btn');
            var panes = container.querySelectorAll('.dma-tab-pane');

            buttons.forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var targetId = btn.getAttribute('data-tab');

                    buttons.forEach(function (b) { b.classList.remove('active'); });
                    panes.forEach(function (p) { p.classList.remove('active'); });

                    btn.classList.add('active');
                    var targetPane = document.getElementById(targetId);
                    if (targetPane) {
                        targetPane.classList.add('active');
                    }
                });
            });
        });
    }

    // 3. Dynamic Scroll-Spy for Right Table of Contents
    function initScrollSpy() {
        var tocList = document.getElementById('dmaTocList');
        if (!tocList) return;

        var headings = document.querySelectorAll('.dma-prose h2, .dma-prose h3');
        if (headings.length === 0) return;

        var observer = new IntersectionObserver(function (entries) {
            entries.forEach(function (entry) {
                if (entry.isIntersecting) {
                    var id = entry.target.getAttribute('id');
                    if (!id) return;

                    tocList.querySelectorAll('.dma-toc-item').forEach(function (item) {
                        item.classList.remove('active');
                    });

                    var activeLink = tocList.querySelector('a[href="#' + id + '"]');
                    if (activeLink && activeLink.parentElement) {
                        activeLink.parentElement.classList.add('active');
                    }
                }
            });
        }, {
            rootMargin: '-80px 0px -70% 0px',
            threshold: 0
        });

        headings.forEach(function (h) {
            observer.observe(h);
        });
    }

    // 4. Mobile Navigation Drawer Toggle
    function initMobileNav() {
        var toggle = document.getElementById('dmaMobileNavToggle');
        var sidebar = document.getElementById('dmaSidebar');
        if (!toggle || !sidebar) return;

        toggle.addEventListener('click', function () {
            sidebar.classList.toggle('open');
        });

        document.addEventListener('click', function (e) {
            if (sidebar.classList.contains('open') && !sidebar.contains(e.target) && !toggle.contains(e.target)) {
                sidebar.classList.remove('open');
            }
        });
    }

    // 5. Command+K Instant Search Modal
    function initSearch() {
        var modal = document.getElementById('dmaSearchModal');
        var openBtn = document.getElementById('dmaOpenSearchBtn');
        var closeBtn = document.getElementById('dmaCloseSearchBtn');
        var input = document.getElementById('dmaSearchInput');
        var resultsContainer = document.getElementById('dmaSearchResults');

        if (!modal || !input || !resultsContainer) return;

        var searchIndex = null;
        var selectedIndex = -1;

        // Fetch Search Index
        function loadIndex() {
            if (searchIndex !== null) return;
            var url = (window.DMA_CONFIG && window.DMA_CONFIG.searchIndexUrl) ? window.DMA_CONFIG.searchIndexUrl : '/wp-json/dma/v1/search-index';
            fetch(url)
                .then(function (res) { return res.json(); })
                .then(function (data) {
                    searchIndex = Array.isArray(data) ? data : [];
                })
                .catch(function (err) {
                    console.error('Failed to load search index:', err);
                    searchIndex = [];
                });
        }

        function openModal() {
            modal.style.display = 'flex';
            input.value = '';
            input.focus();
            loadIndex();
            renderResults([]);
        }

        function closeModal() {
            modal.style.display = 'none';
        }

        if (openBtn) openBtn.addEventListener('click', openModal);
        if (closeBtn) closeBtn.addEventListener('click', closeModal);

        modal.addEventListener('click', function (e) {
            if (e.target === modal) closeModal();
        });

        // Global Keyboard Shortcut: Cmd+K, Ctrl+K, or "/"
        document.addEventListener('keydown', function (e) {
            if ((e.metaKey || e.ctrlKey) && e.key === 'k') {
                e.preventDefault();
                if (modal.style.display === 'none' || !modal.style.display) {
                    openModal();
                } else {
                    closeModal();
                }
            } else if (e.key === 'Escape' && modal.style.display === 'flex') {
                closeModal();
            } else if (e.key === '/' && document.activeElement.tagName !== 'INPUT' && document.activeElement.tagName !== 'TEXTAREA') {
                e.preventDefault();
                openModal();
            }
        });

        // Filter and Search
        input.addEventListener('input', function () {
            var query = input.value.trim().toLowerCase();
            if (!query) {
                renderResults([]);
                return;
            }

            if (!searchIndex) {
                resultsContainer.innerHTML = '<div class="dma-search-hint">Loading search index...</div>';
                return;
            }

            var terms = query.split(/\s+/);
            var matches = [];

            for (var i = 0; i < searchIndex.length; i++) {
                var doc = searchIndex[i];
                var score = 0;

                var title = (doc.title || '').toLowerCase();
                var excerpt = (doc.excerpt || '').toLowerCase();
                var prod = (doc.product && doc.product.name ? doc.product.name : '').toLowerCase();
                var topic = (doc.topic || '').toLowerCase();

                // Check title matches
                if (title.indexOf(query) !== -1) score += 50;
                // Check product match
                if (prod.indexOf(query) !== -1) score += 30;
                // Check topic match
                if (topic.indexOf(query) !== -1) score += 20;
                // Check excerpt
                if (excerpt.indexOf(query) !== -1) score += 10;

                // Check headings
                if (doc.headings && Array.isArray(doc.headings)) {
                    for (var h = 0; h < doc.headings.length; h++) {
                        var hTitle = (doc.headings[h].title || '').toLowerCase();
                        if (hTitle.indexOf(query) !== -1) {
                            score += 25;
                            break;
                        }
                    }
                }

                if (score > 0) {
                    matches.push({ doc: doc, score: score });
                }
            }

            matches.sort(function (a, b) { return b.score - a.score; });
            var finalDocs = matches.slice(0, 10).map(function (m) { return m.doc; });
            renderResults(finalDocs, query);
        });

        // Render Search Results
        function renderResults(results, query) {
            selectedIndex = -1;
            if (!query) {
                resultsContainer.innerHTML = '<div class="dma-search-hint">Type a query to search Doc My A$$ across the suite...</div>';
                return;
            }

            if (results.length === 0) {
                resultsContainer.innerHTML = '<div class="dma-search-hint">No results found for "<strong>' + escapeHtml(query) + '</strong>"</div>';
                return;
            }

            var html = '';
            for (var i = 0; i < results.length; i++) {
                var doc = results[i];
                var prodName = (doc.product && doc.product.name) ? doc.product.name : 'Doc My A$$';
                var topicName = doc.topic || 'General';

                html += '<a href="' + doc.url + '" class="dma-result-item" data-index="' + i + '">';
                html += '<div class="dma-result-meta">';
                html += '<span class="dma-result-prod">' + escapeHtml(prodName) + '</span>';
                html += '<span>/</span>';
                html += '<span>' + escapeHtml(topicName) + '</span>';
                if (doc.badge) {
                    html += '<span class="dma-nav-badge">' + escapeHtml(doc.badge) + '</span>';
                }
                html += '</div>';
                html += '<div class="dma-result-title">' + highlightQuery(doc.title, query) + '</div>';
                html += '<div class="dma-result-excerpt">' + escapeHtml(doc.excerpt) + '</div>';
                html += '</a>';
            }

            resultsContainer.innerHTML = html;
        }

        // Arrow Key Navigation in Search Results
        input.addEventListener('keydown', function (e) {
            var items = resultsContainer.querySelectorAll('.dma-result-item');
            if (items.length === 0) return;

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                selectedIndex = (selectedIndex + 1) % items.length;
                updateSelection(items);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                selectedIndex = (selectedIndex - 1 + items.length) % items.length;
                updateSelection(items);
            } else if (e.key === 'Enter') {
                e.preventDefault();
                if (selectedIndex >= 0 && items[selectedIndex]) {
                    items[selectedIndex].click();
                } else if (items[0]) {
                    items[0].click();
                }
            }
        });

        function updateSelection(items) {
            items.forEach(function (el, idx) {
                if (idx === selectedIndex) {
                    el.classList.add('selected');
                    el.scrollIntoView({ block: 'nearest' });
                } else {
                    el.classList.remove('selected');
                }
            });
        }

        function escapeHtml(str) {
            return (str || '').replace(/[&<>"']/g, function (m) {
                return { '&': '&amp;', '<': '&lt;', '>': '&gt;', '"': '&quot;', "'": '&#039;' }[m];
            });
        }

        function highlightQuery(text, q) {
            if (!text) return '';
            var escapedQ = q.replace(/[-\/\\^$*+?.()|[\]{}]/g, '\\$&');
            var regex = new RegExp('(' + escapedQ + ')', 'gi');
            return escapeHtml(text).replace(regex, '<mark style="background:rgba(6,182,212,0.3);color:#fff;border-radius:2px;padding:0 2px;">$1</mark>');
        }
    }

    // Initialize all components on DOM ready
    document.addEventListener('DOMContentLoaded', function () {
        initCodeCopy();
        initTabSwitchers();
        initScrollSpy();
        initMobileNav();
        initSearch();
    });

})();
