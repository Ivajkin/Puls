<?php
/**
 * Flex Slider specific markup, javascript, css and settings.
 */
class MetaBxSlider extends MetaSlider {

    protected $js_function = 'bxSlider';
    protected $js_path = 'sliders/bxslider/jquery.bxslider.min.js';
    protected $css_path = 'sliders/bxslider/jquery.bxslider.css';

    /**
     * Constructor
     *
     * @param integer $id slideshow ID
     */
    public function __construct( $id, $shortcode_settings ) {
        parent::__construct( $id, $shortcode_settings );

//        add_filter( 'metaslider_bx_slider_parameters', array( $this, 'manage_easing' ), 10, 2 );
    }

    /**
     * Enable the parameters that are accepted by the slider
     *
     * @param string  $param
     * @return array|boolean enabled parameters (false if parameter doesn't exist)
     */
    protected function get_param( $param ) {
        $params = array(
            'params' => 'params',
            'bx_width' => 'bx_width',
        );

        if ( isset( $params[$param] ) ) {
            return $params[$param];
        }

        return false;
    }


    /**
     * Build the HTML for a slider.
     *
     * @return string slider markup.
     */
    protected function get_html() {
        $return_value =
            '<div class="slider-viewport">' ."\n".
                '<article class="bxslider" id="' . $this->get_identifier() . '">';

        foreach ( $this->slides as $slide ) {
            $return_value .= "\n            <section>" . $slide . "</section>";
        }

        $return_value .= "\n        </article>\n<div class='time-indicator'></div>\n".
            '<div class="arrows">'.
                '<div class="s-left">'.
                    '<div class="triple-left ">'.
                        '<div class="inside"></div>'.
                    '</div>'.
                    '<div class="main-slider"></div>'.
                '</div>'.
                '<div class="s-right">'.
                    '<div class="triple-right">'.
                        '<div class="inside"></div>'.
                    '</div>'.
                    '<div class="main-slider"></div>'.
                '</div>'.
            "</div>\n</div>";

        return apply_filters( 'metaslider_bx_slider_get_html', $return_value, $this->id, $this->settings );
    }

    /**
     * Ensure CSS transitions are disabled when easing is enabled.
     *
     * @param array   $options
     * @param integer $slider_id
     * @return array $options
     */
    public function manage_easing( $options, $slider_id ) {

        if ( $options["animation"] == '"fade"' ) {
            unset( $options['easing'] );
        }

        if ( isset( $options["easing"] ) && $options["easing"] != '"linear"' ) {
            $options['useCSS'] = 'false';
        }


        // we don't want this filter hanging around if there's more than one slideshow on the page
        remove_filter( 'metaslider_flex_slider_parameters', array( $this, 'manage_easing' ), 10, 2 );

        return $options;
    }

}
?>
