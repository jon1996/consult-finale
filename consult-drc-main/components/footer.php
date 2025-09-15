<!-- Footer -->
<section class="footer-style-3">
    <div class="container">
        <div class="row footer-content">
            <div class="col-12 col-md-6 col-lg-3">
                <img class="brand-logo" src="assets/img/logos/logo_1.png" alt="Logo Université">
                <div class="contact-links">
                    <div class="link">
                        <p><?php echo __('footer.email'); ?></p>
                    </div>
                    <div class="link">
                        <p><?php echo __('footer.phone'); ?></p>
                    </div>
                    <div class="link">
                        <p><?php echo __('footer.address'); ?></p>
                    </div>
                    <div class="link social-icons">
                        <a href="https://x.com/" target="_blank" rel="noopener" aria-label="X (Twitter)" style="display:inline-block;vertical-align:middle;width:22px;height:22px;">
                          <svg viewBox="0 0 120 120" width="22" height="22" fill="currentColor">
                            <rect width="120" height="120" rx="24" fill="none"/>
                            <path d="M87.5 32H104L72.5 68.5L109 112H85.5L62.5 84.5L36.5 112H20L54.5 73.5L19 32H43L63.5 56.5L87.5 32ZM83.5 104H91.5L44.5 40H36.5L83.5 104Z"/>
                          </svg>
                        </a>
                    </div>
                </div>
            </div>
            <div class="col-12 col-md-6 col-lg-2 offset-lg-1">
                <h5><?php echo __('footer.quick_links'); ?></h5>
                <ul>
                    <li><a href="http://127.0.0.1/app/accounts/login/"><?php echo __('nav.reserve_service'); ?></a></li>
                    <li><a href="index#why-choose-us" id="footer-why-choose-us-link"><?php echo __('nav.why_choose_us'); ?></a></li>
                </ul>
            </div>
            <div class="col-12 col-md-6 col-lg-2">
                <h5><?php echo __('nav.contact'); ?></h5>
                <ul>
                    <li><a href="contact"><?php echo __('nav.contact'); ?></a></li>
                </ul>
            </div>
        </div>
    </div>

    <div class="copyright">
        <div class="container-fluid">
            <p class="text-center">
                &copy; <span id="footer-year" style="color:#222;"></span> <?php echo __('footer.copyright'); ?>
            </p>
        </div>
        <!-- Scroll Top Start -->
        <a id="scroll-top" class="d-flex justify-content-center align-items-center">
            <i class="flaticon-up-arrow"></i>
        </a>
    </div>
</section>
<script>
document.addEventListener('DOMContentLoaded', function() {
  // Set copyright year
  var yearSpan = document.getElementById('footer-year');
  if (yearSpan) {
    yearSpan.textContent = new Date().getFullYear();
  }
  // Smooth scroll for why choose us link
  var link = document.getElementById('footer-why-choose-us-link');
  if (link) {
    link.addEventListener('click', function(e) {
      if (window.location.pathname.endsWith('/index.php') || window.location.pathname === '/' || window.location.pathname === '/index.php') {
        var target = document.getElementById('why-choose-us');
        if (target) {
          e.preventDefault();
          target.scrollIntoView({ behavior: 'smooth' });
        }
      }
    });
  }
});
</script>
