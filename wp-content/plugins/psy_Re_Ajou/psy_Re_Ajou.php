
<?php

/*
Plugin Name: Research Ajou
Plugin URI: https://github.com/live4574/wp_psycholgocial_research
Description: A research.
Version: 1.0
Author: Lee
Author URI: https://github.com/live4574
License: GPL2
*/
/*  Copyright 2019  Lee  (email : adolfd@naver.com)

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License, version 2, as 
    published by the Free Software Foundation.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA
*/
    function activate_plugin_name(){
        require_once plugin_dir_path(__FILE__) . 'includes/
            class-wp-research-activator.php';

        Plugin_Name_Activator::activate();
    }//activator

    function deactivate_plugin_name(){
        require_once plugin_dir_path(__FILE__) . 'includes/
            class-wp-research-deactivator.php';
        Plugin_Name_Deactivator::deactivate();
    }//deactivator

    register_activation_hook(__FILE__,'activate_plugin_name');
    register_deactivation_hook(__FILE__, 'deactivate_plugin_name');
    require plugin_dir_path(__FILE__) . 'includes/class-wp-research.php';

    function run_plugin_name(){
        $plugin = new WP_Research();
        $plugin->run();
    }
    run_plugin_name();
?>