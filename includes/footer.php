<?php if(strpos($_SERVER['SCRIPT_NAME'],'index.php')){ } ?>
<div class="call-out">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-12">
                <figure class="logo">
                    <a href="<?php echo SITE_URL; ?>"><img src="<?php echo SITE_URL; ?>images/logo/logo2.png" alt=""></a>
                </figure>
            </div>
            <div class="col-md-9 col-sm-12">
                <div class="float_left"><h4></h4> </div>
                <div class="float_right"></div>
            </div>
        </div>
                
    </div>
</div>

<footer class="main-footer">
    
    <!--Widgets Section-->
    <div class="widgets-section">
        <div class="container">
            <div class="row">
                <!--Big Column-->
                <div class="big-column col-md-6 col-sm-12 col-xs-12">
                    <div class="row clearfix">
                        
                        <!--Footer Column-->
                        <div class="footer-column col-md-6 col-sm-6 col-xs-12">
                            <div class="footer-widget about-widget">
                                <h3 class="footer-title">About Us</h3>
                                
                                <div class="widget-content">
                                    <div class="text"><p class="text-justify"><?php echo StringManipulator::trimStringToFullWord(250, trim(stripcslashes(strip_tags(Setting::getValue($dbObj, 'ABOUT_US'))))); ?></p> </div>
                                    <div class="link"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!--Big Column-->
                <div class="big-column col-md-6 col-sm-12 col-xs-12">
                    <div class="row clearfix">
                        <!--Footer Column-->
                        <div class="footer-column col-md-6 col-sm-6 col-xs-12">
                            <div class="footer-widget contact-widget">
                                <h3 class="footer-title">Contact us</h3>
                                <div class="widget-content">
                                    <ul class="contact-info">
                                        <li><span class="icon-signs"></span><?php echo GROUP_ADDRESS; ?></li>
                                        <li><span class="icon-phone-call"></span> Phone: <?php echo GROUP_HOTLINE; ?></li>
                                        <li><span class="icon-e-mail-envelope"></span><?php echo GROUP_EMAIL; ?></li>
                                    </ul>
                                </div>
                                <ul class="social">
                                    <li><a href="<?php echo FACEBOOK_LINK; ?>" target="_blank" rel="nofollow"><i class="fa fa-facebook"></i></a></li>
                                    <li><a href="<?php echo TWITTER_LINK; ?>" target="_blank" rel="nofollow"><i class="fa fa-twitter"></i></a></li>
                                    <li><a href="<?php echo GOOGLEPLUS_LINK; ?>" target="_blank" rel="nofollow"><i class="fa fa-google-plus"></i></a></li>
                                    <li><a href="<?php echo LINKEDIN_LINK; ?>" target="_blank" rel="nofollow"><i class="fa fa-linkedin"></i></a></li>
                                </ul>
                            </div>
                        </div>

                        <!--Footer Column-->
                        <div class="footer-column col-md-6 col-sm-6 col-xs-12">
                            <div class="footer-widget news-widget">
                                <h3 class="footer-title">Newsletter</h3>
                                <div class="widget-content">
                                    <!--Post-->
                                    <div class="text"><p>Sign up today for newsletter</p></div>
                                    <!--Post-->
                                    <form action="<?php echo SITE_URL.'REST/subscribe.php'; ?>" class="default-form">
                                        <input type="email" id="subscriberEmail" name="subscriberEmail" value="" required="required" placeholder="Email Address">
                                        <button type="submit"  name="subscriberSubmit" class="thm-btn">Subscribe Now!</button>
                                    </form>
                                </div>
                                
                            </div>
                        </div>                     
                        
                    </div>
                </div>
                
             </div>
         </div>
     </div>
     
     <!--Footer Bottom-->
     <section class="footer-bottom">
        <div class="container">
            <div class="pull-left copy-text">
                <p>Copyrights © <?php $currYear   = new DateTime(); echo $currYear->format('Y'); ?> All Rights Reserved.
                <?php echo Setting::getValue($dbObj, 'ADDTHIS_SHARE_BUTTON') ? Setting::getValue($dbObj, 'ADDTHIS_SHARE_BUTTON') : ''; ?>
            </div><!-- /.pull-right --><ul></ul></div><!-- /.pull-left -->
        </div><!-- /.container -->
    </section>
     
</footer>

<!-- Scroll Top Button -->
    <button class="scroll-top tran3s color2_bg">
        <span class="fa fa-angle-up"></span>
    </button>
    <!-- pre loader  -->
    <div class="preloader"></div>