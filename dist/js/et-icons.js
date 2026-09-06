(function () {
  'use strict';

  var icons = {
    'dashboard':'<rect x="3" y="3" width="7" height="7" rx="2"/><rect x="14" y="3" width="7" height="7" rx="2"/><rect x="3" y="14" width="7" height="7" rx="2"/><rect x="14" y="14" width="7" height="7" rx="2"/>',
    'home':'<path d="M3 11.5 12 4l9 7.5"/><path d="M5.5 10.5V20h13v-9.5"/><path d="M9.5 20v-6h5v6"/>',
    'laptop':'<rect x="4" y="5" width="16" height="11" rx="2"/><path d="M2.5 19h19"/>',
    'users':'<path d="M16 20v-1.5A4.5 4.5 0 0 0 11.5 14H7a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="3.5"/><path d="M16 4.5a3 3 0 0 1 0 5.8M21 20v-1.5a4 4 0 0 0-3-3.9"/>',
    'user':'<circle cx="12" cy="8" r="4"/><path d="M4.5 21a7.5 7.5 0 0 1 15 0"/>',
    'sitemap':'<rect x="9" y="3" width="6" height="4" rx="1"/><rect x="2" y="17" width="6" height="4" rx="1"/><rect x="9" y="17" width="6" height="4" rx="1"/><rect x="16" y="17" width="6" height="4" rx="1"/><path d="M12 7v5M5 17v-5h14v5M12 12v5"/>',
    'bar-chart':'<path d="M4 20V10h4v10M10 20V4h4v16M16 20v-7h4v7M2 20h20"/>',
    'line-chart':'<path d="M3 19 9 13l4 3 8-10"/><path d="M16 6h5v5"/>',
    'pie-chart':'<path d="M12 3a9 9 0 1 0 9 9h-9z"/><path d="M14 3.2A9 9 0 0 1 20.8 10H14z"/>',
    'undo':'<path d="M9 7H4v-5"/><path d="M4.5 7.5A8 8 0 1 1 4 15"/>',
    'exchange':'<path d="M5 7h14M16 4l3 3-3 3M19 17H5M8 14l-3 3 3 3"/>',
    'warning':'<path d="M10.2 4.3 2.7 18a2 2 0 0 0 1.8 3h15a2 2 0 0 0 1.8-3L13.8 4.3a2 2 0 0 0-3.6 0z"/><path d="M12 9v4M12 17h.01"/>',
    'tags':'<path d="M20.5 13.5 13 21l-10-10V3h8z"/><circle cx="7.5" cy="7.5" r="1.2"/><path d="m14 5 7 7-5 5"/>',
    'check-circle':'<circle cx="12" cy="12" r="9"/><path d="m8 12 2.7 2.7L16.5 9"/>',
    'key':'<circle cx="8" cy="15" r="4"/><path d="m11 12 8-8M16 7l2 2M14 9l2 2"/>',
    'history':'<path d="M4 4v5h5"/><path d="M4.8 9A8 8 0 1 1 4 15"/><path d="M12 7v5l3 2"/>',
    'calendar':'<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M7 3v4M17 3v4M3 10h18"/>',
    'calendar-o':'<rect x="3" y="5" width="18" height="16" rx="2"/><path d="M7 3v4M17 3v4M3 10h18"/>',
    'plus-circle':'<circle cx="12" cy="12" r="9"/><path d="M12 8v8M8 12h8"/>',
    'wrench':'<path d="M14.5 6.5a4 4 0 0 0-5.2 5.2L4 17l3 3 5.3-5.3a4 4 0 0 0 5.2-5.2l-2.5 2.5-3-3z"/>',
    'save':'<path d="M5 3h12l4 4v14H3V3z"/><path d="M7 3v6h9V3M7 21v-7h10v7"/>',
    'close':'<path d="m6 6 12 12M18 6 6 18"/>',
    'times':'<path d="m6 6 12 12M18 6 6 18"/>',
    'pencil':'<path d="M4 20h4l11-11a2.8 2.8 0 0 0-4-4L4 16v4z"/><path d="m13.5 6.5 4 4"/>',
    'image':'<rect x="3" y="4" width="18" height="16" rx="2"/><circle cx="8.5" cy="9" r="1.5"/><path d="m4 17 4.5-4.5 3 3L14 13l6 6"/>',
    'info-circle':'<circle cx="12" cy="12" r="9"/><path d="M12 11v6M12 7h.01"/>',
    'sign-in':'<path d="M14 5h5v14h-5M10 8l4 4-4 4M5 12h9"/>',
    'trash':'<path d="M4 7h16M9 7V4h6v3M7 7l1 14h8l1-14M10 11v6M14 11v6"/>',
    'sign-out':'<path d="M10 5H5v14h5M14 8l4 4-4 4M9 12h9"/>',
    'bars':'<path d="M4 7h16M4 12h16M4 17h16"/>',
    'angle-down':'<path d="m7 9.5 5 5 5-5"/>',
    'cube':'<path d="m12 3 8 4.5v9L12 21l-8-4.5v-9z"/><path d="m4 7.5 8 4.5 8-4.5M12 12v9"/>',
    'cubes':'<path d="m8 3 5 3v6l-5 3-5-3V6z"/><path d="m3 6 5 3 5-3M8 9v6"/><path d="m16 9 5 3v6l-5 3-5-3v-3"/><path d="m11 12 5 3 5-3M16 15v6"/>',
    'exclamation-circle':'<circle cx="12" cy="12" r="9"/><path d="M12 7v6M12 17h.01"/>',
    'eye':'<path d="M2.5 12s3.5-6 9.5-6 9.5 6 9.5 6-3.5 6-9.5 6-9.5-6-9.5-6z"/><circle cx="12" cy="12" r="2.5"/>',
    'search':'<circle cx="11" cy="11" r="7"/><path d="m20 20-4-4"/>',
    'eye-slash':'<path d="M3 3l18 18M10.6 6.2A9.8 9.8 0 0 1 12 6c6 0 9.5 6 9.5 6a14 14 0 0 1-2.1 2.8M6.2 6.2A15 15 0 0 0 2.5 12s3.5 6 9.5 6a9.6 9.6 0 0 0 3-.5"/><path d="M10.5 10.5a2.2 2.2 0 0 0 3 3"/>',
    'lock':'<rect x="5" y="10" width="14" height="11" rx="2"/><path d="M8 10V7a4 4 0 0 1 8 0v3"/>'
  };

  function findIconName(el) {
    var cls = (el.className || '').split(/\s+/);
    for (var i = 0; i < cls.length; i++) {
      if (cls[i].indexOf('fa-') === 0 && cls[i] !== 'fa-fw') return cls[i].slice(3);
    }
    return 'cube';
  }

  function replaceIcon(el) {
    if (!el || el.getAttribute('data-et-icon-done') === '1') return;
    var name = findIconName(el);
    var svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
    svg.setAttribute('viewBox', '0 0 24 24');
    svg.setAttribute('fill', 'none');
    svg.setAttribute('stroke', 'currentColor');
    svg.setAttribute('stroke-width', '1.8');
    svg.setAttribute('stroke-linecap', 'round');
    svg.setAttribute('stroke-linejoin', 'round');
    svg.setAttribute('aria-hidden', 'true');
    svg.setAttribute('focusable', 'false');
    svg.setAttribute('class', 'et-svg-icon' + (el.classList.contains('fa-fw') ? ' et-svg-fw' : ''));
    svg.innerHTML = icons[name] || icons.cube;
    el.setAttribute('data-et-icon-done', '1');
    el.parentNode.replaceChild(svg, el);
  }

  function render(root) {
    var nodes = (root || document).querySelectorAll ? (root || document).querySelectorAll('i.fa') : [];
    for (var i = 0; i < nodes.length; i++) replaceIcon(nodes[i]);
  }

  function boot() {
    render(document);
    if (window.MutationObserver) {
      var observer = new MutationObserver(function (mutations) {
        for (var i = 0; i < mutations.length; i++) {
          for (var j = 0; j < mutations[i].addedNodes.length; j++) {
            var node = mutations[i].addedNodes[j];
            if (node.nodeType !== 1) continue;
            if (node.matches && node.matches('i.fa')) replaceIcon(node);
            render(node);
          }
        }
      });
      observer.observe(document.body, { childList: true, subtree: true });
    }
  }

  if (document.readyState === 'loading') document.addEventListener('DOMContentLoaded', boot);
  else boot();
})();
