<?php get_header(); ?>

			<div id="content">

				<div id="inner-content" class="wrap cf">

						<div id="main" class="m-all t-2of3 d-5of7 cf" role="main">

							<?php if (have_posts()) : while (have_posts()) : the_post(); ?>

							<article id="post-<?php the_ID(); ?>" <?php post_class( 'cf' ); ?> role="article" itemscope itemtype="http://schema.org/BlogPosting">

								<header class="article-header">

									<h1 class="page-title" itemprop="headline"><?php the_title(); ?></h1>

<!--									<p class="byline vcard">-->
<!--										--><?php //printf( __( 'Posted <time class="updated" datetime="%1$s" pubdate>%2$s</time> by <span class="author">%3$s</span>', 'bonestheme' ), get_the_time('Y-m-j'), get_the_time(get_option('date_format')), get_the_author_link( get_the_author_meta( 'ID' ) )); ?>
<!--									</p>-->

								</header> <?php // end article header ?>

								<section class="entry-content cf" itemprop="articleBody">
									<?php
										// the content (pretty self explanatory huh)
										the_content();

										/*
										 * Link Pages is used in case you have posts that are set to break into
										 * multiple pages. You can remove this if you don't plan on doing that.
										 *
										 * Also, breaking content up into multiple pages is a horrible experience,
										 * so don't do it. While there are SOME edge cases where this is useful, it's
										 * mostly used for people to get more ad views. It's up to you but if you want
										 * to do it, you're wrong and I hate you. (Ok, I still love you but just not as much)
										 *
										 * http://gizmodo.com/5841121/google-wants-to-help-you-avoid-stupid-annoying-multiple-page-articles
										 *
										*/
										wp_link_pages( array(
											'before'      => '<div class="page-links"><span class="page-links-title">' . __( 'Pages:', 'bonestheme' ) . '</span>',
											'after'       => '</div>',
											'link_before' => '<span>',
											'link_after'  => '</span>',
										) );
									?>
								</section> <?php // end article section ?>

								<footer class="article-footer cf">
                                    <p class="home-select">Выберите тип дома</p>
                                    <div class="home-type m-base">
                                        <div>
                                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/library/images/img_blank.png" alt="КомфортСтрой Калькулятор" />
                                            <p>Кирпичный</p>
                                        </div>
                                        <div>
                                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/library/images/img_blank.png" alt="КомфортСтрой Калькулятор" />
                                            <p>Монолитный</p>
                                        </div>
                                        <div>
                                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/library/images/img_blank.png" alt="КомфортСтрой Калькулятор" />
                                            <p>Панельный</p>
                                        </div>
                                        <div>
                                            <img src="<?php echo get_stylesheet_directory_uri(); ?>/library/images/img_blank.png" alt="КомфортСтрой Калькулятор" />
                                            <p>Частный</p>
                                        </div>
                                        <p class="hr"></p>
                                    </div>

                                    <div class="messages">
                                            <div id="m-100" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="userinfoLabel" aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                            <h3 class="modal-title" id="userinfoLabel">Калькулятор расчёта стоимости ремонта квартир</h3>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div class="preview">
                                                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/library/images/img_blank.png" alt="КомфортСтрой Калькулятор" />
                                                                <p>Кирпичный</p>
                                                                <div class="hint">Выберите тип помещения</div>
                                                            </div>
                                                            <div class="content c-100">
                                                                <div>
                                                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/library/images/img_blank.png" alt="КомфортСтрой Калькулятор" />
                                                                    <p>Ванная</p>
                                                                </div>
                                                                <div>
                                                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/library/images/img_blank.png" alt="КомфортСтрой Калькулятор" />
                                                                    <p>Гостинная</p>
                                                                </div>
                                                                <div>
                                                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/library/images/img_blank.png" alt="КомфортСтрой Калькулятор" />
                                                                    <p>Спальня</p>
                                                                </div>
                                                                <div>
                                                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/library/images/img_blank.png" alt="КомфортСтрой Калькулятор" />
                                                                    <p>Кухня</p>
                                                                </div>
                                                                <div>
                                                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/library/images/img_blank.png" alt="КомфортСтрой Калькулятор" />
                                                                    <p>Корридор</p>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                                                        </div>
                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                            </div>
                                            <div id="m-110" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="userinfoLabel" aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                            <h3 class="modal-title" id="userinfoLabel">Калькулятор расчёта стоимости ремонта квартир</h3>
                                                        </div>
                                                        <div class="modal-body c-final">
                                                            <h4>План ванны</h4>
                                                            <div>
                                                                <p class="label">Ширина и длинна</p>
                                                                <p><input type="text" val="" /> м</p>
                                                                <span><input type="text" val="" /> м</span>
                                                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/library/images/img_blank.png" alt="КомфортСтрой Калькулятор" />
                                                            </div>
                                                            <div>
                                                                <p class="label">Высота</p>
                                                                <p>&nbsp;</p>
                                                                <span><input type="text" val="" /> м</span>
                                                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/library/images/img_blank.png" alt="КомфортСтрой Калькулятор" />
                                                            </div>
                                                            <div>
                                                                <p class="label">Размер двери</p>
                                                                <p><input type="text" val="" /> м</p>
                                                                <span><input type="text" val="" /> м</span>
                                                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/library/images/img_blank.png" alt="КомфортСтрой Калькулятор" />
                                                            </div>
                                                        </div>
                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-success">Рассчитать</button>
                                                        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                                                        <button type="button" class="btn btn-primary back">Назад</button>
                                                    </div>
                                                </div><!-- /.modal-content -->
                                            </div><!-- /.modal-dialog -->
                                        </div>
                                            <div id="m-120" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="userinfoLabel" aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                            <h3 class="modal-title" id="userinfoLabel">Калькулятор расчёта стоимости ремонта квартир</h3>
                                                        </div>
                                                        <div class="modal-body c-10">
                                                            <h4>План гостинной</h4>
                                                            <div>
                                                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/library/images/img_blank.png" alt="КомфортСтрой Калькулятор" />
                                                            </div>
                                                            <div>
                                                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/library/images/img_blank.png" alt="КомфортСтрой Калькулятор" />
                                                            </div>
                                                            <div>
                                                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/library/images/img_blank.png" alt="КомфортСтрой Калькулятор" />
                                                            </div>
                                                            <div>
                                                                <img src="<?php echo get_stylesheet_directory_uri(); ?>/library/images/img_blank.png" alt="КомфортСтрой Калькулятор" />
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                                                            <button type="button" class="btn btn-primary back">Назад</button>
                                                        </div>
                                                    </div><!-- /.modal-content -->
                                                </div><!-- /.modal-dialog -->
                                            </div>
                                            <div id="m-121" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="userinfoLabel" aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                            <h3 class="modal-title" id="userinfoLabel">Калькулятор расчёта стоимости ремонта квартир</h3>
                                                        </div>
                                                        <div class="modal-body c-final">
                                                            <h4>План гостинной</h4>
                                                            <div class="wrap main">
                                                                <div>
                                                                    <p class="label">Ширина и длинна</p>
                                                                    <p><input type="text" val="" /> м</p>
                                                                    <span><input type="text" val="" /> м</span>
                                                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/library/images/img_blank.png" alt="КомфортСтрой Калькулятор" />
                                                                </div>
                                                                <div>
                                                                    <p class="label">Высота</p>
                                                                    <p>&nbsp;</p>
                                                                    <span><input type="text" val="" /> м</span>
                                                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/library/images/img_blank.png" alt="КомфортСтрой Калькулятор" />
                                                                </div>
                                                            </div>
                                                            <div class="wrap door">
                                                                <div class="object">
                                                                    <p class="label">Размер двери</p>
                                                                    <p class="button"><button class="add">Добавить</button> <button class="del">Удалить</button></p>
                                                                    <p><input type="text" val="" /> м</p>
                                                                    <span><input type="text" val="" /> м</span>
                                                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/library/images/img_blank.png" alt="КомфортСтрой Калькулятор" />
                                                                </div>
                                                            </div>
                                                            <div class="wrap window">
                                                                <div class="object">
                                                                    <p class="label">Размер Окна</p>
                                                                    <p class="button"><button class="add">Добавить</button> <button class="del">Удалить</button></p>
                                                                    <p><input type="text" val="" /> м</p>
                                                                    <span><input type="text" val="" /> м</span>
                                                                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/library/images/img_blank.png" alt="КомфортСтрой Калькулятор" />
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-success">Рассчитать</button>
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                                                            <button type="button" class="btn btn-primary back">Назад</button>
                                                        </div>
                                                    </div><!-- /.modal-content -->
                                                </div><!-- /.modal-dialog -->
                                            </div>

                                            <div id="m-100000" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="userinfoLabel" aria-hidden="true" style="display: none;">
                                                <div class="modal-dialog">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                                                            <h3 class="modal-title" id="userinfoLabel">Калькулятор расчёта стоимости ремонта квартир</h3>
                                                        </div>
                                                        <div class="modal-body">
                                                            <section>
                                                                <h4>Потолок</h4>
                                                                <table>
                                                                    <tbody>

                                                                    </tbody>
                                                                </table>
                                                            </section>
                                                            <section>
                                                                <h4>Стены</h4>
                                                                <table>
                                                                    <tbody>

                                                                    </tbody>
                                                                </table>
                                                            </section>
                                                            <section>
                                                                <h4>Пол</h4>
                                                                <table>
                                                                    <tbody>

                                                                    </tbody>
                                                                </table>
                                                            </section>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                                                            <button type="button" class="btn btn-primary back">Назад</button>
                                                        </div>
                                                    </div><!-- /.modal-content -->
                                                </div><!-- /.modal-dialog -->
                                            </div>
                                    </div>

								</footer>

								<?php //comments_template(); ?>

							</article>

							<?php endwhile; else : ?>

									<article id="post-not-found" class="hentry cf">
										<header class="article-header">
											<h1><?php _e( 'Oops, Post Not Found!', 'bonestheme' ); ?></h1>
										</header>
										<section class="entry-content">
											<p><?php _e( 'Uh Oh. Something is missing. Try double checking things.', 'bonestheme' ); ?></p>
										</section>
										<footer class="article-footer">
												<p><?php _e( 'This is the error message in the page.php template.', 'bonestheme' ); ?></p>
										</footer>
									</article>

							<?php endif; ?>

						</div>

						<?php get_sidebar(); ?>

				</div>

			</div>
</div></div><!-- h-fix-->
<?php get_footer(); ?>
