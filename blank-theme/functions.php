<?php

/**
* Wordpress Naked, a very minimal wordpress theme designed to be used as a base for other themes.
*
* @licence LGPL
* @author Darren Beale - http://siftware.co.uk - bealers@gmail.com - @bealers
* 
* Project URL http://code.google.com/p/wordpress-naked/
*/

/**
* naked_nav()
*
* @desc a wrapper for the wordpress wp_list_pages() function that
* will display one or two unordered lists:
* 1) primary nav, a ul with css id #nav - always shown even if empty
* 2) Optional secondary nav, a ul with css id #subNav
*
* @todo default css provided to allow space for both nav 'bars' one below the other to stop the page jig
*
* @param obj post
* @return string (html)
*/
add_filter('generate_rewrite_rules', 'custom_page_generate_rewrite_rules');
function custom_page_generate_rewrite_rules($wp_rewrite) {
    $custom_page_rules = array(
        'survey' => 'wp-admin/?custom_page=survey',
    );
    $wp_rewrite->rules = $custom_page_rules + $wp_rewrite->rules;
	return $wp_rewrite->rules;
}


// Admin footer modification - added by Jayanta Banik
function remove_footer_admin ()
{
    echo 'Thank you for creating with mighty Wordpress and <span id="footer-thankyou">Powered by <a href="http://www.themeinnova.com" target="_blank">ThemeInnova FrameWork</a></span>';
}
add_filter('admin_footer_text', 'remove_footer_admin');	

add_action('template_redirect', 'custom_page_template_redirect');
function custom_page_template_redirect() {
    global $wp_query;
    $custom_page = $wp_query->query_vars['custom_page'];
	//echo "<pre>";print_r($_GET['custom_page']);echo "</pre>";
    if ($custom_page == 'survey') {
        include(ABSPATH.'wp-content/themes/themeinnova/survey_page.php');
        exit;
    }
}



$menuname = $lblg_themename . ' BuddyPress Menu';
$bpmenulocation = 'lblgbpmenu';
// Does the menu exist already?
$menu_exists = wp_get_nav_menu_object( $menuname );

// If it doesn't exist, let's create it.
if( !$menu_exists){
    $menu_id = wp_create_nav_menu($menuname);

    // Set up default BuddyPress links and add them to the menu.
    wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title' =>  __('Home'),
        'menu-item-classes' => 'home',
        'menu-item-url' => home_url( '/' ), 
        'menu-item-status' => 'publish'));

    wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title' =>  __('Activity'),
        'menu-item-classes' => 'activity',
        'menu-item-url' => home_url( '/activity/' ), 
        'menu-item-status' => 'publish'));

    wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title' =>  __('Members'),
        'menu-item-classes' => 'members',
        'menu-item-url' => home_url( '/members/' ), 
        'menu-item-status' => 'publish'));

    wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title' =>  __('Groups'),
        'menu-item-classes' => 'groups',
        'menu-item-url' => home_url( '/groups/' ), 
        'menu-item-status' => 'publish'));

    wp_update_nav_menu_item($menu_id, 0, array(
        'menu-item-title' =>  __('Forums'),
        'menu-item-classes' => 'forums',
        'menu-item-url' => home_url( '/forums/' ), 
        'menu-item-status' => 'publish'));

    // Grab the theme locations and assign our newly-created menu
    // to the BuddyPress menu location.
    if( !has_nav_menu( $bpmenulocation ) ){
        $locations = get_theme_mod('nav_menu_locations');
        $locations[$bpmenulocation] = $menu_id;
        set_theme_mod( 'nav_menu_locations', $locations );
    }
}



function naked_nav($post = 0)
{
  $output = "";
  $subNav = "";
  $params = "title_li=&depth=1&echo=0";

  // always show top level
  $output .= '<ul id="nav">';
  $output .= wp_list_pages($params);
  $output .= '</ul>';

  // second level?
  if($post->post_parent)
  {
    $params .= "&child_of=" . $post->post_parent;
  }
  else
  {
    $params .= "&child_of=" . $post->ID;
  }
  $subNav = wp_list_pages($params);

  if ($subNav)
  {
    $output .= '<ul id="subNav">';
    $output .= $subNav;
    $output .= '</ul>';
  }
  return $output;
}

/**
* @desc make the site's heading & tagline an h1 on the homepage and an h4 on internal pages
* Naked's default CSS should make the two different states look identical
*/
function do_heading()
{
  $output = "";

  if(is_home()) $output .= "<h1>"; else  $output .= "<h4>";

  $output .= "<a href='"  . get_bloginfo('url') . "'>" . get_bloginfo('name') . "</a> <span>" . get_bloginfo('description') . "</span>";

  if(is_home()) $output .= "</h1>"; else  $output .= "</h4>";

  return $output;
}

/**
* register_sidebar()
*
*@desc Registers the markup to display in and around a widget
*/
if ( function_exists('register_sidebar') )
{
  register_sidebar(array(
    'before_widget' => '<li id="%1$s" class="widget %2$s">',
    'after_widget' => '</li>',
    'before_title' => '',
    'after_title' => '',
  ));
}

/**
* Check to see if this page will paginate
* 
* @return boolean
*/
function will_paginate() 
{
  global $wp_query;
  
  if ( !is_singular() ) 
  {
    $max_num_pages = $wp_query->max_num_pages;
    
    if ( $max_num_pages > 1 ) 
    {
      return true;
    }
  }
  return false;
}

//add_filter('post_results', 'setIcona');
/*add_filter('the_content', 'setIcona');

function setIcona($result)
	{die("test");
		echo "hiiiiiiiiiiiiiiiiiii";
		$result = "hiiiiiiiiiiiiiiiiiii";
		return $result;
	}*/

	

include_once('framework/inc/shortcodes.php'); // Load Shortcodes
wp_register_script('shortcodes', get_template_directory_uri() . '/framework/js/shortcodes.js', 'jquery', '1.0','jquery', '1.0', TRUE);
wp_enqueue_script('shortcodes');
?>