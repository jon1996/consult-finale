<?php
// Initialize language system
require_once 'includes/language.php';
$lang = new Language();
?>
<!DOCTYPE html>
<html lang="<?php echo $lang->getCurrentLanguage(); ?>">
<head>
    <?php include 'components/head.php'; ?>
    <title><?php echo __('services.visit_drc'); ?> - <?php echo __('site.title'); ?></title>
</head>
<body>

<?php $stickyGlass = true; ?>
<?php $stickyGlassMobile = true; ?>
<?php include 'components/navbar-desktop.php'; ?>

<?php include 'components/nav-mob-2.php'; ?>

<?php include 'components/menu-visit.php'; ?>

<?php /* include 'components/pricing-visit-drc.php'; */ ?>

<?php include 'components/side-popups.php'; ?>

<?php include 'components/footer.php'; ?>

<?php include 'components/preloader.php'; ?>

<?php include 'components/scripts.php'; ?>

</body>
</html>
