<?php

function art_get_pages_path($parser) {
  $pages_info = $parser->get_pages();
  $posts_info = $parser->get_posts();
  $result_paths = array();
  art_get_path($pages_info, $result_paths);
  art_get_path($posts_info, $result_paths);

  return $result_paths;
}

function art_get_path($post_info, &$path) {
  if (!$post_info || !isset( $post_info ) ) {
    return;
  }
  foreach ( $post_info as $post ) {
    if (isset($post['path']) && !empty($post['path'])) {
      $path[] = art_get_cut_path($post['path']);
    }
  }

  return $path;
}

function art_get_cut_path($path) {
  $result = $path;
  $pos = strpos($path, "/");
  if ($pos !== FALSE) {
    $result = substr_replace($path, '', 0, 1); // replace first '/' in path
  }
  return $result;
}

/*
* Modifies path to images, links etc in the source content
*/
function art_modify_content_paths($content, $paths_to_replace = NULL) {
  $result = art_replace_image_sources($content);
  if (isset($paths_to_replace)) {
    $result = art_fix_path($result, $paths_to_replace);
  }

  return $result;
}

function art_fix_path($content, $paths_to_fix) {
  if (!isset($paths_to_fix)) {
    return $content;
  }

  $result = $content;
  $base_path = base_path();
  foreach ( $paths_to_fix as $path ) {
    //$result = preg_replace( '~<a(.*?href=[\'\"])(.*?)/'.$path.'.*?([\"\']+)~' , '<a$1'.$base_path.$path.'$3' , $result);
    $result = preg_replace('~<a(.*?href=[\'"])(.*?)/' . $path . '([\'"])~', '<a$1' . $base_path . $path . '$3', $result);
  }
  return $result;
}

function art_replace_image_sources($content) {
  if (!isset($content)) return;
  $content = preg_replace_callback('/(src=)([\'"])(?:images[\/\\\]?)?(?!https?:\/\/)(.*?)\2()/', 'art_real_sources', $content);
  $content = preg_replace_callback('/(url\()([\'"])(?:images[\/\\\]?)?(?!https?:\/\/)(.*?)\2(\))/', 'art_real_sources', $content);
  return $content;
}

function art_real_sources($match) {
  list($str, $start, $quote, $filename, $end) = $match;
  $upload_dir = base_path().drupal_get_path('module', 'art_content') . '/content/images';
  return $start . $quote . $upload_dir . '/' . $filename . $quote . $end;
}