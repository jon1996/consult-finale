<?php include 'components/head.php'; ?>
<body>

<?php include 'components/navbar-desktop.php'; ?>

<?php include 'components/nav-mob-2.php'; ?>

<!-- Breadcrumb -->
<div class="breadcrub-style-5 section">
    <div class="container">
        <div class="heading">
            <h1>Connexion</h1>
        </div>
    </div>
</div>

<!-- Signin Section -->
<section class="sign-in-section section">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-6 sign-in">
                <h2>Bienvenue chez DRC Business Consult</h2>
                <div class="sign-with-social">
                    <h6>Se connecter avec</h6>
                    <div class="social-links">
                        <a target="_blank" href="#"><i class="fab fa-facebook-square"></i></a>
                        <a target="_blank" href="#"><i class="fab fa-twitter"></i></a>
                        <a target="_blank" href="#"><i class="fab fa-linkedin"></i></a>
                    </div>
                </div>
                <div class="sign-with-email">
                    <h6>Ou connectez-vous avec votre email</h6>
                    <form action="#" class="form-style-2" method="POST">
                        <div class="form-group required">
                            <label for="email_address">Adresse email</label>
                            <input type="email" id="email_address" class="form-control" name="email" placeholder="votre.email@exemple.com" required />
                        </div>

                        <div class="form-group required">
                            <label for="password">Mot de passe</label>
                            <input type="password" id="password" class="form-control" name="password" placeholder="*****************" required>
                        </div>
                        
                        <div class="form-options">
                            <div class="remember-me">
                                <input type="checkbox" id="remember" name="remember">
                                <label for="remember">Se souvenir de moi</label>
                            </div>
                            <span class="fp"><a href="#" onclick="forgotPassword()">Mot de passe oublié ?</a></span>
                        </div>
                        
                        <button type="submit" class="enhanced-login-btn">
                            <span>Se connecter</span>
                            <i class="fas fa-arrow-right"></i>
                        </button>
                        
                        <div class="signup-link-section">
                            <p>Vous n'avez pas encore de compte ?</p>
                            <a href="inscription" class="signup-link">
                                <i class="fas fa-user-plus"></i>
                                Créer un compte gratuitement
                            </a>
                        </div>
                    </form>
                </div>
            </div>
            
        </div>
    </div>
</section>

<!-- Additional Login Options -->
<section class="additional-options section" style="padding: 40px 0; background-color: #f8f9fa;">
    <div class="container">
        <div class="row text-center">
            <div class="col-12">
                <h4>Vous êtes une entreprise ?</h4>
                <p>Découvrez nos solutions dédiées aux entreprises congolaises</p>
                <div class="enterprise-buttons">
                    <a href="#" class="btn btn-outline-primary me-3">Solutions Entreprise</a>
                    <a href="contact" class="btn btn-primary">Nous contacter</a>
                </div>
            </div>
        </div>
    </div>
</section>

<?php include 'components/side-popups.php'; ?>

<?php include 'components/footer.php'; ?>

<?php include 'components/preloader.php'; ?>

<script>
// Enhanced login form handling
document.querySelector('.form-style-2').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const email = document.getElementById('email_address').value;
    const password = document.getElementById('password').value;
    const submitBtn = this.querySelector('.enhanced-login-btn');
    
    // Add loading state
    const originalContent = submitBtn.innerHTML;
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Connexion en cours...';
    submitBtn.disabled = true;
    
    // Simulate login process
    setTimeout(() => {
        if (email && password) {
            alert('Tentative de connexion pour: ' + email);
            // For demo purposes, redirect to index.php after "login"
            // window.location.href = 'index.php';
        } else {
            alert('Veuillez remplir tous les champs obligatoires.');
        }
        
        // Reset button
        submitBtn.innerHTML = originalContent;
        submitBtn.disabled = false;
    }, 1500);
});

function forgotPassword() {
    const email = prompt('Veuillez entrer votre adresse email pour recevoir un lien de réinitialisation:');
    if (email) {
        alert('Un lien de réinitialisation a été envoyé à: ' + email);
    }
}

// Add some styling for the new elements
document.addEventListener('DOMContentLoaded', function() {
    // Style the form options
    const style = document.createElement('style');
    style.textContent = `
        .form-options {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 15px 0;
            flex-wrap: wrap;
        }
        
        .remember-me {
            display: flex;
            align-items: center;
            gap: 8px;
        }
        
        .remember-me input[type="checkbox"] {
            margin: 0;
        }
        
        /* Enhanced Login Button */
        .enhanced-login-btn {
            width: 100%;
            height: 55px;
            background: linear-gradient(135deg, #3498db, #2980b9);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 18px;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            margin: 25px 0;
            transition: all 0.3s ease;
            cursor: pointer;
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
        }
        
        .enhanced-login-btn:hover {
            background: linear-gradient(135deg, #2980b9, #1f618d);
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(52, 152, 219, 0.4);
        }
        
        .enhanced-login-btn:active {
            transform: translateY(0);
            box-shadow: 0 4px 15px rgba(52, 152, 219, 0.3);
        }
        
        .enhanced-login-btn i {
            transition: transform 0.3s ease;
        }
        
        .enhanced-login-btn:hover i {
            transform: translateX(3px);
        }
        
        /* Signup Link Section */
        .signup-link-section {
            text-align: center;
            padding: 25px 0;
            border-top: 1px solid #eee;
            margin-top: 20px;
        }
        
        .signup-link-section p {
            color: #666;
            margin-bottom: 15px;
            font-size: 15px;
        }
        
        .signup-link {
            display: inline-flex;
            align-items: center;
            gap: 10px;
            padding: 12px 25px;
            background: linear-gradient(135deg, #e74c3c, #c0392b);
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: 0 3px 12px rgba(231, 76, 60, 0.3);
        }
        
        .signup-link:hover {
            color: white;
            background: linear-gradient(135deg, #c0392b, #a93226);
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(231, 76, 60, 0.4);
            text-decoration: none;
        }
        
        .signup-link:active {
            transform: translateY(0);
        }
        
        /* Improved form styling */
        .form-control {
            height: 50px;
            border: 2px solid #e1e8ed;
            border-radius: 8px;
            padding: 0 15px;
            font-size: 16px;
            transition: all 0.3s ease;
        }
        
        .form-control:focus {
            border-color: #3498db;
            box-shadow: 0 0 0 0.2rem rgba(52, 152, 219, 0.25);
        }
        
        .form-group label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
        }
        
        .benefits-list {
            list-style: none;
            padding: 0;
            margin: 20px 0;
        }
        
        .benefits-list li {
            padding: 8px 0;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .benefits-list i {
            color: #28a745;
        }
        
        .enterprise-buttons {
            margin-top: 20px;
        }
        
        .enterprise-buttons .btn {
            margin: 5px;
            padding: 12px 25px;
            border-radius: 8px;
            font-weight: 600;
            transition: all 0.3s ease;
        }
        
        .enterprise-buttons .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.15);
        }
        
        @media (max-width: 768px) {
            .form-options {
                flex-direction: column;
                align-items: flex-start;
                gap: 10px;
            }
            
            .enhanced-login-btn {
                height: 50px;
                font-size: 16px;
            }
            
            .signup-link {
                font-size: 14px;
                padding: 10px 20px;
            }
        }
    `;
    document.head.appendChild(style);
});
</script>

<?php include 'components/scripts.php'; ?>

</body>
</html>
