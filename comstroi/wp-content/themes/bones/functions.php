<?php
/*
Author: Eddie Machado
URL: htp://themble.com/bones/

This is where you can drop your custom functions or
just edit things like thumbnail sizes, header images,
sidebars, comments, ect.
*/

// LOAD BONES CORE (if you remove this, the theme will break)
require_once( 'library/bones.php' );

// USE THIS TEMPLATE TO CREATE CUSTOM POST TYPES EASILY
require_once( 'library/custom-post-type.php' );

// CUSTOMIZE THE WORDPRESS ADMIN (off by default)
// require_once( 'library/admin.php' );

/*********************
LAUNCH BONES
Let's get everything up and running.
*********************/

function bones_ahoy() {

  // let's get language support going, if you need it
  load_theme_textdomain( 'bonestheme', get_template_directory() . '/library/translation' );

  // launching operation cleanup
  add_action( 'init', 'bones_head_cleanup' );
  // A better title
  add_filter( 'wp_title', 'rw_title', 10, 3 );
  // remove WP version from RSS
  add_filter( 'the_generator', 'bones_rss_version' );
  // remove pesky injected css for recent comments widget
  add_filter( 'wp_head', 'bones_remove_wp_widget_recent_comments_style', 1 );
  // clean up comment styles in the head
  add_action( 'wp_head', 'bones_remove_recent_comments_style', 1 );
  // clean up gallery output in wp
  add_filter( 'gallery_style', 'bones_gallery_style' );

  // enqueue base scripts and styles
  add_action( 'wp_enqueue_scripts', 'bones_scripts_and_styles', 999 );
  // ie conditional wrapper

  // launching this stuff after theme setup
  bones_theme_support();

  // adding sidebars to Wordpress (these are created in functions.php)
  add_action( 'widgets_init', 'bones_register_sidebars' );

  // cleaning up random code around images
  add_filter( 'the_content', 'bones_filter_ptags_on_images' );
  // cleaning up excerpt
  add_filter( 'excerpt_more', 'bones_excerpt_more' );

} /* end bones ahoy */

// let's get this party started
add_action( 'after_setup_theme', 'bones_ahoy' );


/************* OEMBED SIZE OPTIONS *************/

if ( ! isset( $content_width ) ) {
	$content_width = 640;
}

/************* THUMBNAIL SIZE OPTIONS *************/

// Thumbnail sizes
add_image_size( 'bones-thumb-1280', 1280, 1024, true );
add_image_size( 'bones-thumb-600', 600, 400, true );
add_image_size( 'bones-thumb-300', 300, 200, true );
add_image_size( 'bones-thumb-150', 150, 150, true );

/*
to add more sizes, simply copy a line from above
and change the dimensions & name. As long as you
upload a "featured image" as large as the biggest
set width or height, all the other sizes will be
auto-cropped.

To call a different size, simply change the text
inside the thumbnail function.

For example, to call the 300 x 300 sized image,
we would use the function:
<?php the_post_thumbnail( 'bones-thumb-300' ); ?>
for the 600 x 100 image:
<?php the_post_thumbnail( 'bones-thumb-600' ); ?>

You can change the names and dimensions to whatever
you like. Enjoy!
*/

add_filter( 'image_size_names_choose', 'bones_custom_image_sizes' );

function bones_custom_image_sizes( $sizes ) {
    return array_merge( $sizes, array(
        'bones-thumb-1280' => __('1280px на 1024px'),
        'bones-thumb-600' => __('600px на 400px'),
    ) );
}

/*
The function above adds the ability to use the dropdown menu to select
the new images sizes you have just created from within the media manager
when you add media to your content blocks. If you add more image sizes,
duplicate one of the lines in the array and name it according to your
new image size.
*/

/************* ACTIVE SIDEBARS ********************/

// Sidebars & Widgetizes Areas
function bones_register_sidebars() {
	register_sidebar(array(
		'id' => 'sidebar-left',
		'name' => __( 'Sidebar Left', 'bonestheme' ),
		'description' => __( 'The first (primary) sidebar.', 'bonestheme' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));
    register_sidebar(array(
        'id' => 'sidebar-right',
        'name' => __( 'Sidebar Right', 'bonestheme' ),
        'description' => __( 'The Second (secondary) sidebar.', 'bonestheme' ),
        'before_widget' => '<div id="%1$s" class="widget %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<h4 class="widgettitle">',
        'after_title' => '</h4>',
    ));

	/*
	to add more sidebars or widgetized areas, just copy
	and edit the above sidebar code. In order to call
	your new sidebar just use the following code:

	Just change the name to whatever your new
	sidebar's id is, for example:

	register_sidebar(array(
		'id' => 'sidebar2',
		'name' => __( 'Sidebar 2', 'bonestheme' ),
		'description' => __( 'The second (secondary) sidebar.', 'bonestheme' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h4 class="widgettitle">',
		'after_title' => '</h4>',
	));

	To call the sidebar in your template, you can just copy
	the sidebar.php file and rename it to your sidebar's name.
	So using the above example, it would be:
	sidebar-sidebar2.php

	*/
} // don't remove this bracket!
function omega_layout($condition) {
    switch($condition) {
        case 'left': return 'has-one-sidebar has-sidebar-first';
        case 'right': return 'has-one-sidebar has-sidebar-second';
        case 'both': return 'has-two-sidebars';
        default: return '';
    }
}


/************* COMMENT LAYOUT *********************/

// Comment Layout
function bones_comments( $comment, $args, $depth ) {
   $GLOBALS['comment'] = $comment; ?>
  <div id="comment-<?php comment_ID(); ?>" <?php comment_class('cf'); ?>>
    <article  class="cf">
      <header class="comment-author vcard">
        <?php
        /*
          this is the new responsive optimized comment image. It used the new HTML5 data-attribute to display comment gravatars on larger screens only. What this means is that on larger posts, mobile sites don't have a ton of requests for comment images. This makes load time incredibly fast! If you'd like to change it back, just replace it with the regular wordpress gravatar call:
          echo get_avatar($comment,$size='32',$default='<path_to_url>' );
        */
        ?>
        <?php // custom gravatar call ?>
        <?php
          // create variable
          $bgauthemail = get_comment_author_email();
        ?>
        <img data-gravatar="http://www.gravatar.com/avatar/<?php echo md5( $bgauthemail ); ?>?s=40" class="load-gravatar avatar avatar-48 photo" height="40" width="40" src="<?php echo get_template_directory_uri(); ?>/library/images/nothing.gif" />
        <?php // end custom gravatar call ?>
        <?php printf(__( '<cite class="fn">%1$s</cite> %2$s', 'bonestheme' ), get_comment_author_link(), edit_comment_link(__( '(Edit)', 'bonestheme' ),'  ','') ) ?>
        <time datetime="<?php echo comment_time('Y-m-j'); ?>"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>"><?php comment_time(__( 'F jS, Y', 'bonestheme' )); ?> </a></time>

      </header>
      <?php if ($comment->comment_approved == '0') : ?>
        <div class="alert alert-info">
          <p><?php _e( 'Your comment is awaiting moderation.', 'bonestheme' ) ?></p>
        </div>
      <?php endif; ?>
      <section class="comment_content cf">
        <?php comment_text() ?>
      </section>
      <?php comment_reply_link(array_merge( $args, array('depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
    </article>
  <?php // </li> is added by WordPress automatically ?>
<?php
} // don't remove this bracket!


/*
This is a modification of a function found in the
twentythirteen theme where we can declare some
external fonts. If you're using Google Fonts, you
can replace these fonts, change it in your scss files
and be up and running in seconds.
*/
function bones_fonts() {
  wp_register_style('googleFonts', 'http://fonts.googleapis.com/css?family=Lato:400,700,400italic,700italic');
  wp_register_style('googleOS', 'http://fonts.googleapis.com/css?family=Open+Sans:100,300,400,500,700');
  wp_enqueue_style( 'googleFonts');
  wp_enqueue_style( 'googleOS');
}

add_action('wp_print_styles', 'bones_fonts');


/**
 * My Custom Code
 */

/**
 * WooCommerce Intergation
 */
remove_action( 'woocommerce_before_main_content', 'woocommerce_output_content_wrapper', 10);
remove_action( 'woocommerce_after_main_content', 'woocommerce_output_content_wrapper_end', 10);

add_action('woocommerce_before_main_content', 'bones_start', 10);
add_action('woocommerce_after_main_content', 'bones_end', 10);

function bones_start() {
    echo '<div id="content">'.
				'<div id="inner-content" class="wrap cf">'.
						'<div id="main" class="m-all t-2of3 d-5of7 cf" role="main">';
}
function bones_end() {
    echo '</div></div></div></div></div>';
}

add_theme_support( 'woocommerce' );

/**
 * On Page load
 */

//Page Specific vars
$vars= array();
$vars['before_page_load']= false;
$vars['after_page_load']= false;

function h_event_trigger() {
    global $vars;
    if (!$vars['before_page_load']) { $vars['before_page_load']= true;
    //library addition
        $tdir= get_stylesheet_directory_uri();
        $pdir= plugins_url();
$libs= <<<EOT
    <!--[if (gte IE 6)&(lte IE 8)]>
    <script src="$tdir/library/js/libs/html5shiv/html5shiv.min.js"></script>
    <![endif]-->

    <!--[if (gte IE 6)&(lte IE 8)]>
    <script src="$tdir/library/js/libs/html5shiv/html5shiv-printshiv.min.js"></script>
    <![endif]-->

    <!--[if (gte IE 6)&(lte IE 8)]>
    <script src="$tdir/library/js/libs/selectivizr/selectivizr.min.js"></script>
    <![endif]-->

    <!--[if (gte IE 6)&(lte IE 8)]>
    <script src="$tdir/library/js/libs/selectivizr/respond.min.js"></script>
    <![endif]-->

    <link rel='stylesheet' href="$pdir/bootstrap/css/bootstrap.nocf.css" media='all' />
    <script src="$pdir/bootstrap/js/bootstrap.min.js"></script>
EOT;
        echo $libs;

        //to footer
//        wp_register_script( 'width-indicator', get_stylesheet_directory_uri() . '/library/js/libs/indicator/omega.indicator.js', array(  ), '', true );
//        wp_enqueue_script( 'width-indicator' );

//    wp_register_script( 'no-js', get_stylesheet_directory_uri() . '/library/js/no-js.js', array( 'jquery' ), '');
//    //adding scripts file in the footer
//    wp_register_script( 'bones-js', get_stylesheet_directory_uri() . '/library/js/scripts.js', array( 'jquery' ), '', true );
//    wp_enqueue_script( 'jquery' );
//    wp_enqueue_script( 'no-js' );
//    wp_register_style( 'normalize', get_stylesheet_directory_uri() . '/library/css/normalize.css', array(), '', 'all' );

        $vars['condition']= '';
        $vars['b_popup']= false;

        if (is_home()) {
            return;
        }
        if (trim($_SERVER['REQUEST_URI'], '/') == 'kal-kulyator') {

            wp_register_script( 'product-popup', get_stylesheet_directory_uri() . '/library/js/kalkulyator.js', array( 'jquery' ), '', true );
            wp_enqueue_script('product-popup');
            return;
        }

        //if (is_page('Shop')) {var_dump($libs); return;}
    }
}
add_action('wp_head','h_event_trigger');

function f_event_trigger() {
    global $vars;
    if (!$vars['after_page_load']) { $vars['after_page_load']= true;
        if ($vars['b_popup'] ||
            trim($_SERVER['REQUEST_URI'], '/') == 'store' ||
            mb_strpos($_SERVER['REQUEST_URI'], 'product-category') !== false
        ) : ?>
        <div id="thanks" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="userinfoLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <p>Спасибо за обращение, наш менеджер свяжется с Вами в ближайшее время.</p>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
        <div id="badinput" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="userinfoLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h3 class="modal-title" id="badinfoLabel">Сообщение не было доставлено!</h3>
                    </div>
                    <div class="modal-body">
                        <p>Проверьте правильность ввода данных и попробуйте ещё раз.</p>
                        <p>при повторном появлении этого сообщения свяжитесь с нами по <strong>тел. 8(ххх) хх-хх-хх</strong></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-warning" data-dismiss="modal">Закрыть</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
        <div id="error" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="userinfoLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h3 class="modal-title" id="errorinfoLabel">Произошла не предвиденная ошибка.</h3>
                    </div>
                    <div class="modal-body">
                        <p>Проверьте правильность ввода данных и попробуйте ещё раз.</p>
                        <p>При повторном появлении этого сообщения свяжитесь с нами по <strong>тел. 8(ххх) хх-хх-хх</strong></p>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-danger" data-dismiss="modal">Закрыть</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
    <?php endif;

        $sidebar_fix= <<<EOT
<script>
    (function($){
        $(document).ready(function(){
            $('aside.sidebar').appendTo('#inner-content');
        });
    })(jQuery);
</script>
EOT;
        if ( (mb_strpos($_SERVER['REQUEST_URI'], 'store') !== false ||
              mb_strpos($_SERVER['REQUEST_URI'], 'product-category') !== false) &&
            !empty($vars['condition'])) {
                echo $sidebar_fix;
                return;
        }
    }
}
add_action('wp_footer','f_event_trigger');

/**
 * Widget Development https://github.com/toscho/T5-Default-Widget-Demo
 * http://codesymphony.co/programmatically-creating-a-wordpress-widget-instance/
 */

add_action( 'widgets_init', 't5_default_widget_demo' );
function t5_default_widget_demo()
{
    // Register our own widget.
    register_widget( 'T5_Demo_Widget' );
    register_widget( 'PopButton_Widget' );
    register_widget( 'ViewPost_Widget' );

//    // Register two sidebars.
//    $sidebars = array ( 'a' => 'sidebar-left', 'b' => 'sidebar-right' );
////    foreach ( $sidebars as $sidebar )
////    {
////        register_sidebar(
////            array (
////                'name'          => $sidebar,
////                'id'            => $sidebar,
////                'before_widget' => '',
////                'after_widget'  => ''
////            )
////        );
////    }
//
//    // Okay, now the funny part.
//
//    // We don't want to undo user changes, so we look for changes first.
//    $active_widgets = get_option( 'sidebars_widgets' );
//
////    if ( ! empty ( $active_widgets[ $sidebars['a'] ] )
////        or ! empty ( $active_widgets[ $sidebars['b'] ] )
////    )
////    {	// Okay, no fun anymore. There is already some content.
////        return;
////    }
//
//    // The sidebars are empty, let's put something into them.
//    // How about a RSS widget and two instances of our demo widget?
//
//    // Note that widgets are numbered. We need a counter:
//    $counter = 1;
//
//    // Add a 'demo' widget to the top sidebar …
//    $active_widgets[ $sidebars['a'] ][0] = 't5_demo_widget-' . $counter;
//    // … and write some text into it:
//    $demo_widget_content[ $counter ] = array ( 'text' => "This works!\n\nAmazing!" );
//    #update_option( 'widget_t5_demo_widget', $demo_widget_content );
//
//    $counter++;
//
//    // That was easy. Now a RSS widget. More fields, more fun!
//    $active_widgets[ $sidebars['a'] ][] = 'rss-' . $counter;
//    // The latest 15 questions from WordPress Stack Exchange.
//    $rss_content[ $counter ] = array (
//        'title'        => 'WordPress Stack Exchange',
//        'url'          => 'http://wordpress.stackexchange.com/feeds',
//        'link'         => 'http://wordpress.stackexchange.com/questions',
//        'items'        => 15,
//        'show_summary' => 0,
//        'show_author'  => 1,
//        'show_date'    => 1,
//    );
//    update_option( 'widget_rss', $rss_content );
//
//    $counter++;
//
//    // Okay, now to our second sidebar. We make it short.
//    $active_widgets[ $sidebars['b'] ][] = 't5_demo_widget-' . $counter;
//    #$demo_widget_content = get_option( 'widget_t5_demo_widget', array() );
//    $demo_widget_content[ $counter ] = array ( 'text' => 'The second instance of our amazing demo widget.' );
//    update_option( 'widget_t5_demo_widget', $demo_widget_content );
//
//    // Now save the $active_widgets array.
//    update_option( 'sidebars_widgets', $active_widgets );
}

/**
 * Super simple widget.
 */
class T5_Demo_Widget extends WP_Widget {
    public function __construct()
    {                      // id_base        ,  visible name
        parent::__construct( 't5_demo_widget', 'T5 Demo Widget' );
    }

    public function widget( $args, $instance )
    {
        echo $args['before_widget'], wpautop( $instance['text'] ), $args['after_widget'];
    }

    public function form( $instance )
    {
        $text = isset ( $instance['text'] )
            ? esc_textarea( $instance['text'] ) : '';
        printf(
            '<textarea class="widefat" rows="7" cols="20" id="%1$s" name="%2$s">%3$s</textarea>',
            $this->get_field_id( 'text' ),
            $this->get_field_name( 'text' ),
            $text
        );
    }
}
class PopButton_Widget extends WP_Widget {
    public function __construct()
    {                      // id_base        ,  visible name
        parent::__construct( 'pop_button', 'Pop Up Buttons' );
    }

    public function widget( $args, $instance )
    {
        echo $args['before_widget'];

        global $vars;
        if (!$vars['b_popup']) $vars['b_popup']= true;
        ?>
        <div class="button btn btn-default">
            <?php echo wpautop( $instance['name'] ); ?>
        </div>
        <div class="modal fade" tabindex="-1" role="dialog" aria-labelledby="userinfoLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h3 class="modal-title" id="userinfoLabel"><?php echo $instance['title']; ?></h3>
                    </div>
                    <div class="modal-body">
                        <?php
                        echo do_shortcode(trim($instance['data']));
                        ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
        <script>
            (function ($) {
                $("#<?php echo $args['widget_id']; ?> .button").click(function(){
                    $("#<?php echo $args['widget_id']; ?> .modal").modal();
                });
            })(jQuery);
        </script>
        <?php
        echo$args['after_widget'];
    }
    public function form( $instance )
    {
        $name = isset ( $instance['name'] )
            ? esc_html( $instance['name'] ) : '';
        printf(
            '<p><label for="%1$s">Название кнопки</label><input type="text" autocomplete id="%1$s" name="%2$s" value="%3$s"/></p>',
            $this->get_field_id( 'name' ),
            $this->get_field_name( 'name' ),
            $name
        );
        $title = isset ( $instance['title'] )
            ? esc_html( $instance['title'] ) : '';
        printf(
            '<p><label for="%1$s">Заголовок сообщения</label><input type="text" autocomplete id="%1$s" name="%2$s" value="%3$s"/></p>',
            $this->get_field_id( 'title' ),
            $this->get_field_name( 'title' ),
            $title
        );
        $data = isset ( $instance['data'] )
            ? esc_html( $instance['data'] ) : '';
        printf(
            '<p><label for="%1$s">Shortcode</label><input type="text" autocomplete id="%1$s" name="%2$s" value="%3$s"/></p>',
            $this->get_field_id( 'data' ),
            $this->get_field_name( 'data' ),
            $data
        );

    }
}
class ViewPost_Widget extends WP_Widget {
    public function __construct()
    {                      // id_base        ,  visible name
        parent::__construct( 'view_post', 'Page View' );
    }

    public function widget( $args, $instance )
    {
        echo $args['before_widget'];

        global $vars;
        if (!$vars['b_popup']) $vars['b_popup']= true;

        echo "<div class='widget-skin'>\n<h3>" . $instance['name'] . '</h3>';

        $tmp= trim($instance['title']);
        if (!empty($tmp)) {
            $post= intval($tmp) ? get_post($tmp) : get_page_by_title($tmp);

            if (isset($post->post_content)) echo apply_filters( 'the_content', $post->post_content );
        }
        echo $args['after_widget'];
    }
    public function form( $instance )
    {
        $name = isset ( $instance['name'] )
            ? esc_html( $instance['name'] ) : '';
        printf(
            '<p><label for="%1$s">Название блока</label><input type="text" autocomplete id="%1$s" name="%2$s" value="%3$s"/></p>',
            $this->get_field_id( 'name' ),
            $this->get_field_name( 'name' ),
            $name
        );
        $title = isset ( $instance['title'] )
            ? esc_html( $instance['title'] ) : '';
        printf(
            '<p><label for="%1$s">Заголовок или ID страницы</label><input type="text" autocomplete id="%1$s" name="%2$s" value="%3$s"/></p>',
            $this->get_field_id( 'title' ),
            $this->get_field_name( 'title' ),
            $title
        );

    }
}

/**
 * Output caterogy image on category page
 *
 */
//add_action( 'woocommerce_archive_description', 'woocommerce_category_image', 2 );
//function woocommerce_category_image() {
//    if ( is_product_category() ){
//        global $wp_query;
//        $cat = $wp_query->get_queried_object();
//        $thumbnail_id = get_woocommerce_term_meta( $cat->term_id, 'thumbnail_id', true );
//        $image = wp_get_attachment_url( $thumbnail_id );
//        if ( $image ) {
//            echo '<img src="' . $image . '" alt="" />';
//        }
//    }
//}

//add_filter( 'image_size_names_choose', 'custom_image_sizes_choose' );
//function custom_image_sizes_choose( $sizes ) {
//    $custom_sizes = array(
//        'featured-image' => 'Featured Image'
//    );
//    return array_merge( $sizes, $custom_sizes );
//}

/* DON'T DELETE THIS CLOSING TAG */
?>
