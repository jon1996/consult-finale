<?php
// test-form.php: Page to test and showcase reusable form components
// Usage: Place in project root and access via /test-form.php

define('COMPONENTS_PATH', __DIR__ . '/components');

?><!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Test des Formulaires Reutilisables</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/assets/css/style.css">
    <style>
        body { background: #f7f7fa; font-family: system-ui, sans-serif; }
        .test-form-section { margin: 2em auto; max-width: 600px; background: #fff; border-radius: 1em; box-shadow: 0 2px 12px rgba(0,0,0,0.07); padding: 2em; }
        h2 { margin-top: 0; }
        hr { margin: 2em 0; border: none; border-top: 1px solid #eee; }
    </style>
</head>
<body>
    <div class="test-form-section">
        <h2>Test des formulaires réutilisables</h2>
        <?php if (isset($_GET['form'])) { ?>
            <nav style="margin-bottom:1em;">
                <a href="test-form">&larr; Retour à la liste des formulaires</a>
            </nav>
        <?php } ?>
        <?php if (!isset($_GET['form'])) { ?>
            <ul>
                <li><a href="?form=tdg">Formulaire Traduction/Assistance/Guide</a></li>
                <li><a href="?form=hotel">Formulaire Réservations hôtelières</a></li>
                <li><a href="?form=location">Formulaire Location de véhicules avec chauffeur</a></li>
                <li><a href="?form=conciergerie">Formulaire Conciergerie 24h/24</a></li>
                <li><a href="?form=aeroport">Formulaire Accompagnement aéroport</a></li>
                <li><a href="?form=visa">Formulaire Visa/Carte de Travail</a></li>
                <!-- Ajoutez ici d'autres liens de formulaires à tester -->
            </ul>
        <?php } ?>
        <hr>
        <?php
        if (isset($_GET['form']) && $_GET['form'] === 'tdg') {
            include COMPONENTS_PATH . '/forms/form-traduction-assistance-guide.php';
            echo render_traduction_assistance_guide_form([
                'action' => '', // empty for demo
                'mock' => true,
            ]);
        } else if (isset($_GET['form']) && $_GET['form'] === 'hotel') {
            include COMPONENTS_PATH . '/forms/form-reservation-hotel.php';
            echo render_reservation_hotel_form([
                'action' => '', // empty for demo
                'mock' => true,
            ]);
        } else if (isset($_GET['form']) && $_GET['form'] === 'location') {
            include COMPONENTS_PATH . '/forms/form-location-vehicule.php';
            echo render_location_vehicule_form([
                'action' => '', // empty for demo
                'mock' => true,
            ]);
        } else if (isset($_GET['form']) && $_GET['form'] === 'conciergerie') {
            include COMPONENTS_PATH . '/forms/form-conciergerie.php';
            echo render_conciergerie_form([
                'action' => '', // empty for demo
                'mock' => true,
            ]);
        } else if (isset($_GET['form']) && $_GET['form'] === 'aeroport') {
            include COMPONENTS_PATH . '/forms/form-accompagnement-aeroport.php';
            echo render_accompagnement_aeroport_form([
                'action' => '', // empty for demo
                'mock' => true,
            ]);
        } else if (isset($_GET['form']) && $_GET['form'] === 'visa') {
            include COMPONENTS_PATH . '/forms/form-visa-carte.php';
            echo render_visa_carte_form([
                'action' => '', // empty for demo
                'mock' => true,
            ]);
        } else if (!isset($_GET['form'])) {
            echo '<p>Sélectionnez un formulaire à tester.</p>';
        }
        ?>
    </div>
</body>
</html>
