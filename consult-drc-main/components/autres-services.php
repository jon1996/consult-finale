<!-- Autres Services Component -->
<div class="autres-services-section section" style="background-color: white;">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="sector-header text-center">
                    <h1 class="sector-title" style="color: #2c3e50; font-weight: 700; font-size: 2.2rem; margin-bottom: 0.2em;">
                        <?php echo __('autres_services.title'); ?>
                    </h1>
                    <p class="subtitle" style="color: #ff6b35; font-weight: 600; font-size: 1.2rem; font-style: italic;">
                        <?php echo __('autres_services.subtitle'); ?>
                    </p>
                </div>
            </div>
        </div>

        <div class="row mt-5">
            <div class="col-12 col-lg-10 mx-auto">
                <div class="content-wrapper">
                    <!-- Removed heading and intro per request -->

                    <ul style="list-style:none; padding-left:0; margin-top:25px;">
                        <li style="margin-bottom:12px;"><?php echo __('autres_services.bullet_1'); ?></li>
                        <li style="margin-bottom:12px;"><?php echo __('autres_services.bullet_2'); ?></li>
                        <li style="margin-bottom:12px;"><?php echo __('autres_services.bullet_3'); ?></li>
                        <li style="margin-bottom:12px;"><?php echo __('autres_services.bullet_4'); ?></li>
                        <li style="margin-bottom:12px;"><?php echo __('autres_services.bullet_5'); ?></li>
                    </ul>

                    <!-- Local specific-service CTA removed; keeping only main CTA -->
                </div>
            </div>
        </div>

        <!-- Shared CTA replicated from Doing Business -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="cta-section text-center">
                    <h3><span style="color: #ff6b35;"><?php echo __('doing_business.cta_title'); ?></span></h3>
                    <p><?php echo __('doing_business.cta_text'); ?></p>
                    <div class="cta-buttons">
                        <a href="connexion" class="btn btn-primary">
                            <i class="fas fa-user-tie mr-2"></i><?php echo __('doing_business.cta_primary_button'); ?>
                        </a>
                        <a href="#" class="btn btn-outline-secondary">
                            <i class="fas fa-download mr-2"></i><?php echo __('doing_business.cta_secondary_button'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.autres-services-section { padding:80px 0; background:#f8f9fa; }
.autres-services-section .content-wrapper p { font-size:1.05rem; line-height:1.55; color:#2c3e50; }
.autres-services-section ul li { font-size:1.0rem; line-height:1.5; }
.autres-services-section .cta-local-services h4 { margin-bottom:10px; }
.autres-services-section .cta-local-services p { margin-bottom:18px; }
/* Reuse CTA styles if not already globally available */
/* Match Doing Business CTA styles */
.autres-services-section .cta-section {background:linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);padding:40px;border-radius:15px;box-shadow:0 10px 30px rgba(0,0,0,0.1);} 
.autres-services-section .cta-section h3 {color:#2c3e50;font-weight:600;font-size:1.6rem;margin-bottom:15px;} 
.autres-services-section .cta-section p {color:#666;font-size:1rem;margin-bottom:30px;line-height:1.6;} 
.autres-services-section .cta-buttons {display:flex;gap:15px;justify-content:center;flex-wrap:wrap;} 
.autres-services-section .cta-buttons .btn {padding:12px 25px;font-weight:500;border-radius:6px;transition:all 0.3s ease;font-size:0.95rem;min-width:200px;margin:0 10px;} 
.autres-services-section .cta-buttons .btn-primary {background:#ff6b35;border-color:#ff6b35;} 
.autres-services-section .cta-buttons .btn-primary:hover {background:#e55a2b;border-color:#e55a2b;transform:translateY(-2px);box-shadow:0 5px 15px rgba(255,107,53,0.3);} 
.autres-services-section .cta-buttons .btn-outline-secondary {border-color:#ff6b35;color:#ff6b35;} 
.autres-services-section .cta-buttons .btn-outline-secondary:hover {background:#ff6b35;border-color:#ff6b35;transform:translateY(-2px);box-shadow:0 5px 15px rgba(255,107,53,0.3);color:#fff;} 
.autres-services-section .back-link:hover {text-decoration:underline;}
@media (max-width: 767px){
    .autres-services-section .cta-section {padding:45px 22px;}
    .autres-services-section .cta-section h3 {font-size:1.55rem;}
    .autres-services-section .cta-section p {font-size:1rem;}
    .autres-services-section .cta-buttons .btn {width:100%;}
}
@media (max-width: 767px){
  .autres-services-section { padding:60px 0; }
  .autres-services-section .sector-title { font-size:1.8rem; }
}
</style>
