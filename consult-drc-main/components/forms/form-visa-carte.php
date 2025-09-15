<?php
/**
 * Reusable Form Component: Demande de Visa/Carte de Travail
 * Usage:
 *   include COMPONENTS_PATH . '/form-visa-carte.php';
 *   echo render_visa_carte_form([
 *     'action' => '/api/submit-visa-carte.php', // or any endpoint
 *     'mock' => false, // set true for mock/demo mode
 *     'class' => '', // optional extra classes for the form
 *   ]);
 */

if (!function_exists('render_visa_carte_form')) {
    function render_visa_carte_form($opts = []) {
        $action = isset($opts['action']) ? $opts['action'] : '';
        $mock = isset($opts['mock']) ? (bool)$opts['mock'] : false;
        $class = isset($opts['class']) ? $opts['class'] : '';
        ob_start();
        ?>
        <form id="visa-carte-form" action="<?= htmlspecialchars($action) ?>" method="post" enctype="multipart/form-data" class="visa-carte-form-component <?= htmlspecialchars($class) ?>" novalidate>
            <fieldset>
                <legend>Demande de Visa / Carte de Travail</legend>
                <p>Merci de sélectionner le but de votre séjour ou la démarche souhaitée, puis de compléter le formulaire adapté.</p>
                <div class="form-group">
                    <label>Quel est le but de votre séjour en RDC ? <span style="color:red">*</span></label>
                    <label><input type="radio" name="but_sejour" value="volant" required> Court séjour (Visa Volant)</label>
                    <label><input type="radio" name="but_sejour" value="etablissement"> Établissement à long terme (Visa d'Établissement)</label>
                    <label><input type="radio" name="but_sejour" value="carte"> Demande de Carte de Travail</label>
                </div>
                <!-- Visa Volant Section -->
                <div class="visa-section" id="section-volant" style="display:none;">
                    <h4>Visa Volant</h4>
                    <div class="form-group"><label>Nom complet <input type="text" name="volant_nom" /></label></div>
                    <div class="form-group"><label>Nationalité <input type="text" name="volant_nationalite" /></label></div>
                    <div class="form-group"><label>Lieu de naissance <input type="text" name="volant_lieu_naissance" /></label></div>
                    <div class="form-group"><label>Adresse <input type="text" name="volant_adresse" /></label></div>
                    <div class="form-group"><label>Raison de la visite <input type="text" name="volant_raison" /></label></div>
                    <div class="form-group"><label>Identité du preneur en charge (si applicable) <input type="text" name="volant_preneur" /></label></div>
                    <div class="form-group">
                        <label>Avez-vous besoin d’une assistance pour obtenir une invitation officielle ?</label>
                        <label><input type="radio" name="volant_invitation" value="oui"> Oui</label>
                        <label><input type="radio" name="volant_invitation" value="non"> Non</label>
                    </div>
                    <div class="form-group"><label>Durée prévue du séjour <input type="text" name="volant_duree" /></label></div>
                    <div class="form-group"><label>Date estimée d’arrivée <input type="date" name="volant_date_arrivee" /></label></div>
                    <div class="form-group"><strong>Documents à joindre :</strong></div>
                    <div class="form-group"><label>Lettre de demande au Directeur Général de la Migration <input type="file" name="volant_lettre" /></label></div>
                    <div class="form-group"><label>Photocopie du passeport du requérant <input type="file" name="volant_passeport" /></label></div>
                    <div class="form-group"><label>Photocopie de l’identité du preneur en charge <input type="file" name="volant_id_preneur" /></label></div>
                    <div class="form-group"><label>Preuve d'inscription au RCCM (preneur en charge personne morale) <input type="file" name="volant_rccm" /></label></div>
                    <div class="form-group"><label>Numéro d'Impôt (preneur en charge personne morale) <input type="file" name="volant_impot" /></label></div>
                    <div class="form-group"><label>Identification Nationale (preneur en charge personne morale) <input type="file" name="volant_id_nat" /></label></div>
                    <button type="submit">Envoyer ma demande d'assistance</button>
                </div>
                <!-- Visa d'Établissement Section -->
                <div class="visa-section" id="section-etablissement" style="display:none;">
                    <h4>Visa d'Établissement</h4>
                    <div class="form-group">
                        <label>Quel type de Visa d'Établissement vous intéresse ?</label>
                        <select name="etab_type">
                            <option value="">-- Choisir --</option>
                            <option value="ordinaire">Ordinaire</option>
                            <option value="travail">Travail</option>
                            <option value="permanent">Permanent</option>
                        </select>
                    </div>
                    <div class="etab-subsection" id="etab-ordinaire" style="display:none;">
                        <h5>Visa Ordinaire</h5>
                        <div class="form-group"><label>Nom complet <input type="text" name="ord_nom" /></label></div>
                        <div class="form-group"><label>Email <input type="email" name="ord_email" /></label></div>
                        <div class="form-group"><label>Téléphone <input type="tel" name="ord_tel" /></label></div>
                        <div class="form-group"><label>Nationalité <input type="text" name="ord_nationalite" /></label></div>
                        <div class="form-group"><label>Votre situation actuelle <input type="text" name="ord_situation" /></label></div>
                        <div class="form-group"><label>Informations supplémentaires <textarea name="ord_infos"></textarea></label></div>
                        <div class="form-group"><strong>Documents à joindre :</strong></div>
                        <div class="form-group"><label>Lettre de demande au Directeur Général de la Migration <input type="file" name="ord_lettre" /></label></div>
                        <div class="form-group"><label>Statuts de société <input type="file" name="ord_statuts" /></label></div>
                        <div class="form-group"><label>Registre de commerce et de crédit mobilier <input type="file" name="ord_rccm" /></label></div>
                        <div class="form-group"><label>Numéro d’identification Nationale <input type="file" name="ord_id_nat" /></label></div>
                        <div class="form-group"><label>Quitus Fiscale <input type="file" name="ord_quitus" /></label></div>
                        <div class="form-group"><label>Affiliation INPP <input type="file" name="ord_inpp" /></label></div>
                        <div class="form-group"><label>Affiliation CNSS <input type="file" name="ord_cnss" /></label></div>
                        <div class="form-group"><label>Extrait bancaire <input type="file" name="ord_banque" /></label></div>
                        <button type="button" class="contact-btn">Me contacter pour discuter de mon cas</button>
                        <button type="submit">Envoyer ma demande d'assistance</button>
                    </div>
                    <div class="etab-subsection" id="etab-travail" style="display:none;">
                        <h5>Visa de Travail</h5>
                        <div class="form-group"><label>Nom complet <input type="text" name="trav_nom" /></label></div>
                        <div class="form-group"><label>Email <input type="email" name="trav_email" /></label></div>
                        <div class="form-group"><label>Téléphone <input type="tel" name="trav_tel" /></label></div>
                        <div class="form-group"><label>Nationalité <input type="text" name="trav_nationalite" /></label></div>
                        <div class="form-group"><label>Nom de l’employeur <input type="text" name="trav_employeur" /></label></div>
                        <div class="form-group"><label>Informations supplémentaires <textarea name="trav_infos"></textarea></label></div>
                        <div class="form-group"><strong>Documents à joindre :</strong></div>
                        <div class="form-group"><label>RCCM de l’employeur <input type="file" name="trav_rccm" /></label></div>
                        <div class="form-group"><label>ID Nationale de l’employeur <input type="file" name="trav_id_nat" /></label></div>
                        <div class="form-group"><label>NIF de l’employeur <input type="file" name="trav_nif" /></label></div>
                        <div class="form-group"><label>Photo d’identité récente <input type="file" name="trav_photo" /></label></div>
                        <div class="form-group"><label>Preuve paiement INPP/ONEM <input type="file" name="trav_inpp_onem" /></label></div>
                        <div class="form-group"><label>Certificat médical <input type="file" name="trav_medical" /></label></div>
                        <div class="form-group"><label>Carte de vaccination internationale <input type="file" name="trav_vaccin" /></label></div>
                        <div class="form-group"><label>Carte de travail en cours <input type="file" name="trav_carte_travail" /></label></div>
                        <div class="form-group"><label>Contrat de travail visé ONEM <input type="file" name="trav_contrat" /></label></div>
                        <div class="form-group"><label>Diplôme/certificat qualification <input type="file" name="trav_diplome" /></label></div>
                        <div class="form-group"><label>Attestation de service <input type="file" name="trav_attestation_service" /></label></div>
                        <div class="form-group"><label>Attestation de résidence <input type="file" name="trav_residence" /></label></div>
                        <div class="form-group"><label>Attestation de bonne vie et mœurs <input type="file" name="trav_bonne_vie" /></label></div>
                        <button type="button" class="contact-btn">Me contacter pour discuter de mon cas</button>
                        <button type="submit">Envoyer ma demande d'assistance</button>
                    </div>
                    <div class="etab-subsection" id="etab-permanent" style="display:none;">
                        <h5>Visa Permanent</h5>
                        <div class="form-group"><label>Nom complet <input type="text" name="perm_nom" /></label></div>
                        <div class="form-group"><label>Email <input type="email" name="perm_email" /></label></div>
                        <div class="form-group"><label>Téléphone <input type="tel" name="perm_tel" /></label></div>
                        <div class="form-group"><label>Nationalité <input type="text" name="perm_nationalite" /></label></div>
                        <div class="form-group"><label>Informations supplémentaires <textarea name="perm_infos"></textarea></label></div>
                        <button type="button" class="contact-btn">Me contacter pour discuter de mon cas</button>
                        <button type="submit">Envoyer ma demande d'assistance</button>
                    </div>
                </div>
                <!-- Carte de Travail Section -->
                <div class="visa-section" id="section-carte" style="display:none;">
                    <h4>Demande de Carte de Travail</h4>
                    <div class="form-group"><label>Nom complet <input type="text" name="carte_nom" /></label></div>
                    <div class="form-group"><label>Email <input type="email" name="carte_email" /></label></div>
                    <div class="form-group"><label>Téléphone <input type="tel" name="carte_tel" /></label></div>
                    <div class="form-group"><label>Nationalité <input type="text" name="carte_nationalite" /></label></div>
                    <div class="form-group"><label>Nom de l’employeur <input type="text" name="carte_employeur" /></label></div>
                    <div class="form-group"><label>Informations supplémentaires <textarea name="carte_infos"></textarea></label></div>
                    <div class="form-group"><strong>Documents à joindre :</strong></div>
                    <div class="form-group"><label>Formulaire de demande de carte <input type="file" name="carte_formulaire" /></label></div>
                    <div class="form-group"><label>Lettre de transmission <input type="file" name="carte_lettre" /></label></div>
                    <div class="form-group"><label>État nominatif du personnel étranger <input type="file" name="carte_etat" /></label></div>
                    <div class="form-group"><label>Projet de contrat de travail <input type="file" name="carte_contrat" /></label></div>
                    <div class="form-group"><label>CV du requérant <input type="file" name="carte_cv" /></label></div>
                    <div class="form-group"><label>Qualification professionnelle <input type="file" name="carte_qualification" /></label></div>
                    <div class="form-group"><label>Photo passeport récente <input type="file" name="carte_photo" /></label></div>
                    <div class="form-group"><label>Organigramme société <input type="file" name="carte_organigramme" /></label></div>
                    <div class="form-group"><label>Description du poste <input type="file" name="carte_poste" /></label></div>
                    <div class="form-group"><label>Preuve paiement CNSS/INPP <input type="file" name="carte_cnss_inpp" /></label></div>
                    <div class="form-group"><label>Photocopies passeport <input type="file" name="carte_passeport" /></label></div>
                    <button type="button" class="contact-btn">Me contacter pour discuter de mon cas</button>
                    <button type="submit">Envoyer ma demande d'assistance</button>
                </div>
            </fieldset>
        </form>
        <script>
        (function() {
            var form = document.getElementById('visa-carte-form');
            var radios = form.querySelectorAll('input[name="but_sejour"]');
            var etabType = form.querySelector('select[name="etab_type"]');
            var sections = {
                volant: document.getElementById('section-volant'),
                etablissement: document.getElementById('section-etablissement'),
                carte: document.getElementById('section-carte')
            };
            var etabOrd = document.getElementById('etab-ordinaire');
            var etabTrav = document.getElementById('etab-travail');
            var etabPerm = document.getElementById('etab-permanent');
            function showSection(val) {
                Object.values(sections).forEach(function(sec) { sec.style.display = 'none'; });
                if (sections[val]) sections[val].style.display = '';
            }
            radios.forEach(function(r) {
                r.addEventListener('change', function() {
                    showSection(this.value);
                });
            });
            if (etabType) {
                etabType.addEventListener('change', function() {
                    etabOrd.style.display = etabType.value === 'ordinaire' ? '' : 'none';
                    etabTrav.style.display = etabType.value === 'travail' ? '' : 'none';
                    etabPerm.style.display = etabType.value === 'permanent' ? '' : 'none';
                });
            }
            // On submit
            form.addEventListener('submit', function(e) {
                <?php if ($mock): ?>
                e.preventDefault();
                alert('Demande envoyée (mode démo).');
                form.reset();
                Object.values(sections).forEach(function(sec) { sec.style.display = 'none'; });
                <?php endif; ?>
            });
        })();
        </script>
        <style>
        .visa-carte-form-component {
            max-width: 600px;
            margin: 2em auto;
            background: rgba(255,255,255,0.97);
            border-radius: 1em;
            box-shadow: 0 2px 12px rgba(0,0,0,0.08);
            padding: 2em 1.5em;
            font-size: 1rem;
        }
        .visa-carte-form-component fieldset {
            border: none;
            padding: 0;
        }
        .visa-carte-form-component legend {
            font-weight: bold;
            font-size: 1.2em;
            margin-bottom: 1em;
        }
        .visa-carte-form-component .form-group {
            margin-bottom: 1.2em;
        }
        .visa-carte-form-component label {
            display: block;
            margin-bottom: 0.3em;
        }
        .visa-carte-form-component input[type="text"],
        .visa-carte-form-component input[type="email"],
        .visa-carte-form-component input[type="tel"],
        .visa-carte-form-component input[type="date"],
        .visa-carte-form-component input[type="file"],
        .visa-carte-form-component select,
        .visa-carte-form-component textarea {
            width: 100%;
            padding: 0.5em;
            border: 1px solid #bbb;
            border-radius: 0.3em;
            font-size: 1em;
        }
        .visa-carte-form-component button[type="submit"],
        .visa-carte-form-component .contact-btn {
            background: #007bff;
            color: #fff;
            border: none;
            border-radius: 0.3em;
            padding: 0.7em 1.5em;
            font-size: 1em;
            cursor: pointer;
            margin-right: 0.7em;
            transition: background 0.2s;
        }
        .visa-carte-form-component button[type="submit"]:hover,
        .visa-carte-form-component .contact-btn:hover {
            background: #0056b3;
        }
        .visa-carte-form-component h4 {
            margin-top: 1.5em;
            margin-bottom: 0.7em;
            font-size: 1.1em;
            color: #007bff;
        }
        .visa-carte-form-component h5 {
            margin-top: 1em;
            margin-bottom: 0.5em;
            font-size: 1em;
            color: #333;
        }
        </style>
        <?php
        return ob_get_clean();
    }
}
