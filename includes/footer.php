<?php if(strpos($_SERVER['SCRIPT_NAME'],'index.php')){ ?>
<footer class="footer_wrap bg_tint_light footer_style_white widget_area">
                <div class="content_wrap">
                    <div class="columns_wrap">
                        <aside id="wp-calendar-1773395015" class="widget_number_1 column-2_3 widget widget_calendar">
                            <h5 class="widget_title">Courses Calendar</h5>
                            <?php
                            echo $calendar->show();
                            ?>
                        </aside><aside id="themerex_widget_top10-4" class="widget_number_1 column-1_3 widget widget_top10 not-calendar">
                            <?php 
                            $widgetCourArr = array('Public', 'Private');
                            $widgetCourse = rand(0, 1);
                            ?>
                            <h5 class="widget_title">Private Sector Courses</h5>
                            <?php 
                            foreach ($courseObj->fetchRaw("*", " status = 1 AND featured = 1 AND CURRENT_DATE <= start_date ", " RAND() LIMIT 2 ") as $course) {
                                $courseData = array('id' => 'id', 'name' => 'name', 'code' => 'code', 'image' => 'image', 'media' => 'media', 'amount' => 'amount', 'shortName' => 'short_name', 'category' => 'category', 'startDate' => 'start_date', 'endDate' => 'end_date', 'description' => 'description', 'status' => 'status', 'currency' => 'currency');
                                foreach ($courseData as $key => $value){
                                    switch ($key) { 
                                        case 'image': $courseObj->$key = MEDIA_FILES_PATH1.'course-image/'.$course[$value];break;
                                        case 'media': $courseObj->$key = MEDIA_FILES_PATH1.'course/'.$course[$value];break;
                                        case 'startDate': $dateParam = explode('-', $course[$value]);
                                                          $dateObj   = DateTime::createFromFormat('!m', $dateParam[1]);
                                                          $courseObj->$key = $dateParam[2].' '.$dateObj->format('F').', '.$dateParam[0].'.';;
                                                          break;
                                        case 'endDate': $dateParam = explode('-', $course[$value]);
                                                          $dateObj   = DateTime::createFromFormat('!m', $dateParam[1]);
                                                          $courseObj->$key = $dateParam[2].' '.$dateObj->format('F').', '.$dateParam[0].'.';;
                                                          break;
                                        default     :   $courseObj->$key = $course[$value]; break; 
                                    }
                                }
                            ?>
                            <article class="post_item with_thumb first">
                                <div class="post_thumb">
                                    <img class="wp-post-image" width="75" height="75" alt="<?php echo $courseObj->name; ?>" src="<?php echo $courseObj->image; ?>">
                                </div>
                                <div class="post_content">
                                    <h6 class="post_title">
                                        <a href="<?php echo SITE_URL.'course/'.$courseObj->id.'/'.StringManipulator::slugify($courseObj->name).'/'; ?>"><?php echo $courseObj->name; ?></a>
                                    </h6>
                                    <div class="post_info">
                                        <span class="post_info_item post_info_posted">
                                            <a href="<?php echo SITE_URL.'course/'.$courseObj->id.'/'.StringManipulator::slugify($courseObj->name).'/'; ?>" class="post_info_date"><?php echo $courseObj->startDate; ?></a>
                                        </span>
                                        <span class="post_info_item post_info_posted_by"> |  
                                            <a href="<?php echo SITE_URL.'category/'.$courseObj->category.'/'.StringManipulator::slugify(CourseCategory::getName($dbObj, $courseObj->category)).'/'; ?>" class="post_info_author"><?php echo CourseCategory::getName($dbObj, $courseObj->category); ?></a>
                                        </span>
                                        <span class="post_info_item post_info_counters">
                                            <a href="<?php echo SITE_URL.'course/'.$courseObj->id.'/'.StringManipulator::slugify($courseObj->name).'/'; ?>" class="post_counters_item post_counters_rating icon-star-1">
                                                <span class="post_counters_number"><?php echo $courseObj->currency.' '.  number_format($courseObj->amount, 2); ?></span>
                                            </a>
                                                
                                        </span>
                                    </div>
                                </div>
                            </article>
                            <?php } ?>
                            <h5 class="widget_title" style="margin-top:10px;">Public Sector Courses</h5>
                            <?php 
                            foreach ($courseObj->fetchRaw("*", " status = 1 AND featured = 0 AND CURRENT_DATE <= start_date ", " RAND() LIMIT 2 ") as $course) {
                                $courseData = array('id' => 'id', 'name' => 'name', 'code' => 'code', 'image' => 'image', 'media' => 'media', 'amount' => 'amount', 'shortName' => 'short_name', 'category' => 'category', 'startDate' => 'start_date', 'endDate' => 'end_date', 'description' => 'description', 'status' => 'status', 'currency' => 'currency');
                                foreach ($courseData as $key => $value){
                                    switch ($key) { 
                                        case 'image': $courseObj->$key = MEDIA_FILES_PATH1.'course-image/'.$course[$value];break;
                                        case 'media': $courseObj->$key = MEDIA_FILES_PATH1.'course/'.$course[$value];break;
                                        case 'startDate': $dateParam = explode('-', $course[$value]);
                                                          $dateObj   = DateTime::createFromFormat('!m', $dateParam[1]);
                                                          $courseObj->$key = $dateParam[2].' '.$dateObj->format('F').', '.$dateParam[0].'.';;
                                                          break;
                                        case 'endDate': $dateParam = explode('-', $course[$value]);
                                                          $dateObj   = DateTime::createFromFormat('!m', $dateParam[1]);
                                                          $courseObj->$key = $dateParam[2].' '.$dateObj->format('F').', '.$dateParam[0].'.';;
                                                          break;
                                        default     :   $courseObj->$key = $course[$value]; break; 
                                    }
                                }
                            ?>
                            <article class="post_item with_thumb first">
                                <div class="post_thumb">
                                    <img class="wp-post-image" width="75" height="75" alt="<?php echo $courseObj->name; ?>" src="<?php echo $courseObj->image; ?>">
                                </div>
                                <div class="post_content">
                                    <h6 class="post_title">
                                        <a href="<?php echo SITE_URL.'course/'.$courseObj->id.'/'.StringManipulator::slugify($courseObj->name).'/'; ?>"><?php echo $courseObj->name; ?></a>
                                    </h6>
                                    <div class="post_info">
                                        <span class="post_info_item post_info_posted">
                                            <a href="<?php echo SITE_URL.'course/'.$courseObj->id.'/'.StringManipulator::slugify($courseObj->name).'/'; ?>" class="post_info_date"><?php echo $courseObj->startDate; ?></a>
                                        </span>
                                        <span class="post_info_item post_info_posted_by"> |  
                                            <a href="<?php echo SITE_URL.'category/'.$courseObj->category.'/'.StringManipulator::slugify(CourseCategory::getName($dbObj, $courseObj->category)).'/'; ?>" class="post_info_author"><?php echo CourseCategory::getName($dbObj, $courseObj->category); ?></a>
                                        </span>
                                        <span class="post_info_item post_info_counters">
                                            <a href="<?php echo SITE_URL.'course/'.$courseObj->id.'/'.StringManipulator::slugify($courseObj->name).'/'; ?>" class="post_counters_item post_counters_rating icon-star-1">
                                                <span class="post_counters_number"><?php echo $courseObj->currency.' '.  number_format($courseObj->amount, 2); ?></span>
                                            </a>
                                                
                                        </span>
                                    </div>
                                </div>
                            </article>
                            <?php } ?>
                        </aside>
                        
                    </div>	<!-- /.columns_wrap -->
                </div>	<!-- /.content_wrap -->
            </footer>	<!-- /.footer_wrap -->
            <footer class="testimonials_wrap sc_section bg_tint_dark" style="background-color:#1eaace;">
                <div class="sc_section_overlay" data-bg_color="#1eaace" data-overlay="0">
                    <div class="content_wrap">
                        <p class="sc_testimonials" style="text-align: center">QUOTES</p>
                        <div id="sc_testimonials_231119059" class="sc_testimonials sc_slider_swiper swiper-slider-container sc_slider_nopagination sc_slider_controls" data-interval="6238" style="width:100%;">
                            <div class="slides swiper-wrapper">
                                <?php foreach($quoteObj->fetchRaw("*", " 1=1 ", " RAND() LIMIT 10") as $quote) { ?>
                                <div class="swiper-slide" data-style="width:100%;" style="width:100%;">
                                    <div class="sc_testimonial_item">
                                        <div class="sc_testimonial_avatar">
                                            <img class="wp-post-image" width="70" height="70" alt="<?php echo $quote['content']; ?>" src="<?php echo MEDIA_FILES_PATH1.'quote/'.$quote['image']; ?>">
                                        </div>
                                        <div class="sc_testimonial_content">
                                            <p><?php echo $quote['content']; ?></p>
                                        </div>
                                        <div class="sc_testimonial_author">
                                            <a href="#"><?php echo $quote['author']; ?></a>
                                        </div>
                                    </div>
                                </div>
                                <?php } ?>
                            </div>
                            <div class="sc_slider_controls_wrap"><a class="sc_slider_prev" href="#"></a><a class="sc_slider_next" href="#"></a></div>
                        </div>
                    </div>
                </div>
            </footer>
            <?php } ?>
            <footer class="contacts_wrap bg_tint_dark contacts_style_dark">
                <div class="content_wrap">
                    <div class="logo">
                        <a href="<?php echo SITE_URL; ?>"><img src="<?php echo SITE_URL; ?>images/logo-dark.png" alt="" style="width:402px;width:180px "></a>
                    </div>
                    <div class="contacts_address">
                        <address class="address_right">
                            Phone: <a href="tel:<?php echo COMPANY_HOTLINE; ?>"><?php echo COMPANY_HOTLINE; ?></a>, <?php echo COMPANY_NUMBERS; ?>			<br/><br/>
                        Email:  <a href="mailto:<?php echo COMPANY_EMAIL; ?>"><?php echo COMPANY_EMAIL; ?></a>, <?php echo COMPANY_OTHER_EMAILS; ?></address>
                        <address class="address_left">
                        <?php echo COMPANY_ADDRESS; ?>							</address>
                    </div>
                    <div class="sc_socials sc_socials_size_big">
                        <div class="sc_socials_item">
                            <a href="<?php echo FACEBOOK_LINK; ?>" target="_blank" class="social_icons social_facebook" style="background-image: url(<?php echo SITE_URL; ?>themes/education/fw/images/socials/facebook.png);">
                                <span class="sc_socials_hover" style="background-image: url(<?php echo SITE_URL; ?>themes/education/fw/images/socials/facebook.png);"></span>
                            </a>
                        </div>
<!--                        <div class="sc_socials_item">
                            <a href="<?php //echo PINTEREST_LINK; ?>" target="_blank" class="social_icons social_pinterest" style="background-image: url(<?php //echo SITE_URL; ?>themes/education/fw/images/socials/pinterest.png);"><span class="sc_socials_hover" style="background-image: url(<?php //echo SITE_URL; ?>themes/education/fw/images/socials/pinterest.png);"></span></a>
                        </div>-->
                        <div class="sc_socials_item">
                            <a href="<?php echo TWITTER_LINK; ?>" target="_blank" class="social_icons social_twitter" style="background-image: url(<?php echo SITE_URL; ?>themes/education/fw/images/socials/twitter.png);">
                                <span class="sc_socials_hover" style="background-image: url(<?php echo SITE_URL; ?>themes/education/fw/images/socials/twitter.png);"></span>
                            </a>
                        </div>
                        <div class="sc_socials_item">
                            <a href="<?php echo GOOGLEPLUS_LINK; ?>" target="_blank" class="social_icons social_gplus" style="background-image: url(<?php echo SITE_URL; ?>themes/education/fw/images/socials/gplus.png);">
                                <span class="sc_socials_hover" style="background-image: url(<?php echo SITE_URL; ?>themes/education/fw/images/socials/gplus.png);"></span>
                            </a>
                        </div>
                            <div class="sc_socials_item">
                                <a href="<?php echo LINKEDIN_LINK; ?>" target="_blank" class="social_icons social_rss" style="background-image: url(<?php echo SITE_URL; ?>themes/education/fw/images/socials/linkedin.png);">
                                    <span class="sc_socials_hover" style="background-image: url(<?php echo SITE_URL; ?>themes/education/fw/images/socials/linkedin.png);"></span>
                                </a>
                            </div>
<!--                        <div class="sc_socials_item">
                            <a href="<?php //echo DRIBBBLE_LINK; ?>" target="_blank" class="social_icons social_dribbble" style="background-image: url(<?php //echo SITE_URL; ?>themes/education/fw/images/socials/dribbble.png);"><span class="sc_socials_hover" style="background-image: url(<?php //echo SITE_URL; ?>themes/education/fw/images/socials/dribbble.png);"></span>
                            </a>
                        </div>-->
                    </div>						
                </div>	<!-- /.content_wrap -->
            </footer>	<!-- /.contacts_wrap -->

            <div class="copyright_wrap">
                <div class="content_wrap">
                    <p>&copy; <?php $currYear   = new DateTime(); echo $currYear->format('Y'); ?> All Rights Reserved.</p> 
                </div>
            </div>
            <?php echo Setting::getValue($dbObj, 'ADDTHIS_SHARE_BUTTON') ? Setting::getValue($dbObj, 'ADDTHIS_SHARE_BUTTON') : ''; ?>
            
