<?php
/**
 * Plugin Name: Lifecity Facebook Live Embed
 * Plugin URI: https://github.com/rizennews/lifecity-facebook-live-embed
 * Description: Automatically embeds Facebook Live video and chat on your website when live.
 * Version: 1.0
 * Author: Padmore Aning
 * Author URI: https://designolabs.com
 * License:  MIT License
 * Requires at least: 6.0
 * Tested up to: 6.4
 * Requires PHP: 7.0
 */


if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly.
}

// Enqueue necessary scripts and styles
function lifecity_facebook_enqueue_scripts() {
    wp_enqueue_style('lifecity-facebook-styles', plugin_dir_url(__FILE__) . 'css/styles.css');
    wp_enqueue_script('lifecity-facebook-scripts', plugin_dir_url(__FILE__) . 'js/scripts.js', array('jquery'), null, true);
    if (is_admin()) {
        wp_enqueue_script('jquery-ui-accordion');
        wp_enqueue_script('lifecity-facebook-admin-scripts', plugin_dir_url(__FILE__) . 'js/admin-script.js', array('jquery-ui-accordion'), null, true);
    }
}
add_action('wp_enqueue_scripts', 'lifecity_facebook_enqueue_scripts');

// Admin settings menu
function lifecity_facebook_add_admin_menu() {
    add_options_page('Lifecity Facebook Settings', 'Lifecity Facebook', 'manage_options', 'lifecity-facebook', 'lifecity_facebook_options_page');
}
add_action('admin_menu', 'lifecity_facebook_add_admin_menu');

// Register settings
function lifecity_facebook_settings_init() {
    register_setting('lifecity_facebook', 'lifecity_facebook_api_key', 'lifecity_facebook_api_key_validate');

    add_settings_section(
        'lifecity_facebook_section',
        __('Lifecity Facebook Live Settings', 'lifecity-facebook'),
        'lifecity_facebook_section_callback',
        'lifecity_facebook'
    );

    add_settings_field(
        'lifecity_facebook_api_key',
        __('Facebook API Key', 'lifecity-facebook'),
        'lifecity_facebook_api_key_render',
        'lifecity_facebook',
        'lifecity_facebook_section'
    );
}
add_action('admin_init', 'lifecity_facebook_settings_init');

function lifecity_facebook_api_key_render() {
    $options = get_option('lifecity_facebook_api_key');
    ?>
    <input type='text' name='lifecity_facebook_api_key' value='<?php echo $options; ?>' style='width: 50%;'>
    <?php
}

function lifecity_facebook_section_callback() {
    echo __('Enter your Facebook API key to automatically embed Facebook Live videos.', 'lifecity-facebook');
}

// Plugin options page
function lifecity_facebook_options_page() {
    ?>
    <div class="wrap">
        <h1><?php _e('Lifecity Facebook Live Embed', 'lifecity-facebook'); ?></h1>
        <form action='options.php' method='post'>
            <?php
            settings_fields('lifecity_facebook');
            do_settings_sections('lifecity_facebook');
            submit_button();
            ?>
        </form>
        <h2><?php _e('How to Use', 'lifecity-facebook'); ?></h2>
        <div class="accordion">
            <h3><?php _e('Shortcode Usage', 'lifecity-facebook'); ?></h3>
            <div>
                <p><?php _e('To embed the Facebook Live video and chat, use the following shortcode:', 'lifecity-facebook'); ?></p>
                <code>[lifecity-facebook]</code>
            </div>
        </div>
        <div>
            <h3><?php _e('How to get Facebook Live Video ID', 'lifecity-facebook'); ?></h3>
            <p><?php _e('To get the Facebook API key for your Lifecity Facebook Live Embed plugin, you need to create a Facebook app in the Facebook Developer Console. Here\'s a step-by-step guide:', 'lifecity-facebook');?></p>
            <ol>
            <hr>
            <ol>
                <li><?php _e('Go to <a href="https://developers.facebook.com/" target="_blank">Facebook Developer Console</a>.', 'lifecity-facebook');?></li>
                <li><?php _e('Click on <strong>My Apps</strong>.', 'lifecity-facebook');?></li>
                <li><?php _e('Click on <strong>Add a New App</strong>.', 'lifecity-facebook');?></li>
                <li><?php _e('Enter a name for your app and click <strong>Create App ID</strong>.', 'lifecity-facebook');?></li>
                <li><?php _e('Click on <strong>Settings</strong>.', 'lifecity-facebook');?></li>
                <li><?php _e('Click on <strong>Basic</strong>.', 'lifecity-facebook');?></li>
                <li><?php _e('Click on <strong>Add Platform</strong>.', 'lifecity-facebook');?></li>
                <li><?php _e('Click on <strong>Website</strong>.', 'lifecity-facebook');?></li>
                <li><?php _e('Enter your website URL in the <strong>Site URL</strong> field.', 'lifecity-facebook');?></li>
                <li><?php _e('Click on <strong>Save Changes</strong>.', 'lifecity-facebook');?></li>
                <li><?php _e('Click on <strong>Settings</strong>.', 'lifecity-facebook');?></li>
                <li><?php _e('Click on <strong>Advanced</strong>.', 'lifecity-facebook');?></li>
                <li><?php _e('Click on <strong>Show</strong>.', 'lifecity-facebook');?></li>
                <li><?php _e('Copy the <strong>App ID</strong> and <strong>App Secret</strong> values.', 'lifecity-facebook');?></li>
                <li><?php _e('Paste the <strong>App ID</strong> and <strong>App Secret</strong> values into the <strong>Facebook API Key</strong> field in the Lifecity Facebook Live Embed plugin settings.', 'lifecity-facebook');?></li>
                <li><?php _e('Click on <strong>Save Changes</strong>.', 'lifecity-facebook');?></li>
            </ol>
            <hr>
            </ol>
        </div>
        <h2><?php _e('Support', 'lifecity-facebook'); ?></h2>
        <p><?php _e('If you have any questions, please contact us at <a href="https://designolabs.com/contact/" target="_blank">https://designolabs.com/contact/</a>.', 'lifecity-facebook');?></p>
        <h2><?php _e('Donate', 'lifecity-facebook'); ?></h2>
        <p><?php _e('If you find this plugin helpful, consider buying us a coffee!', 'lifecity-facebook');?></p>
        <a href="https://www.buymeacoffee.com/designolabs" target="_blank">
            <img src="https://img.buymeacoffee.com/button-api/?text=Buy%20us%20a%20coffee&emoji=&slug=yourusername&button_colour=FFDD00&font_colour=000000&font_family=Cookie&outline_colour=000000&coffee_colour=ffffff">
        </a>
        <p>This plugin was developed by <a href="https://github.com/rizennews/" target="_blank">Designolabs Studio</a>.</p>
    </div>
    <?php
}

// Function to get the Facebook Live video (you'll need to implement this)
function lifecity_facebook_get_live_video($api_key) {
    // Assuming you have a method to fetch live video ID from Facebook API
    $live_video_id = ''; // Fetch the live video ID using Facebook API
    return $live_video_id;
}

// Shortcode to embed Facebook Live video
function lifecity_facebook_shortcode() {
    $api_key = get_option('lifecity_facebook_api_key');
    $live_video_id = lifecity_facebook_get_live_video($api_key);

    if ($live_video_id) {
        ob_start();
        ?>
        <div id="lifecity-facebook-container">
            <div id="lifecity-facebook-video">
                <!-- Replace this with your Facebook Live video embed code -->
                <!-- Example: -->
                <!-- <iframe src="https://www.facebook.com/plugins/video.php?href=https://www.facebook.com/facebook/videos/<?php echo $live_video_id; ?>/"></iframe> -->
            </div>
            <div id="lifecity-facebook-chat" style="display:none;">
                <!-- Automatically generate Facebook Live chat embed code -->
                <div class="fb-comments" data-href="https://www.facebook.com/facebook/videos/<?php echo $live_video_id; ?>" data-numposts="5" data-width=""></div>
            </div>
        </div>
        <?php
        return ob_get_clean();
    } else {
        return __('No live video is currently available.', 'lifecity-facebook');
    }
}
add_shortcode('lifecity-facebook', 'lifecity_facebook_shortcode');

// Validate and fetch live video ID after API key is saved
function lifecity_facebook_api_key_validate($input) {
    $new_value = $input;
    if (!empty($input)) {
        $live_video_id = lifecity_facebook_get_live_video($input);
        // Save live video ID to use in shortcode
        update_option('lifecity_facebook_live_video_id', $live_video_id);
    }
    return $new_value;
}
