<?php
/**
 * Reusable Form Component: Réservations hôtelières
 * Usage:
 *   include COMPONENTS_PATH . '/form-reservation-hotel.php';
 *   echo render_reservation_hotel_form([
 *     'action' => '/api/submit-hotel.php', // or any endpoint
 *     'mock' => false, // set true for mock/demo mode
 *     'class' => '', // optional extra classes for the form
 *   ]);
 */

if (!function_exists('render_reservation_hotel_form')) {
    function render_reservation_hotel_form($opts = []) {
        $action = isset($opts['action']) ? $opts['action'] : '';
        $mock = isset($opts['mock']) ? (bool)$opts['mock'] : false;
        $class = isset($opts['class']) ? $opts['class'] : '';
        ob_start();
        ?>
        <form id="hotel-form" action="<?= htmlspecialchars($action) ?>" method="post" class="hotel-form-component <?= htmlspecialchars($class) ?>" novalidate>
            <fieldset>
                <legend>Réservations hôtelières</legend>
                <p>Trouvons l’hôtel qui vous correspond, selon vos besoins, votre budget et votre lieu de mission.</p>
                <p><strong>Remplissez ce formulaire pour une réservation personnalisée :</strong></p>
                <div class="form-group">
                    <label for="hotel-ville">Ville de séjour <span style="color:red">*</span></label>
                    <select id="hotel-ville" name="ville" required>
                        <option value="">-- Choisir une ville --</option>
                        <option>Kinshasa</option>
                        <option>Lubumbashi</option>
                        <option>Kolwezi</option>
                        <option>Goma</option>
                        <option>Matadi</option>
                        <option>Autre</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Date d’arrivée <span style="color:red">*</span></label>
                    <input type="date" name="date_arrivee" required />
                </div>
                <div class="form-group">
                    <label>Date de départ <span style="color:red">*</span></label>
                    <input type="date" name="date_depart" required />
                </div>
                <div class="form-group">
                    <label>Type d’hébergement souhaité <span style="color:red">*</span></label>
                    <label><input type="radio" name="hebergement" value="3etoiles" required> Hôtel 3 étoiles</label>
                    <label><input type="radio" name="hebergement" value="4etoiles"> Hôtel 4 étoiles</label>
                    <label><input type="radio" name="hebergement" value="5etoiles"> Hôtel 5 étoiles</label>
                    <label><input type="radio" name="hebergement" value="appartement"> Appartement meublé</label>
                    <label><input type="radio" name="hebergement" value="lodge"> Lodge ou Résidence sécurisée</label>
                </div>
                <div class="form-group">
                    <label>Type de chambre <span style="color:red">*</span></label>
                    <label><input type="radio" name="chambre" value="standard" required> Standard</label>
                    <label><input type="radio" name="chambre" value="suite"> Suite</label>
                    <label><input type="radio" name="chambre" value="appartement"> Appartement avec cuisine</label>
                    <label><input type="radio" name="chambre" value="simple"> Lit simple</label>
                    <label><input type="radio" name="chambre" value="double"> Lit double</label>
                </div>
                <div class="form-group">
                    <label>Besoins spécifiques (optionnel)</label>
                    <div class="checkbox-group">
                        <label><input type="checkbox" name="besoins[]" value="petitdej"> Petit déjeuner inclus</label>
                        <label><input type="checkbox" name="besoins[]" value="reunion"> Salle de réunion</label>
                        <label><input type="checkbox" name="besoins[]" value="internet"> Internet haut débit</label>
                        <label><input type="checkbox" name="besoins[]" value="navette"> Navette aéroport</label>
                        <label><input type="checkbox" name="besoins[]" value="securite"> Sécurité renforcée</label>
                        <label><input type="checkbox" name="besoins[]" value="centre"> Proximité centre-ville / institutions</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="hotel-budget">Budget par nuitée (USD) <span style="color:red">*</span></label>
                    <select id="hotel-budget" name="budget" required>
                        <option value="">-- Choisir une tranche --</option>
                        <option value="<50">< 50 USD</option>
                        <option value="50-100">50–100 USD</option>
                        <option value="100-200">100–200 USD</option>
                        <option value=">200">> 200 USD</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Informations complémentaires</label>
                    <textarea name="infos" rows="3" placeholder="Détails ou préférences..."></textarea>
                </div>
                <button type="submit">Envoyer ma demande</button>
            </fieldset>
        </form>
        <script>
        (function() {
            var form = document.getElementById('hotel-form');
            form.addEventListener('submit', function(e) {
                <?php if ($mock): ?>
                e.preventDefault();
                alert('Demande envoyée (mode démo).');
                form.reset();
                <?php endif; ?>
            });
        })();
        </script>
        <style>
        .hotel-form-component {
            max-width: 480px;
            margin: 2em auto;
            background: rgba(255,255,255,0.97);
            border-radius: 1em;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            padding: 2em 1.5em;
            font-size: 1rem;
        }
        .hotel-form-component fieldset {
            border: none;
            padding: 0;
        }
        .hotel-form-component legend {
            font-weight: bold;
            font-size: 1.2em;
            margin-bottom: 1em;
        }
        .hotel-form-component .form-group {
            margin-bottom: 1.2em;
        }
        .hotel-form-component label {
            display: block;
            margin-bottom: 0.3em;
        }
        .hotel-form-component input[type="text"],
        .hotel-form-component input[type="email"],
        .hotel-form-component input[type="tel"],
        .hotel-form-component input[type="date"],
        .hotel-form-component select,
        .hotel-form-component textarea {
            width: 100%;
            padding: 0.5em;
            border: 1px solid #bbb;
            border-radius: 0.3em;
            font-size: 1em;
        }
        .hotel-form-component .checkbox-group label {
            display: inline-block;
            margin-right: 1em;
            margin-bottom: 0.5em;
        }
        .hotel-form-component button[type="submit"] {
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 0.3em;
            padding: 0.7em 1.5em;
            font-size: 1em;
            cursor: pointer;
            transition: background 0.2s;
        }
        .hotel-form-component button[type="submit"]:hover {
            background: #0056b3;
        }
        </style>
        <?php
        return ob_get_clean();
    }
}
