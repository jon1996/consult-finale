<?php
/**
 * Pricing Visit DRC Component - English Only Version
 * This component displays pricing packages exclusively in English regardless of the selected language.
 * All text content is hardcoded in English to ensure consistent display.
 */

function getPricingEnglishText($key) {
    $englishTexts = [
        'pricing.title' => 'Our DRC Visit Packages',
        'pricing.rotated_text' => 'Pricing',
        'pricing.basic.title' => 'Discovery Package',
        'pricing.basic.subtitle' => 'Free',
        'pricing.basic.price_currency' => 'USD',
        'pricing.basic.price_amount' => '0',
        'pricing.basic.price_period' => '/month',
        'pricing.basic.feature1' => 'Monthly newsletter subscription',
        'pricing.basic.feature2' => 'Logistics and regulatory news (visa, customs, security...)',
        'pricing.basic.button' => 'Book Now',
        'pricing.basic.click_hint' => 'Click to see details',
        'pricing.standard.title' => 'Practical Package',
        'pricing.standard.subtitle' => 'Priced at',
        'pricing.standard.price_currency' => 'USD',
        'pricing.standard.price_amount' => '49',
        'pricing.standard.price_period' => '/month',
        'pricing.standard.feature1' => 'Everything included in the free package',
        'pricing.standard.feature2' => 'Visa assistance (invitation letters, official form completion, legal advice)',
        'pricing.standard.feature3' => 'Organized business meetings',
        'pricing.standard.feature4' => 'Hotel recommendation and reservation (according to budget)',
        'pricing.standard.feature5' => 'WhatsApp customer service available 7 days a week before your arrival',
        'pricing.standard.button' => 'Book Now',
        'pricing.standard.click_hint' => 'Click to see details',
        'pricing.premium.title' => 'Prestige Package',
        'pricing.premium.subtitle' => 'Priced at',
        'pricing.premium.price_currency' => 'USD',
        'pricing.premium.price_amount' => '129',
        'pricing.premium.price_period' => '/week',
        'pricing.premium.feature1' => 'Complete VIP experience',
        'pricing.premium.feature2' => 'Airport pickup and private transfer',
        'pricing.premium.feature3' => 'Private transport with driver',
        'pricing.premium.feature4' => 'Exclusive access to mines/sites',
        'pricing.premium.feature5' => 'Senior consultant 24/7',
        'pricing.premium.button' => 'Book Now',
        'pricing.premium.click_hint' => 'Click to see details',
        'ui.mouse_pointer' => 'Click to see details'
    ];
    
    return isset($englishTexts[$key]) ? $englishTexts[$key] : $key;
}
?>

<!-- Pricing Packages Section -->
<div class="membership-section section" style="background-color: white;">
    <div class="container">
        <h2><span style="color: #ff6b35;"><?php echo getPricingEnglishText('pricing.title'); ?></span></h2>
        <div class="text-center price-cards">
            <h1 class="rotated-text hide-md-and-down" style="color: #ff6b35;"><?php echo getPricingEnglishText('pricing.rotated_text'); ?></h1>
            
            <!-- Basic Package -->
            <div class="price-card basic clickable-card">
                <i class="flaticon-hiring-1" style="color: #ff6b35;"></i>
                <h4><span style="color: #ff6b35;"><?php echo getPricingEnglishText('pricing.basic.title'); ?></span></h4>
                <h6><?php echo getPricingEnglishText('pricing.basic.subtitle'); ?></h6>
                <div class="price">
                    <span style="font-size: 14px; color: #ff6b35;"><?php echo getPricingEnglishText('pricing.basic.price_currency'); ?></span>
                    <h1 style="color: #ff6b35;"><?php echo getPricingEnglishText('pricing.basic.price_amount'); ?></h1>
                    <span class="time"><?php echo getPricingEnglishText('pricing.basic.price_period'); ?></span>
                </div>
                <div class="features">
                    <div class="feature">
                        <i class="fas fa-check" style="color: #ff6b35;"></i>
                        <p><?php echo getPricingEnglishText('pricing.basic.feature1'); ?></p>
                    </div>
                    <div class="feature">
                        <i class="fas fa-check" style="color: #ff6b35;"></i>
                        <p><?php echo getPricingEnglishText('pricing.basic.feature2'); ?></p>
                    </div>
                    
                </div>
                <button class="main-btn"><?php echo getPricingEnglishText('pricing.basic.button'); ?></button>
                <div class="click-hint">
                    <small><i class="fas fa-mouse-pointer" style="color: #ff6b35;"></i> <?php echo getPricingEnglishText('pricing.basic.click_hint'); ?></small>
                </div>
            </div>

            <!-- Standard Package (Featured) -->
            <div class="price-card standard active-price-card clickable-card">
                <i class="flaticon-hiring" style="color: #ff6b35;"></i>
                <h4><span style="color: #ff6b35;"><?php echo getPricingEnglishText('pricing.standard.title'); ?></span></h4>
                <h6><?php echo getPricingEnglishText('pricing.standard.subtitle'); ?></h6>
                <div class="price">
                    <span style="font-size: 14px; color: #ff6b35;"><?php echo getPricingEnglishText('pricing.standard.price_currency'); ?></span>
                    <h1 style="color: #ff6b35;"><?php echo getPricingEnglishText('pricing.standard.price_amount'); ?></h1>
                    <span class="time"><?php echo getPricingEnglishText('pricing.standard.price_period'); ?></span>
                </div>
                <div class="features">
                    <div class="feature">
                        <i class="fas fa-check" style="color: #ff6b35;"></i>
                        <p><?php echo getPricingEnglishText('pricing.standard.feature1'); ?></p>
                    </div>
                    <div class="feature">
                        <i class="fas fa-check" style="color: #ff6b35;"></i>
                        <p>Assistance à l'obtention du visa (lettres d'invitation, Remplissage des Formulaire officiels, conseil juridique)</p>
                    </div>
                    <div class="feature">
                        <i class="fas fa-check" style="color: #ff6b35;"></i>
                        <p><?php echo getPricingEnglishText('pricing.standard.feature3'); ?></p>
                    </div>
                    <div class="feature">
                        <i class="fas fa-check" style="color: #ff6b35;"></i>
                        <p>Recommandation et réservation d'hôtel (Selon le budget)</p>
                    </div>
                    <div class="feature">
                        <i class="fas fa-check" style="color: #ff6b35;"></i>
                        <p><?php echo getPricingEnglishText('pricing.standard.feature5'); ?></p>
                    </div>
                </div>
                <button class="main-btn"><?php echo getPricingEnglishText('pricing.standard.button'); ?></button>
                <div class="triangle"></div>
                <div class="click-hint">
                    <small><i class="fas fa-mouse-pointer" style="color: #ff6b35;"></i> <?php echo getPricingEnglishText('pricing.standard.click_hint'); ?></small>
                </div>
            </div>

            <!-- Premium Package -->
            <div class="price-card advanced clickable-card">
                <i class="flaticon-student" style="color: #ff6b35;"></i>
                <h4><span style="color: #ff6b35;"><?php echo getPricingEnglishText('pricing.premium.title'); ?></span></h4>
                <h6><?php echo getPricingEnglishText('pricing.premium.subtitle'); ?></h6>
                <div class="price">
                    <span style="font-size: 14px; color: #ff6b35;"><?php echo getPricingEnglishText('pricing.premium.price_currency'); ?></span>
                    <h1 style="color: #ff6b35;"><?php echo getPricingEnglishText('pricing.premium.price_amount'); ?></h1>
                    <span class="time"><?php echo getPricingEnglishText('pricing.premium.price_period'); ?></span>
                </div>
                <div class="features">
                    <div class="feature">
                        <i class="fas fa-check" style="color: #ff6b35;"></i>
                        <p><?php echo getPricingEnglishText('pricing.premium.feature1'); ?></p>
                    </div>
                    <div class="feature">
                        <i class="fas fa-check" style="color: #ff6b35;"></i>
                        <p><?php echo getPricingEnglishText('pricing.premium.feature2'); ?></p>
                    </div>
                    <div class="feature">
                        <i class="fas fa-check" style="color: #ff6b35;"></i>
                        <p><?php echo getPricingEnglishText('pricing.premium.feature3'); ?></p>
                    </div>
                    <div class="feature">
                        <i class="fas fa-check" style="color: #ff6b35;"></i>
                        <p><?php echo getPricingEnglishText('pricing.premium.feature4'); ?></p>
                    </div>
                    <div class="feature">
                        <i class="fas fa-check" style="color: #ff6b35;"></i>
                        <p><?php echo getPricingEnglishText('pricing.premium.feature5'); ?></p>
                    </div>
                </div>
                <button class="main-btn"><?php echo getPricingEnglishText('pricing.premium.button'); ?></button>
                <div class="click-hint">
                    <small><i class="fas fa-mouse-pointer" style="color: #ff6b35;"></i> <?php echo getPricingEnglishText('pricing.premium.click_hint'); ?></small>
                </div>
            </div>
        </div>
        
        

<!-- Custom CSS for Enhanced Pricing Popup Functionality -->
<style>
/* Clickable pricing cards */
.clickable-card {
    cursor: pointer;
    transition: all 0.3s ease;
    position: relative;
}

.clickable-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.15);
}

.clickable-card:active {
    transform: translateY(-2px);
}

/* Click hint styling */
.click-hint {
    position: absolute;
    bottom: 10px;
    left: 50%;
    transform: translateX(-50%);
    opacity: 0.7;
    transition: opacity 0.3s ease;
}

.clickable-card:hover .click-hint {
    opacity: 1;
}

.click-hint small {
    color: #666;
    font-size: 11px;
    display: flex;
    align-items: center;
    gap: 5px;
}

.click-hint i {
    font-size: 10px;
    animation: pointPulse 2s infinite;
}

@keyframes pointPulse {
    0%, 100% { transform: scale(1); opacity: 0.7; }
    50% { transform: scale(1.1); opacity: 1; }
}

/* Enhanced active card styling */
.active-price-card {
    border: 2px solid #007bff;
    background: linear-gradient(135deg, #f8f9ff 0%, #ffffff 100%);
}

.active-price-card .triangle {
    border-bottom-color: #007bff;
}

/* Table transition effects */
.package-tables {
    opacity: 0;
    max-height: 0;
    overflow: hidden;
    transition: all 0.5s ease;
    transform: translateY(-20px);
}

.package-tables.show {
    opacity: 1;
    max-height: 1000px;
    transform: translateY(0);
    margin-top: 30px;
}

/* Enhanced table styling */
.basic-package-table {
    background: white;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 5px 20px rgba(0, 0, 0, 0.1);
}

.basic-package-table thead th {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    font-weight: 600;
    padding: 15px;
    border: none;
}

.basic-package-table tbody tr:nth-child(even) {
    background-color: #f8f9fa;
}

.basic-package-table tbody tr:hover {
    background-color: #e3f2fd;
    transition: background-color 0.3s ease;
}

.basic-package-table tbody th {
    font-weight: 500;
    color: #2c3e50;
    padding: 12px 15px;
}

.basic-package-table tbody td {
    padding: 12px 15px;
    text-align: center;
}

.basic-package-table .fas.fa-check {
    color: #28a745;
    font-size: 16px;
}

.basic-package-table .fas.fa-times {
    color: #dc3545;
    font-size: 16px;
}

/* Mobile responsiveness for tables */
@media (max-width: 768px) {
    .package-tables {
        display: block !important;
    }
    
    .basic-package-table {
        font-size: 14px;
    }
    
    .basic-package-table th,
    .basic-package-table td {
        padding: 8px 10px;
    }
}

/* Loading animation for table transitions */
.package-tables.loading {
    opacity: 0.5;
}

.package-tables.loading::after {
    content: '';
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    width: 30px;
    height: 30px;
    border: 3px solid #f3f3f3;
    border-top: 3px solid #3498db;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: translate(-50%, -50%) rotate(0deg); }
    100% { transform: translate(-50%, -50%) rotate(360deg); }
}

/* Enhanced button styling for active cards */
.active-price-card .main-btn {
    background: linear-gradient(135deg, #007bff, #0056b3);
    transform: scale(1.05);
    box-shadow: 0 5px 15px rgba(0, 123, 255, 0.3);
}

.active-price-card .main-btn:hover {
    transform: scale(1.08);
    box-shadow: 0 8px 25px rgba(0, 123, 255, 0.4);
}
</style>
