<?php $isIndex = basename($_SERVER['SCRIPT_NAME']) === 'index.php'; ?>
<!-- Navbar for mobile screen -->
<div class="navbar-mobile-2 sticky-bar d-block d-md-none<?php echo $isIndex ? ' index-hero-overlay-mobile' : ' is-sticky-glass-mobile'; ?>">
    <div class="container-fluid">
        <div class="menu-toggler">
            <div class="hamburger-menu">
                <a href="#">
                    <span><?php echo __('nav.menu'); ?></span>
                </a>
            </div>
        </div>
        <div class="divider"></div>
        <div class="logo">
            <a href="#"><img src="assets/img/logos/logo_3.png" alt="logo" class="img-fluid"></a>
        </div>
        <div class="divider"></div>
    <div class="language">
            <?php include 'components/language-switcher.php'; ?>
        </div>
        <div class="divider"></div>
    <div class="reserve-cta">
                    <a href="http://127.0.0.1/app/accounts/login/" class="btn-reserve-mobile"><?php echo htmlspecialchars(__('nav.reserve_service'), ENT_QUOTES, 'UTF-8'); ?></a>
    </div>
    </div>

    <div class="nav-menu-items"<?php if($isIndex) echo ' data-mobile-scrollspy-nav'; ?>>
        <ul class="nav-items">
            <?php if($isIndex): ?>
            <li><a href="index" data-mobile-nav-root><?php echo __('nav.home'); ?></a></li>
            <li><a href="index#about" class="smooth-scroll" data-mobile-section-target="about"><?php echo __('nav.about'); ?></a></li>
            <li><a href="index#why-choose-us" data-mobile-section-target="why-choose-us"><?php echo __('nav.why_choose_us'); ?></a></li>
            <li><a href="visit" data-external><?php echo __('services.visit_drc'); ?></a></li>
            <li><a href="doing-business" data-external><?php echo __('services.doing_business'); ?></a></li>
            <?php else: ?>
            <li><a href="index"><?php echo __('nav.home'); ?></a></li>
            <li><a href="index#about" class="smooth-scroll"><?php echo __('nav.about'); ?></a></li>
            <li><a href="index#why-choose-us"><?php echo __('nav.why_choose_us'); ?></a></li>
            <li><a href="visit"><?php echo __('services.visit_drc'); ?></a></li>
            <li><a href="doing-business"><?php echo __('services.doing_business'); ?></a></li>
            <?php endif; ?>
        </ul>
    </div>

    
</div>
<?php if($isIndex): ?><div class="nav-mobile-placeholder d-md-none"></div><?php endif; ?>

<style>
/* Base glass style (non-index pages opt-in via $stickyGlassMobile) */
.navbar-mobile-2.is-sticky-glass-mobile {
  position: fixed; top: 0; left: 0; right: 0; width: 100%; z-index: 3000;
  background: rgba(255, 255, 255, 0.55); /* more transparent */
  -webkit-backdrop-filter: saturate(180%) blur(16px);
  backdrop-filter: saturate(180%) blur(16px);
  border-bottom: 1px solid rgba(229, 231, 235, 0.65);
  transform: translateZ(0);
}
@supports not ((-webkit-backdrop-filter: blur(0)) or (backdrop-filter: blur(0))) {
  .navbar-mobile-2.is-sticky-glass-mobile { background: rgba(255, 255, 255, 0.95); }
}

/* Index only: initial transparent overlay over hero */
<?php if($isIndex): ?>
.navbar-mobile-2.index-hero-overlay-mobile { position:fixed; top:0; left:0; right:0; background:transparent; border-bottom:none; box-shadow:none; -webkit-backdrop-filter:none; backdrop-filter:none; transition:background .35s ease, backdrop-filter .35s ease, box-shadow .35s ease, border-color .35s ease; }
.navbar-mobile-2.index-hero-overlay-mobile .menu-toggler .hamburger-menu a span { color:#ffffff; text-shadow:0 1px 2px rgba(0,0,0,.25); }
.navbar-mobile-2.index-hero-overlay-mobile .container-fluid .divider { background-color:rgba(255,255,255,0.7); }
.navbar-mobile-2.index-hero-overlay-mobile.scrolled { background:rgba(255,255,255,0.62); box-shadow:0 6px 24px -8px rgba(0,0,0,0.22); -webkit-backdrop-filter:blur(18px) saturate(180%); backdrop-filter:blur(18px) saturate(180%); border-bottom:1px solid rgba(229,231,235,0.6); }
@supports not ((-webkit-backdrop-filter: blur(0)) or (backdrop-filter: blur(0))) { .navbar-mobile-2.index-hero-overlay-mobile.scrolled { background:rgba(255,255,255,0.96); } }
/* Keep language toggle readable (white bg) already enforced below */

/* Active link styling via scrollspy */
.nav-menu-items[data-mobile-scrollspy-nav] a.is-mobile-section-active { color:#ff8c00; font-weight:700; }
.nav-menu-items[data-mobile-scrollspy-nav] a.is-mobile-section-active:after { width:70%; }

/* Underlines similar to desktop for feedback */
.nav-menu-items[data-mobile-scrollspy-nav] a { position:relative; }
.nav-menu-items[data-mobile-scrollspy-nav] a:after { content:""; position:absolute; left:50%; transform:translateX(-50%); bottom:4px; height:2px; width:0; background:#ff8c00; transition:width .3s ease; }
.nav-menu-items[data-mobile-scrollspy-nav] a:hover:after { width:70%; }

.nav-mobile-placeholder { width:100%; display:none; }
.nav-mobile-placeholder.active { display:block; }
<?php endif; ?>

/* Homepage only: high contrast language button */
@media (max-width: 767.98px) {
  body.index-page .navbar-mobile-2 .language-switcher .dropdown-toggle { background:#ffffff !important; color:#111827 !important; border-color:#e5e7eb !important; }
}

/* Scrolled state: make hamburger (text & bars), dividers, and search icon dark; leave language switcher as-is */
<?php if($isIndex): ?>
.navbar-mobile-2.index-hero-overlay-mobile.scrolled .container-fluid .divider { background-color:#111827 !important; }
.navbar-mobile-2.index-hero-overlay-mobile.scrolled .menu-toggler .hamburger-menu a span { color:#111827 !important; }
/* If hamburger uses bar style spans (with pseudo elements) ensure bars dark */
.navbar-mobile-2.index-hero-overlay-mobile.scrolled .menu-toggler .hamburger-menu span,
.navbar-mobile-2.index-hero-overlay-mobile.scrolled .menu-toggler .hamburger-menu span::before,
.navbar-mobile-2.index-hero-overlay-mobile.scrolled .menu-toggler .hamburger-menu span::after { background-color:#111827 !important; }
<?php endif; ?>
</style>

<style>
/* Mobile navbar (variant 2) full-bleed adjustment */
@media (max-width: 767.98px){
  .navbar-mobile-2 .container-fluid { padding-left:0; padding-right:0; }
}
/* Reserve CTA mobile styles */
.navbar-mobile-2 .reserve-cta { padding-right: 10px; }
.navbar-mobile-2 .btn-reserve-mobile {
  display:inline-block; background:#ff8c00; color:#fff; font-weight:700; border-radius:999px; padding:6px 12px; font-size:14px; box-shadow:0 6px 12px -4px rgba(255,140,0,0.5);
  animation: reservePulse 5s ease-out infinite; transition: background .2s ease, transform .15s ease, box-shadow .2s ease;
}
.navbar-mobile-2 .btn-reserve-mobile:active { transform: translateY(0); }
.navbar-mobile-2 .btn-reserve-mobile:hover { background:#ff9f2b; transform: translateY(-1px); box-shadow:0 10px 16px -6px rgba(255,140,0,0.55); }
@media (max-width: 360px){ .navbar-mobile-2 .btn-reserve-mobile { padding:5px 10px; font-size:13px; } }
@media (min-width: 576px) and (max-width: 767.98px){ .navbar-mobile-2 .btn-reserve-mobile { padding:7px 14px; } }
@media (prefers-reduced-motion: reduce){ .navbar-mobile-2 .btn-reserve-mobile { animation:none; } }
@keyframes reservePulse {
  0% { box-shadow:0 0 0 0 rgba(255,140,0,0.55); }
  2% { box-shadow:0 0 0 12px rgba(255,140,0,0); }
  100% { box-shadow:0 0 0 0 rgba(255,140,0,0); }
}
</style>

<?php if (!empty($stickyGlassMobile) && !$isIndex): ?>
<style>
  /* Removed body padding-top (handled by breadcrumb + nav stacking) */
</style>
<?php endif; ?>

<?php if($isIndex): ?>
<script>
// Enhanced deferred initialization so hero element exists (previous early return prevented behavior)
(function(){
  if(window.__mobileNavEnhanced) return; // avoid double init
  function init(){
    const nav = document.querySelector('.navbar-mobile-2.index-hero-overlay-mobile');
    const hero = document.querySelector('.showcase-style-2');
    const placeholder = document.querySelector('.nav-mobile-placeholder');
    if(!nav || !hero) return false;

    // Guard flag
    window.__mobileNavEnhanced = true;

    // Transparent -> glass on scroll via IntersectionObserver
    const makeScrolled = ()=>{ if(!nav.classList.contains('scrolled')){ nav.classList.add('scrolled'); if(placeholder){ placeholder.style.height = nav.offsetHeight + 'px'; placeholder.classList.add('active'); } }};
    const makeTop = ()=>{ if(nav.classList.contains('scrolled')){ nav.classList.remove('scrolled'); if(placeholder){ placeholder.classList.remove('active'); placeholder.style.height = 0; } }};

    if('IntersectionObserver' in window){
      const io = new IntersectionObserver(entries=>{
        entries.forEach(entry=>{ entry.isIntersecting ? makeTop() : makeScrolled(); });
      }, { threshold:0, rootMargin:'-100px 0px 0px 0px' });
      io.observe(hero);
    } else {
      const fallback = ()=>{ window.scrollY > 120 ? makeScrolled() : makeTop(); };
      fallback(); window.addEventListener('scroll', fallback, {passive:true});
    }

  // Scrollspy for About & Why Choose Us (gated until user scrolls)
  const mMap = {};
    document.querySelectorAll('[data-mobile-section-target]').forEach(a=>{ const id=a.getAttribute('data-mobile-section-target'); const el=document.getElementById(id); if(el){ mMap[id] = { link:a, el }; }});
  // Prevent initial highlight before the user scrolls
  let mobSpyEnabled = false;
  window.addEventListener('scroll', ()=>{ mobSpyEnabled = true; }, { passive:true });
  if('IntersectionObserver' in window && Object.keys(mMap).length){
      const spy = new IntersectionObserver(entries=>{
    if(!mobSpyEnabled) return; // do not highlight until user scrolls
    entries.forEach(entry=>{ const id=entry.target.id; if(!mMap[id]) return; if(entry.isIntersecting){ Object.values(mMap).forEach(o=>o.link.classList.remove('is-mobile-section-active','active')); mMap[id].link.classList.add('is-mobile-section-active'); mMap[id].link.setAttribute('aria-current','true'); } else { mMap[id].link.removeAttribute('aria-current'); }});
      }, { threshold:0.45 });
      Object.values(mMap).forEach(o=>spy.observe(o.el));
    }

    // Anchor persistence patch (idempotent)
    if(!window.__langPatchedForAnchor){
      const originalChangeLanguage = window.changeLanguage;
      window.changeLanguage = function(lang){
        let anchor = '';
        const activeDesk = document.querySelector('.nav-link-inline.is-section-active');
        const activeMob = document.querySelector('.nav-menu-items a.is-mobile-section-active');
        const chosen = activeDesk || activeMob;
        if(chosen){ const ds = chosen.getAttribute('data-section-target') || chosen.getAttribute('data-mobile-section-target'); if(ds) anchor = '#' + ds; }
        if(typeof originalChangeLanguage === 'function'){
          const origReload = window.location.reload.bind(window.location);
            window.location.reload = function(){ if(anchor){ location.hash = anchor; } origReload(); };
            originalChangeLanguage(lang);
            setTimeout(()=>{ window.location.reload = origReload; }, 2000);
        } else {
          const url = new URL(window.location.href);
          url.searchParams.set('lang', lang);
          if(anchor) url.hash = anchor; window.location.href = url.toString();
        }
      };
      window.__langPatchedForAnchor = true;
    }
    return true;
  }
  // Try immediately
  if(!init()){
     // Defer to DOMContentLoaded / load
     document.addEventListener('DOMContentLoaded', init, { once:true });
     window.addEventListener('load', init, { once:true });
     // Poll a few times in case hero injected later
     let attempts = 0; const poll = setInterval(()=>{ if(init() || ++attempts>20) clearInterval(poll); }, 150);
  }
})();
</script>
<?php endif; ?>
