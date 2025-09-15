<?php
/**
 * Reusable Form Component: Accompagnement aéroport
 * Usage:
 *   include COMPONENTS_PATH . '/form-accompagnement-aeroport.php';
 *   echo render_accompagnement_aeroport_form([
 *     'action' => '/api/submit-aeroport.php', // or any endpoint
 *     'mock' => false, // set true for mock/demo mode
 *     'class' => '', // optional extra classes for the form
 *   ]);
 */

if (!function_exists('render_accompagnement_aeroport_form')) {
    function render_accompagnement_aeroport_form($opts = []) {
        $action = isset($opts['action']) ? $opts['action'] : '';
        $mock = isset($opts['mock']) ? (bool)$opts['mock'] : false;
        $class = isset($opts['class']) ? $opts['class'] : '';
        ob_start();
        ?>
        <form id="aeroport-form" action="<?= htmlspecialchars($action) ?>" method="post" class="aeroport-form-component <?= htmlspecialchars($class) ?>" novalidate>
            <fieldset>
                <legend>Accompagnement aéroport</legend>
                <p>Un accueil VIP dès votre arrivée, un départ sans stress.</p>
                <p>Que vous arriviez pour un séjour professionnel ou repartiez après une mission réussie, nous vous offrons un accompagnement complet pour tous vos déplacements à l’aéroport.</p>
                <div class="form-group">
                    <label>Type de service souhaité <span style="color:red">*</span></label>
                    <label><input type="radio" name="service" value="arrivee" required> Accueil à l’arrivée</label>
                    <label><input type="radio" name="service" value="depart"> Assistance au départ</label>
                    <label><input type="radio" name="service" value="transit"> Transit ou correspondance</label>
                </div>
                <div class="form-group">
                    <label for="aeroport">Aéroport concerné <span style="color:red">*</span></label>
                    <select id="aeroport" name="aeroport" required>
                        <option value="">-- Choisir l’aéroport --</option>
                        <option>Kinshasa – N’djili</option>
                        <option>Lubumbashi – Luano</option>
                        <option>Kolwezi</option>
                        <option>Goma</option>
                        <option>Autres</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Date et heure du vol <span style="color:red">*</span></label>
                    <input type="date" name="date_vol" required />
                    <label style="margin-top:0.5em;">Heure du vol <span style="color:red">*</span></label>
                    <input type="time" name="heure_vol" required />
                </div>
                <div class="form-group">
                    <label for="num_vol">Numéro du vol (si disponible)</label>
                    <input type="text" id="num_vol" name="num_vol" placeholder="Numéro du vol" />
                </div>
                <div class="form-group">
                    <label>Langue préférée du personnel d’accueil <span style="color:red">*</span></label>
                    <label><input type="radio" name="langue" value="fr" required> Français</label>
                    <label><input type="radio" name="langue" value="en"> Anglais</label>
                    <label><input type="radio" name="langue" value="sw"> Swahili</label>
                    <label><input type="radio" name="langue" value="autre"> Autre : <input type="text" name="langue_autre" placeholder="Précisez" /></label>
                </div>
                <div class="form-group">
                    <label>Services souhaités</label>
                    <div class="checkbox-group">
                        <label><input type="checkbox" name="services[]" value="pancarte"> Accueil avec pancarte personnalisée</label>
                        <label><input type="checkbox" name="services[]" value="formalites"> Accélération des formalités (visa, immigration, douanes)</label>
                        <label><input type="checkbox" name="services[]" value="bagages"> Port des bagages</label>
                        <label><input type="checkbox" name="services[]" value="transfert"> Transfert aéroport–hôtel ou domicile</label>
                        <label><input type="checkbox" name="services[]" value="coordination"> Coordination avec votre chauffeur ou hôtel</label>
                        <label><input type="checkbox" name="services[]" value="visa"> Assistance pour visa à l’arrivée (le cas échéant)</label>
                        <label><input type="checkbox" name="services[]" value="lounge"> Lounge VIP (optionnel selon l’aéroport)</label>
                    </div>
                </div>
                <div class="form-group">
                    <label>Informations complémentaires ou instructions spéciales</label>
                    <textarea name="infos" rows="3" placeholder="Détails ou instructions..."></textarea>
                </div>
                <button type="submit">Réserver mon accompagnement</button>
            </fieldset>
        </form>
        <script>
        (function() {
            var form = document.getElementById('aeroport-form');
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
        .aeroport-form-component {
            max-width: 480px;
            margin: 2em auto;
            background: rgba(255,255,255,0.97);
            border-radius: 1em;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            padding: 2em 1.5em;
            font-size: 1rem;
        }
        .aeroport-form-component fieldset {
            border: none;
            padding: 0;
        }
        .aeroport-form-component legend {
            font-weight: bold;
            font-size: 1.2em;
            margin-bottom: 1em;
        }
        .aeroport-form-component .form-group {
            margin-bottom: 1.2em;
        }
        .aeroport-form-component label {
            display: block;
            margin-bottom: 0.3em;
        }
        .aeroport-form-component input[type="text"],
        .aeroport-form-component input[type="email"],
        .aeroport-form-component input[type="tel"],
        .aeroport-form-component input[type="date"],
        .aeroport-form-component input[type="time"],
        .aeroport-form-component select,
        .aeroport-form-component textarea {
            width: 100%;
            padding: 0.5em;
            border: 1px solid #bbb;
            border-radius: 0.3em;
            font-size: 1em;
        }
        .aeroport-form-component .checkbox-group label {
            display: inline-block;
            margin-right: 1em;
            margin-bottom: 0.5em;
        }
        .aeroport-form-component button[type="submit"] {
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 0.3em;
            padding: 0.7em 1.5em;
            font-size: 1em;
            cursor: pointer;
            transition: background 0.2s;
        }
        .aeroport-form-component button[type="submit"]:hover {
            background: #0056b3;
        }
        </style>
        <?php
        return ob_get_clean();
    }
}
