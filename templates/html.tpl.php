<!DOCTYPE html>
  <head>
  <?php print $head; ?>
  <title><?php print $head_title; ?></title>
  <?php print $styles; ?>
  <?php print art_compatible_scripts($scripts); ?>
  <!-- Created by Artisteer v4.0.0.58475 -->

<meta name="viewport" content="initial-scale = 1.0, maximum-scale = 1.0, user-scalable = no, width = device-width">

<!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->

<!--[if lte IE 7]><link rel="stylesheet" href="<?php echo base_path() . $directory; ?>/style.ie7.css" media="screen" /><![endif]-->


</head>
<body class="<?php print $classes; ?>" <?php print $attributes;?>>
  <?php print $page_top; ?>
  <?php print $page; ?>
  <?php print $page_bottom; ?>
</body>
</html>
