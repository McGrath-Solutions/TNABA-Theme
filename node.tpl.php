<?php 
$vars = get_defined_vars();
$view = get_artx_drupal_view();
$message = $view->get_incorrect_version_message();
if (!empty($message)) {
print $message;
die();
}
$is_blog_page = isset($node->body['und'][0]['summary']) && ($node->body['und'][0]['summary'] == 'ART_BLOG_PAGE') ? true : false;
?>
<div id="mainContent"></div>
<div id="node-<?php print $node->nid; ?>" class="node<?php if(!empty($type)) { echo ' '.$type; } if ($sticky) { echo ' sticky'; } if ($promote) { print ' promote'; } if (!$status) { print ' node-unpublished'; } ?>">
	<?php if (!$is_blog_page): ?>
<article class="ms-post ms-article">
                                <div class="ms-postmetadataheader">
                                        <h1 class="ms-postheader"><?php print render($title_prefix); ?>
<?php echo art_node_title_output($title, $node_url, $page); ?>
<?php print render($title_suffix); ?>
</h1>
                                    </div>
                                                <?php if ($submitted): ?>
<div class="ms-postheadericons ms-metadata-icons"><?php echo art_submitted_worker($date, $name); ?>
</div><?php endif; ?>

                <div class="ms-postcontent ms-postcontent-0 clearfix"><div class="ms-article">
  <?php endif; ?>
  <?php
    // We hide the comments and links now so that we can render them later.
    hide($content['comments']);
    hide($content['links']);
    $terms = get_terms_D7($content);
    hide($content[$terms['#field_name']]);
    print render($content);
  ?>
  <?php if (!$is_blog_page): ?>
</div>
</div>
                                <?php print $user_picture; ?>
<?php $access_links = true;
if (isset($content['links']['#access'])) {
$access_links = $content['links']['#access'];
}
if ($access_links && (isset($content['links']) || isset($content['comments']))):
$output = art_links_woker_D7($content);
if (!empty($output)):	?>
<div class="ms-postfootericons ms-metadata-icons"><?php echo $output; ?>
</div><?php endif; endif; ?>

                </article><?php
$view->print_comment_node($vars);
?>
	<?php endif; ?>
</div>
