<?php
/**
 * Reusable Form Component: Conciergerie 24h/24
 * Usage:
 *   include COMPONENTS_PATH . '/form-conciergerie.php';
 *   echo render_conciergerie_form([
 *     'action' => '/api/submit-conciergerie.php', // or any endpoint
 *     'mock' => false, // set true for mock/demo mode
 *     'class' => '', // optional extra classes for the form
 *   ]);
 */

if (!function_exists('render_conciergerie_form')) {
    function render_conciergerie_form($opts = []) {
        $action = isset($opts['action']) ? $opts['action'] : '';
        $mock = isset($opts['mock']) ? (bool)$opts['mock'] : false;
        $class = isset($opts['class']) ? $opts['class'] : '';
        ob_start();
        ?>
        <form id="conciergerie-form" action="<?= htmlspecialchars($action) ?>" method="post" class="conciergerie-form-component <?= htmlspecialchars($class) ?>" novalidate>
            <fieldset>
                <legend>Conciergerie 24h/24</legend>
                <p>Une assistance personnalisée, disponible à tout moment, pour tous vos besoins en RDC.</p>
                <p>Parce qu’un séjour réussi dépend aussi de la réactivité et du confort, notre service de conciergerie accessible 24h/24 et 7j/7 prend en charge tous les détails logistiques, pratiques ou d’urgence pendant votre séjour.</p>
                <p><strong>Faites-nous part de vos besoins :</strong></p>
                <div class="form-group">
                    <label for="concierge-nature">Nature de la demande <span style="color:red">*</span></label>
                    <select id="concierge-nature" name="nature" required>
                        <option value="">-- Choisir une option --</option>
                        <option>Réservation de restaurant / salle de réunion</option>
                        <option>Organisation d’un rendez-vous professionnel</option>
                        <option>Achat de billet (avion, Bus, événement)</option>
                        <option>Livraison à domicile ou bureau</option>
                        <option>Recommandation médicale / urgence santé</option>
                        <option>Assistance administrative</option>
                        <option>Recherche de prestataire (notaire, avocat, traducteur, etc.)</option>
                        <option>Services personnels (pressing, coiffure, bien-être…)</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Degré d’urgence <span style="color:red">*</span></label>
                    <label><input type="radio" name="urgence" value="immediat" required> Immédiat (dans l’heure)</label>
                    <label><input type="radio" name="urgence" value="aujourdhui"> Aujourd’hui</label>
                    <label><input type="radio" name="urgence" value="semaine"> Cette semaine</label>
                </div>
                <div class="form-group">
                    <label>Moyen de communication préféré <span style="color:red">*</span></label>
                    <label><input type="radio" name="contact" value="whatsapp" required> WhatsApp</label>
                    <label><input type="radio" name="contact" value="telephone"> Téléphone</label>
                    <label><input type="radio" name="contact" value="email"> Email</label>
                    <label><input type="radio" name="contact" value="physique"> Rencontre physique</label>
                </div>
                <div class="form-group">
                    <label>Souhaitez-vous un agent dédié pendant tout votre séjour ? <span style="color:red">*</span></label>
                    <label><input type="radio" name="agent" value="oui" required> Oui</label>
                    <label><input type="radio" name="agent" value="non"> Non</label>
                </div>
                <div class="form-group">
                    <label>Détail de votre demande ou consigne particulière</label>
                    <textarea name="details" rows="3" placeholder="Votre demande ou consigne..."></textarea>
                </div>
                <button type="submit">Soumettre ma demande</button>
            </fieldset>
        </form>
        <script>
        (function() {
            var form = document.getElementById('conciergerie-form');
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
        .conciergerie-form-component {
            max-width: 480px;
            margin: 2em auto;
            background: rgba(255,255,255,0.97);
            border-radius: 1em;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            padding: 2em 1.5em;
            font-size: 1rem;
        }
        .conciergerie-form-component fieldset {
            border: none;
            padding: 0;
        }
        .conciergerie-form-component legend {
            font-weight: bold;
            font-size: 1.2em;
            margin-bottom: 1em;
        }
        .conciergerie-form-component .form-group {
            margin-bottom: 1.2em;
        }
        .conciergerie-form-component label {
            display: block;
            margin-bottom: 0.3em;
        }
        .conciergerie-form-component input[type="text"],
        .conciergerie-form-component input[type="email"],
        .conciergerie-form-component input[type="tel"],
        .conciergerie-form-component select,
        .conciergerie-form-component textarea {
            width: 100%;
            padding: 0.5em;
            border: 1px solid #bbb;
            border-radius: 0.3em;
            font-size: 1em;
        }
        .conciergerie-form-component button[type="submit"] {
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 0.3em;
            padding: 0.7em 1.5em;
            font-size: 1em;
            cursor: pointer;
            transition: background 0.2s;
        }
        .conciergerie-form-component button[type="submit"]:hover {
            background: #0056b3;
        }
        </style>
        <?php
        return ob_get_clean();
    }
}
