<?php
/**
 * Breadcrumb Component
 * Dynamic breadcrumb navigation for sector pages
 */

// Define breadcrumb configurations for different pages
$breadcrumbConfig = [
    'mines.php' => [
        'title' => __('sectors.mining.title'),
        'icon' => 'fas fa-pickaxe'
    ],
    'hydrocarbures.php' => [
        'title' => __('sectors.hydrocarbon.title'),
        'icon' => 'fas fa-oil-well'
    ],
    'energies.php' => [
        'title' => __('sectors.energy.title'),
        'icon' => 'fas fa-bolt'
    ],
    'telecom.php' => [
        'title' => __('sectors.telecom.title'),
        'icon' => 'fas fa-phone'
    ],
    'transport.php' => [
        'title' => __('sectors.transport.title'),
        'icon' => 'fas fa-truck'
    ],
    'assurance.php' => [
        'title' => __('sectors.insurance.title'),
        'icon' => 'fas fa-shield-alt'
    ],
    'autres-services.php' => [
        'title' => __('autres_services.title'),
        'icon' => 'fas fa-concierge-bell'
    ]
];

// Get current page name
$currentPage = basename($_SERVER['PHP_SELF']);
$currentConfig = isset($breadcrumbConfig[$currentPage]) ? $breadcrumbConfig[$currentPage] : null;
?>

<?php if ($currentConfig): ?>
<div class="breadcrumb-section-mini">
    <a href="javascript:history.back()" class="back-arrow">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
            <path d="M15.41 16.59L10.83 12l4.58-4.59L14 6l-6 6 6 6z"/>
        </svg>
        <?php echo __('nav.back_to_priorities'); ?>
    </a>
</div>
<?php endif; ?>

<!-- Breadcrumb Custom CSS -->
<style>
:root { --nav-offset: 70px; }
.breadcrumb-section-mini {position:sticky;top:var(--nav-offset);left:0;right:0;z-index:1000;background:rgba(255,255,255,0.55);backdrop-filter:blur(14px) saturate(180%);-webkit-backdrop-filter:blur(14px) saturate(180%);padding:6px 0;display:flex;justify-content:center;align-items:center;min-height:34px;transition:background .35s ease, backdrop-filter .35s ease, top .25s ease;}
@supports not ((-webkit-backdrop-filter: blur(0)) or (backdrop-filter: blur(0))) { .breadcrumb-section-mini { background:rgba(255,255,255,0.9); } }
.back-arrow {display:flex;align-items:center;gap:6px;font-size:0.9rem;font-weight:600;color:#1f2937;text-decoration:none;transition:color .25s ease, transform .25s ease;}
.back-arrow:hover {color:#ff6b35;transform:translateX(-2px);} 
.back-arrow svg {width:15px;height:15px;fill:currentColor;}
</style>
</style>

<script>
(function(){
    const bc = document.querySelector('.breadcrumb-section-mini');
    if(!bc) return;
    function navHeight(){
        // Prefer visible mobile navs, else desktop.
        const candidates = [
            '.navbar-mobile.is-sticky-glass-mobile',
            '.navbar-mobile-2.index-hero-overlay-mobile.scrolled',
            '.navbar-mobile-2.is-sticky-glass-mobile',
            '.navbar-mobile-2',
            '.navbar-mobile',
            '.navbar-style-4.always-glass-fixed',
            '.navbar-style-4.index-hero-overlay.scrolled'
        ];
        for(const sel of candidates){
            const el = document.querySelector(sel);
            if(el && window.getComputedStyle(el).display !== 'none') return el.offsetHeight;
        }
        return 0;
    }
    function apply(){
        const h = navHeight();
        document.documentElement.style.setProperty('--nav-offset', h + 'px');
    }
    ['load','resize'].forEach(evt=> window.addEventListener(evt, ()=> setTimeout(apply, 30)));
    // Observe class changes that may alter height
    const watchSelectors = [
        '.navbar-style-4.index-hero-overlay',
        '.navbar-mobile-2.index-hero-overlay-mobile'
    ];
    if('MutationObserver' in window){
        const mo = new MutationObserver(()=> apply());
        watchSelectors.forEach(sel=>{ const el=document.querySelector(sel); if(el) mo.observe(el,{attributes:true,attributeFilter:['class']}); });
    }
    apply();
})();
</script>
