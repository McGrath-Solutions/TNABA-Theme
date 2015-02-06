<?php
$vars = get_defined_vars();
$view = get_artx_drupal_view();
$view->print_maintenance_head($vars);
if (isset($page))
foreach (array_keys($page) as $name)
$$name = & $page[$name];
$art_sidebar_left = isset($sidebar_left) && !empty($sidebar_left) ? $sidebar_left : NULL;
$art_sidebar_right = isset($sidebar_right) && !empty($sidebar_right) ? $sidebar_right : NULL;
if (!isset($vnavigation_left)) $vnavigation_left = NULL;
if (!isset($vnavigation_right)) $vnavigation_right = NULL;
$tabs = (isset($tabs) && !(empty($tabs))) ? '<ul class="arttabs_primary">'.render($tabs).'</ul>' : NULL;
$tabs2 = (isset($tabs2) && !(empty($tabs2))) ?'<ul class="arttabs_secondary">'.render($tabs2).'</ul>' : NULL;
?>

<div id="ms-main">
<?php if (!empty($navigation) || !empty($extra1) || !empty($extra2)): ?>
<nav class="ms-nav clearfix">
    <div class="ms-nav-inner">
     
        <?php if (!empty($extra1)) : ?>
<div class="ms-hmenu-extra1"><?php echo render($extra1); ?></div>
<?php endif; ?>
<?php if (!empty($extra2)) : ?>
<div class="ms-hmenu-extra2"><?php echo render($extra2); ?></div>
<?php endif; ?>
<?php if (!empty($navigation)) : ?>
<?php echo art_hmenu_output(render($navigation)); ?>
<?php endif; ?>
</div>
    </nav><?php endif; ?>

<header class="ms-header clearfix"><?php if (!empty($art_header)) { echo render($art_header); } ?>


    <div class="ms-shapes">
<?php if (!empty($site_name)) : ?>
<h1 class="ms-headline" data-left="83.68%"><a href="<?php echo check_url($front_page); ?>" title = "<?php echo $site_name; ?>"><?php echo $site_name;  ?></a></h1><?php endif; ?>

<?php if (!empty($site_slogan)) : ?>
<h2 class="ms-slogan" data-left="78.35%"><?php echo $site_slogan; ?>
</h2><?php endif; ?>



            </div>

                
                    
</header>
<div class="ms-sheet clearfix">
            <?php if (!empty($banner1)) { echo '<div id="banner1">'.render($banner1).'</div>'; } ?>
<?php echo art_placeholders_output(render($top1), render($top2), render($top3)); ?>
<div class="ms-layout-wrapper clearfix">
                <div class="ms-content-layout">
                    <div class="ms-content-layout-row">
                        <?php if (!empty($art_sidebar_left) || !empty($vnavigation_left)) : ?>
<div class="ms-layout-cell ms-sidebar1 clearfix"><?php echo render($vnavigation_left); ?>
<?php echo render($art_sidebar_left); ?>
</div><?php endif; ?>
                        <div class="ms-layout-cell ms-content clearfix"><?php if (!empty($banner2)) { echo '<div id="banner2">'.render($banner2).'</div>'; } ?>
<?php if ((!empty($user1)) && (!empty($user2))) : ?>
<table class="position" cellpadding="0" cellspacing="0" border="0">
<tr valign="top"><td class="half-width"><?php echo render($user1); ?></td>
<td><?php echo render($user2); ?></td></tr>
</table>
<?php else: ?>
<?php if (!empty($user1)) { echo '<div id="user1">'.render($user1).'</div>'; }?>
<?php if (!empty($user2)) { echo '<div id="user2">'.render($user2).'</div>'; }?>
<?php endif; ?>
<?php if (!empty($banner3)) { echo '<div id="banner3">'.render($banner3).'</div>'; } ?>

<?php if (!empty($breadcrumb)): ?>
<article class="ms-post ms-article">
                                
                                                
                <div class="ms-postcontent"><?php { echo $breadcrumb; } ?>
</div>
                                
                </article><?php endif; ?>
<?php $art_post_position = strpos(render($content), "ms-postcontent"); ?>
<?php if (($is_front) || (isset($node) && isset($node->nid))): ?>

<?php if (!empty($tabs) || !empty($tabs2)): ?>
<article class="ms-post ms-article">
                                
                                                
                <div class="ms-postcontent"><?php if (!empty($tabs)) { echo $tabs.'<div class="cleared"></div>'; }; ?>
<?php if (!empty($tabs2)) { echo $tabs2.'<div class="cleared"></div>'; } ?>
</div>
                                
                </article><?php endif; ?>

<?php if (!empty($mission) || !empty($help) || !empty($messages) || !empty($action_links)): ?>
<article class="ms-post ms-article">
                                
                                                
                <div class="ms-postcontent"><?php if (isset($mission) && !empty($mission)) { echo '<div id="mission">'.$mission.'</div>'; }; ?>
<?php if (!empty($help)) { echo render($help); } ?>
<?php if (!empty($messages)) { echo $messages; } ?>
<?php if (isset($action_links) && !empty($action_links)): ?><ul class="action-links"><?php print render($action_links); ?></ul><?php endif; ?>
</div>
                                
                </article><?php endif; ?>

<?php if ($art_post_position === FALSE): ?>
<article class="ms-post ms-article">
                                
                                                
                <div class="ms-postcontent"><?php endif; ?>
<?php echo art_content_replace(render($content)); ?>
<?php if ($art_post_position === FALSE): ?>
</div>
                                
                </article><?php endif; ?>

<?php else: ?>

<article class="ms-post ms-article">
                                
                                                
                <div class="ms-postcontent"><?php if (!empty($title)): print '<h2'. ($tabs ? ' class="with-tabs"' : '') .'>'. $title .'</h2>'; endif; ?>
<?php if (!empty($tabs)) { echo $tabs.'<div class="cleared"></div>'; }; ?>
<?php if (!empty($tabs2)) { echo $tabs2.'<div class="cleared"></div>'; } ?>
<?php if (isset($mission) && !empty($mission)) { echo '<div id="mission">'.$mission.'</div>'; }; ?>
<?php if (!empty($help)) { echo render($help); } ?>
<?php if (!empty($messages)) { echo $messages; } ?>
<?php if (isset($action_links) && !empty($action_links)): ?><ul class="action-links"><?php print render($action_links); ?></ul><?php endif; ?>
<?php if ($art_post_position): ?>
      </div>
    </article>
  <?php echo art_content_replace(render($content)); ?>
<?php else: ?>
  <?php echo art_content_replace(render($content)); ?>
</div>
                                
                </article>
<?php endif; ?>
<?php endif; ?>

<?php if (!empty($banner4)) { echo '<div id="banner4">'.render($banner4).'</div>'; } ?>
<?php if ((!empty($user3)) && (!empty($user4))) : ?>
<table class="position" cellpadding="0" cellspacing="0" border="0">
<tr valign="top"><td class="half-width"><?php echo render($user3); ?></td>
<td><?php echo render($user4); ?></td></tr>
</table>
<?php else: ?>
<?php if (!empty($user3)) { echo '<div id="user3">'.render($user3).'</div>'; }?>
<?php if (!empty($user4)) { echo '<div id="user4">'.render($user4).'</div>'; }?>
<?php endif; ?>
<?php if (!empty($banner5)) { echo '<div id="banner5">'.render($banner5).'</div>'; } ?>
</div>
                    </div>
                </div>
            </div><?php echo art_placeholders_output(render($bottom1), render($bottom2), render($bottom3)); ?>
<?php if (!empty($banner6)) { echo '<div id="banner6">'.render($banner6).'</div>'; } ?>

    </div>
<footer class="ms-footer clearfix"><?php
$footer = render($footer_message);
if (isset($footer) && !empty($footer) && (trim($footer) != '')) { echo $footer; } // From Drupal structure
elseif (!empty($art_footer) && (trim($art_footer) != '')) { echo $art_footer; } // From Artisteer Content module
else { // HTML from Artisteer preview
ob_start(); ?>

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
<?php
  $footer = str_replace('%YEAR%', date('Y'), ob_get_clean());
  echo art_replace_image_path($footer);
}
?>
<?php if (!empty($copyright)) { echo '<div id="copyright">'.render($copyright).'</div>'; } ?>
</footer>

</div>


<?php $view->print_closure($vars); ?>