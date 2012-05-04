<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
require(YOURBASEPATH . DS . "function.php");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>" >
<head>
<jdoc:include type="head" />
<meta name="google-site-verification" content="<?php echo $this->params->get('googlekey'); ?>" /> 
<META name="y_key" content="<?php echo $this->params->get('yahookey'); ?>" /> 
<link rel="stylesheet" href="<?php echo $this->baseurl ?>/templates/system/css/system.css" type="text/css" />
<link rel="stylesheet" href="templates/<?php echo $this->template ?>/css/template.css" type="text/css" />
<link rel="stylesheet" href="templates/<?php echo $this->template ?>/css/default.css" type="text/css" />
<link rel="stylesheet" href="templates/<?php echo $this->template ?>/css/config.css" type="text/css" />
<link rel="stylesheet" href="templates/<?php echo $this->template ?>/css/toolbar.css" type="text/css" />
<link rel="stylesheet" href="templates/<?php echo $this->template ?>/css/modules.css" type="text/css" />
<link rel="stylesheet" href="templates/<?php echo $this->template ?>/css/params.css" type="text/css" />
<link rel="stylesheet" href="templates/<?php echo $this->template ?>/css/typography.css" type="text/css" />
<link rel="stylesheet" href="templates/<?php echo $this->template ?>/css/css.css" type="text/css" />
<script type="text/javascript" src="<?php echo $this->baseurl ?>/templates/<?php echo $this->template;?>/js/<?php echo $this->params->get('menutype'); ?>.js"></script>
<!--[if IE 8]>
<link href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template?>/css/template_ie8.css" rel="stylesheet" type="text/css" />
<![endif]-->
<!--[if IE 7]>
<link href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template?>/css/template_ie7.css" rel="stylesheet" type="text/css" />
<![endif]-->
<!--[if lte IE 6]>
<link href="<?php echo $this->baseurl; ?>/templates/<?php echo $this->template?>/css/template_ie6.css" rel="stylesheet" type="text/css" />
<![endif]-->
<style type="text/css">
.wdb_banner a:link,
.wdb_banner a:visited,
.wdb_banner {
	color: <?php echo $this->params->get('bannerfontcolor'); ?>;
}
</style>
</head>