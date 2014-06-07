<?php get_header(); ?>

			<div id="content">

				<div id="inner-content" class="cf">

						<div id="main" class="m-all t-2of3 d-5of7 cf hentry" role="main">
                            <?php echo do_shortcode("[metaslider id=67]"); ?>
<!--                            --><?php //echo do_shortcode("[si-contact-form form='1']"); ?>
<!--                            --><?php //echo do_shortcode('[popupwfancybox id="2"]'); ?>

									<article class="cf">
                                    <?php
                                        $post= get_post(79);
                                        if (isset($post->post_content)) echo apply_filters( 'the_content', $post->post_content );
                                    ?>
									</article>
                            <?php echo do_shortcode("[huge_it_slider id='1']"); ?>

						</div>

					<?php get_sidebar(); ?>

				</div>

			</div>

</div></div><!-- h-fix-->
<?php get_footer(); ?>

