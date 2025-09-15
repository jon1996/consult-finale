<?php
$currentLang = $lang->getCurrentLanguage();
$languages = $lang->getAvailableLanguages();
?>

<div class="language-switcher dropdown">
    <button class="btn dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
        <?php echo strtoupper($currentLang); ?>
    </button>
    
    <div class="dropdown-menu" aria-labelledby="languageDropdown">
        <?php foreach ($languages as $code => $info): ?>
            <button class="dropdown-item <?php echo $currentLang == $code ? 'active' : ''; ?>" 
                    type="button" onclick="changeLanguage('<?php echo $code; ?>')">
                <?php echo strtoupper($code); ?>
            </button>
        <?php endforeach; ?>
    </div>
</div>

<style>
.language-switcher {
    position: relative;
    z-index: 9999; /* Very high z-index to ensure it appears above everything */
}

.language-switcher .dropdown-toggle {
    color: #2c3e50;
    text-decoration: none;
    padding: 6px 10px;
    border-radius: 6px;
    transition: all 0.3s ease;
    font-size: 12px;
    font-weight: 600;
    border: 1px solid #e9ecef;
    background: white;
    min-width: 50px;
}

.language-switcher .dropdown-toggle::after {
    margin-left: 6px;
}

.language-switcher .dropdown-toggle:hover {
    background-color: #f8f9fa;
    color: #667eea;
    border-color: #667eea;
}

.language-switcher .dropdown-menu {
    position: absolute;
    top: 100% !important; /* Position below the button */
    left: 0 !important;
    right: auto !important;
    z-index: 99999 !important; /* Even higher z-index for dropdown menu */
    min-width: 60px;
    border: 1px solid #e9ecef;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    background-color: white !important;
    display: none; /* Hidden by default */
    transform: none !important; /* Override any existing transforms */
}

.language-switcher .dropdown-menu.show {
    display: block !important;
}

.language-switcher .dropdown-item {
    padding: 6px 12px;
    font-size: 12px;
    font-weight: 600;
    transition: all 0.3s ease;
    cursor: pointer;
    border: none;
    background: none;
    width: 100%;
    text-align: left;
}

.language-switcher .dropdown-item:hover {
    background-color: #667eea;
    color: white;
}

.language-switcher .dropdown-item.active {
    background-color: #667eea;
    color: white;
    font-weight: 700;
}

@media (max-width: 768px) {
    .language-switcher .dropdown-toggle {
        font-size: 11px;
        padding: 5px 8px;
        min-width: 45px;
    }
}

/* Mobile navbar overrides: ensure our menu is not repositioned by legacy rules */
.navbar-mobile-2 .container-fluid .language .language-switcher .dropdown-menu,
.navbar-mobile .container-fluid .language .language-switcher .dropdown-menu {
    top: 100% !important;
    left: 0 !important;
    right: auto !important;
    transform: none !important;
    z-index: 999999 !important;
}

/* Keep the toggle above all mobile elements */
.navbar-mobile-2 .container-fluid .language .language-switcher .dropdown-toggle,
.navbar-mobile .container-fluid .language .language-switcher .dropdown-toggle {
    position: relative;
    z-index: 1000000;
}
</style>

<script>
function initializeLanguageDropdown() {
    // Remove previous event handlers
    $('.language-switcher .dropdown-toggle').off('click.language-dropdown');
    $(document).off('click.language-dropdown');
    $('.language-switcher .dropdown-menu').off('click.language-dropdown');

    // Custom dropdown logic
    $('.language-switcher .dropdown-toggle').on('click.language-dropdown', function(e) {
        e.preventDefault();
        e.stopPropagation();
        var $menu = $(this).next('.dropdown-menu');
        var isOpen = $menu.hasClass('show') || $menu.is(':visible');
        $('.language-switcher .dropdown-menu').removeClass('show').hide();
        if (!isOpen) {
            $menu.addClass('show').css({display:'block'});
        } else {
            $menu.removeClass('show').hide();
        }
    });
    $(document).on('click.language-dropdown', function(e) {
        if (!$(e.target).closest('.language-switcher').length) {
            $('.language-switcher .dropdown-menu').removeClass('show').hide();
        }
    });
    $('.language-switcher .dropdown-menu').on('click.language-dropdown', function(e) {
        e.stopPropagation();
    });
}

// Always initialize after page load and after a short delay
if (typeof $ !== 'undefined') {
    $(function() {
        initializeLanguageDropdown();
        setTimeout(initializeLanguageDropdown, 800);
    });
} else {
    document.addEventListener('DOMContentLoaded', function() {
        var checkJQ = setInterval(function() {
            if (typeof $ !== 'undefined') {
                clearInterval(checkJQ);
                initializeLanguageDropdown();
            }
        }, 100);
    });
}

function changeLanguage(lang) {
    $('.language-switcher .dropdown-menu').removeClass('show').hide();
    $('.language-switcher .dropdown-toggle').text('...');
    fetch('includes/change-language.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({language: lang})
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            location.reload();
        } else {
            window.location.href = '?lang=' + lang;
        }
    })
    .catch(() => {
        window.location.href = '?lang=' + lang;
    });
}
</script>
