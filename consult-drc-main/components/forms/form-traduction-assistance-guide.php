<?php
/**
 * Reusable Form Component: Traduction Officielle – Assistance Administrative – Guide Local
 * Usage:
 *   include COMPONENTS_PATH . '/form-traduction-assistance-guide.php';
 *   echo render_traduction_assistance_guide_form([
 *     'action' => '/api/submit-tdg.php', // or any endpoint
 *     'mock' => false, // set true for mock/demo mode
 *     'class' => '', // optional extra classes for the form
 *   ]);
 *
 * Parameters:
 *   - action: string (form action URL)
 *   - mock: bool (if true, disables real submission and shows a mock alert)
 *   - class: string (optional extra classes for the <form>)
 */

if (!function_exists('render_traduction_assistance_guide_form')) {
    function render_traduction_assistance_guide_form($opts = []) {
        $action = isset($opts['action']) ? $opts['action'] : '';
        $mock = isset($opts['mock']) ? (bool)$opts['mock'] : false;
        $class = isset($opts['class']) ? $opts['class'] : '';
        ob_start();
        ?>
        <form id="tdg-form" action="<?= htmlspecialchars($action) ?>" method="post" class="tdg-form-component <?= htmlspecialchars($class) ?>" novalidate>
            <fieldset>
                <legend>Traduction Officielle – Assistance Administrative – Guide Local</legend>
                <div class="form-group">
                    <label>Choisissez le ou les services dont vous avez besoin <span aria-hidden="true" style="color:red">*</span></label>
                    <div class="checkbox-group">
                        <label><input type="checkbox" name="services[]" value="traduction" id="service-traduction"> Traduction officielle de documents</label><br>
                        <label><input type="checkbox" name="services[]" value="assistance" id="service-assistance"> Assistance administrative</label><br>
                        <label><input type="checkbox" name="services[]" value="guide" id="service-guide"> Guide local professionnel</label>
                    </div>
                </div>
                <!-- Traduction officielle section -->
                <div class="service-section" id="section-traduction" style="display:none;">
                    <h4>1. Traduction officielle de documents</h4>
                    <div class="form-group">
                        <label>Documents à traduire :</label>
                        <div class="checkbox-group">
                            <label><input type="checkbox" name="traduction_docs[]" value="contrat"> Contrat / Accord</label><br>
                            <label><input type="checkbox" name="traduction_docs[]" value="piece_identite"> Pièce d’identité</label><br>
                            <label><input type="checkbox" name="traduction_docs[]" value="juridique"> Documents juridiques / notariés</label><br>
                            <label><input type="checkbox" name="traduction_docs[]" value="fiscal"> Documents fiscaux / administratifs</label><br>
                            <label><input type="checkbox" name="traduction_docs[]" value="correspondance"> Correspondance professionnelle</label><br>
                            <label><input type="checkbox" name="traduction_docs[]" value="autre"> Autre : <input type="text" name="traduction_docs_autre" placeholder="Précisez"></label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Langue source :</label>
                        <select name="langue_source">
                            <option value="">-- Sélectionnez --</option>
                            <option value="fr">Français</option>
                            <option value="en">Anglais</option>
                            <option value="sw">Swahili</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Langue cible :</label>
                        <select name="langue_cible">
                            <option value="">-- Sélectionnez --</option>
                            <option value="fr">Français</option>
                            <option value="en">Anglais</option>
                            <option value="sw">Swahili</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Besoin de légalisation / certification officielle ?</label>
                        <label><input type="radio" name="legalisation" value="oui"> Oui</label>
                        <label><input type="radio" name="legalisation" value="non"> Non</label>
                    </div>
                </div>
                <!-- Assistance administrative section -->
                <div class="service-section" id="section-assistance" style="display:none;">
                    <h4>2. Assistance administrative</h4>
                    <div class="form-group">
                        <label>Nous vous accompagnons dans toutes vos démarches :</label>
                        <div class="checkbox-group">
                            <label><input type="checkbox" name="assistance_options[]" value="rdv"> Prise de rendez-vous avec l’administration</label><br>
                            <label><input type="checkbox" name="assistance_options[]" value="dossier"> Constitution de dossier juridique</label><br>
                            <label><input type="checkbox" name="assistance_options[]" value="guichet"> Accompagnement aux guichets</label>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Précisez votre demande :</label>
                        <textarea name="assistance_details" rows="2" placeholder="Votre demande..."></textarea>
                    </div>
                </div>
                <!-- Guide local section -->
                <div class="service-section" id="section-guide" style="display:none;">
                    <h4>3. Guide local professionnel</h4>
                    <div class="form-group">
                        <label>Accompagnement :</label>
                        <label><input type="radio" name="guide_accompagnement" value="journee"> Journée</label>
                        <label><input type="radio" name="guide_accompagnement" value="demi_journee"> Demi-journée</label>
                    </div>
                    <div class="form-group">
                        <label>Langue souhaitée :</label>
                        <select name="guide_langue">
                            <option value="">-- Sélectionnez --</option>
                            <option value="fr">Français</option>
                            <option value="en">Anglais</option>
                            <option value="sw">Swahili</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Lieux à visiter ou à couvrir :</label>
                        <textarea name="guide_lieux" rows="2" placeholder="Ex: Centre-ville, institutions, etc."></textarea>
                    </div>
                    <div class="form-group">
                        <div class="checkbox-group">
                            <label><input type="checkbox" name="guide_options[]" value="culturelle"> Assistance culturelle / protocolaire</label><br>
                            <label><input type="checkbox" name="guide_options[]" value="contacts"> Présentation aux contacts locaux / partenaires</label><br>
                            <label><input type="checkbox" name="guide_options[]" value="terrain"> Accompagnement lors de missions de terrain</label>
                        </div>
                    </div>
                </div>
                <!-- Contact info -->
                <div class="form-group">
                    <label for="tdg-nom">Nom complet <span aria-hidden="true" style="color:red">*</span></label>
                    <input type="text" id="tdg-nom" name="nom" required autocomplete="name" />
                </div>
                <div class="form-group">
                    <label for="tdg-email">Email <span aria-hidden="true" style="color:red">*</span></label>
                    <input type="email" id="tdg-email" name="email" required autocomplete="email" />
                </div>
                <div class="form-group">
                    <label for="tdg-phone">Téléphone <span aria-hidden="true" style="color:red">*</span></label>
                    <input type="tel" id="tdg-phone" name="phone" required autocomplete="tel" pattern="[0-9+ ]{7,}" />
                </div>
                <button type="submit">Réserver un accompagnement</button>
            </fieldset>
        </form>
        <script>
        (function() {
            var form = document.getElementById('tdg-form');
            var cbTraduction = document.getElementById('service-traduction');
            var cbAssistance = document.getElementById('service-assistance');
            var cbGuide = document.getElementById('service-guide');
            var sectionTraduction = document.getElementById('section-traduction');
            var sectionAssistance = document.getElementById('section-assistance');
            var sectionGuide = document.getElementById('section-guide');
            function updateSections() {
                sectionTraduction.style.display = cbTraduction.checked ? '' : 'none';
                sectionAssistance.style.display = cbAssistance.checked ? '' : 'none';
                sectionGuide.style.display = cbGuide.checked ? '' : 'none';
            }
            cbTraduction.addEventListener('change', updateSections);
            cbAssistance.addEventListener('change', updateSections);
            cbGuide.addEventListener('change', updateSections);
            updateSections();
            form.addEventListener('submit', function(e) {
                <?php if ($mock): ?>
                e.preventDefault();
                alert('Demande envoyée (mode démo).');
                form.reset();
                updateSections();
                <?php else: ?>
                // Optionally add real validation/UX here
                <?php endif; ?>
            });
        })();
        </script>
        <style>
        .tdg-form-component {
            max-width: 600px;
            margin: 2em auto;
            background: rgba(255,255,255,0.97);
            border-radius: 1em;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            padding: 2em 1.5em;
            font-size: 1rem;
        }
        .tdg-form-component fieldset {
            border: none;
            padding: 0;
        }
        .tdg-form-component legend {
            font-weight: bold;
            font-size: 1.2em;
            margin-bottom: 1em;
        }
        .tdg-form-component .form-group {
            margin-bottom: 1.2em;
        }
        .tdg-form-component label {
            display: block;
            margin-bottom: 0.3em;
        }
        .tdg-form-component input[type="text"],
        .tdg-form-component input[type="email"],
        .tdg-form-component input[type="tel"],
        .tdg-form-component select,
        .tdg-form-component textarea {
            width: 100%;
            padding: 0.5em;
            border: 1px solid #bbb;
            border-radius: 0.3em;
            font-size: 1em;
        }
        .tdg-form-component .checkbox-group label {
            display: inline-block;
            margin-right: 1em;
            margin-bottom: 0.5em;
        }
        .tdg-form-component button[type="submit"] {
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 0.3em;
            padding: 0.7em 1.5em;
            font-size: 1em;
            cursor: pointer;
            transition: background 0.2s;
        }
        .tdg-form-component button[type="submit"]:hover {
            background: #0056b3;
        }
        .tdg-form-component h4 {
            margin-top: 1.5em;
            margin-bottom: 0.7em;
            font-size: 1.1em;
            color: #007bff;
        }
        </style>
        <?php
        return ob_get_clean();
    }
}
