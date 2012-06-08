<?php
// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
define( 'YOURBASEPATH', dirname(__FILE__) );
require(YOURBASEPATH . DS . "helper.php");
JHTML::_( 'behavior.mootools' );
?>


<body id="<?php echo $this->params->get('presetstyle'); ?>" class="<?php echo $this->params->get('fontfamily'); ?>">
<div id="wdb_wrapper-head" class="clr">
<div id="wdb_crosslink">
<a href="http://webdesignbuilders.net" title="Web Design"><img src="templates/<?php echo $this->template ?>/images/web_design_builders.jpg" alt="web design, web development, website, seo, cms, web maintenance, free joomla template" title="webdesignbuilders.net" name="Web Design Builders" /></a>
<a href="http://adswall.ph" title="Free Online Ads"><img src="templates/<?php echo $this->template ?>/images/ads_wall.jpg" alt="ads wall, web design, web development, joomla templates, free ads online, real estate, for rent apartment, house, condominium, rent to own, car for sale, pickup, motor, vans, heavy equipment, dogs, jobs, events, computer, laptop, aircon, ref, television, apparels, buy and sell, swap, trade" title="adswall.ph" name="Ads Wall" /></a>
<a href="http://germanlanguagemanila.com.ph/" title="German Language"><img src="templates/<?php echo $this->template ?>/images/joomla_builders.jpg" alt="German Language Center Manila" title="germanlanguagemanila.com.ph" name="German Language Manila" /></a>
<a href="http://germanlanguagemanila.com/" title="German Language"><img src="templates/<?php echo $this->template ?>/images/joomla_builders.jpg" alt="German Language Center Manila" title="germanlanguagemanila.com" name="German Language Manila" /></a>
<a href="http://automatictransmissionspecialist.ph" title="Automatic Transmission"><img src="templates/<?php echo $this->template ?>/images/automatic_transmission_specialist.jpg" alt="Automatic Transmission Specialist, Automatic Car Repair, Automatic Vehicles Services, auto gear boxes" title="http://automatictransmissionspecialist.ph/" name="Automatic Transmission Specialist" /></a></div>
<?php if($this->countModules('topmenu') or $this->countModules('topcenter') or $this->countModules('topright')) : ?>
<div id="wdb_top-line" class="clr">
<div id="wdb_top-structure">
<div id="wdb_topmenu">
<jdoc:include type="modules" name="topmenu" style="none" />
</div>
<div id="wdb_topcenter">
<jdoc:include type="modules" name="topcenter" style="none" />
</div>
<div id="wdb_topright">
<jdoc:include type="modules" name="topright" style="none" />
</div>
</div>
</div>
<?php endif; ?>
<div id="wdb_header-line" class="clr">
<div id="wdb_header-structure">
<div id="wdb_logo">
&nbsp;
</div>
<div id="wdb_header-inside">
<?php if($this->countModules('header')) : ?>
<div id="wdb_header">
<jdoc:include type="modules" name="header" style="none" />
</div>
<?php endif; ?>
<?php if($this->countModules('adscorner')) : ?>
<div id="wdb_adscorner">
<jdoc:include type="modules" name="adscorner" style="none" />
</div>
<?php endif; ?>
</div>
</div>
</div>
<div id="wdb_toolbar-line" class="clr">
<div id="wdb_toolbar-structure">
<div id="wdb_toolbar">
<jdoc:include type="modules" name="toolbar" style="none" />
</div>
<?php if($this->countModules('search')) : ?>
<div id="wdb_search">
<jdoc:include type="modules" name="search" style="none" />
</div>
<?php endif; ?>
</div>
</div>
</div>
<div id="wdb_wrapper-body" class="clr">
<?php $mClasses = modulesClasses('block1'); if ($this->countModules('banner') or $this->countModules('banner2') or $this->countModules('banner3')) : ?>
<div id="wdb_banner-line" class="clr">
<div id="wdb_banner-structure">
<div id="wdb_banner-bg" style="background-color:<?php echo $this->params->get('bannercolor'); ?>">
<div class="<?php echo $banner_width; ?>">
<?php if($this->countModules('banner')) : ?>
<div class="wdb_banner <?php echo $mClasses['banner'][0]; ?>">
<jdoc:include type="modules" name="banner" style="xhtml" />
</div>
<?php endif; ?>
<?php if($this->countModules('banner2')) : ?>
<div class="wdb_banner <?php echo $mClasses['banner2'][0]; ?>">
<jdoc:include type="modules" name="banner2" style="xhtml" />
</div>
<?php endif; ?>
<?php if($this->countModules('banner3')) : ?>
<div class="wdb_banner <?php echo $mClasses['banner3'][0]; ?>">
<jdoc:include type="modules" name="banner3" style="xhtml" />
</div>
<?php endif; ?>
</div>
</div>
</div>
</div>
<?php endif; ?>
<div id="wdb_body-line" class="clr">
<div id="wdb_body-structure">
<div id="wdb_body<?php echo $style; ?>" class="clr">
<?php if($this->countModules('left')) : ?>
<div id="wdb_left<?php echo $style; ?>">
<jdoc:include type="modules" name="left" style="xhtml" />
</div>
<?php endif; ?>
<div id="wdb_center<?php echo $style; ?>">
<?php $mClasses = modulesClasses('block2'); if ($this->countModules('user1') or $this->countModules('user2') or $this->countModules('user3')) : ?>
<div id="wdb_user<?php echo $style; ?>" class="clr">
<div id="wdb_user-bg<?php echo $style; ?>">
<div class="<?php echo $user_width; ?>">
<?php if($this->countModules('user1')) : ?>
<div class="wdb_user <?php echo $mClasses['user1'][0]; ?>">
<jdoc:include type="modules" name="user1" style="xhtml" />
</div>
<?php endif; ?>
<?php if($this->countModules('user2')) : ?>
<div class="wdb_user <?php echo $mClasses['user2'][0]; ?>">
<jdoc:include type="modules" name="user2" style="xhtml" />
</div>
<?php endif; ?>
<?php if($this->countModules('user3')) : ?>
<div class="wdb_user <?php echo $mClasses['user3'][0]; ?>">
<jdoc:include type="modules" name="user3" style="xhtml" />
</div>
<?php endif; ?>
</div>
</div>
</div>
<?php endif; ?>
<?php $mClasses = modulesClasses('block3'); if ($this->countModules('user4') or $this->countModules('user5') or $this->countModules('user6')) : ?>
<div id="wdb_user<?php echo $style; ?>" class="clr">
<div id="wdb_user-bg<?php echo $style; ?>">
<div class="<?php echo $user2_width; ?>">
<?php if($this->countModules('user4')) : ?>
<div class="wdb_user <?php echo $mClasses['user4'][0]; ?>">
<jdoc:include type="modules" name="user4" style="xhtml" />
</div>
<?php endif; ?>
<?php if($this->countModules('user5')) : ?>
<div class="wdb_user <?php echo $mClasses['user5'][0]; ?>">
<jdoc:include type="modules" name="user5" style="xhtml" />
</div>
<?php endif; ?>
<?php if($this->countModules('user6')) : ?>
<div class="wdb_user <?php echo $mClasses['user6'][0]; ?>">
<jdoc:include type="modules" name="user6" style="xhtml" />
</div>
<?php endif; ?>
</div>
</div>
</div>
<?php endif; ?>
<div id="wdb_content<?php echo $style; ?>" class="clr">
<div id="wdb_message" class="<?php echo $this->params->get('readmore'); ?>">
<jdoc:include type="message" />
<jdoc:include type="component" />
</div>
</div>
<?php $mClasses = modulesClasses('block4'); if ($this->countModules('user7') or $this->countModules('user8') or $this->countModules('user9')) : ?>
<div id="wdb_user<?php echo $style; ?>" class="clr">
<div id="wdb_user-bg2<?php echo $style; ?>">
<div class="<?php echo $user3_width; ?>">
<?php if($this->countModules('user7')) : ?>
<div class="wdb_user <?php echo $mClasses['user7'][0]; ?>">
<jdoc:include type="modules" name="user7" style="xhtml" />
</div>
<?php endif; ?>
<?php if($this->countModules('user8')) : ?>
<div class="wdb_user <?php echo $mClasses['user8'][0]; ?>">
<jdoc:include type="modules" name="user8" style="xhtml" />
</div>
<?php endif; ?>
<?php if($this->countModules('user9')) : ?>
<div class="wdb_user <?php echo $mClasses['user9'][0]; ?>">
<jdoc:include type="modules" name="user9" style="xhtml" />
</div>
<?php endif; ?>
</div>
</div>
</div>
<?php endif; ?>
</div>
<?php if($this->countModules('right')) : ?>
<div id="wdb_right<?php echo $style; ?>">
<jdoc:include type="modules" name="right" style="xhtml" />
</div>
<?php endif; ?>
</div>
</div>
</div>
</div>
<div id="wdb_wrapper-bottom" class="clr">
<?php $mClasses = modulesClasses('block5'); if ($this->countModules('footer1') or $this->countModules('footer2') or $this->countModules('footer3') or $this->countModules('footer4') or $this->countModules('footer5')) : ?>
<div id="wdb_footer-line" class="clr">
<div id="wdb_footer-structure">
<div id="wdb_footer-bg">
<div class="<?php echo $footer_width; ?>">
<?php if($this->countModules('footer1')) : ?>
<div class="wdb_footer <?php echo $mClasses['footer1'][0]; ?>">
<jdoc:include type="modules" name="footer1" style="xhtml" />
</div>
<?php endif; ?>
<?php if($this->countModules('footer2')) : ?>
<div class="wdb_footer <?php echo $mClasses['footer2'][0]; ?>">
<jdoc:include type="modules" name="footer2" style="xhtml" />
</div>
<?php endif; ?>
<?php if($this->countModules('footer3')) : ?>
<div class="wdb_footer <?php echo $mClasses['footer3'][0]; ?>">
<jdoc:include type="modules" name="footer3" style="xhtml" />
</div>
<?php endif; ?>
<?php if($this->countModules('footer4')) : ?>
<div class="wdb_footer <?php echo $mClasses['footer4'][0]; ?>">
<jdoc:include type="modules" name="footer4" style="xhtml" />
</div>
<?php endif; ?>
<?php if($this->countModules('footer5')) : ?>
<div class="wdb_footer <?php echo $mClasses['footer5'][0]; ?>">
<jdoc:include type="modules" name="footer5" style="xhtml" />
</div>
<?php endif; ?>
</div>
</div>
</div>
</div>
<?php endif; ?>
<div id="wdb_copy-line">
<div id="wdb_copy-structure">
<div id="wdb_copy-bg">
<div id="wdb_copy">
Copyright &copy; <?php echo $this->params->get('copy'); ?>! All right Reserve!
</div>
<div id="wdb_<?php echo $this->params->get('designby'); ?>">
Design by : <a href="http://webdesignbuilders.net" title="Web Design" target="_blank">Web Design Builders</a> - Project by : <a href="http://adswall.ph/" title="Free Online Ads" target="_blank">Ads Wall</a>
</div>
</div>
</div>
</div>
</div>
<div style="height:20px" class="clr">&nbsp;</div>

<div id="wdb_<?php echo $this->params->get('sponsor'); ?>" class="clr">
<div id="wdb_sponsor-structure">

Sponsored by : <a href="http://automatictransmissionspecialist.ph/" title="Automatic Transmission" target="_blank">Automatic Transmission Specialist</a> - <a href="http://adswall.ph/" title="Free Online Ads" target="_blank">Ads Wall</a> - <a href="http://germanlanguagemanila.com.ph/" title="German Languge" target="_blank">German Languge</a> <a href="http://germanlanguagemanila.com/" title="German Languge" target="_blank">Center</a>
</div>
</div>
</body>
</html>
