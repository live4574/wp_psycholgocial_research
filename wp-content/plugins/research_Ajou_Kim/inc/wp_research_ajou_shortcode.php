<?php
defined('ABSPATH') or die('Nope, not accessing this');

class wp_survey_shortcode{
    public function __construct(){
    	add_action('init', array($this,'register_survey_shortcodes'));
    	//shortcodes
    }
    //on initialize

    public function register_survey_shorcodes(){
    	add_shortcode('wp_surveys',array($this,'survey_shortcode_output'));
    }

    //shortcode display
    public function survey_shortcode_output($atts, $content = '', $tag){

        //get the global wp_simple_surveys class
        global $wp_simple_surveys;

        //build default arguments
        $arguments = shortcode_atts(array(
            'survey_id' => '',
            'number_of_surveys' => -1)
        ,$atts,$tag);

        //uses the main output function of the survey class
        $html = $wp_simple_surveys->get_surveys_output($arguments);

        return $html;
    }
}
//defines the functionality for the survey shortcode

$wp_survey_shortcode=new wp_survey_shortcode;
?>