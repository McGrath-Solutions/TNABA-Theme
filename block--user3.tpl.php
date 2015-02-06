<div class="<?php if (isset($classes)) print $classes; ?>" id="<?php print $block_html_id; ?>"<?php print $attributes; ?>>
<div class="ms-box ms-post">
<div class="ms-box-body ms-post-body">
<article class="ms-post-inner ms-article">
<?php print render($title_prefix); ?>
<?php if ($block->subject): ?>
<h2 class="ms-postheader"><?php print $block->subject ?></h2>
<?php endif;?>
<?php print render($title_suffix); ?>
<div class="ms-postcontent">
<div class="ms-article content">
<?php print $content; ?>
</div>
</div>
<div class="cleared"></div>
</article>
<div class="cleared"></div>
</div>
</div>
</div>
