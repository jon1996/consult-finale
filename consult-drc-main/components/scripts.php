<script src="assets/js/vander/jquery-3.4.1.min.js"></script>
<script src="assets/js/vander/jquery-migrate-1.4.1.min.js"></script>
<script src="assets/js/vander/popper.min.js"></script>
<script src="assets/js/vander/bootstrap.min.js"></script>
<script src="assets/js/vander/owl.carousel.min.js"></script>
<script src="assets/js/vander/slick.min.js"></script>
<script src="assets/js/vander/waypoints.min.js"></script>
<script src="assets/js/vander/jQuery.rcounter.js"></script>
<script src="assets/js/vander/jquery.countdown.min.js"></script>

<!-- Google maps geolocation -->
<?php
// Securely load Google Maps API only when a key is configured.
require_once __DIR__ . '/../includes/config.php';
if (has_google_maps_key()) :
        global $GOOGLE_MAPS_KEY;
        $key = htmlspecialchars($GOOGLE_MAPS_KEY, ENT_QUOTES, 'UTF-8');
?>
<script async defer src="https://maps.googleapis.com/maps/api/js?key=<?php echo $key; ?>&callback=initMap"></script>
<?php else: ?>
<script>
    // Google Maps API key not configured - map will not load. See includes/config.php for setup.
    console.warn('Google Maps API key not configured. Set GOOGLE_MAPS_KEY in environment or includes/config.local.php');
</script>
<?php endif; ?>
<!-- Custom Script -->
<script src="assets/js/main.js"></script>
<script src="assets/js/slider.js"></script>
<script src="assets/js/toggler.js"></script>
<script src="assets/js/countdown.js"></script>
<script src="assets/js/map.js"></script>
<script src="assets/js/priceTable.js"></script>

<!-- Smooth Scrolling Script -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Function to smooth scroll to element
    function smoothScrollTo(targetId) {
        const targetElement = document.getElementById(targetId);
        if (targetElement) {
            // Calculate offset for fixed navbar (adjust as needed)
            const navbarHeight = 80;
            const elementPosition = targetElement.getBoundingClientRect().top;
            const offsetPosition = elementPosition + window.pageYOffset - navbarHeight;

            window.scrollTo({
                top: offsetPosition,
                behavior: 'smooth'
            });
        }
    }

    // Handle smooth scrolling for links with class 'smooth-scroll'
    const smoothScrollLinks = document.querySelectorAll('.smooth-scroll');
    
    smoothScrollLinks.forEach(link => {
        link.addEventListener('click', function(e) {
            const href = this.getAttribute('href');
            
            // Check if it's a same-page anchor link
            if (href.includes('#')) {
                const parts = href.split('#');
                const targetId = parts[1];
                
                // If it's a link to the current page or just an anchor
                if (parts[0] === '' || parts[0] === window.location.pathname || parts[0] === 'index.php') {
                    e.preventDefault();
                    smoothScrollTo(targetId);
                }
                // If it's a link to another page with anchor, let it navigate normally
                // The target page will handle the scrolling on load
            }
        });
    });

    // Handle URL hash on page load (for direct links like example.com/index.php#about)
    if (window.location.hash) {
        setTimeout(() => {
            const targetId = window.location.hash.substring(1);
            smoothScrollTo(targetId);
        }, 100);
    }

    // Also add smooth scrolling to regular anchor links
    const anchorLinks = document.querySelectorAll('a[href^="#"]');
    
    anchorLinks.forEach(link => {
        if (!link.classList.contains('smooth-scroll')) {
            link.addEventListener('click', function(e) {
                e.preventDefault();
                const targetId = this.getAttribute('href').substring(1);
                smoothScrollTo(targetId);
            });
        }
    });

    // Active section detection for navigation highlighting
    function updateActiveNavigation() {
        const sections = ['about', 'services'];
        const navLinks = document.querySelectorAll('.smooth-scroll, .nav-link-inline');
        
        sections.forEach(sectionId => {
            const section = document.getElementById(sectionId);
            if (section) {
                const rect = section.getBoundingClientRect();
                const isInView = rect.top <= 100 && rect.bottom >= 100;
                
                // Update navigation active states
                navLinks.forEach(link => {
                    const href = link.getAttribute('href');
                    if (href && href.includes('#' + sectionId)) {
                        if (isInView) {
                            link.classList.add('active');
                        } else {
                            link.classList.remove('active');
                        }
                    }
                });
            }
        });
    }

    // Listen for scroll events to update active navigation
    window.addEventListener('scroll', updateActiveNavigation);
    
    // Initial check
    updateActiveNavigation();
});
</script>

</body>

</html>
