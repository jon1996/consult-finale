<?php
// Minimal router fallback for extensionless URLs.
// If the request path corresponds to an existing PHP file (e.g. /contact -> contact.php), include it.
// This is a safe, server-agnostic workaround when .htaccess/nginx rewrites are not available.
$requestUri = $_SERVER['REQUEST_URI'] ?? '/';
$path = parse_url($requestUri, PHP_URL_PATH);
$path = ltrim($path, '/');
// Default to index if empty or pointing to index
if ($path !== '' && $path !== 'index') {
    $candidate = __DIR__ . DIRECTORY_SEPARATOR . $path . '.php';
    if (file_exists($candidate)) {
        // Serve the requested PHP file directly and stop further processing
        include $candidate;
        exit;
    }
}

// Initialize language system
require_once 'includes/language.php';
$lang = new Language();
?>
<!DOCTYPE html>
<html lang="<?php echo $lang->getCurrentLanguage(); ?>">
<head>
    <?php include 'components/head.php'; ?>
    <title><?php echo __('site.title'); ?></title>
</head>
<body class="index-page">

<?php include 'components/navbar-desktop.php'; ?>

<?php include 'components/navbar-mobile.php'; ?>

<?php include 'components/hero-slider.php'; ?>

<?php include 'components/about.php'; ?>

<?php include 'components/why-choose-us.php'; ?>

<?php include 'components/side-popups.php'; ?>

<?php include 'components/partners.php'; ?>

<?php include 'components/footer.php'; ?>

<?php include 'components/preloader.php'; ?>

<?php include 'components/scripts.php'; ?>

</body>
</html>

