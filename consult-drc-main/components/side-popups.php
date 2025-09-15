    <!-- Popup -->
    <div class="side-form-popup">
            <div class="popup-wrapper">
                <div class="center-popup">
                        <div class="opening-popup">
                                <div class="close-popup">
                                    <i class="fas fa-times"></i>
                                </div>
                                <div class="row">
                                    <div class="col-md-6 col-lg-8 media">
                                        <img src="assets/open.png" alt="média"
                                            class="img-fluid">
                                    </div>
                                    <div class="col-md-6 col-lg-4 content">
                                        <h5><?php echo __('side_popup.opening_hours'); ?></h5>
                                        <div class="openings">
                                            <div class="opening opening-days">
                                                <p class="day"><?php echo __('side_popup.monday'); ?></p>
                                                <div class="divider"></div>
                                                <p class="day"><?php echo __('side_popup.friday'); ?></p>
                                            </div>
                                            <div class="opening opening-time">
                                                <p class="time">08h00</p>
                                                <div class="divider"></div>
                                                <p class="time">16h00</p>
                                            </div>
                                        </div>
                                        <p class="num">+243 81-868-0496</p>
                                    </div>
                                </div>
                            </div>
                            <div class="location-popup">
                                <div class="close-popup">
                                    <i class="fas fa-times"></i>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-md-6 col-lg-8 media">
                                        <div id="location-map"></div>
                                    </div>
                                    <div class="col-12 col-md-6 col-lg-4 content">
                                        <h5><?php echo __('side_popup.address_title'); ?></h5>
                                        <p class="address"><?php echo __('side_popup.address_line'); ?></p>
                                        <a href="#" class="main-btn open-maps" role="button" aria-label="<?php echo __('side_popup.open_maps'); ?>" data-lat="-20.0052713" data-lng="57.6413037" data-address="<?php echo htmlspecialchars('4260/A, Avenue de la Mission, Quartier Révolution, Gombe, Kinshasa, RDC', ENT_QUOTES, 'UTF-8'); ?>" target="_blank" rel="noopener">
                                            <?php echo __('side_popup.open_maps'); ?>
                                        </a>
                                    </div>
                                </div>
                            </div>
                            <div class="form-popup">
                                <div class="close-popup">
                                    <i class="fas fa-times"></i>
                                </div>
                                <div class="row">
                                    <div class="col-12 col-lg-7 content">
                                        <h4><?php echo __('side_popup.contact_prompt_title'); ?></h4>
                                        <p><?php echo __('side_popup.contact_prompt_text'); ?></p>

                                    </div>
                                    <div class="d-none d-lg-block col-lg-5 media">
                                        <img src="assets/email.jpg"
                                            alt="image média" class="img-fluid" />
                                    </div>
                                </div>
                            </div>
                </div>
            </div>
        </div>
