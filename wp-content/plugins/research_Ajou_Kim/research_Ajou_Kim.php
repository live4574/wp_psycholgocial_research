<<<<<<< HEAD

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



include_once(plugin_dir_path(__FILE__) . 'inc/shortcode.php');

//User section

add_action('admin_menu','survey_menu');
function survey_menu(){
    add_menu_page('Survey','Survey','administrator','research-settings','research_plugin_settings_page');
}
add_action('admin_init','research_plugin_settings');

function survey_plugin_settings(){
    register_setting('research-settings-group')
}
?>
=======
<?php
/* 
Plugin Name: Research Ajou 
Plugin URI:https://github.comlive4574/wp_psycholgocial_research 
Description: A research. 
Version: 1.0 
Author: Lee 
Author URI: https://github.com/live4574 
License: GPL2 
 */ 
add_action('admin_menu', 'mt_add_pages');
// 훅에 대한 액션 함수
 function mt_add_pages() {
 // Settings 메뉴에 서브메뉴를 만듬.
 add_options_page('Test Settings', 'Test Settings', 'manage_options', 'testsettings', 'mt_settings_page');
 }
function mt_settings_page() {
 // 유저 접근 권한 체크
 if (!current_user_can('manage_options')) 
     wp_die( __('You do not have sufficient permissions to access this page.') );
// 필드와 옵션 이름으로 사용할 변수
 $opt_name = 'mt_favorite_color';
 $hidden_field_name = 'mt_submit_hidden';
 $data_field_name = 'mt_favorite_color';
// 현재 데이터베이스에 저장된 옵션 값 가져오기
 $opt_val = get_option( $opt_name );
// Form을 Submit 했다면...
 if( isset($_POST[ $hidden_field_name ]) && $_POST[ $hidden_field_name ] == 'Y' ) {
 $opt_val = $_POST[ $data_field_name ];
// 포스트값을 데이터베이스에 저장
 update_option( $opt_name, $opt_val );
// 화면에 업데이트했다는 메세지를 표시
 echo '<div><p><strong>settings saved</p></strong></div>';
 }
 // 옵션 설정 화면을 표시
 ?>
 <div class='wrap'>
 <h2>Menu Test Plugin Settings</h2>
 <form method="post" action="">
 <input value="Y">
 <p>Favorite Color: <input value="<?=$opt_val?>"></p>
 <p><input value="Save Changes" /></p>
 </form>
 </div>
 <?php } ?>
>>>>>>> 778f02df1657bad352ea62bfeb3d2658bb610371
