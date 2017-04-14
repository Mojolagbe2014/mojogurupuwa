<nav class="navbar-default navbar-side" role="navigation">
            <div class="sidebar-collapse">
                <ul class="nav" id="main-menu">
                    <li class="text-center"> <a href="profile" title="My Profile"><img src="assets/img/find_user.png" class="user-image img-responsive"/></a> </li>
                    <li> <a href="index"><i class="fa fa-dashboard fa-2x"></i> Dashboard</a> </li>
                    <?php if(isset($_SESSION['ITCadminRole']) && $_SESSION['ITCadminRole'] =='Admin' ) { ?>
                    <li> <a href="#"><i class="fa fa-user fa-2x"></i>Admin Manager<span class="fa arrow"></span></a>
                        <ul class="nav nav-third-level">
                            <li> <a href="add-admin">Add Site Admin</a> </li>
                            <li> <a href="manage-admins">Manage Site Admins</a> </li>
                        </ul>
                    </li>
                    <?php } ?>
                    <li> <a href="#"><i class="fa fa-briefcase fa-2x"></i>Publication Categories<span class="fa arrow"></span></a>
                        <ul class="nav nav-third-level">
<!--                            <li> <a href="add-category">Add Publication Category</a> </li>-->
                            <li> <a href="manage-categories">Manage Categories</a> </li>
                        </ul>
                    </li>
                    <li> <a href="#"><i class="fa fa-book fa-2x"></i> Publications Manager<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="add-publication">Add Publication</a> </li>
                            <li> <a href="manage-publications">Manage Publications</a> </li>
                        </ul>
                    </li>  
                    <li> <a href="#"><i class="fa fa-folder-open fa-2x"></i>Research Projects<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="add-project">Add Research Project</a> </li>
                            <li> <a href="manage-projects">Manage Research  Projects</a> </li>
                        </ul>
                    </li>  
                    <li> <a href="#"><i class="fa fa-file fa-2x"></i>Presentations Manager<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="add-presentation">Add Presentation</a> </li>
                            <li> <a href="manage-presentations">Manage Presentations</a> </li>
                        </ul>
                    </li>  
                    <li> <a href="#"><i class="fa fa-certificate fa-2x"></i> Patents Manager<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li> <a href="manage-brochures">Manage Brochures</a> </li>
                        </ul>
                    </li>
                    <li> <a href="#"><i class="fa fa-user fa-2x"></i> Subscribers<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li> <a href="manage-subscribers">Manage Subscribers</a> </li>
                        </ul>
                    </li>
                    <li> <a href="#"><i class="fa fa-users fa-2x"></i> Team Members<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li><a href="add-tutor">Add Member</a> </li>
                            <li> <a href="manage-tutors">Manage Members</a> </li>
                        </ul>
                    </li>
                    <li> <a href="#"><i class="fa fa-question-circle fa-2x"></i> FAQs Manager<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li> <a href="manage-faq">Add FAQ</a> </li>
                            <li> <a href="manage-faq">Manage FAQs</a> </li>
                        </ul>
                    </li>
                    <li> <a href="#"><i class="fa fa-bell-o fa-2x"></i> Upcoming Events Manager<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li> <a href="manage-events">Add New Event</a> </li>
                            <li> <a href="manage-events">Manage Events</a> </li>
                        </ul>
                    </li>
                    <li> <a href="#"><i class="fa fa-apple fa-2x"></i> Sponsors/Partners/Clients<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li> <a href="manage-sponsors">Add Partners/Sponsors</a> </li>
                            <li> <a href="manage-sponsors">Manage Partners/Sponsors</a> </li>
                        </ul>
                    </li>
                    <li> <a href="#"><i class="fa fa-quote-left fa-2x"></i> Quote Manager<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li> <a href="manage-quotes">Add Quote</a> </li>
                            <li> <a href="manage-quotes">Manage Quotes</a> </li>
                        </ul>
                    </li>
                    <li> <a href="#"><i class="fa fa-picture-o fa-2x"></i> Gallery Manager<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li> <a href="add-gallery-image">Add Images</a> </li>
                            <li> <a href="manage-gallery">Manage Gallery</a> </li>
                        </ul>
                    </li>
                    <li> <a href="#"><i class="fa fa-video-camera fa-2x"></i> Video Manager<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li> <a href="manage-videos">Add Video</a> </li>
                            <li> <a href="manage-videos">Manage Videos</a> </li>
                        </ul>
                    </li>
                    <?php if(isset($_SESSION['ITCadminEmail']) && $_SESSION['ITCadminEmail'] == trim(stripcslashes(strip_tags(Setting::getValue($dbObj, 'COMPANY_EMAIL'))))) { ?>
                    <li> <a href="#"><i class="fa fa-cog fa-2x"></i>Settings Manager<span class="fa arrow"></span></a>
                        <ul class="nav nav-second-level">
                            <li> <a href="manage-settings"><i class="fa fa-cogs fa-1x"></i> General Settings</a> </li>
                            <li> <a href="manage-webpages"><i class="fa fa-globe fa-1x"></i> Manage Web Pages</a> </li>
                        </ul>
                    </li>
                    <?php } ?>
                    <li>
                        <a  href="profile"><i class="fa fa-book fa-2x"></i> My Profile</a>
                    </li>
                    <li>
                        <a  href="#" class="logout"><i class="fa fa-sign-out fa-2x"></i> Logout</a>
                    </li>
                </ul>
            </div>
        </nav>  