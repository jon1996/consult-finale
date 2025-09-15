/*===================================
          Number Count Up 
=====================================*/
if ($(".count-number").length > 0) {
  $(".count-number").rCounter({
    duration: 20
  });
}

/*===================================
          Search Input Popup
=====================================*/
function sidebarSearch() {
  var searchTrigger = $(".search-active"),
    endTriggersearch = $(".search-close"),
    container = $(".main-search-active");

  searchTrigger.on("click", function(e) {
    e.preventDefault();
    container.toggleClass("search-visible");
  });

  endTriggersearch.on("click", function() {
    container.removeClass("search-visible");
  });
}
sidebarSearch();

/*===================================
            ScrollTop
=====================================*/

$("#scroll-top").click(function() {
  $("html").animate(
    {
      scrollTop: 0
    },
    "slow"
  );
});

/*===================================
            ScrollBottom
=====================================*/
$(".scroll-next").click(function() {
  $("html, body").animate(
    {
      scrollTop: $(window).height()
    },
    "linear"
  );
});

// Scroll then fixed
$(window).scroll(function() {
  if ($(window).scrollTop() > 800) {
    $("#scroll-top").addClass("active");
  } else {
    $("#scroll-top").removeClass("active");
  }
});

// Mobile Bottom popup toggler
if ($(window).width() < 769) {
  var scrollPos = 0;

  window.addEventListener("scroll", function() {
    if (document.body.getBoundingClientRect().top > scrollPos) {
      $(".side-form-icons").removeClass("show-up-form-icons");
    } else {
      $(".side-form-icons").addClass("show-up-form-icons");
    }
    scrollPos = document.body.getBoundingClientRect().top;
  });
}

// Show popup on Btn Click;

if ($("#popup-toggle").length > 0) {
  $("#popup-toggle").click(function() {
    $(".popover-right").toggleClass("show");
  });
}

// Disable Input
if ($(".toggle-switch").length > 0) {
  var ts = $("#job_type");

  if (ts[0].disabled) {
    alert("disabled");
  }
}

// Initialize Nice Select
if ($(".select-box").length > 0) {
  $(".select-box").niceSelect();
}
$(document).ready(function() {});

// Preloader
$(window).on("load", function() {
  var preLoder = $("#preloader");
  preLoder.fadeOut(1000);

  $(document).on("click", ".cancel-preloader a", function(e) {
    e.preventDefault();
    $("#preloader").fadeOut(2000);
  });
});

/*===================================
    Hero Slider Navbar Protection
=====================================*/
$(document).ready(function() {
  // Check if we have a showcase-style-4 hero slider
  if ($('.showcase-style-4').length > 0) {
    var $navbar = $('.navbar-section');
    var $heroSlider = $('.showcase-style-4');
    var heroHeight = $heroSlider.outerHeight();
    
    // Add fixed navbar class when over hero
    $navbar.addClass('fixed-navbar');
    
    // Function to update navbar padding based on screen size
    function updateNavbarPadding() {
      var screenWidth = $(window).width();
      var paddingTop;
      
      if (screenWidth <= 360) {
        paddingTop = '40px';
      } else if (screenWidth <= 480) {
        paddingTop = '45px';
      } else if (screenWidth <= 576) {
        paddingTop = '50px';
      } else if (screenWidth <= 768) {
        paddingTop = '60px';
      } else if (screenWidth <= 1024) {
        paddingTop = '70px';
      } else {
        paddingTop = '80px';
      }
      
      $heroSlider.css('padding-top', paddingTop);
    }
    
    // Update padding on load and resize
    updateNavbarPadding();
    $(window).resize(updateNavbarPadding);
    
    // Handle scroll behavior
    $(window).scroll(function() {
      var scrollTop = $(window).scrollTop();
      
      if (scrollTop > heroHeight - 100) {
        // When scrolled past hero, make navbar solid
        $navbar.css({
          'background': 'rgba(255, 255, 255, 0.98)',
          'backdrop-filter': 'blur(15px)'
        });
      } else {
        // When over hero, keep navbar semi-transparent
        $navbar.css({
          'background': 'rgba(255, 255, 255, 0.95)',
          'backdrop-filter': 'blur(10px)'
        });
      }
    });
    
    // Force background image coverage on mobile
    $('.showcase-style-4 .media').each(function() {
      $(this).css({
        'background-size': 'cover',
        'background-position': 'center center',
        'background-repeat': 'no-repeat'
      });
    });
  }
});

// --- Cross-page anchor navigation for About/Services ---
$(document).on('click', 'a[href="index.php#about"], a[href="index.php#services"]', function(e) {
  var href = $(this).attr('href');
  var anchor = href.split('#')[1];
  var onIndex = window.location.pathname.match(/index\.php$/) || window.location.pathname === '/' || window.location.pathname === '';
  if (!onIndex) {
    // Not on index.php, redirect to index with hash anchor (cleaner than query param)
    e.preventDefault();
    window.location.href = 'index#' + anchor;
  }
  // else, let default anchor behavior work
});
$(document).on('click', 'a[href="index#about"], a[href="index#services"]', function(e) {
  var href = $(this).attr('href');
  var anchor = href.split('#')[1];
  var onIndex = window.location.pathname.match(/index\.php$/) || window.location.pathname === '/' || window.location.pathname === '';
  if (!onIndex) {
    // Not on index.php, redirect to index with hash anchor
    e.preventDefault();
    window.location.href = 'index#' + anchor;
  }
  // else, let default anchor behavior work
});

$(function() {
  // On index.php, scroll to section if scrollto param is present
  // Support both ?scrollto= and #anchor. After scrolling we clean the URL so the address bar stays tidy.
  var params = new URLSearchParams(window.location.search);
  var scrollto = params.get('scrollto');
  if (!scrollto && window.location.hash) {
    scrollto = window.location.hash.replace(/^#/, '');
  }
  if (scrollto) {
    var target = $('#' + scrollto);
    if (target.length) {
      setTimeout(function() {
        $('html, body').animate({ scrollTop: target.offset().top - 60 }, 600); // adjust offset for sticky nav
        // Remove any query or hash from the URL to show only the clean path (e.g., /index)
        try {
          var cleaned = window.location.pathname.replace(/\.php$/i, '').split('?')[0].split('#')[0];
          if (!cleaned || cleaned === '/') cleaned = '/';
          history.replaceState(null, '', cleaned);
        } catch (err) {
          // ignore if history API not available
        }
      }, 200);
    }
  }
});

/* Side popup contact form AJAX submit ------------------------------------------------- */
$(document).on('submit', '#side-contact-form', function(e) {
  e.preventDefault();
  var $form = $(this);
  var $btn = $form.find('button[type=submit]');
  var $feedback = $form.find('.form-feedback');
  $feedback.removeClass('success error').text('');

  var email = $.trim($form.find('input[type="email"]').val() || '');
  var message = $.trim($form.find('input#message').val() || '');

  // Basic client-side validation
  if (!email) { $feedback.addClass('error').text('Veuillez renseigner une adresse email.'); return; }
  var emailRe = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
  if (!emailRe.test(email)) { $feedback.addClass('error').text('Adresse email invalide.'); return; }
  if (!message || message.length < 5) { $feedback.addClass('error').text('Veuillez écrire un message d\'au moins 5 caractères.'); return; }

  $btn.prop('disabled', true).attr('aria-disabled', 'true');

  $.ajax({
    url: $form.attr('action'),
    method: 'POST',
    dataType: 'json',
    data: { email: email, message: message },
    timeout: 10000
  }).done(function(resp) {
    if (resp && resp.success) {
      $feedback.removeClass('error').addClass('success').text(resp.message || 'Message envoyé.');
      $form[0].reset();
    } else {
      $feedback.removeClass('success').addClass('error').text((resp && resp.message) ? resp.message : 'Une erreur est survenue.');
    }
  }).fail(function(xhr) {
    var msg = 'Une erreur réseau est survenue.';
    try { var json = JSON.parse(xhr.responseText || '{}'); if (json.message) msg = json.message; } catch(e){}
    $feedback.removeClass('success').addClass('error').text(msg);
  }).always(function() {
    $btn.prop('disabled', false).removeAttr('aria-disabled');
  });
});


// --- Smooth scroll for "Why Choose Us" link if already on index page ---
// Also handle clicks where href was previously index.php?scrollto=why-choose-us; allow same-page smooth scroll and ensure URL stays clean
$(document).on('click', 'a[href="index.php?scrollto=why-choose-us"], a[href="index#why-choose-us"]', function(e) {
  var onIndex = window.location.pathname.match(/index(\.php)?$/) || window.location.pathname === '/' || window.location.pathname === '';
  if (onIndex) {
    e.preventDefault();
    var target = $('#why-choose-us');
    if (target.length) {
      $('html, body').animate({ scrollTop: target.offset().top - 60 }, 600);
      // clean URL after scrolling
      try { history.replaceState(null, '', window.location.pathname.replace(/\.php$/i, '')); } catch(e){}
    }
  }
});
