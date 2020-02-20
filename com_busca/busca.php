<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link
 * @since             1.0.0
 * @package           Busca do portal
 *
 * @wordpress-plugin
 * Plugin Name:       Pesquisa Geral do Portal
 * Plugin URI:
 * Description:       Plugin de Busca do Portal.
 * Version:           1.0.0
 * Author:            Denilson Alves
 * Author URI:
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       busca de notícias e paginas do portal
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'BUSCA_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-busca-activator.php
 */
function activate_busca() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-busca-activator.php';
	Busca_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-busca-deactivator.php
 */
function deactivate_busca() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-busca-deactivator.php';
	Busca_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_busca' );
register_deactivation_hook( __FILE__, 'deactivate_busca' );


/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-busca.php';


/** Adicionar funcionalidades ao WP_Query de busca **/
add_filter( 'query_vars', 'busca_register_query_vars' );
function busca_register_query_vars( $vars ) {
    $vars[] = 'datainicial';
    $vars[] = 'datafinal';

    return $vars;
}



add_action( 'pre_get_posts', function( \WP_Query $q )
{
    if( $q->is_main_query() )
    {
        $after = $q->get( 'datainicial' );
				$before = $q->get( 'datafinal' );
				if( isset($_GET['s']) && $_GET['s'] == ''  ){
						$q->set('title','eliminarbuscas');
				}

				if( isset($_GET['s']) && strlen($_GET['s']) < 3  ){
						$q->set('title','eliminarbuscas');
				}
        if( isset($_GET['searchphrase']) && $_GET['searchphrase'] == 'exact'){
          $q->set('sentence',true);
          //print("exact");
        }

        /** ordenar por mais recente **/
        if( !isset($_GET['orderby']) AND  !isset($_GET['order']) ) {
            	$q->set('orderby','publish_date');
              $q->set('order','DESC');
        }

        if (isset($_GET['submittype']) and $_GET['submittype'] == 'Limpar'){
          $url = home_url().'?s=';
          wp_redirect( $url );
          exit;

        }

				if ( $q->is_search ) {
	            $q->set( 'date_query', array(
	                array(
	                    'before' => $before,
											'after' => $after,
											'inclusive'=>true,

	                )
	            ) );


	        }
    }

} );


/** hook para contar e mostrar views de posts **/


function yourprefix_add_to_content( $content ) {
    if( is_single() ) {
        	gt_set_post_view();
    }
    return $content;
}
add_filter( 'the_content', 'yourprefix_add_to_content' );



function gt_get_post_view() {
    $count = get_post_meta( get_the_ID(), 'post_views_count', true );
    return "$count views";
}
function gt_set_post_view() {
    $key = 'post_views_count';
    $post_id = get_the_ID();
    $count = (int) get_post_meta( $post_id, $key, true );
    $count++;
    update_post_meta( $post_id, $key, $count );
}
function gt_posts_column_views( $columns ) {
    $columns['post_views'] = 'Views';
    return $columns;
}
function gt_posts_custom_column_views( $column ) {
    if ( $column === 'post_views') {
        echo gt_get_post_view();
    }
}
add_filter( 'manage_posts_columns', 'gt_posts_column_views' );
add_action( 'manage_posts_custom_column', 'gt_posts_custom_column_views' );



/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_busca() {

	$plugin = new Busca();
	$plugin->run();

}
run_busca();
