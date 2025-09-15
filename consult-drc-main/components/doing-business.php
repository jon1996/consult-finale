<!-- Doing Business Component -->
<div class="doing-business-section section" style="background-color: white;">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="sector-header text-center">
                    <p class="subtitle" style="color: #ff6b35; font-weight: 600;"><?php echo __('doing_business.subtitle'); ?></p>
                </div>
            </div>
        </div>
        <!-- Services Section (full width, after business overview) -->
        <div class="row mt-5">
            <div class="col-12">
                <div class="services-section">
                    <h3 class="section-title services-business-title">
                        <span class="services-icon" aria-hidden="true">
                            <!-- Professional SVG: dual gear + briefcase integration -->
                            <svg class="services-icon-svg" width="44" height="32" viewBox="0 0 44 32" fill="none" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Icon">
                              <title>Business Services</title>
                              <!-- Briefcase base -->
                              <rect x="14" y="10" width="24" height="16" rx="2.5" stroke="#ff6b35" stroke-width="2" fill="rgba(255,107,53,0.08)"/>
                              <path d="M18 10v-1.2A2.8 2.8 0 0 1 20.8 6h10.4A2.8 2.8 0 0 1 34 8.8V10" stroke="#ff6b35" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                              <path d="M26 18.5c-.9 0-1.6-.7-1.6-1.6v-1.3h3.2v1.3c0 .9-.7 1.6-1.6 1.6Z" fill="#ff6b35"/>
                              <path d="M14 16h24" stroke="#ff6b35" stroke-width="2" stroke-linecap="round"/>
                              <!-- Large gear (left) -->
                              <g transform="translate(2 4)">
                                <circle cx="7.5" cy="7.5" r="4.4" stroke="#ff6b35" stroke-width="2" fill="white"/>
                                <path d="M7.5 1.2v1.6M7.5 12.2v1.6M1.2 7.5h1.6M12.2 7.5h1.6M3 3l1.2 1.2M10.8 10.8l1.2 1.2M3 12l1.2-1.2M10.8 4.2l1.2-1.2" stroke="#ff6b35" stroke-width="1.8" stroke-linecap="round"/>
                                <circle cx="7.5" cy="7.5" r="1.9" fill="#ff6b35"/>
                              </g>
                              <!-- Small gear (accent) -->
                              <g transform="translate(30 20) scale(.55)">
                                <circle cx="7.5" cy="7.5" r="4.4" stroke="#ff6b35" stroke-width="2" fill="white"/>
                                <path d="M7.5 1.2v1.6M7.5 12.2v1.6M1.2 7.5h1.6M12.2 7.5h1.6M3 3l1.2 1.2M10.8 10.8l1.2 1.2M3 12l1.2-1.2M10.8 4.2l1.2-1.2" stroke="#ff6b35" stroke-width="1.6" stroke-linecap="round"/>
                                <circle cx="7.5" cy="7.5" r="1.8" fill="#ff6b35"/>
                              </g>
                            </svg>
                        </span>
                        <span class="services-text"><?php echo __('doing_business.services_business_heading'); ?></span>
                    </h3>
                    <div class="row justify-content-center">
                        <div class="col-md-10">
                            <p class="custom-service-intro"><?php echo __('doing_business.services_intro'); ?></p>
                            <ul class="custom-service-list">
                                <li><span class="svg-bullet"> <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="11" cy="11" r="10" fill="#ff6b35"/><path d="M7 11.5L10 14.5L15 9.5" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg> </span><?php echo __('doing_business.service_item_company_creation'); ?> ;</li>
                                <li><span class="svg-bullet"> <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="11" cy="11" r="10" fill="#ff6b35"/><path d="M7 11.5L10 14.5L15 9.5" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg> </span><?php echo __('doing_business.service_item_licenses'); ?> ;</li>
                                <li><span class="svg-bullet"> <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="11" cy="11" r="10" fill="#ff6b35"/><path d="M7 11.5L10 14.5L15 9.5" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg> </span><?php echo __('doing_business.service_item_compliance'); ?> ;</li>
                                <li><span class="svg-bullet"> <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg"><circle cx="11" cy="11" r="10" fill="#ff6b35"/><path d="M7 11.5L10 14.5L15 9.5" stroke="#fff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/></svg> </span><?php echo __('doing_business.service_item_accounting'); ?> ;</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-5">
            <div class="col-12">
                <div class="sectors-overview" id="secteurs-prioritaires">
                    <h3 class="text-center mb-4"><i class="fas fa-industry" style="color: #ff6b35;"></i> <span style="color: #ff6b35;"><?php echo __('doing_business.sectors_title'); ?></span></h3>
                    <div class="row">
                        <div class="col-12 col-md-6 col-lg-4">
                            <a href="mines" class="sector-card-link">
                                <div class="sector-card">
                                    <i class="fas fa-pickaxe" style="color: #ff6b35;"></i>
                                    <h5><span style="color: #ff6b35;"><?php echo __('sectors.mining.title'); ?></span></h5>
                                    <p><?php echo __('doing_business.mineral_desc_full'); ?></p>
                                    <div class="learn-more-btn"><?php echo __('doing_business.learn_more'); ?></div>
                                </div>
                            </a>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4">
                            <a href="hydrocarbures" class="sector-card-link">
                                <div class="sector-card">
                                    <i class="fas fa-oil-well" style="color: #ff6b35;"></i>
                                    <h5><span style="color: #ff6b35;"><?php echo __('sectors.hydrocarbon.title'); ?></span></h5>
                                    <p><?php echo __('doing_business.hydrocarbon_desc'); ?></p>
                                    <div class="learn-more-btn"><?php echo __('doing_business.learn_more'); ?></div>
                                </div>
                            </a>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4">
                            <a href="energies" class="sector-card-link">
                                <div class="sector-card">
                                    <i class="fas fa-bolt" style="color: #ff6b35;"></i>
                                    <h5><span style="color: #ff6b35;"><?php echo __('sectors.energy.title'); ?></span></h5>
                                    <p><?php echo __('doing_business.energy_desc'); ?></p>
                                    <div class="learn-more-btn"><?php echo __('doing_business.learn_more'); ?></div>
                                </div>
                            </a>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4">
                            <a href="autres-services" class="sector-card-link">
                                <div class="sector-card">
                                    <i class="fas fa-concierge-bell" style="color: #ff6b35;"></i>
                                    <h5><span style="color: #ff6b35;"><?php echo __('autres_services.title'); ?></span></h5>
                                    <p><?php echo __('autres_services.intro_short'); ?></p>
                                    <div class="learn-more-btn"><?php echo __('doing_business.learn_more'); ?></div>
                                </div>
                            </a>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4">
                            <a href="transport" class="sector-card-link">
                                <div class="sector-card">
                                    <i class="fas fa-truck" style="color: #ff6b35;"></i>
                                    <h5><span style="color: #ff6b35;"><?php echo __('sectors.transport.title'); ?></span></h5>
                                    <p><?php echo __('doing_business.transport_desc'); ?></p>
                                    <div class="learn-more-btn"><?php echo __('doing_business.learn_more'); ?></div>
                                </div>
                            </a>
                        </div>
                        <div class="col-12 col-md-6 col-lg-4">
                            <a href="assurance" class="sector-card-link">
                                <div class="sector-card">
                                    <i class="fas fa-shield-alt" style="color: #ff6b35;"></i>
                                    <h5><span style="color: #ff6b35;"><?php echo __('sectors.insurance.title'); ?></span></h5>
                                    <p><?php echo __('doing_business.insurance_desc'); ?></p>
                                    <div class="learn-more-btn"><?php echo __('doing_business.learn_more'); ?></div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-5">
            <div class="col-12">
                <div class="cta-section text-center">
                    <h3><?php echo __('doing_business.cta_title'); ?></h3>
                    <p><?php echo __('doing_business.cta_text'); ?></p>
                    <div class="cta-buttons">
                        <a href="http://127.0.0.1/app/accounts/login/" class="btn btn-primary btn-lg">
                            <i class="fas fa-user-tie mr-2" style="color: #ff6b35;"></i><?php echo __('doing_business.cta_primary_button'); ?>
                        </a>
                        <a href="#" class="btn btn-outline-secondary btn-lg">
                            <i class="fas fa-download mr-2" style="color: #ff6b35;"></i><?php echo __('doing_business.cta_secondary_button'); ?>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom CSS for Doing Business Component -->
<style>
.doing-business-section {
    background: #f8f9fa;
    padding: 80px 0;
}

.sector-header h2 {
    color: #2c3e50;
    font-weight: 700;
    font-size: 2.5rem;
    margin-bottom: 15px;
}

.sector-header .subtitle {
    color: #667eea;
    font-size: 1.2rem;
    font-weight: 500;
    font-style: italic;
}

.services-section {
    background: white;
    padding: 40px;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    height: 100%;
    margin-bottom: 30px;
    text-align: center;
}

.services-section h3 {
    color: #2c3e50;
    font-weight: 700;
    margin-bottom: 20px;
}

.services-section h3 i {
    color: #667eea;
    margin-right: 10px;
}

.service-category {
    margin-bottom: 30px;
}

.service-category h4 {
    color: #34495e;
    font-weight: 600;
    margin-bottom: 15px;
    border-bottom: 2px solid #ff6b35;
    padding-bottom: 8px;
    transition: all 0.3s ease;
}

.service-list {
    list-style: none;
    padding: 0;
}

.service-list li {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 8px;
    color: #2c3e50;
    justify-content: center;
    text-align: center;
    font-size: 1.13rem; /* Increased font size for service list items */
}

.service-list li i {
    color: #ff6b35;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.service-list li:hover i {
    transform: scale(1.2);
}

.custom-service-intro {
  font-family: var(--main-font, inherit);
  font-size: 1.22rem;
  font-weight: 500;
  margin-bottom: 1.3rem;
  color: #222;
  text-align:center;
}
.custom-service-list {
  list-style: none;
  padding-left: 0;
  font-family: var(--main-font, inherit);
  font-size: 1.18rem;
  margin-top: 1.5rem;
  text-align:center;
}
.custom-service-list li {
  display: flex;
  align-items: flex-start;
  margin-bottom: 1.1em;
  font-family: var(--main-font, inherit);
  font-size: 1.18rem;
  color: #222;
  justify-content:center;
  text-align:center;
}
.custom-service-list .svg-bullet {
  display: inline-flex;
  margin-right: 0.75em;
  flex-shrink: 0;
  margin-top: 0.15em;
}

.sectors-overview {
    background: white;
    padding: 50px;
    border-radius: 20px;
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
}

.sectors-overview h3 {
    color: #2c3e50;
    font-weight: 700;
    font-size: 1.8rem;
}

.sectors-overview h3 i {
    color: #667eea;
    margin-right: 15px;
}

.sector-card-link {
    text-decoration: none;
    color: inherit;
    display: block;
    margin-bottom: 20px;
}

.sector-card-link:hover {
    text-decoration: none;
    color: inherit;
}

.sector-card {
    background: #f8f9fa;
    padding: 30px 20px;
    border-radius: 15px;
    text-align: center;
    height: 100%;
    transition: all 0.3s ease;
    border: 2px solid transparent;
    position: relative;
}

.sector-card:hover {
    transform: translateY(-5px);
    border-color: #ff6b35;
    background: white;
    box-shadow: 0 10px 30px rgba(255, 107, 53, 0.15);
}

.sector-card i {
    font-size: 2.5rem;
    margin-bottom: 20px;
    transition: all 0.3s ease;
}

.sector-card:hover i {
    transform: scale(1.1);
    color: #ff6b35 !important;
}

.sector-card h5 {
    font-weight: 600;
    margin-bottom: 15px;
    transition: all 0.3s ease;
}

.sector-card:hover h5 span {
    color: #ff6b35 !important;
}

.learn-more-btn {
    background: #ff6b35;
    color: white;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 0.8rem;
    font-weight: 500;
    margin-top: 15px;
    display: inline-block;
    transition: all 0.3s ease;
}

.learn-more-btn:hover {
    background: #e55a2b;
    transform: scale(1.05);
    box-shadow: 0 5px 15px rgba(255, 107, 53, 0.3);
}

.cta-section {
    background: linear-gradient(135deg, #f8f9fa 0%, #ffffff 100%);
    padding: 40px;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
}

.cta-section h3 {
    color: #2c3e50;
    font-weight: 600;
    font-size: 1.6rem;
    margin-bottom: 15px;
}

.cta-section p {
    color: #666;
    font-size: 1rem;
    margin-bottom: 30px;
    line-height: 1.6;
}

.cta-buttons {
    display: flex;
    gap: 15px;
    justify-content: center;
    flex-wrap: wrap;
}

.cta-buttons .btn {
    padding: 12px 25px;
    font-weight: 500;
    border-radius: 6px;
    transition: all 0.3s ease;
    font-size: 0.95rem;
    min-width: 200px;
    margin: 0 10px;
}

.cta-buttons .btn-primary {
    background: #ff6b35;
    border-color: #ff6b35;
}

.cta-buttons .btn-primary:hover {
    background: #e55a2b;
    border-color: #e55a2b;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 107, 53, 0.3);
}

.cta-buttons .btn-outline-secondary {
    border-color: #ff6b35;
    color: #ff6b35;
}

.cta-buttons .btn-outline-secondary:hover {
    background: #ff6b35;
    border-color: #ff6b35;
    transform: translateY(-2px);
    box-shadow: 0 5px 15px rgba(255, 107, 53, 0.3);
}

.services-business-title {display:flex; flex-direction:row; align-items:center; justify-content:center; gap:0.75rem; font-size:2.1rem; font-weight:500; color:#ff6b35 !important; text-align:center; margin-bottom:1.75rem;}
.services-business-title .services-icon-svg {display:block; height:auto;}
.services-business-title .services-icon {display:inline-flex; line-height:0;}
.services-business-title .services-text {font-family: 'Montserrat', var(--main-font, inherit); letter-spacing:.5px; color:#ff6b35 !important;}
@media (max-width: 767.98px){ .services-business-title{font-size:1.65rem;} .services-business-title .services-icon-svg{width:38px;} }

@media (max-width: 768px) {
    .doing-business-section {
        padding: 50px 0;
    }
    
    .sector-header h2 {
        font-size: 2rem;
    }
    
    .services-section,
    .sectors-overview {
        padding: 25px;
    }
    
    .cta-section {
        padding: 30px 20px;
    }
    
    .cta-section h3 {
        font-size: 1.4rem;
    }
    
    .cta-buttons {
        flex-direction: column;
        align-items: center;
        gap: 12px;
    }
    
    .cta-buttons .btn {
        width: 90%;
        max-width: 280px;
        min-width: auto;
    }
}
</style>
