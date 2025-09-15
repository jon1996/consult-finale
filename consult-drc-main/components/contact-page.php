<!-- Contact Page Component -->
<div class="contact-page-section section" style="background-color: white;">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="sector-header text-center">
                    <h1 class="sector-title" style="color: #2c3e50; font-weight: 700; font-size: 2.2rem; margin-bottom: 0.2em;">
                        <?php echo __('contact_page.page_title'); ?>
                    </h1>
                    <p class="subtitle" style="color: #ff6b35; font-weight: 600; font-size: 1.2rem; font-style: italic;">
                        <?php echo __('contact_page.hero_subtitle'); ?>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Contact Information Section -->
<div class="contact-info-section section">
    <div class="container">
        <div class="row">
            <div class="col-12 col-lg-6">
                <div class="office-info">
                    <h3><i class="fas fa-building"></i> <?php echo __('contact_page.main_office'); ?></h3>
                    <div class="info-item">
                        <i class="fas fa-map-marker-alt"></i>
                        <span><?php echo __('contact_page.main_office_address'); ?></span>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-phone"></i>
                        <span><?php echo __('footer.phone'); ?></span>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-envelope"></i>
                        <span><?php echo __('footer.email'); ?></span>
                    </div>
                    <div class="info-item">
                        <i class="fas fa-clock"></i>
                        <div>
                            <strong><?php echo __('contact_page.office_hours'); ?></strong><br>
                            <?php echo __('contact_page.monday_friday'); ?><br>
                            <?php echo __('contact_page.saturday'); ?><br>
                            <?php echo __('contact_page.sunday_closed'); ?>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="col-12 col-lg-6">
                <div class="additional-info">
                    <h3><i class="fas fa-info-circle"></i> Additional Information</h3>
                    
                    <div class="info-item">
                        <i class="fas fa-language"></i>
                        <div>
                            <strong><?php echo __('contact_page.languages_spoken'); ?></strong><br>
                            <span><?php echo __('contact_page.languages_desc'); ?></span>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Removed contact form and maps per request -->

<!-- Custom CSS for Contact Page Component -->
<style>
.contact-page-section {
    background: #f8f9fa;
    padding: 80px 0;
}

.sector-header h1 {
    color: #2c3e50;
    font-weight: 700;
    font-size: 2.2rem;
    margin-bottom: 0.2em;
}

.sector-header .subtitle {
    color: #ff6b35;
    font-size: 1.2rem;
    font-weight: 600;
    font-style: italic;
}

.office-info h3 i,
.additional-info h3 i {
    color: #ff6b35;
}

.contact-info-section {
    background: #f8f9fa;
    padding: 80px 0;
}

.office-info,
.additional-info {
    background: white;
    padding: 40px;
    border-radius: 15px;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
    height: 100%;
}

.office-info h3,
.additional-info h3 {
    color: #2c3e50;
    font-weight: 700;
    margin-bottom: 30px;
}

.info-item {
    display: flex;
    align-items: flex-start;
    gap: 15px;
    margin-bottom: 20px;
    padding: 15px 0;
    border-bottom: 1px solid #e9ecef;
}

.info-item:last-child {
    border-bottom: none;
}

.info-item i {
    color: #ff6b35;
    font-size: 1.2rem;
    margin-top: 3px;
    min-width: 20px;
}

.info-item span,
.info-item div {
    color: #2c3e50;
    line-height: 1.5;
}

.info-item strong {
    color: #2c3e50;
    font-weight: 600;
}

@media (max-width: 768px) {
    .contact-page-section,
    .contact-info-section {
        padding: 50px 0;
    }

    .sector-header h1 {
        font-size: 2rem;
    }
    .office-info,
    .additional-info {
        padding: 25px;
    }
}
</style>
<!-- Removed testimonial script as testimonials are no longer displayed -->
