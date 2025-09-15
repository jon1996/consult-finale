<?php
/**
 * Reusable Form Component: Location de véhicules avec chauffeur
 * Usage:
 *   include COMPONENTS_PATH . '/form-location-vehicule.php';
 *   echo render_location_vehicule_form([
 *     'action' => '/api/submit-location.php', // or any endpoint
 *     'mock' => false, // set true for mock/demo mode
 *     'class' => '', // optional extra classes for the form
 *   ]);
 */

if (!function_exists('render_location_vehicule_form')) {
    function render_location_vehicule_form($opts = []) {
        $action = isset($opts['action']) ? $opts['action'] : '';
        $mock = isset($opts['mock']) ? (bool)$opts['mock'] : false;
        $class = isset($opts['class']) ? $opts['class'] : '';
        ob_start();
        ?>
        <form id="location-form" action="<?= htmlspecialchars($action) ?>" method="post" class="location-form-component <?= htmlspecialchars($class) ?>" novalidate>
            <fieldset>
                <legend>Location de véhicules avec chauffeur</legend>
                <p>Déplacez-vous en toute sécurité, avec confort et ponctualité, partout en RDC.</p>
                <p>Nous mettons à votre disposition un parc de véhicules adaptés à tous les besoins professionnels et personnels, accompagnés de chauffeurs expérimentés, discrets et multilingues.</p>
                <p><strong>Remplissez ce formulaire pour réserver un véhicule :</strong></p>
                <div class="form-group">
                    <label for="loc-ville">Ville de prise en charge <span style="color:red">*</span></label>
                    <select id="loc-ville" name="ville" required>
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
                    <label>Date de début de location <span style="color:red">*</span></label>
                    <input type="date" name="date_debut" required />
                    <label style="margin-top:0.5em;">Heure de début <span style="color:red">*</span></label>
                    <input type="time" name="heure_debut" required />
                </div>
                <div class="form-group">
                    <label>Durée de la location <span style="color:red">*</span></label>
                    <select name="duree" required>
                        <option value="">-- Choisir --</option>
                        <option>1 jour</option>
                        <option>2-3 jours</option>
                        <option>1 semaine</option>
                        <option>2 semaines</option>
                        <option>1 mois</option>
                        <option>autre</option>
                    </select>
                    <input type="text" name="duree_autre" placeholder="Si autre, précisez" style="margin-top:0.5em;" />
                </div>
                <div class="form-group">
                    <label>Type de véhicule souhaité <span style="color:red">*</span></label>
                    <label><input type="radio" name="vehicule" value="berline" required> Berline (Corolla, Accent…)</label>
                    <label><input type="radio" name="vehicule" value="suv"> SUV (Prado, Fortuner…)</label>
                    <label><input type="radio" name="vehicule" value="pickup"> 4x4 Pick-up (double cabine)</label>
                    <label><input type="radio" name="vehicule" value="minibus"> Minibus / Van (jusqu’à 15 places)</label>
                </div>
                <div class="form-group">
                    <label>Finalité de l’usage <span style="color:red">*</span></label>
                    <select name="finalite" required>
                        <option value="">-- Choisir --</option>
                        <option>Déplacement professionnel</option>
                        <option>Missions institutionnelles</option>
                        <option>Transport minier/logistique</option>
                        <option>Visites privées</option>
                        <option>Tourisme</option>
                        <option>Autre</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Services complémentaires</label>
                    <div class="checkbox-group">
                        <label><input type="checkbox" name="services[]" value="chauffeur"> Chauffeur multilingue (Français/Anglais/Swahili)</label>
                        <label><input type="checkbox" name="services[]" value="wifi"> Accès Wi-Fi dans le véhicule</label>
                        <label><input type="checkbox" name="services[]" value="securite"> Assistance sécurité</label>
                        <label><input type="checkbox" name="services[]" value="clim"> Climatisation garantie</label>
                        <label><input type="checkbox" name="services[]" value="carburant"> Carburant inclus</label>
                        <label><input type="checkbox" name="services[]" value="gps"> GPS et géolocalisation temps réel</label>
                    </div>
                </div>
                <div class="form-group">
                    <label for="loc-depose">Lieu de dépose (si différent du départ)</label>
                    <input type="text" id="loc-depose" name="lieu_depose" placeholder="Précisez le lieu de dépose" />
                </div>
                <div class="form-group">
                    <label>Informations complémentaires ou préférences spécifiques</label>
                    <textarea name="infos" rows="3" placeholder="Détails ou préférences..."></textarea>
                </div>
                <button type="submit">Réserver mon véhicule</button>
            </fieldset>
        </form>
        <script>
        (function() {
            var form = document.getElementById('location-form');
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
        .location-form-component {
            max-width: 480px;
            margin: 2em auto;
            background: rgba(255,255,255,0.97);
            border-radius: 1em;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            padding: 2em 1.5em;
            font-size: 1rem;
        }
        .location-form-component fieldset {
            border: none;
            padding: 0;
        }
        .location-form-component legend {
            font-weight: bold;
            font-size: 1.2em;
            margin-bottom: 1em;
        }
        .location-form-component .form-group {
            margin-bottom: 1.2em;
        }
        .location-form-component label {
            display: block;
            margin-bottom: 0.3em;
        }
        .location-form-component input[type="text"],
        .location-form-component input[type="email"],
        .location-form-component input[type="tel"],
        .location-form-component input[type="date"],
        .location-form-component input[type="time"],
        .location-form-component select,
        .location-form-component textarea {
            width: 100%;
            padding: 0.5em;
            border: 1px solid #bbb;
            border-radius: 0.3em;
            font-size: 1em;
        }
        .location-form-component .checkbox-group label {
            display: inline-block;
            margin-right: 1em;
            margin-bottom: 0.5em;
        }
        .location-form-component button[type="submit"] {
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 0.3em;
            padding: 0.7em 1.5em;
            font-size: 1em;
            cursor: pointer;
            transition: background 0.2s;
        }
        .location-form-component button[type="submit"]:hover {
            background: #0056b3;
        }
        </style>
        <?php
        return ob_get_clean();
    }
}
