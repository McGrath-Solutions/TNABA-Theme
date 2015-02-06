<?php
// $Id

require_once("common_methods.php");


global $language;
if (isset($language)) {
	$language->direction = LANGUAGE_LTR;
}

switch (get_drupal_major_version()) {
	case 5:
	  require_once("drupal5_theme_methods.php");
	  break;
	case 6:
	  require_once("drupal6_theme_methods.php");
	  break;
	case 7:
	  require_once("drupal7_theme_methods.php");
	  break;
    default:
		  break;
}

/* Common methods */

function get_drupal_major_version() {	
	$tok = strtok(VERSION, '.');
	//return first part of version number
	return (int)$tok[0];
}

function get_page_language($language) {
  if (get_drupal_major_version() >= 6) return $language->language;
  return $language;
}

function get_page_direction($language) {
  if (isset($language) && isset($language->dir)) { 
	  return 'dir="'.$language->dir.'"';
  }
  return 'dir="'.ltr.'"';
}

function get_full_path_to_theme() {
  return base_path().path_to_theme();
}

function get_artx_drupal_view() {
	if (get_drupal_major_version() == 7)
		return new artx_view_drupal7();
	return new artx_view_drupal56();
}

function get_artisteer_export_version() {
	return 7;
}

if (!function_exists('render'))	{
	function render($var) {
		return $var;
	}
}

class artx_view_drupal56 {
	
	function print_head($vars) {
		foreach (array_keys($vars) as $name)
			$$name = & $vars[$name];
?>
<!DOCTYPE html>
<html lang="<?php echo get_page_language($language); ?>" <?php echo get_page_direction($language); ?> >
<head>
  <?php echo $head; ?>
  <title><?php if (isset($head_title )) { echo $head_title; } ?></title>
  <?php echo $styles ?>
  <?php if (arg(2) != null && (arg(2) == 'block' || arg(2) == 'views')): ?>
  <?php echo $scripts; ?>
  <?php else: ?>
  <?php echo art_compatible_scripts($scripts) ?>
  <?php endif; ?>
  <!-- Created by Artisteer v4.0.0.58475 -->

<meta name="viewport" content="initial-scale = 1.0, maximum-scale = 1.0, user-scalable = no, width = device-width">

<!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->

<!--[if lte IE 7]><link rel="stylesheet" href="<?php echo base_path() . $directory; ?>/style.ie7.css" media="screen" /><![endif]-->

  
</head>

<body <?php if (!empty($body_classes)) { echo 'class="'.$body_classes.'"'; } ?>>
<?php
	}


	function print_closure($vars) {
	echo $vars['closure'];
?>
</body>
</html>
<?php
	}

	function print_maintenance_head($vars) {
		foreach (array_keys($vars) as $name)
			$$name = & $vars[$name];
?>
<!DOCTYPE html >
<html lang="<?php echo get_page_language($language); ?>" <?php echo get_page_direction($language); ?> >
<head>
  <?php echo $head; ?>
  <title><?php if (isset($head_title )) { echo $head_title; } ?></title>  
  <?php echo $styles ?>
  <?php echo $scripts ?>
  <!-- Created by Artisteer v4.0.0.58475 -->

<meta name="viewport" content="initial-scale = 1.0, maximum-scale = 1.0, user-scalable = no, width = device-width">

<!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->

<!--[if lte IE 7]><link rel="stylesheet" href="<?php echo base_path() . $directory; ?>/style.ie7.css" media="screen" /><![endif]-->


</head>

<body <?php if (!empty($body_classes)) { echo 'class="'.$body_classes.'"'; } ?>>
<?php
	}
	
	function print_comment($vars) {
		foreach (array_keys($vars) as $name)
		$$name = & $vars[$name];
?>
<div class="comment<?php print ($comment->new) ? ' comment-new' : ''; print ' '. $status; print ' '. $zebra; ?>">

  <div class="clear-block">
  <?php if ($submitted): ?>
    <span class="submitted"><?php print $submitted; ?></span>
  <?php endif; ?>

  <?php if ($comment->new) : ?>
    <span class="new"><?php print drupal_ucfirst($new) ?></span>
  <?php endif; ?>

  <?php print $picture ?>

    <h3><?php print $title ?></h3>

    <div class="content">
      <?php print $content ?>
      <?php if ($signature): ?>
      <div class="clear-block">
        <div>-</div>
        <?php print $signature ?>
      </div>
      <?php endif; ?>
    </div>
  </div>

  <?php if ($links): ?>
    <div class="links"><?php print $links ?></div>
  <?php endif; ?>
</div>
<?php
	}

	function print_comment_wrapper($vars) {
		foreach (array_keys($vars) as $name)
			$$name = & $vars[$name];
?>
<div id="comments">
  <?php print $content; ?>
</div>
	<?php
	}

	function print_comment_node($vars) {
		return;
	}

	function get_incorrect_version_message() {
		if (get_artisteer_export_version() > 6) {
			return t('This version is not compatible with Drupal 5.x or 6.x and should be replaced.');
		}
		return '';
	}
}


class artx_view_drupal7 {

	function print_head($vars) {
		print render($vars['page']['header']);
	}
	
	function print_closure($vars) {
		return;
	}

	function print_maintenance_head($vars) {
		foreach (array_keys($vars) as $name)
			$$name = & $vars[$name];
?>
<!DOCTYPE html>
<html lang="<?php print $language->language ?>" dir="<?php print $language->dir ?>">
<head>
  <title><?php print $head_title; ?></title>
  <?php print $head; ?>
  <?php print $styles; ?>
  <?php print $scripts; ?>
  <!-- Created by Artisteer v4.0.0.58475 -->

<meta name="viewport" content="initial-scale = 1.0, maximum-scale = 1.0, user-scalable = no, width = device-width">

<!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->

<!--[if lte IE 7]><link rel="stylesheet" href="<?php echo base_path() . $directory; ?>/style.ie7.css" media="screen" /><![endif]-->


</head>
<body class="<?php print $classes; ?>">
<?php
	}
	
	function print_comment($vars) {
		foreach (array_keys($vars) as $name)
			$$name = & $vars[$name];
?>
<div class="<?php print $classes; ?> clearfix"<?php print $attributes; ?>>
  <?php print $picture ?>

  <div class="submitted">
    <?php print $permalink; ?>
    <?php
      print t('Submitted by !username on !datetime.',
        array('!username' => $author, '!datetime' => $created));
    ?>
  </div>

  <?php if ($new): ?>
    <span class="new"><?php print $new ?></span>
  <?php endif; ?>

  <?php print render($title_prefix); ?>
  <h3><?php print $title ?></h3>
  <?php print render($title_suffix); ?>

  <div class="content"<?php print $content_attributes; ?>>
    <?php
      // We hide the comments and links now so that we can render them later.
      hide($content['links']);
      print render($content);
    ?>
    <?php if ($signature): ?>
    <div class="user-signature clearfix">
      <?php print $signature ?>
    </div>
    <?php endif; ?>
  </div>

  <?php print render($content['links']) ?>
</div>
<?php
	}

	function print_comment_wrapper($vars)	{
		foreach (array_keys($vars) as $name)
			$$name = & $vars[$name];
?>
<div id="comments" class="<?php print $classes; ?>"<?php print $attributes; ?>>
  <?php if ($content['comments'] && $node->type != 'forum'): ?>
    <?php print render($title_prefix); ?>
    <h2 class="ms-postheader"><?php print t('Comments'); ?></h2>
    <?php print render($title_suffix); ?>
  <?php endif; ?>

  <?php print render($content['comments']); ?>

  <?php if ($content['comment_form']): ?>
    <h2 class="ms-postheader"><?php print t('Add new comment'); ?></h2>
    <?php print render($content['comment_form']); ?>
  <?php endif; ?>
</div>
	<?php
	}

	function print_comment_node($vars) {
		foreach (array_keys($vars) as $name)
			$$name = & $vars[$name];
		$comments = (isset($content['comments']) ? render($content['comments']) : '');
		if (!empty($comments) && $page):
?>
<div class="ms-box ms-post">
	<div class="ms-box-body ms-post-body">
    <article class="ms-post-inner ms-article">
    <div class="ms-postcontent">
<?php
		echo $comments;
?>
	</div>
    <div class="cleared"></div>
    </article>
	<div class="cleared"></div>
    </div>
    </div>
<?php endif;
	}

	function get_incorrect_version_message() {
		if (get_artisteer_export_version() < get_drupal_major_version()) {
			return t('This version is not compatible with Drupal 7.x. and should be replaced.');
		}
		return '';
	}
}
