<?php

defined('ABSPATH') or die('Nope, not accessing this');

class wp_survey_widget extends WP_widget{

    public function __construct(){
        parent::__construct(
            'wp_survey_widget',
            'WP Survey Widget', 
            array('description' => 'A widget that displays your surveys')
        );
        //set base values for the widget (override parent)
        
        add_action('widgets_init',array($this,'register_wp_survey_widgets'));
    }
    //initialise widget values

  public function form($instance){
        //collect variables 
        $survey_id = (isset($instance['survey_id']) ? $instance['survey_id'] : 'default');
        $number_of_surveys = (isset($instance['number_of_surveys']) ? $instance['number_of_surveys'] : 5);

        ?>
        <p>Select your options below</p>
        <p>
            <label for="<?php echo $this->get_field_name('survey_id'); ?>">Survey to display</label>
            <select class="widefat" name="<?php echo $this->get_field_name('survey_id'); ?>" id="<?php echo $this->get_field_id('survey_id'); ?>" value="<?php echo $survey_id; ?>">
                <option value="default">All Surveys</option>
                <?php
                $args = array(
                    'posts_per_page'    => -1,
                    'post_type'         => 'wp_surveys'
                );
                $surveys = get_posts($args);
                if($surveys){
                    foreach($surveys as $survey){
                        if($survey->ID == $survey_id){
                            echo '<option selected value="' . $survey->ID . '">' . get_the_title($survey->ID) . '</option>';
                        }else{
                            echo '<option value="' . $survey->ID . '">' . get_the_title($survey->ID) . '</option>';
                        }
                    }
                }
                ?>
            </select>
        </p>
        <p>
            <small>If you want to display multiple surveys select how many below</small><br/>
            <label for="<?php echo $this->get_field_id('number_of_surveys'); ?>">Number of surveys</label>
            <select class="widefat" name="<?php echo $this->get_field_name('number_of_surveys'); ?>" id="<?php echo $this->get_field_id('number_of_surveys'); ?>" value="<?php echo $number_of_surveys; ?>">
                <option value="default" <?php if($number_of_surveys == 'default'){ echo 'selected';}?>>All surveys</option>
                <option value="1" <?php if($number_of_surveys == '1'){ echo 'selected';}?>>1</option>
                <option value="2" <?php if($number_of_surveys == '2'){ echo 'selected';}?>>2</option>
                <option value="3" <?php if($number_of_surveys == '3'){ echo 'selected';}?>>3</option>
                <option value="4" <?php if($number_of_surveys == '4'){ echo 'selected';}?>>4</option>
                <option value="5" <?php if($number_of_surveys == '5'){ echo 'selected';}?>>5</option>
                <option value="10" <?php if($number_of_surveys == '10'){ echo 'selected';}?>>10</option>
            </select>
        </p>
        <?php
    }
    //handles the back-end admin of the widget
    //$instance - saved values for the form
    
    public function update($new_instance, $old_instance){

        $instance = array();

        $instance['survey_id'] = $new_instance['survey_id'];
        $instance['number_of_surveys'] = $new_instance['number_of_surveys'];

        return $instance;
    }
        //handles updating the widget 
    //$new_instance - new values, $old_instance - old saved values

    public function widget( $args, $instance ) {

        //get wp_simple_survey class (as it builds out output)
        global $wp_simple_surveys;

        //pass any arguments if we have any from the widget
        $arguments = array();
        //if we specify a survey

        //if we specify a single survey
        if($instance['survey_id'] != 'default'){
            $arguments['survey_id'] = $instance['survey_id'];
        }
        //if we specify a number of surveys
        if($instance['number_of_surveys'] != 'default'){
            $arguments['number_of_surveys'] = $instance['number_of_surveys'];
        }

        $html = '';
        //get the output
        $html .= $args['before_widget'];
        $html .= $args['before_title'];
        $html .= 'Surveys';
        $html .= $args['after_title'];
        
        $html .= $wp_simple_surveys->get_surveys_output($arguments);
        //uses the main output function of the survey class
        $html .= $args['after_widget'];

        echo $html;
    }
    //handles public display of the widget
    //$args - arguments set by the widget area, $instance - saved values

    public function register_wp_survey_widgets(){
        register_widget('wp_survey_widget');
    }
    //registers our widget for use

}
//main widget used for displaying survey

 $wp_survey_widget=new wp_survey_widget;
 ?>