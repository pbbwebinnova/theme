<?php
if (file_exists(TEMPLATEPATH.'/page.php')) {
    include(get_page_template());
}
elseif (file_exists(TEMPLATEPATH.'/single.php')) {
    include(get_single_template());
}
else {
    include(TEMPLATEPATH.'/index.php');
}

add_action('get_header', 'custom_page_remove_filters');
add_action('get_sidebar', 'custom_page_remove_filters');
add_action('get_footer', 'custom_page_remove_filters');
function custom_page_remove_filters() {
    remove_filter('the_title', 'custom_page_title');
    remove_filter('the_content', 'custom_page_content');
}
 
add_action('loop_start', 'custom_page_add_filters');
function custom_page_add_filters() {
    add_filter('the_title', 'custom_page_title');
    add_filter('the_content', 'custom_page_content');
}