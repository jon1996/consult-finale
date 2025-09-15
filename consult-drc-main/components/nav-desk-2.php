<?php
// Desktop Navbar Component - Style 2 (Blog Style)
?>
<!-- Navbar Section -->
<nav class="navbar-section container-fluid d-none d-sm-block">
    <div class="row">
        <div class="col-12 nav-container">
            <!-- Main Nav Start -->
            <div class="main-nav">
                <nav class="navbar navbar-expand-lg">
                    <a class="navbar-brand" href="index">
                        <img src="assets/img/logos/logo_3.png" alt="Nav Logo">
                    </a>

                    <!-- For Tablet and Desktop Screen -> Start -->
                    <div class="navbar-nav-inline d-none d-lg-flex">
                        <a href="index" class="nav-link-inline"><?php echo __('nav.home'); ?></a>
                        <a href="index#about" class="nav-link-inline smooth-scroll"><?php echo __('nav.about'); ?></a>
                        <a href="index#why-choose-us" class="nav-link-inline"><?php echo __('nav.why_choose_us'); ?></a>
                        <a href="visit" class="nav-link-inline"><?php echo __('services.visit_drc'); ?></a>
                        <a href="doing-business" class="nav-link-inline"><?php echo __('services.doing_business'); ?></a>
                    </div>
                    <!-- For Tablet and Desktop Screen -> End -->

                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav">
                            <li class="menu-item-has-no-children">
                                <a href="index"><?php echo __('nav.home'); ?></a>
                            </li>
                            <li class="menu-item-has-no-children">
                                <a href="index#about" class="smooth-scroll"><?php echo __('nav.about'); ?></a>
                            </li>
                            <li class="menu-item-has-no-children">
                                <a href="index#why-choose-us"><?php echo __('nav.why_choose_us'); ?></a>
                            </li>
                            <li class="menu-item-has-no-children">
                                <a href="visit"><?php echo __('services.visit_drc'); ?></a>
                            </li>
                            <li class="menu-item-has-no-children">
                                <a href="doing-business"><?php echo __('services.doing_business'); ?></a>
                            </li>
                        </ul>
                    </div>
                </nav>
            </div>

            <!-- Side Nav Start -->
            <div class="side-nav d-flex align-items-center">
                <a class="auth btn-reserve" href="http://127.0.0.1/app/accounts/login/"><?php echo htmlspecialchars(__('nav.reserve_service'), ENT_QUOTES, 'UTF-8'); ?></a>
                <div class="location">
                    <?php include 'components/language-switcher.php'; ?>
                </div>
            </div>
        </div>

        <!-- search start -->
        <div class="search-content-wrap main-search-active">
            <div class="search-content">
                <form class="search-form" action="#">
                    <input type="text" placeholder="<?php echo __('nav.search_placeholder'); ?>">
                </form>

                <a class="search-close d-flex justify-content-center align-items-center">
                    <i class="fas fa-times icon"></i>
                </a>
            </div>
        </div>
    </div>
</nav>

<style>
/* Reserve CTA (desktop nav-desk-2) */
.side-nav .auth.btn-reserve {
    background:#ff8c00; color:#ffffff; padding:8px 16px; border-radius:999px; font-weight:700; display:inline-block;
    box-shadow:0 6px 12px -4px rgba(255,140,0,0.5);
    transition: transform .15s ease, background .2s ease, box-shadow .2s ease;
    animation: reservePulse 5s ease-out infinite;
}
.side-nav .auth.btn-reserve:hover { background:#ff9f2b; transform: translateY(-1px); box-shadow:0 10px 16px -6px rgba(255,140,0,0.55); color:#fff; }
.side-nav .auth.btn-reserve:active { transform: translateY(0); }
@keyframes reservePulse { 0%{ box-shadow:0 0 0 0 rgba(255,140,0,0.55);} 2%{ box-shadow:0 0 0 12px rgba(255,140,0,0);} 100%{ box-shadow:0 0 0 0 rgba(255,140,0,0);} }
@media (prefers-reduced-motion: reduce){ .side-nav .auth.btn-reserve { animation:none; } }
</style>
