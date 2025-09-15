<?php include 'components/head.php'; ?>
<body>

<?php include 'components/navbar-desktop.php'; ?>

<?php include 'components/nav-mob-2.php'; ?>

<!-- Breadcrumb -->
<div class="breadcrub-style-5 section">
    <div class="container">
        <div class="heading">
            <h1>Inscription</h1>
        </div>
    </div>
</div>

<!-- Signup Section -->
<section class="sign-in-section section">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-8 mx-auto">
                <div class="signup-form">
                    <h2 class="text-center mb-4">Créer votre compte DRC Business Consult</h2>
                    <p class="text-center mb-4">Rejoignez notre communauté d'entrepreneurs et professionnels congolais</p>
                    
                    <form action="#" class="form-style-2" method="POST">
                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group required">
                                    <label for="first_name">Prénom</label>
                                    <input type="text" id="first_name" class="form-control" name="first_name" placeholder="Votre prénom" required />
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group required">
                                    <label for="last_name">Nom</label>
                                    <input type="text" id="last_name" class="form-control" name="last_name" placeholder="Votre nom" required />
                                </div>
                            </div>
                        </div>

                        <div class="form-group required">
                            <label for="email">Adresse email</label>
                            <input type="email" id="email" class="form-control" name="email" placeholder="votre.email@exemple.com" required />
                        </div>

                        <div class="form-group required">
                            <label for="phone">Numéro de téléphone</label>
                            <input type="tel" id="phone" class="form-control" name="phone" placeholder="+243 xxx xxx xxx" required />
                        </div>

                        <div class="form-group required">
                            <label for="company">Entreprise/Organisation</label>
                            <input type="text" id="company" class="form-control" name="company" placeholder="Nom de votre entreprise (optionnel)" />
                        </div>

                        <div class="form-group required">
                            <label for="sector">Secteur d'activité</label>
                            <select id="sector" class="form-control" name="sector" required>
                                <option value="">Sélectionnez votre secteur</option>
                                <option value="banque">Banque et Finance</option>
                                <option value="telecom">Télécommunications</option>
                                <option value="energie">Énergie</option>
                                <option value="mines">Mines et Ressources</option>
                                <option value="commerce">Commerce et Distribution</option>
                                <option value="transport">Transport et Logistique</option>
                                <option value="agriculture">Agriculture</option>
                                <option value="education">Éducation</option>
                                <option value="sante">Santé</option>
                                <option value="technologie">Technologie</option>
                                <option value="autre">Autre</option>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-12 col-md-6">
                                <div class="form-group required">
                                    <label for="password">Mot de passe</label>
                                    <input type="password" id="password" class="form-control" name="password" placeholder="Minimum 8 caractères" required />
                                </div>
                            </div>
                            <div class="col-12 col-md-6">
                                <div class="form-group required">
                                    <label for="confirm_password">Confirmer le mot de passe</label>
                                    <input type="password" id="confirm_password" class="form-control" name="confirm_password" placeholder="Répétez votre mot de passe" required />
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="terms-acceptance">
                                <input type="checkbox" id="terms" name="terms" required>
                                <label for="terms">J'accepte les <a href="#" target="_blank">conditions d'utilisation</a> et la <a href="#" target="_blank">politique de confidentialité</a></label>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="newsletter-subscription">
                                <input type="checkbox" id="newsletter" name="newsletter" checked>
                                <label for="newsletter">Je souhaite recevoir la newsletter avec les dernières actualités et conseils business</label>
                            </div>
                        </div>

                        <button type="submit" class="small-btn btn-block">Créer mon compte</button>
                    </form>

                    <div class="login-link text-center mt-4">
                        <p>Vous avez déjà un compte ? <a href="connexion">Se connecter</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'components/side-popups.php'; ?>

<?php include 'components/footer.php'; ?>

<?php include 'components/preloader.php'; ?>

<script>
// Signup form handling
document.querySelector('.form-style-2').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const password = document.getElementById('password').value;
    const confirmPassword = document.getElementById('confirm_password').value;
    const terms = document.getElementById('terms').checked;
    
    // Validate password match
    if (password !== confirmPassword) {
        alert('Les mots de passe ne correspondent pas.');
        return;
    }
    
    // Validate password strength
    if (password.length < 8) {
        alert('Le mot de passe doit contenir au moins 8 caractères.');
        return;
    }
    
    // Validate terms acceptance
    if (!terms) {
        alert('Vous devez accepter les conditions d\'utilisation.');
        return;
    }
    
    // Collect form data
    const formData = new FormData(this);
    const userData = Object.fromEntries(formData);
    
    // Here you would typically send the data to your backend
    alert('Inscription réussie pour: ' + userData.email);
    // For demo purposes, redirect to login page
    window.location.href = 'connexion.php';
});

// Add custom styling
document.addEventListener('DOMContentLoaded', function() {
    const style = document.createElement('style');
    style.textContent = `
        .signup-form {
            background: #fff;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        
        .terms-acceptance, .newsletter-subscription {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin: 15px 0;
        }
        
        .terms-acceptance input, .newsletter-subscription input {
            margin: 0;
            margin-top: 3px;
        }
        
        .terms-acceptance label, .newsletter-subscription label {
            font-size: 14px;
            line-height: 1.4;
        }
        
        .btn-block {
            width: 100%;
            padding: 15px;
            font-size: 16px;
            margin-top: 20px;
        }
        
        .login-link a {
            color: #007bff;
            text-decoration: none;
        }
        
        .login-link a:hover {
            text-decoration: underline;
        }
        
        @media (max-width: 768px) {
            .signup-form {
                padding: 20px;
                margin: 20px;
            }
        }
    `;
    document.head.appendChild(style);
});
</script>

<?php include 'components/scripts.php'; ?>

</body>
</html>
