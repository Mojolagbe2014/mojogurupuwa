<div>
<?php if(isset($_SESSION['msg'])) {  ?>
<script src="<?php echo SITE_URL; ?>sweet-alert/sweetalert.min.js" type="text/javascript"></script>
<script>
    swal({
        title: "Message Box!",
        text: '<?php echo $_SESSION['msg']; ?>',
        confirmButtonText: "Okay",
        customClass: 'twitter',
        html: true,
        type: '<?php echo $_SESSION['msgStatus']; ?>'
    });
</script>
<?php  unset($_SESSION['msg']); unset($_SESSION['msgStatus']);  } ?>
</div>
<header class="top-bar">
    <div class="container">
        <div class="clearfix">
            <div class="col-left float_left">
                <ul class="top-bar-info">
                    <li><i class="icon-technology"></i>Phone: <?php echo GROUP_HOTLINE; ?></li>
                    <li><i class="icon-note2"></i><a class="text-white" href="mailto:<?php echo GROUP_EMAIL; ?>"><?php echo GROUP_EMAIL; ?></a></li>
                </ul>
            </div>
            <div class="col-right float_right">
                <ul class="social">
                    <li>Stay Connected: </li>
                    <li><a href="<?php echo FACEBOOK_LINK; ?>" target="_blank" rel="nofollow"><i class="fa fa-facebook"></i></a></li>
                    <li><a href="<?php echo LINKEDIN_LINK; ?>" target="_blank" rel="nofollow"><i class="fa fa-linkedin"></i></a></li>
                    <li><a href="<?php echo TWITTER_LINK; ?>" target="_blank" rel="nofollow"><i class="fa fa-twitter"></i></a></li>
                </ul>
                <div class="link">
                    <a href="<?php echo SITE_URL; ?>solvers" class="thm-btn underProcessing">use our em-solver</a>
                </div>
            </div>
                
                
        </div>
            

    </div>
</header>

<section class="theme_menu stricky">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <div class="main-logo">
                    <a href="<?php echo SITE_URL; ?>"><img src="<?php echo SITE_URL; ?>images/logo/logo.png" alt=""></a>
                </div>
            </div>
            <div class="col-md-8 menu-column">
                <nav class="menuzord" id="main_menu">
                   <ul class="menuzord-menu">
                        <li class="<?php echo $thisPage->active($_SERVER['SCRIPT_NAME'], 'index', 'active'); ?>"><a href="<?php echo SITE_URL; ?>">Home</a></li>

                        <li class="<?php echo $thisPage->active($_SERVER['REQUEST_URI'], 'publication', 'active'); ?>"><a href="<?php echo SITE_URL; ?>publications/">Publications</a></li>

                        <li class="<?php echo $thisPage->active($_SERVER['REQUEST_URI'], 'project', 'active'); ?>"><a href="<?php echo SITE_URL; ?>projects/">Projects</a></li>

                        <li class="<?php echo $thisPage->active($_SERVER['REQUEST_URI'], 'member', 'active'); ?>"><a href="<?php echo SITE_URL; ?>members/">Members</a></li>

                        <li class="<?php echo $thisPage->active($_SERVER['REQUEST_URI'], 'gallery', 'active'); ?>"><a href="<?php echo SITE_URL; ?>contact/">Contact</a></li>
                        
                        <?php $linkArray = array('gallery', 'presentation', 'patent', 'about', 'video', 'sponsor', 'new', 'faq'); ?>
                        <li class="<?php echo $thisPage->active($_SERVER['REQUEST_URI'], $linkArray, 'active'); ?>"><a href="#">More</a>
                        <ul class="dropdown">
                            <li><a href="<?php echo SITE_URL; ?>presentations/">Presentations</a></li>
                            <li><a href="<?php echo SITE_URL; ?>patents/">Patents</a></li>
                            <li><a href="<?php echo SITE_URL; ?>about/">About Me</a></li>
                            <li><a href="<?php echo SITE_URL; ?>gallery">Gallery</a></li>
                            <li><a href="<?php echo SITE_URL; ?>videos/">Course Videos</a></li>
                            <li><a href="<?php echo SITE_URL; ?>sponsors/">Sponsors/Partners</a></li>
                            <li><a href="<?php echo SITE_URL; ?>news/">News/Events</a></li>
                            <li><a href="<?php echo SITE_URL; ?>faq/">FAQ’s</a></li>
                         </ul>
                        </li>
                        
                        
                    </ul><!-- End of .menuzord-menu -->
                </nav> <!-- End of #main_menu -->
            </div>
            <div class="right-column">
                <div class="right-area">
                    <div class="nav_side_content">
                        <div class="search_option">
                            <button class="search tran3s dropdown-toggle color1_bg" id="searchDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><i class="fa fa-search" aria-hidden="true"></i></button>
                            <form action="<?php echo SITE_URL; ?>search" class="dropdown-menu" aria-labelledby="searchDropdown">
                                <input type="text" placeholder="Search...">
                                <button><i class="fa fa-search" aria-hidden="true"></i></button>
                            </form>
                       </div>
                   </div>
                </div>
                    
            </div>
        </div>
                

   </div> <!-- End of .conatiner -->
</section>