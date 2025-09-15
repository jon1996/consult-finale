<?php
// Mobile Navbar Component - Style 2 (Blog Style)
?>
<!-- Navbar for mobile screen -->
<div class="navbar-mobile sticky-bar d-block d-sm-none is-sticky-glass-mobile">
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
                    <a href="index"><img src="assets/img/logos/logo_3.png" alt="logo" class="img-fluid"></a>
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

    <div class="nav-menu-items">
        <ul class="nav-items">
            <li><a href="index"><?php echo __('nav.home'); ?></a></li>
            <li><a href="index#about" class="smooth-scroll"><?php echo __('nav.about'); ?></a></li>
            <li><a href="index#why-choose-us"><?php echo __('nav.why_choose_us'); ?></a></li>
            <li><a href="visit"><?php echo __('services.visit_drc'); ?></a></li>
            <li><a href="doing-business"><?php echo __('services.doing_business'); ?></a></li>
            
        </ul>
    </div>

    
</div>

<style>
/* Opt-in sticky glass for mobile navbar (nav-mob-2) */
.navbar-mobile.is-sticky-glass-mobile {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    width: 100%;
    z-index: 3000; /* above breadcrumb and page content */
    background: rgba(255, 255, 255, 0.52); /* more transparent */
    -webkit-backdrop-filter: saturate(180%) blur(18px);
    backdrop-filter: saturate(180%) blur(18px);
    border-bottom: 1px solid rgba(229, 231, 235, 0.55);
    transform: translateZ(0);
}

/* Fallback when backdrop-filter is unsupported */
@supports not ((-webkit-backdrop-filter: blur(0)) or (backdrop-filter: blur(0))) {
  .navbar-mobile.is-sticky-glass-mobile {
    background: rgba(255, 255, 255, 0.95);
  }
}
</style>

<style>
/* Mobile navbar full-bleed adjustment */
@media (max-width: 767.98px){
    .navbar-mobile .container-fluid { padding-left:0; padding-right:0; }
}
/* Compact, even spacing like navbar-mobile */
.navbar-mobile .container-fluid { display:flex; align-items:center; gap:8px; }
.navbar-mobile .container-fluid .divider { width:1px; height:28px; background: rgba(17, 24, 39, 0.12); }
.navbar-mobile .logo img { max-height:36px; height:auto; width:auto; }
.navbar-mobile .menu-toggler .hamburger-menu a span { font-size:14px; font-weight:600; }
/* Reserve CTA mobile styles */
.navbar-mobile .reserve-cta { padding-right: 0; }
.navbar-mobile .btn-reserve-mobile {
    display:inline-block; background:#ff8c00; color:#fff; font-weight:700; border-radius:999px; padding:5px 10px; font-size:13.5px; box-shadow:0 6px 12px -4px rgba(255,140,0,0.5);
    animation: reservePulse 5s ease-out infinite; transition: background .2s ease, transform .15s ease, box-shadow .2s ease;
}
.navbar-mobile .btn-reserve-mobile:active { transform: translateY(0); }
.navbar-mobile .btn-reserve-mobile:hover { background:#ff9f2b; transform: translateY(-1px); box-shadow:0 10px 16px -6px rgba(255,140,0,0.55); }
@media (max-width: 360px){ .navbar-mobile .btn-reserve-mobile { padding:4px 9px; font-size:12.5px; } }
@media (min-width: 576px) and (max-width: 767.98px){ .navbar-mobile .btn-reserve-mobile { padding:7px 14px; } }
@media (prefers-reduced-motion: reduce){ .navbar-mobile .btn-reserve-mobile { animation:none; } }
@keyframes reservePulse { 0% { box-shadow:0 0 0 0 rgba(255,140,0,0.55);} 2% { box-shadow:0 0 0 12px rgba(255,140,0,0);} 100% { box-shadow:0 0 0 0 rgba(255,140,0,0);} }
</style>

<?php if (!empty($stickyGlassMobile)): ?>
<style>
    /* Removed body padding-top to eliminate gap; breadcrumb offset handles spacing */
</style>
<?php endif; ?>
