			<footer class="footer" role="contentinfo">

				<div id="inner-footer" class="wrap cf">
                    <div class="hr"></div>

					<nav role="navigation">
						<?php wp_nav_menu(array(
    					'container' => '',                              // remove nav container
    					'container_class' => 'footer-links cf',         // class of container (should you choose to use it)
    					'menu' => __( 'Footer Links', 'bonestheme' ),   // nav name
    					'menu_class' => 'nav footer-nav cf',            // adding custom nav class
    					'theme_location' => 'footer-links',             // where it's located in the theme
    					'before' => '',                                 // before the menu
        			'after' => '',                                  // after the menu
        			'link_before' => '',                            // before each link
        			'link_after' => '',                             // after each link
        			'depth' => 0,                                   // limit the depth of the nav
    					'fallback_cb' => 'bones_footer_links_fallback'  // fallback function
						)); ?>
					</nav>

					<div class="cf">
                        <div class="source-org copyright"><a href="#">&copy; <?php echo date('Y'); ?> <?php bloginfo( 'name' ); ?>.</a></div>
                        <div class="tm-copyright">Разработано <a href="http://www.tmedia.pro/"><img src="<?php echo get_stylesheet_directory_uri(); ?>/library/images/tm-copyright.png" alt="Техно Медиа Techno Media"></a> <span class="s-nowrap">© ООО «Техно Медиа»</span></div>
                    </div>
                        <script src="<?php echo get_stylesheet_directory_uri(); ?>/library/js/sitelibs/jquery.transit.min"></script>
                    <script>
                        (function ($) {
                            $(".tm-copyright img").mouseover(function(){
                                $(this).stop(true,true);
                                $(this).transition({
                                    perspective: '500px',
                                    //rotateX: 360,
                                    scale: [1.5, 1.25],
                                    duration: 500,
                                    easing: 'in'
                                })
                                    .transition({
                                        //perspective: '500px',
                                        //rotateX: 360,
                                        scale: [1.0, 1.0],
                                        duration: 500,
                                        easing: 'out'
                                    });
                            });
                        })(jQuery);
                    </script>
				</div>

			</footer>

		</div>

		<?php // all js scripts are loaded in library/bones.php ?>
		<?php wp_footer(); ?>

	</body>

</html> <!-- end of site. what a ride! -->
