<?php

/**
* Gavick News Pro GK1 - default template
* @package Joomla!
* @Copyright (C) 2009 Gavick.com
* @ All rights reserved
* @ Joomla! is Free Software
* @ Released under GNU/GPL License : http://www.gnu.org/copyleft/gpl.html
* @version $Revision: 1.0.0 $
**/

// no direct access
defined('_JEXEC') or die('Restricted access');

$news_amount = $this->content['news_amount'];

?>

<?php if($news_amount > 0) : ?>

	<?php
	
		//
		$block_width = 0;
		$module_width = 0;
		$list_width = 0;
		//
		if($this->config['width_module'] != 0 && $this->config['width_links'] != 0)
		{
			//
			if($this->config['list_position'] == 'bottom')
			{
				//
				$block_width = $this->config['width_module'].'px';
				$module_width = $this->config['width_module'].'px';
				$list_width = $this->config['width_module'].'px';
			}
			else //
			{
				//
				if($this->config['show_list'] == 1)
				{
					//
					$block_width = ($this->config['width_module'] + $this->config['width_links']).'px';
				}
				else //
				{
					//
					$block_width = $this->config['width_module'].'px';
				}
				//
				$module_width = $this->config['width_module'].'px';
				$list_width = $this->config['width_links'].'px';
			}
		}
		else //
		{
			//
			if($this->config['list_position'] == 'bottom')
			{
				//
				$block_width = '300px';
				$module_width = '300px';
				$list_width = '300px';
			}
			else //
			{
				//
				$block_width = '500px';
				//
				if($this->config['show_list'] == 1)
				{
					//
					$module_width = '300px';
				}
				else //
				{
					//
					$module_width = '500px';
				}
				//
				$list_width = '200px';
			}
		}
		
	?>

	<div class="gk_npro_mainwrap" id="gk_npro-<?php echo $this->config['module_unique_id']; ?>" style="width: <?php echo $block_width; ?>;">

		<?php if(($news_amount > ($this->config['news_column'] * $this->config['news_rows'])) && $this->config['news_full_pages'] > 1) : ?>
		<div class="gk_npro_full_interface">
							
			<ul>
				<?php for($i = 0; $i < count($news_html_tab); $i++) : ?>
				<li><?php echo $i; ?></li>	
				<?php endfor; ?>	
			</ul>
								
			<div class="gk_npro_interface_bg">
				<div class="gk_npro_full_prev">prev</div>
				<div class="gk_npro_full_next">next</div>
			</div>
		</div>
		<?php endif; ?>

		<div class="gk_npro_wrap1" style="width: <?php echo $block_width; ?>;">
			<div class="gk_npro_wrap2" style="width: 10000px;">
				<?php for($n = 0; $n < count($this->content['ID']); $n++) : ?>
				<div class="gk_npro_block_wrap" style="width: <?php echo $block_width; ?>;">
					
					<?php
						
						//
						if($this->config['list_position'] == 'bottom')
						{
							//
							$module_float = 'left';
							$list_float = 'left';
						}
						elseif($this->config['list_position'] == 'left') //
						{
							//
							$module_float = 'right';
							$list_float = 'left';
						}
						else //
						{
							//
							$module_float = 'left';
							$list_float = 'right';	
						}
					
					?>
				
					<div class="gk_npro_full" style="width: <?php echo $module_width; ?>;float: <?php echo $module_float; ?>;">
						<h3 class="gk_npro_cat_header">
						<a href="<?php echo JRoute::_(ContentHelperRoute::getCategoryRoute($this->content["CID"][$n][0],$this->content["SID"][$n][0])); ?>"><?php echo $this->content["catname"][$n][0]; ?></a>
						</h3>
						<div class="gk_npro_full_tablewrap" style="width: <?php echo $module_width; ?>;">
							<table class="gk_npro_table" style="width: <?php echo $module_width; ?>;">
														
								<?php for($j = 0; $j < $this->config['news_rows']; $j++) : ?>
								<tr valign="top">
									<?php for($k = 0; $k < $this->config['news_column']; $k++) : ?>		
										<?php if((($j * $this->config['news_column']) + $k) >= count($news_html_tab)) : ?>
											<td style="width: <?php echo $column_width; ?>;padding: <?php echo $this->config['td_padding']; ?>;"></td>
										<?php else : ?>
											<td style="width: <?php echo $column_width; ?>;padding: <?php echo $this->config['td_padding']; ?>;"> 			
										<?php if(isset($news_html_tab[$n][(($j * $this->config['news_column']) + $k)])) echo $news_html_tab[$n][(($j * $this->config['news_column']) + $k)];?>	 
											</td>		
										<?php endif; ?>
									<?php endfor; ?>
								</tr>
								<?php endfor; ?>
										
							</table>			
						</div>
					</div>
				
					<?php if($this->config['show_list'] == 1) : ?>
					<div class="gk_npro_short" style="width: <?php echo $list_width; ?>;float: <?php echo $module_float; ?>;">
						
						<div class="gk_npro_short_scroll1" style="width: <?php echo $list_width; ?>;">
							<div class="gk_npro_short_ulwrap" style="width: <?php echo $list_width; ?>;">
								<ul>
									<?php for($j = 0; $j < count($news_list_tab[$n]); $j++) : ?>
									<?php if(isset($news_list_tab[$n][$j])) echo $news_list_tab[$n][$j]; ?>
									<?php endfor; ?>
								</ul>
							</div>
						</div>
						
						<div class="gk_npro_short_interface">
							<?php if($this->config['news_more_in'] == 1) : ?>
							<span><?php echo $this->more_in_text[$n]; ?></span>
							<?php endif; ?>
						</div>
						
					</div>
					<?php endif; ?>
					
				</div>
				<?php endfor; ?>
			</div>
		</div>
	
	</div>

<?php else : ?>

	<p><strong>Error:</strong> Any articles to show</p>

<?php endif; ?>