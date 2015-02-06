<?php

function art_content_form_submit($form, &$form_state) {
  $value = $form_state['values']['import_content'];
  variable_set('import_content', $value);
  art_content_clear_previous_import();
  art_content_apply();
  drupal_set_message(t('Import is complete'));
}

function art_vertical_menu_title_process() {
  $parser = art_get_content_parser();
  $sidebars_info = $parser->get_sidebars();
  if ( ! isset( $sidebars_info ) ) {
    return;
  }

  foreach ( $sidebars_info as $sidebar ) {
    foreach ( $sidebar['blocks'] as $block ) {
      if (strpos($block['name'], 'vmenu') !== false) { //proccess VMenu
        return $block['title'];
      }
    }
  }

  return t('Vertical menu');
}

function art_insert_header_footer() {
  $title_exists = true;
  if ($title_exists) {
  $site_name = <<<EOT
Tennessee Association of Blind Athletes
EOT;
  variable_set('site_name', $site_name);
  }
  $slogan_exists = true;
  if ($slogan_exists) {
  $site_slogan = <<<EOT
Living active and fulfilling lives through adventure!
EOT;
  variable_set('site_slogan', $site_slogan);
  }
  $footer_exists = true;
  $base_path = base_path().'rss.xml';
  if ($footer_exists) {
  $footer = <<<EOT

  <div class="ms-footer-inner">
<div class="ms-content-layout">
    <div class="ms-content-layout-row">
    <div class="ms-layout-cell" style="width: 50%">
        <p><a href="http://findaclub.usparalympics.org/default.aspx"><img width="133" height="148" alt="TNABA is an official U.S. Paralympic Sport Club." src="images/paralympic_sport_club.png" style="border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;" class="ms-"></a></p>
    </div><div class="ms-layout-cell" style="width: 50%">
        <p><a href="http://www.usaba.org"><img width="420" height="100" alt="TNABA is the official Tennessee State chapter for the United States Association of Blind Athletes." src="images/usaba_logo.png" style="border-top-width: 0px; border-right-width: 0px; border-bottom-width: 0px; border-left-width: 0px;"></a><br></p>
    </div>
    </div>
</div>
<div class="ms-content-layout">
    <div class="ms-content-layout-row">
    <div class="ms-layout-cell" style="width: 100%">
        <p>Copyright © 2014 Tennessee Association of Blind Athletes. All Rights Reserved.</p>
        <p>Created by <a href="www.mcgrathsolutions.org">McGrath Solutions, Inc.</a><br></p>
    </div>
    </div>
</div>

  </div>

EOT;
  $parser = art_get_content_parser();
  $pages_path = art_get_pages_path($parser);
  variable_set('art_footer', art_modify_content_paths($footer, $pages_path));
  }
}
function art_process_sidebars($parser) {
  $sidebars_info = $parser->get_sidebars();
  if ( ! isset( $sidebars_info ) ) {
    return;
  }

  $pages_path = art_get_pages_path($parser);

  $blocks = array();
  $blocks_head = '';
  foreach ( $sidebars_info as $sidebar ) {
      $region_name = $sidebar['name'] == 'sidebar1' ? 'left' : 'right';
      $sidebar_name = 'sidebar_'.$region_name;
      foreach ( $sidebar['blocks'] as $block ) {
          $content = isset($block['content']) ? $block['content'] : 'New block content';
          $drupal_region = $sidebar_name;
          if (strpos($block['name'], 'vmenu') !== false) //proccess VMenu
            continue;

          $content = art_modify_content_paths($block['content'], $pages_path);
          $blocks[$block['name']] = array(
            'name' => $block['name'],
            'info' => $block['title'],
            'subject' => $block['title'],
            'status' => 1,
            'region' => $drupal_region,
            'weight' => 0,
            'visibility' => BLOCK_VISIBILITY_NOTLISTED,
            'content' => $content,
            'cache' => DRUPAL_NO_CACHE,
          );
          if (isset($block['head'])) {
            $blocks_head .= $block['head'];
          }
      }
  }

  variable_set('blocks_head', $blocks_head);
  return $blocks;
}

function art_content_apply() {
  art_insert_header_footer();

  $parser = art_get_content_parser();
  $pages_info = $parser->get_pages();
  $posts_info = $parser->get_posts();
  $pages_path = art_get_pages_path($parser);
  $posts_nid = art_insert_posts('article', $posts_info, $pages_path);
  $pages_nid = art_insert_posts('page', $pages_info, $pages_path, $posts_nid);
  variable_set('art_node_nids', array_merge($posts_nid, $pages_nid));
}

function art_comment_link($nid) {
  global $user;
  if ($user->uid && user_access('post comments')) {
    return t('<a href="comment/reply/'.$nid.'" title="'.t('Add a new comment to this page.').'">Add new comment</a>');
  }
  if (variable_get('user_register', USER_REGISTER_VISITORS_ADMINISTRATIVE_APPROVAL)) {
    // Users can register themselves.
    return t('<a href="@login">Log in</a> or <a href="@register">register</a> to post comments', array('@login' => url('user/login', array('query' => $destination)), '@register' => url('user/register', array('query' => $destination))));
  }
  // Only admins can add new users, no public registration.
  return t('<a href="@login">Log in</a> to post comments', array('@login' => url('user/login', array('query' => $destination))));
}

/**
 * hook_preprocess allows node_style to override default variables.
 *
 * @param Array $variables
 *   The variables available for overriding.
 * @param String $hook
 *   The section of a Drupal page that the variables might belong to. This can
 *   be page, block, node etc.
 * @return string
 */
function art_content_preprocess(&$variables, $hook) {
  art_content_return_vars($variables, $hook);
}

/**
 * Returns a PHPTemplate variables array based on $hook. Called from node_style.inc.
 *
 * @see _phptemplate_variables
 */
function art_content_return_vars(&$variables, $hook) {
  $art_styles = variable_get('art_styles', NULL);
  $art_head = variable_get('art_head', NULL);
  if ($hook == 'page') {
    if (isset($art_styles)) {
      foreach ($art_styles as $node_id => $art_style) {
        $variables['art_style_'.$node_id] = "\n<style>\n".art_replace_image_sources($art_style)."\n</style>\n";
      }
    }
    if (isset($art_head)) {
      foreach ($art_head as $node_id => $head) {
        $variables['art_head_'.$node_id] = "\n".art_replace_image_sources($head)."\n";
      }
    }
  }
  $variables['art_blocks_head'] = art_replace_image_sources(variable_get('blocks_head', NULL));
  $variables['art_footer'] = variable_get('art_footer', NULL);
  return $variables;
}