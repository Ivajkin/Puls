<?php
global $multicolBlocks;
$multicolBlocks = array(
	'block1' => array('banner', 'banner2', 'banner3'),
	'block2' => array('user1', 'user2', 'user3'),
	'block3' => array('user4', 'user5', 'user6'),
	'block4' => array('user7', 'user8', 'user9'),
	'block5' => array('footer1', 'footer2', 'footer3', 'footer4', 'footer5'));
$banner_count = ($this->countModules('banner')>0) + ($this->countModules('banner2')>0) + ($this->countModules('banner3')>0);
$banner_width = $banner_count > 0 ? ' banner' . floor(99 / $banner_count) : '';
$user_count = ($this->countModules('user1')>0) + ($this->countModules('user2')>0) + ($this->countModules('user3')>0);
$user_width = $user_count > 0 ? ' user' . floor(99 / $user_count) : '';
$user2_count = ($this->countModules('user4')>0) + ($this->countModules('user5')>0) + ($this->countModules('user6')>0);
$user2_width = $user2_count > 0 ? ' user' . floor(99 / $user2_count) : '';
$user3_count = ($this->countModules('user7')>0) + ($this->countModules('user8')>0) + ($this->countModules('user9')>0);
$user3_width = $user3_count > 0 ? ' user' . floor(99 / $user3_count) : '';
$footer_count = ($this->countModules('footer1')>0) + ($this->countModules('footer2')>0) + ($this->countModules('footer3')>0) + ($this->countModules('footer4')>0) + ($this->countModules('footer5')>0);
$footer_width = $footer_count > 0 ? ' footer' . floor(99 / $footer_count) : '';
function modulesClasses($case, $loaded_only = false) {
  global $mainmodulesBlocks;
  $document	= &JFactory::getDocument();
  $modules = $multicoBlocks[$case];
  $loaded = 0;
  $loadedModule = array();
  $classes = array();
  foreach($multicolBlocks[$case] as $block) if ($document->countModules($block)>0) { $loaded++; array_push($loadedModule, $block); }
  if ($loaded_only) return $loaded;
  switch ($loaded) {
    case 1:
      $classes[$loadedModule[0]][0] = 'full';
      $classes[$loadedModule[0]][1] = $width[0];
      break;
    case 2: 
      for ($i = 0; $i < count($loadedModule); $i++){
        if (!$i) {
		$classes[$loadedModule[$i]][0] = 'first';
		$classes[$loadedModule[$i]][1] = $width[0];
	}
        else {
		$classes[$loadedModule[$i]][0] = 'second';
		$classes[$loadedModule[$i]][1] = $width[1];
	}
      }
      break;
    case 3:
      for ($i = 0; $i < count($loadedModule); $i++){
        if (!$i) {
		$classes[$loadedModule[$i]][0] = 'first';
		$classes[$loadedModule[$i]][1] = $width[0];
	}
        elseif ($i == 1) {
		$classes[$loadedModule[$i]][0] = 'second';
		$classes[$loadedModule[$i]][1] = $width[1];
	}
        else {
		$classes[$loadedModule[$i]][0] = 'third';
		$classes[$loadedModule[$i]][1] = $width[2];
	}
      }
      break;
    case 4:
      for ($i = 0; $i < count($loadedModule); $i++){
        if (!$i) {
		$classes[$loadedModule[$i]][0] = 'first';
		$classes[$loadedModule[$i]][1] = $width[0];
	}
        elseif ($i == 1) {
		$classes[$loadedModule[$i]][0] = 'second';
		$classes[$loadedModule[$i]][1] = $width[1];
	}
	 	elseif ($i == 2) {
		$classes[$loadedModule[$i]][0] = 'third';
		$classes[$loadedModule[$i]][1] = $width[2];
	}
		else {
		$classes[$loadedModule[$i]][0] = 'forth';
		$classes[$loadedModule[$i]][1] = $width[3];
	}
      }
      break;
    case 5:
      for ($i = 0; $i < count($loadedModule); $i++){
        if (!$i) {
		$classes[$loadedModule[$i]][0] = 'first';
		$classes[$loadedModule[$i]][1] = $width[0];
	}
        elseif ($i == 1) {
		$classes[$loadedModule[$i]][0] = 'second';
		$classes[$loadedModule[$i]][1] = $width[1];
	}
	 	elseif ($i == 2) {
		$classes[$loadedModule[$i]][0] = 'third';
		$classes[$loadedModule[$i]][1] = $width[2];
	}
		elseif ($i == 3) {
		$classes[$loadedModule[$i]][0] = 'forth';
		$classes[$loadedModule[$i]][1] = $width[3];
	}
        else {
		$classes[$loadedModule[$i]][0] = 'fifth';
		$classes[$loadedModule[$i]][1] = $width[4];
	}
      }
      break;
  }
  return $classes;
}
?>

<?php
function getColumns ($left, $right, $insetleft, $insetright){
	if ($left && !$right && !$insetleft && !$insetright) {$style = "-left-only";}
	if ($right && !$left && !$insetleft && !$insetright) $style = "-right-only";
	if ($left && $right && !$insetleft && !$insetright) $style = "-left-right";
	if (!$left && !$right && !$insetleft && !$insetright) $style = "-wide";
	if ($right && !$left && !$insetleft && $insetright) $style = "-right-insetright";
	if (!$right && $left && !$insetleft && $insetright) $style = "-left-insetright";
	if ($right && $left && !$insetleft && $insetright) $style = "-left-right-insetright";
	if ($right && !$left && $insetleft && !$insetright) $style = "-right-insetleft";
	if (!$right && $left && $insetleft && !$insetright) $style = "-left-insetleft";
	if ($right && $left && $insetleft && !$insetright) $style = "-left-right-insetleft";
	if ($right && $left && $insetleft && $insetright) $style = "-left-right-insetleft-insetright";
	if (!$right && !$left && !$insetleft && $insetright) $style = "-insetright";
	if (!$right && !$left && $insetleft && !$insetright) $style = "-insetleft";
	if (!$right && !$left && $insetleft && $insetright) $style = "-insetleft-insetright";
	return $style;
	}
	$style = getColumns($this->countModules( 'left' ),$this->countModules( 'right' ),$this->countModules( 'insetleft' ),$this->countModules( 'insetright' ));
?>