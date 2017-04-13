<?php if(SETTINGS_PANEL == 'TRUE'){ ?>
<div class="custom_options_shadow"></div>

    <div id="custom_options" class="custom_options co_light">

    <a href="#" id="co_toggle" class="icon-params"></a>

    <div class="co_header">
    <div class="co_title">
    <span>Style switcher</span>
    <a href="#" id="co_theme_reset" class="co_reset icon-retweet-1" title="Reset to defaults">RESET</a>
    </div>
    </div>

    <div id="sc_custom_scroll" class="co_options sc_scroll sc_scroll_vertical">
    <div class="sc_scroll_wrapper swiper-wrapper">
    <div class="sc_scroll_slide swiper-slide">
    <input type="hidden" id="co_site_url" name="co_site_url" value="homepage-3/" />

    <div class="co_section">
    <div class="co_label">Body styles</div>
    <div class="co_switch_box co_switch_horizontal co_switch_columns_3" data-options="body_style">
    <div class="switcher" data-value="fullscreen"></div>
    <a href="#" data-value="boxed">Boxed</a>
    <a href="#" data-value="wide">Wide</a>
    <a href="#" data-value="fullscreen">Fullscreen</a>
    </div>
    </div>

    <div class="co_section">
    <div class="co_label">Color settings</div>
    <div class="co_colorpic_list">
    <div class="iColorPicker" data-options="link_color" data-value="#f55c6d"><span>Link color</span></div>
    <div class="iColorPicker" data-options="menu_color" data-value="#26c3d6"><span>Menu color</span></div>
    <div class="iColorPicker" data-options="user_color" data-value="#2d3e50"><span>User color</span></div>
    </div>
    </div>

    <div class="co_section">
    <div class="co_label">Background pattern</div>
    <div id="co_bg_pattern_list" class="co_image_check" data-options="bg_pattern">
    <a href="#" id="pattern_1" class="co_pattern_wrapper" style="background-image: url(<?php echo SITE_URL; ?>themes/education/images/bg/pattern_1_thumb.jpg)"><span class="co_bg_preview" style="background-image: url(<?php echo SITE_URL; ?>themes/education/images/bg/pattern_1.jpg)"></span></a>
    <a href="#" id="pattern_2" class="co_pattern_wrapper" style="background-image: url(<?php echo SITE_URL; ?>themes/education/images/bg/pattern_2_thumb.jpg)"><span class="co_bg_preview" style="background-image: url(<?php echo SITE_URL; ?>themes/education/images/bg/pattern_2.jpg)"></span></a>
    <a href="#" id="pattern_3" class="co_pattern_wrapper" style="background-image: url(<?php echo SITE_URL; ?>themes/education/images/bg/pattern_3_thumb.jpg)"><span class="co_bg_preview" style="background-image: url(<?php echo SITE_URL; ?>themes/education/images/bg/pattern_3.jpg)"></span></a>
    <a href="#" id="pattern_4" class="co_pattern_wrapper" style="background-image: url(<?php echo SITE_URL; ?>themes/education/images/bg/pattern_4_thumb.jpg)"><span class="co_bg_preview" style="background-image: url(<?php echo SITE_URL; ?>themes/education/images/bg/pattern_4.jpg)"></span></a>
    <a href="#" id="pattern_5" class="co_pattern_wrapper" style="background-image: url(<?php echo SITE_URL; ?>themes/education/images/bg/pattern_5_thumb.jpg)"><span class="co_bg_preview" style="background-image: url(<?php echo SITE_URL; ?>themes/education/images/bg/pattern_5.jpg)"></span></a>
    </div>
    </div>

    <div class="co_section">
    <div class="co_label">Background image</div>
    <div id="co_bg_images_list" class="co_image_check" data-options="bg_image">
    <a href="#" id="pattern_1" class="co_image_wrapper" style="background-image: url(<?php echo SITE_URL; ?>themes/education/images/bg/image_1_thumb.jpg)"><span class="co_bg_preview" style="background-image: url(<?php echo SITE_URL; ?>themes/education/images/bg/image_1.jpg)"></span></a>
    <a href="#" id="pattern_2" class="co_image_wrapper" style="background-image: url(<?php echo SITE_URL; ?>themes/education/images/bg/image_2_thumb.jpg)"><span class="co_bg_preview" style="background-image: url(<?php echo SITE_URL; ?>themes/education/images/bg/image_2.jpg)"></span></a>
    <a href="#" id="pattern_3" class="co_image_wrapper" style="background-image: url(<?php echo SITE_URL; ?>themes/education/images/bg/image_3_thumb.jpg)"><span class="co_bg_preview" style="background-image: url(<?php echo SITE_URL; ?>themes/education/images/bg/image_3.jpg)"></span></a>
    </div>
    </div>

    </div><!-- .sc_scroll_slide -->
    </div><!-- .sc_scroll_wrapper -->
    <div id="sc_custom_scroll_bar" class="sc_scroll_bar sc_scroll_bar_vertical sc_custom_scroll_bar"></div>
    </div><!-- .sc_scroll -->
    </div><!-- .custom_options -->
<?php } ?>