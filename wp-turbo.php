<?php

/**
 * @wordpress-plugin
 * Plugin Name: WP Turbo
 * Version:     20230630
 * Plugin URI:  https://github.com/harkalygergo/wp-turbo
 * Description: Universal plugin to make WordPress better, faster, safer. More info in README.md
 * Author:      Harkály Gergő
 * Author URI:  https://www.harkalygergo.hu
 * Text Domain: wp-turbo
 * Domain Path: /languages/
 * License:     GPL v3
 * Requires at least: 5.9
 * Requires PHP: 8.0
 *
 * WC requires at least: 3.0
 */


use WPTurbo\App\App;

$config = [];

//include_once 'src/App/App.php';
include_once 'config.php';
require_once 'vendor/autoload.php';

global $WPTurbo;

$WPTurbo = new App($config);

function dump(mixed $variable, bool $exit=false)
{
    global $WPTurbo;
    $WPTurbo->debug->dump($variable, $exit);
}

// TODO disable /wp-json/wp/v2/users



class WPTurbo
{
    public function __construct()
    {
        register_activation_hook( __FILE__, [$this, 'createWPTurboDirectory'] );
    }

    public function createWPTurboDirectory()
    {
        // create directory under wp-content/uploads
        $wp_upload_dir = wp_upload_dir();
        $basedir = $wp_upload_dir['basedir'];
        $WPTurboDirectory = $basedir . '/wp-turbo';
        if (!file_exists($WPTurboDirectory)) {
            mkdir($WPTurboDirectory, 0777, true);
        }
    }

}
new WPTurbo();

