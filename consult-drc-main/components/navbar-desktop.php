<?php
// More robust index detection; allow parent to set $isIndex explicitly.
if (!isset($isIndex)) {
    $req = $_SERVER['REQUEST_URI'] ?? '';
    $isIndex = (bool)preg_match('#/(index\.php)?$#', $req);
}
?>
<div class="navbar-style-4 d-none d-md-block<?php echo $isIndex ? ( !empty($stickyGlass) ? ' is-sticky-glass index-hero-overlay' : ' index-hero-overlay') : ' always-glass-fixed'; ?>">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12 nav-container">
                <!-- Main Nav Start -->
                <div class="main-nav">
                    <nav class="navbar navbar-expand-lg" role="navigation" aria-label="Main navigation">
                        <a class="navbar-brand" href="index">
                            <img src="assets/img/logos/logo_3.png" alt="Nav Logo" width="140" height="42">
                        </a>
                        
                        <!-- Navigation Links Next to Logo -->
                        <div class="navbar-nav-inline"<?php if($isIndex) echo ' data-scrollspy-nav'; ?>>
                            <?php if($isIndex): ?>
                                <a href="index" class="nav-link-inline" data-nav-root><?php echo htmlspecialchars(__('nav.home'), ENT_QUOTES, 'UTF-8'); ?></a>
                                <a href="index#about" class="nav-link-inline smooth-scroll" data-section-target="about"><?php echo htmlspecialchars(__('nav.about'), ENT_QUOTES, 'UTF-8'); ?></a>
                                <a href="index#why-choose-us" class="nav-link-inline" data-section-target="why-choose-us"><?php echo htmlspecialchars(__('nav.why_choose_us'), ENT_QUOTES, 'UTF-8'); ?></a>
                                <a href="visit" class="nav-link-inline" data-external><?php echo htmlspecialchars(__('services.visit_drc'), ENT_QUOTES, 'UTF-8'); ?></a>
                                <a href="doing-business" class="nav-link-inline" data-external><?php echo htmlspecialchars(__('services.doing_business'), ENT_QUOTES, 'UTF-8'); ?></a>
                            <?php else: ?>
                                <!-- Original links for non-index pages (no scrollspy attributes) -->
                                <a href="index" class="nav-link-inline"><?php echo htmlspecialchars(__('nav.home'), ENT_QUOTES, 'UTF-8'); ?></a>
                                <a href="index#about" class="nav-link-inline"><?php echo htmlspecialchars(__('nav.about'), ENT_QUOTES, 'UTF-8'); ?></a>
                                <a href="index#why-choose-us" class="nav-link-inline"><?php echo htmlspecialchars(__('nav.why_choose_us'), ENT_QUOTES, 'UTF-8'); ?></a>
                                <a href="visit" class="nav-link-inline"><?php echo htmlspecialchars(__('services.visit_drc'), ENT_QUOTES, 'UTF-8'); ?></a>
                                <a href="doing-business" class="nav-link-inline"><?php echo htmlspecialchars(__('services.doing_business'), ENT_QUOTES, 'UTF-8'); ?></a>
                            <?php endif; ?>
                        </div>
                    </nav>
                </div>

                <!-- Side Nav Start -->
                <div class="side-nav d-flex align-items-center">
          <a class="auth btn-reserve" href="http://127.0.0.1/app/accounts/login/"><?php echo htmlspecialchars(__('nav.reserve_service'), ENT_QUOTES, 'UTF-8'); ?></a>
                    <div class="divider"></div>
                    <div class="location" aria-label="Language selector">
                        <?php include __DIR__ . '/language-switcher.php'; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Removed empty nav-items list to reduce noise; re-add if used -->
    </div>

    <!-- search start -->
    <div class="search-content-wrap main-search-active">
        <div class="search-content">
            <form class="search-form" action="#">
                <input type="text" placeholder="<?php echo htmlspecialchars(__('nav.search_placeholder'), ENT_QUOTES, 'UTF-8'); ?>">
            </form>

            <a class="search-close d-flex justify-content-center align-items-center">
                <i class="fas fa-times icon"></i>
            </a>
        </div>
    </div>
</div>
<?php if ($isIndex): ?>
<div class="nav-placeholder d-none d-md-block"></div>
<?php endif; ?>

<!-- Custom CSS for Inline Navigation -->
<style>
.navbar-style-4 {
    background: white;
    border-bottom: 1px solid #e9ecef;
    position: relative;
    z-index: 1000;
    padding: 15px 0;
    margin-bottom: 0;
    transition: background .35s ease, box-shadow .35s ease, border-color .35s ease;
}

/* Index initial transparent overlay */
.navbar-style-4.index-hero-overlay { position: absolute; top:0; left:0; right:0; background: transparent; border: none; box-shadow: none; }
/* Enhanced scrolled state with translucency + blur */
.navbar-style-4.index-hero-overlay.scrolled {
    position: fixed;
    background: rgba(255,255,255,0.85);
    -webkit-backdrop-filter: blur(6px) saturate(180%);
    backdrop-filter: blur(6px) saturate(180%);
    border-bottom:1px solid rgba(233,236,239,0.7);
    box-shadow:0 6px 18px -6px rgba(0,0,0,0.12);
    transition: background .35s ease, backdrop-filter .35s ease, box-shadow .35s ease;
}

/* Link colors default (solid background state) */
.nav-link-inline { color: #2c3e50; font-weight: 600; font-size: 14px; text-decoration: none; padding: 8px 16px; border-radius: 6px; transition: all 0.3s ease; position: relative; }

/* Transparent state link colors (only when not scrolled) */
.navbar-style-4.index-hero-overlay:not(.scrolled) .nav-link-inline,
.navbar-style-4.index-hero-overlay:not(.scrolled) .auth { color:#ffffff; }
.navbar-style-4.index-hero-overlay:not(.scrolled) .nav-link-inline { text-shadow:0 1px 2px rgba(0,0,0,0.45); }
.navbar-style-4.index-hero-overlay:not(.scrolled) .nav-link-inline:hover { background:rgba(255,255,255,0.15); color:#ffffff; }
.navbar-style-4.index-hero-overlay:not(.scrolled) .nav-link-inline:after { border-bottom-color:#ffffff; }

/* Hover / active underline */
.nav-link-inline:after { content:""; display:block; border-bottom:2px solid #ff8c00; width:0; position:absolute; bottom:2px; left:50%; transform:translateX(-50%); transition: width 0.3s ease-in-out; }
.nav-link-inline:hover:after { width:80%; }

.navbar-nav-inline { display:flex; align-items:center; gap:30px; margin-left:40px; }
.navbar-style-4.index-hero-overlay.scrolled .nav-link-inline:hover { background:rgba(255,140,0,0.12); color:#ff8c00; }

/* Auth link color adjustments */
.side-nav .auth { color:#2c3e50; font-weight:600; transition:color .3s ease; }
.navbar-style-4.index-hero-overlay:not(.scrolled) .side-nav .auth { color:#ffffff; text-shadow:0 1px 2px rgba(0,0,0,0.45); }
.navbar-style-4.index-hero-overlay:not(.scrolled) .side-nav .auth:hover { color:#ffddaa; }

/* Reserve button styling (orange) + gentle blink every 5s */
.side-nav .auth.btn-reserve {
  background:#ff8c00;
  color:#ffffff;
  padding:6px 12px;
  border-radius:999px;
  font-weight:700;
  font-size:14px;
  white-space:nowrap;
  box-shadow:0 6px 12px -4px rgba(255,140,0,0.5);
  transition: transform .15s ease, background .2s ease, box-shadow .2s ease, color .2s ease;
  animation: reservePulse 5s ease-out infinite;
}
.side-nav .auth.btn-reserve:hover {
  background:#ff9f2b;
  transform: translateY(-1px);
  box-shadow:0 10px 16px -6px rgba(255,140,0,0.55);
  color:#ffffff;
}
.side-nav .auth.btn-reserve:active {
  transform: translateY(0);
  box-shadow:0 6px 12px -4px rgba(255,140,0,0.5);
}
.navbar-style-4.index-hero-overlay:not(.scrolled) .side-nav .auth.btn-reserve {
  color:#ffffff;
  text-shadow:none;
}
@keyframes reservePulse {
  0% { box-shadow:0 0 0 0 rgba(255,140,0,0.55); }
  2% { box-shadow:0 0 0 12px rgba(255,140,0,0); }
  100% { box-shadow:0 0 0 0 rgba(255,140,0,0); }
}

/* Respect reduced motion preferences */
@media (prefers-reduced-motion: reduce) {
  .side-nav .auth.btn-reserve { animation: none; }
}

/* Make sure the reserve button never reserves width that could push the logo */
@media (max-width: 1200px){ .side-nav .auth.btn-reserve { padding:5px 10px; font-size:13px; } }
@media (max-width: 992px){ .side-nav .auth.btn-reserve { padding:4px 10px; font-size:12.5px; } }

/* Placeholder hidden by default */
.nav-placeholder { width:100%; display:none; }
.nav-placeholder.active { display:block; }

/* Responsive tweaks (retain existing breakpoints) */
@media (max-width: 1200px) { .navbar-nav-inline { margin-left:20px; gap:20px; } .nav-link-inline { font-size:13px; padding:6px 12px; } .navbar-style-4 { padding:12px 0; } }
@media (max-width: 992px) { .navbar-nav-inline { margin-left:15px; gap:15px; } .nav-link-inline { font-size:12px; padding:5px 10px; } .navbar-style-4 { padding:10px 0; } }

<?php if ($isIndex): ?>
.navbar-nav-inline[data-scrollspy-nav] .nav-link-inline.is-section-active { color:#ff8c00; }
.navbar-nav-inline[data-scrollspy-nav] .nav-link-inline.is-section-active:after { width:80%; }
<?php endif; ?>

<?php if(!$isIndex): ?>
.navbar-style-4.always-glass-fixed { position:fixed; top:0; left:0; right:0; z-index:1100; background:rgba(255,255,255,0.82); -webkit-backdrop-filter:blur(8px) saturate(180%); backdrop-filter:blur(8px) saturate(180%); border-bottom:1px solid rgba(233,236,239,0.65); box-shadow:0 4px 14px -6px rgba(0,0,0,0.1); }
@supports not ((-webkit-backdrop-filter: blur(0)) or (backdrop-filter: blur(0))) { .navbar-style-4.always-glass-fixed { background:rgba(255,255,255,0.95); } }
/* Removed body padding-top to eliminate gap; breadcrumb script handles offset */
<?php endif; ?>
</style>

<?php if ($isIndex): ?>
<script>
(function(){
  const nav = document.querySelector('.navbar-style-4.index-hero-overlay');
  if(!nav) return;
  const placeholder = document.querySelector('.nav-placeholder');
  const hero = document.querySelector('.showcase-style-2');
  // Transparent -> solid logic via IntersectionObserver
  if('IntersectionObserver' in window && hero){
    const stateObs = new IntersectionObserver(entries=>{
      entries.forEach(entry=>{
        if(!entry.isIntersecting){
          if(!nav.classList.contains('scrolled')){
            nav.classList.add('scrolled');
            if(placeholder){ placeholder.style.height = nav.offsetHeight+'px'; placeholder.classList.add('active'); }
          }
        } else {
          if(nav.classList.contains('scrolled')){
            nav.classList.remove('scrolled');
            if(placeholder){ placeholder.classList.remove('active'); placeholder.style.height=0; }
          }
        }
      });
    }, { threshold:0, rootMargin:'-120px 0px 0px 0px' });
    stateObs.observe(hero);
  } else {
    const fallback = ()=>{ if(window.scrollY>140){ nav.classList.add('scrolled'); if(placeholder){ placeholder.style.height=nav.offsetHeight+'px'; placeholder.classList.add('active'); } } else { nav.classList.remove('scrolled'); if(placeholder){ placeholder.classList.remove('active'); placeholder.style.height=0; } } }; fallback(); window.addEventListener('scroll', fallback,{passive:true});
  }
  // Scrollspy for sections About & Why Choose Us
  const map = {};
  document.querySelectorAll('[data-section-target]').forEach(a=>{ const id=a.getAttribute('data-section-target'); const el=document.getElementById(id); if(el){ map[id]={link:a, el}; }});
  if('IntersectionObserver' in window && Object.keys(map).length){
    const spy = new IntersectionObserver(entries=>{
      entries.forEach(entry=>{ const id=entry.target.id; if(!map[id]) return; if(entry.isIntersecting){ Object.values(map).forEach(o=>o.link.classList.remove('is-section-active')); map[id].link.classList.add('is-section-active'); } });
    }, { threshold:0.45 });
    Object.values(map).forEach(o=>spy.observe(o.el));
  }
  // Preserve anchor on language change
  const originalChangeLanguage = window.changeLanguage;
  window.changeLanguage = function(lang){
    let anchor='';
    const active = document.querySelector('.nav-link-inline.is-section-active');
    if(active && active.dataset.sectionTarget){ anchor = '#'+active.dataset.sectionTarget; }
    if(typeof originalChangeLanguage === 'function'){
      const origReload = window.location.reload.bind(window.location);
      window.location.reload = function(){ if(anchor){ location.hash = anchor; } origReload(); };
      originalChangeLanguage(lang);
      setTimeout(()=>{ window.location.reload = origReload; }, 2000);
    } else {
      // Fallback direct param
      const url = new URL(window.location.href);
      url.searchParams.set('lang', lang);
      if(anchor) url.hash = anchor;
      window.location.href = url.toString();
    }
  };
})();
</script>
<?php endif; ?>
