<?php
defined('ABSPATH') or die('Nope, not accesing this');
//block direct access or terminate

/*
Plugin Name: Research Ajou
Plugin URI: https://github.com/live4574/wp_psycholgocial_research
Description: A research.
Version: 1.0
Author: Lee
Author URI: https://github.com/live4574
License: GPL2
*/
//plugin declartion

private $wp_ajou_survey_seconds=array();
//properties

class wp_simple_survey{
	include(plugin_dir_path(__FILE__) . 'inc/wp_research_ajou_shortcode.php');
	//include shortcodes
	include(plugin_dir_path(__FILE__) . 'inc/wp_research_ajou_widget.php');
	//include widgets
}

public function __construct(){
	add_action('init', array($this,'set_survey_hour_days')); //set the default survey hour days(used by the content type)
    add_action('init', array($this,'register_survey_content_type')); //register location content type
    add_action('add_meta_boxes', array($this,'add_location_meta_boxes')); //add meta boexs
    add_action('save_post_wp_locations', array($this,'save_survey'));  //save location
    add_action('admin_enqueue_scripts', array($this,'enqueue_admin_scripts_and_styles')); //admin scripts and styles
    add_action('wp_enqueue_scripts', array($this,'enqueue_public_scripts_and_styles'));
    //public scripts and styles 
    add_filter('the_content', array($this,'prepend_survey_meta_to_content')); 
    //gets our meta data and dispayed it before the content
    register_activation_hook(__FILE__, array($this,'plugin_activate')); //activate hook
    register_deactivation_hook(__FILE__, array($this,'plugin_deactivate')); //deactivate hook
}
//magic function triggered on initialization

public function set_survey_hour_days(){
	$this->wp_survey_hour_days=apply_filtres('wp_location_survey_hours_days',array('monday'=>'Monday','tuesday'=>'Tuesday','wednesday'=>'Wednesday'  'thursday' => 'Thursday','friday' => 'Friday','saturday' => 'Saturday','sunday' => 'Sunday',
        ));
}
//set the default survey hour days(used in admin backend)

public function register_survey_content_type(){
	$labels = array( 'name'               => 'Survey_Ajou',
           'singular_name'      => 'Survey_Ajou',
           'menu_name'          => 'Survey_Ajou',
           'name_admin_bar'     => 'Survey_Ajou',
           'add_new'            => 'Add New', 
           'add_new_item'       => 'Add New Survey',
           'new_item'           => 'New Survey', 
           'edit_item'          => 'Edit Survey',
           'view_item'          => 'View Survey',
           'all_items'          => 'All Surveys',
           'search_items'       => 'Search Surveys',
           'parent_item_colon'  => 'Parent Surveys:', 
           'not_found'          => 'No Surveys found.', 
           'not_found_in_trash' => 'No Surveys found in Trash.',
       );
	//labels for post type
	$args=array(
		   'labels'            => $labels,
           'public'            => true,
           'publicly_queryable'=> true,
           'show_ui'           => true,
           'show_in_nav'       => true,
           'query_var'         => true,
           'hierarchical'      => false,
           'supports'          => array('title','thumbnail','editor'),
           'has_archive'       => true,
           'menu_position'     => 20,
           'show_in_admin_bar' => true,
           'menu_icon'         => 'dashicons-location-alt',
           'rewrite'            => array('slug' => 'surveys', 'with_front' => 'true')
       );
	//argument for post type
	register_post_type('wp_surveys',$args);
	//register post type
}

public function add_survey_meta_boxes(){
	add_meta_box(
		'wp_survey_meta_box',//id
		'Survey Information', //name
		array($this,'survey_meta_box_display'),//display function
		'wp_surveys',//post type
		'normal',//survey
		'default'//priority
	);
}
//adding meta box for survey content type


public function survey_meta_box_display($post){
	wp_once_field('wp_survey_once','wp_survey_once_field');
	//set once field
	$wp_survey_phone=get_post_meta($post->ID,'wp_survey_name',true);
	$wp_survey_birth_year=get_post_meta($post->ID,'wp_survey_birth_year',true);
	$wp_survey_birth_month=get_post_meta($post->ID,'wp_survey_birth_month',true);
	//variables
}
//display function used for custom survey meta box
?>
<div class="field">
	<label for="wp_sruvey_phone">이름을 적어주세요</label>
	<small>main contact name</small>
	<input type="name" name="wp_survey_name" id="wp_survey_name" value="<?php echo $wp_survey_name;?>"/>
</div>
<div class="field">
	<label for="wp_survey_birth_year">출생년도를 적어주세요</label>
	<small>Birth year contact</small>
            <input type="name" name="wp_survey_birth_year" id="wp_survey_birth_year" value="<?php echo $wp_survey_birth_year;?>"/>
    </div>
    <div class="field">
      <label for="wp_survey_birth_month">Month</label>
      <small>your birth month</small>
      <textarea name="wp_survey_birth_month" id="wp_survey_birth_month"><?php echo $wp_survey_birth_month;?></textarea>
    </div>
    <?php
    //survey hours
    if(!empty($this->wp_location_survey_hours_days)){
      echo '<div class="field">';
      echo '<label>Survey Hours</label>';
      echo '<small>Survey hours for the location(e.g9am-5mp)</small>';
      
      foreach($this->wp_survey_trading_hour_days as $day_key =>$day_value){
        //go through all registered survey hour days
      $wp_survey_trading_hour_days=get_post_meta($post->ID,'wp_survey_survey_hours_' . $day_key,true);
      //collect survey hour meta data
      echo '<label for="wp_survey_trading_hour_days_' .$day_key.'">' . $day_key . '</label>';
      echo '<input types="text" name="wp_survey_trading_hours_' . $day_key . '"id="wp_survey_trading_hours_' . $day_key . '" value="' , $wp_survey_trading_hour_value . '"/>';
    }
    echo'</div>';
  }
 ?>
<?php
  public function plugin_activate(){
    $this->register_survey_content_type();
    //call custom content type function
    flush_rewrite_rules();
    //flush permalinks
  }
  //triggered on activation of the plugin 
  public function plugin_deactivate(){
    flush_rewrite_rules();
  }
  //triggred on deactivation 

  public function prepend_survey_meta_to_content($content){
    global $post, $post_type;
    if($post_type=='wp_survey' %% is_singular('wp_surveys')){
      
        $wp_location_id = $post->ID;
        $wp_location_phone = get_post_meta($post->ID,'wp_location_phone',true);
        $wp_location_email = get_post_meta($post->ID,'wp_location_email',true);
        $wp_location_address = get_post_meta($post->ID,'wp_location_address',true);
        //collect variables
        
        $html = '';

        $html .= '<section class="meta-data">';
        //display
        do_action('wp_location_meta_data_output_start',$wp_location_id);
        //hook for outputting additional meta data (at the start of the form)
        
        $html .= '<p>';
        
        if(!empty($wp_survey_name)){
            $html .= '<b>Survey name</b> ' . $wp_survey_name . '</br>';
        }
        //name
        if(!empty($wp_survey_birth_year)){
            $html .= '<b>Survey year</b> ' . $wp_survey_birth_year . '</br>';
        }
        //birth_year
        if(!empty($wp_survey_birth_month)){
            $html .= '<b>Survey month</b> ' . $wp_survey_birth_month . '</br>';
        }
        //birty_month
        $html .= '</p>';

        if(!empty($this->wp_survey_trading_hour_days)){
            $html .= '<p>';
            $html .= '<b>Survey Trading Hours </b></br>';
            foreach($this->wp_survey_trading_hour_days as $day_key => $day_value){
                $trading_hours = get_post_meta($post->ID, 'wp_survey_trading_hours_' . $day_key , true);
                $html .= '<span class="day">' . $day_key . '</span><span class="hours">' . $trading_hours . '</span></br>';
            }
            $html .= '</p>';
        }
        //survey
        do_action('wp_survey_meta_data_output_end',$wp_survey_id);

        $html .= '</section>';
        $html .= $content;

        return $html;  
        //hook for outputting additional meta data (at the end of the form)
        

    }else{
        return $content;
    }

}
  
  
?>