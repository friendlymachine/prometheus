<?php
/**
 * @file
 * Contains the theme's functions to manipulate Drupal's default markup.
 *
 * Complete documentation for this file is available online.
 * @see https://drupal.org/node/1728096
 * Special thanks to all the Drupal devs I have learned from and that have inspired the code below...
 */


function prometheus_preprocess_html(&$variables, $hook) {

  drupal_add_css('http://fonts.googleapis.com/css?family=Roboto:300,400,500', array('type' => 'external', 'weight' => 0,));
  drupal_add_js(drupal_get_path('theme', 'prometheus') .'/js/scripts.js');

  // get rid of .no-sidebars class that Drupal adds
  //$variables['classes_array'] = array_diff($variables['classes_array'], array(
  //  'no-sidebars',
  //));
}

function prometheus_css_alter(&$css) {
  // Remove Drupal core css
  $exclude = array(
    'modules/aggregator/aggregator.css' => FALSE,
    'modules/block/block.css' => FALSE,
    'modules/book/book.css' => FALSE,
    'modules/comment/comment.css' => FALSE,
    'modules/dblog/dblog.css' => FALSE,
    'modules/field/theme/field.css' => FALSE,
    'modules/file/file.css' => FALSE,
    'modules/filter/filter.css' => FALSE,
    'modules/forum/forum.css' => FALSE,
    'modules/help/help.css' => FALSE,
    'modules/menu/menu.css' => FALSE,
    'modules/node/node.css' => FALSE,
    'modules/openid/openid.css' => FALSE,
    'modules/poll/poll.css' => FALSE,
    'modules/profile/profile.css' => FALSE,
    'modules/search/search.css' => FALSE,
    'modules/statistics/statistics.css' => FALSE,
    'modules/syslog/syslog.css' => FALSE,
    'modules/system/admin.css' => FALSE,
    'modules/system/maintenance.css' => FALSE,
    'modules/system/system.css' => FALSE,
    'modules/system/system.admin.css' => FALSE,
    'modules/system/system.base.css' => FALSE,
    'modules/system/system.maintenance.css' => FALSE,
    'modules/system/system.messages.css' => FALSE,
    'modules/system/system.menus.css' => FALSE,
    'modules/system/system.theme.css' => FALSE,
    'modules/taxonomy/taxonomy.css' => FALSE,
    'modules/tracker/tracker.css' => FALSE,
    'modules/update/update.css' => FALSE,
    'modules/user/user.css' => FALSE,
    'misc/vertical-tabs.css' => FALSE,
    // Remove contrib module CSS
    drupal_get_path('module', 'views') . '/css/views.css' => FALSE,
  );
  $css = array_diff_key($css, $exclude);
}

/**
* Add page template suggestions based on the path alias.
*/

function prometheus_preprocess_page(&$vars) {
  if (module_exists('path')) {
    $alias = drupal_get_path_alias(str_replace('/edit','',$_GET['q']));
    if ($alias != $_GET['q']) {
      $template_filename = 'page';
      foreach (explode('/', $alias) as $path_part) {
        $template_filename = $template_filename . '-' . $path_part;
        $vars['template_files'][] = $template_filename;
      }
    }
  }
  //remove messages
  $variables['show_messages'] = FALSE;


}

function prometheus_page_alter(&$page) {
  // force the footer to render even if empty because region template have copyright info
    if ( !isset($page["footer"]) || empty($page["footer"])) {
        $page["footer"] = array(
            '#region' => 'footer',
            '#weight' => '-10',
            '#theme_wrappers' => array('region'));
    }
      // get rid of main content block wrapper
    if (!empty($page['content']['system_main'])) {
      $page['content']['system_main']['#theme_wrappers'] = array_diff($page['content']['system_main']['#theme_wrappers'], array('block'));
    }
}

// Gets rid of separator div in panels layouts
function prometheus_panels_default_style_render_region($variables) {
  $output = '';
  $output .= implode('', $variables['panes']);
  return $output;
}
