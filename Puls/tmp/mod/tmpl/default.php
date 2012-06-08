<?php
/**
* @package Joomla! 1.5.x
* @author 2008 (c)  Denys Nosov (aka Dutch)
* @author web-site: www.joomla-ua.org
* @copyright This module is licensed under a Creative Commons Attribution-Noncommercial-No Derivative Works 3.0 License.
**/

/*******************PARAMS****************/
/*
/* $params->get('moduleclass_sfx') - module class suffix
/*
/* $item->link        -   display link
/* $item->text        -   display title
/*
/* $item->image       -   display image
/*
/* $item->created     -   display date & time
/* $item->df_d        -   display day
/* $item->df_m        -   display mounth
/* $item->df_y        -   display mounth
/*
/* $item->author      -   display author
/*
/* $item->sectitle    -   display Section title
/* $item->cattitle    -   display Category title
/*
/* $item->hits        -   display Hits
/*
/* $item->introtext   -   display introtex
/* $item->fulltext    -   display fulltext
/* $item->readmore    -   display Read more...
/* $item->rmtext      -   display Read more... text
/*
/* $item->comments    - display comments
/*
/*****************************************/

// no direct access
defined('_JEXEC') or die('Restricted access');

?>
<div class="junewsultra<?php echo $params->get('moduleclass_sfx'); ?>">
<?php foreach ($list as $item) :  ?>
	<div class="junews">
        <?php if($item->text): ?>
		    <a href="<?php echo $item->link; ?>"><?php echo $item->text; ?></a><br />
        <?php endif; ?>

        <?php if($params->get('pik')): ?>
			<?php echo $item->image; ?><br />
        <?php endif; ?>

        <div class="small juinfo">
          <?php if($params->get('showsec')): ?>
  			<?php echo $item->sectitle; ?>&nbsp;|&nbsp;
          <?php endif; ?>
          <?php if($params->get('showcat')): ?>
  			<?php echo $item->cattitle; ?>
          <?php endif; ?>
        </div>

        <div class="small juinfo">
          <?php if($params->get('showDate')): ?>
  			<?php echo $item->created; ?>&nbsp;|&nbsp;
          <?php endif; ?>
          <?php if($params->def('avtor')): ?>
  			<?php echo $item->author; ?>&nbsp;|&nbsp;
          <?php endif; ?>
          <?php if($params->get('showHits')): ?>
  			<?php echo $item->hits; ?>
          <?php endif; ?>
        </div>

        <?php if($params->get('show_intro')): ?>
            <?php echo $item->introtext; ?>
        <?php endif; ?>

        <?php if($params->get('read_more')): ?>
            <a href="<?php echo $item->readmore; ?>" class="readmore<?php echo $params->get('moduleclass_sfx'); ?>" title="<?php echo $item->text; ?>"><?php echo $item->rmtext; ?></a>
        <?php endif; ?>

        <?php if($params->def('JC')): ?>
            <?php echo $item->comments; ?>
        <?php endif; ?>
	</div>
<?php endforeach; ?>
</div>