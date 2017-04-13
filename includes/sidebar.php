<div class="sidebar widget_area bg_tint_light sidebar_style_light" role="complementary">
    <aside id="woocommerce_product_categories-3" class="widget_number_1 widget woocommerce widget_product_categories">
        <h5 class="widget_title">Categories</h5>
        <ul class="product-categories">
            <?php 
            $sideCategoryObj = new CourseCategory($dbObj);
            foreach ($sideCategoryObj->fetchRaw("*", " 1=1 ", " name ASC LIMIT 10") as $category) {
            $categoryData = array('id' => 'id', 'name' => 'name', 'image' => 'image', 'description' => 'description');
            foreach ($categoryData as $key => $value){
                switch ($key) { 
                    case 'image': $sideCategoryObj->$key = MEDIA_FILES_PATH1.'category/'.$category[$value];break;
                    default     :   $sideCategoryObj->$key = $category[$value]; break; 
                }
            }
            ?>
            <li class="cat-item cat-item-65"><a href="<?php echo SITE_URL.'courses/category/'.$sideCategoryObj->id.'/'.StringManipulator::slugify($sideCategoryObj->name).'/'; ?>"><?php echo $sideCategoryObj->name; ?></a> <span class="count">(<?php echo Course::getSingleCategoryCount($dbObj, $sideCategoryObj->id); ?>)</span></li>
            <?php } ?>
        </ul>
    </aside>
    
    <aside id="woocommerce_products-3" class="widget_number_3 widget woocommerce widget_products">
        <h5 class="widget_title">Recent Courses</h5>
        <ul class="product_list_widget">
            <?php 
            $sideCourseObj = new Course($dbObj);
            foreach ($sideCourseObj->fetchRaw("*", " status =1 AND CURRENT_DATE <= start_date ", " RAND() LIMIT 4 ") as $sideCourse) {
                $courseData = array('id' => 'id', 'name' => 'name', 'code' => 'code', 'image' => 'image', 'media' => 'media', 'amount' => 'amount', 'shortName' => 'short_name', 'category' => 'category', 'startDate' => 'start_date', 'endDate' => 'end_date', 'description' => 'description', 'status' => 'status', 'featured' => 'featured', 'currency' => 'currency');
                foreach ($courseData as $key => $value){
                    switch ($key) { 
                        case 'image': $sideCourseObj->$key = MEDIA_FILES_PATH1.'course-image/'.$sideCourse[$value];break;
                        case 'media': $sideCourseObj->$key = MEDIA_FILES_PATH1.'course/'.$sideCourse[$value];break;
                        case 'startDate': $dateParam = explode('-', $sideCourse[$value]);
                                          $dateObj   = DateTime::createFromFormat('!m', $dateParam[1]);
                                          $sideCourseObj->$key = '<i class="fa fa-calendar"></i> '.$dateParam[2].' '.substr($dateObj->format('F'), 0, 3).', '.$dateParam[0].'.';;
                                          break;
                        case 'endDate': $dateParam = explode('-', $sideCourse[$value]);
                                          $dateObj   = DateTime::createFromFormat('!m', $dateParam[1]);
                                          $sideCourseObj->$key = $dateParam[2].' '.$dateObj->format('F').', '.$dateParam[0].'.';;
                                          break;
                        default     :   $sideCourseObj->$key = $sideCourse[$value]; break; 
                    }
                }
            ?>
            <li>
                <a href="<?php echo SITE_URL.'course/'.$sideCourseObj->id.'/'.StringManipulator::slugify($sideCourseObj->name).'/'; ?>" title="<?php echo $sideCourseObj->name; ?>">
                    <img width="150" height="150" src="<?php echo $sideCourseObj->image; ?>" class="attachment-shop_thumbnail wp-post-image" alt="<?php echo $sideCourseObj->name; ?>" />		
                    <span class="product-title"><?php echo $sideCourseObj->name; ?></span>
                </a>
                <span class="amount"><?php echo $sideCourseObj->currency.  number_format($sideCourseObj->amount); ?></span>
            </li>
            <?php } ?>
        </ul>
    </aside>
    
    
    <aside id="woocommerce_top_rated_products-3" class="widget_number_5 widget woocommerce widget_top_rated_products">
        <h5 class="widget_title">Upcoming Events</h5>
        <ul class="product_list_widget">
            <?php 
            $sideEventObj = new Event($dbObj);
            foreach ($sideEventObj->fetchRaw("*", " NOW() <= date_time AND status = 1 ", " RAND() LIMIT 2 ") as $sideEvent) {
                $dateTimeObj = explode(' ', $sideEvent['date_time']);
                $dateParam = explode('/', $dateTimeObj[0]);
                $dateObj   = DateTime::createFromFormat('!m', $dateParam[1]);
                $sideEvent['date_time'] = '<i class="fa fa-calendar"></i> '.$dateParam[2].' '.substr($dateObj->format('F'), 0, 3).', '.$dateParam[0].'. <br/><i class="fa fa-clock-o"></i> '.$dateTimeObj[1];
            ?>
            <li>
                <a href="<?php echo SITE_URL; ?>event/<?php echo $sideEvent['id'].'/'.StringManipulator::slugify($sideEvent['name']); ?>/">
                    <img width="150" height="150" src="<?php echo MEDIA_FILES_PATH1; ?>event/<?php echo $sideEvent['image']; ?>" class="attachment-shop_thumbnail wp-post-image" alt="<?php echo $sideEvent['name']; ?>" />		
                    <span class="product-title"><?php echo $sideEvent['name']; ?></span>
                </a>
                <span class="amount"><?php //echo $sideEvent['date_time']; ?></span>
            </li>
            <?php } ?>
        </ul>
    </aside>	
</div> <!-- /.sidebar -->