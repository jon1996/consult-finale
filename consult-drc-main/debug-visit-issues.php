<?php
// Initialize language system
require_once 'includes/language.php';
$lang = new Language();
?>
<!DOCTYPE html>
<html lang="<?php echo $lang->getCurrentLanguage(); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Visit Page - Debug Test</title>
    
    <!-- Bootstrap 4 CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    
    <style>
        body { 
            margin: 0; 
            padding: 0; 
            background: #f8f9fa; 
        }
        .debug-section {
            background: #fff;
            border: 2px solid #dc3545;
            margin: 10px;
            padding: 20px;
            border-radius: 8px;
        }
        .debug-header {
            background: #dc3545;
            color: white;
            padding: 10px 15px;
            margin: -20px -20px 20px -20px;
            border-radius: 6px 6px 0 0;
        }
        .test-navbar {
            background: #2c3e50;
            padding: 15px 0;
            color: white;
        }
        .test-footer {
            background: #34495e;
            color: white;
            padding: 30px 0;
            margin-top: 50px;
        }
        .component-wrapper {
            min-height: 400px;
            border: 2px dashed #28a745;
            margin: 20px 0;
            position: relative;
        }
        .component-label {
            position: absolute;
            top: -12px;
            left: 20px;
            background: #28a745;
            color: white;
            padding: 5px 15px;
            border-radius: 15px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="debug-section">
        <div class="debug-header">
            <h3><i class="fas fa-bug"></i> Visit Page Debug Test</h3>
        </div>
        
        <h4>🔍 Testing Issues:</h4>
        <ul>
            <li><strong>Issue 1:</strong> Footer not showing up</li>
            <li><strong>Issue 2:</strong> Language dropdown not working</li>
        </ul>
        
        <h4>📋 Current Language Info:</h4>
        <p><strong>Current Language:</strong> <?php echo strtoupper($lang->getCurrentLanguage()); ?></p>
        <p><strong>Available Languages:</strong> <?php echo implode(', ', array_keys($lang->getAvailableLanguages())); ?></p>
    </div>

    <!-- Test Navbar with Language Switcher -->
    <div class="test-navbar">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-8">
                    <h4>Test Navigation</h4>
                </div>
                <div class="col-4 text-right">
                    <strong>Language:</strong>
                    <?php include 'components/language-switcher.php'; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <!-- Menu Visit Component Test -->
        <div class="component-wrapper">
            <div class="component-label">Menu Visit Component</div>
            <?php include 'components/menu-visit.php'; ?>
        </div>

        <!-- Pricing Component Test -->
        <div class="component-wrapper">
            <div class="component-label">Pricing Component</div>
            <?php include 'components/pricing-visit-drc.php'; ?>
        </div>
    </div>

    <!-- Test Footer -->
    <div class="test-footer">
        <div class="container">
            <div class="row">
                <div class="col-12 text-center">
                    <h4>🦶 Test Footer Section</h4>
                    <p>This is a test footer to verify footer visibility</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Real Footer Component -->
    <div style="border: 3px solid #ff6b35; margin: 20px 0;">
        <div style="background: #ff6b35; color: white; padding: 10px; text-align: center; font-weight: bold;">
            REAL FOOTER COMPONENT BELOW
        </div>
        <?php include 'components/footer.php'; ?>
    </div>

    <!-- Debug Info -->
    <div class="debug-section">
        <div class="debug-header">
            <h4><i class="fas fa-info-circle"></i> Debug Information</h4>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <h5>Page Structure:</h5>
                <ul>
                    <li>✅ Language system initialized</li>
                    <li>✅ Navbar with language switcher loaded</li>
                    <li>✅ Menu-visit component loaded</li>
                    <li>✅ Pricing component loaded</li>
                    <li>✅ Footer component loaded</li>
                </ul>
            </div>
            <div class="col-md-6">
                <h5>JavaScript Console Test:</h5>
                <button class="btn btn-primary" onclick="testDropdown()">Test Language Dropdown</button>
                <button class="btn btn-secondary" onclick="testScrollToFooter()">Scroll to Footer</button>
            </div>
        </div>
    </div>

    <!-- Scripts -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <script>
        $(document).ready(function() {
            console.log('Debug page loaded');
            console.log('jQuery version:', $.fn.jquery);
            console.log('Bootstrap loaded:', typeof $.fn.dropdown !== 'undefined');
            
            // Test language switcher
            setTimeout(function() {
                var $dropdown = $('.language-switcher .dropdown-toggle');
                console.log('Language dropdown found:', $dropdown.length > 0);
                if ($dropdown.length > 0) {
                    console.log('Dropdown element:', $dropdown[0]);
                }
            }, 1000);
        });
        
        function testDropdown() {
            console.log('Testing dropdown manually...');
            $('.language-switcher .dropdown-toggle').trigger('click');
        }
        
        function testScrollToFooter() {
            $('html, body').animate({
                scrollTop: $('.test-footer').offset().top
            }, 1000);
        }
    </script>
</body>
</html>
