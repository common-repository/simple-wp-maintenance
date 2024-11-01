<?php
/**
 * Plugin Name: Simple WP Maintenance
 * Description: Sets the WordPress website to maintenance mode and displays a custom message and background image.
 * Version: 1.1
 * Author: dreiebenen.de - Dominik Hammerschmidt
 * Author URI: https://dreiebenen.de
 * 
 */



// Plugin-Optionsseite erstellen
function simple_wp_maintenance_menu() {
    add_options_page('Simple WP Maintenance', 'Simple WP Maintenance', 'manage_options', 'simple-wp-maintenance', 'simple_wp_maintenance_options_page');
}
add_action('admin_menu', 'simple_wp_maintenance_menu');

// Fügt einen Link zu den Einstellungen auf der Plugin-Seite hinzu
function simple_wp_maintenance_add_settings_link($links) {
    $settings_link = '<a href="options-general.php?page=simple-wp-maintenance">' . __('Settings','simple-wp-maintenance') . '</a>';
    array_push($links, $settings_link);
    return $links;
}

$plugin_basename = plugin_basename(__FILE__);
add_filter("plugin_action_links_{$plugin_basename}", 'simple_wp_maintenance_add_settings_link');

// Optionsseiten-Inhalt
function simple_wp_maintenance_options_page() {
    ?>
<style>
	.iris-picker-inner {width:260px;}	
	
.switch {
  position: relative;
  display: inline-block;
  width: 40px;
  height: 24px;
}

.switch input {
  opacity: 0;
  width: 0;
  height: 0;
}

.slider {
  position: absolute;
  cursor: pointer;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background-color: #ccc;
  -webkit-transition: .4s;
  transition: .4s;
}

.slider:before {
  position: absolute;
  content: "";
  height: 16px;
  width: 16px;
  left: 4px;
  bottom: 4px;
  background-color: white;
  -webkit-transition: .4s;
  transition: .4s;
}

input:checked + .slider {
  background-color: #dd3333;
}

input:focus + .slider {
  box-shadow: 0 0 1px #dd3333;
}

input:checked + .slider:before {
  -webkit-transform: translateX(16px);
  -ms-transform: translateX(16px);
  transform: translateX(16px);
}

/* Rounded sliders */
.slider.round {
  border-radius: 34px;
}

.slider.round:before {
  border-radius: 50%;
}
</style>

    <div class="wrap">
        <h1>Simple WP Maintenance</h1>
        <form method="post" action="options.php">
            <?php
            settings_fields('simple_wp_maintenance_settings');
            do_settings_sections('simple-wp-maintenance');
            submit_button();
            ?>
        </form>
    </div>
    <?php
}
// ...
function simple_wp_maintenance_settings_init() {
    register_setting('simple_wp_maintenance_settings', 'simple_wp_maintenance_options');
    add_settings_section('simple_wp_maintenance_main', __('Settings','simple-wp-maintenance'), 'simple_wp_maintenance_section_text', 'simple-wp-maintenance');
    add_settings_field('maintenance_mode', __('Maintenance Mode','simple-wp-maintenance'), 'simple_wp_maintenance_mode_input', 'simple-wp-maintenance', 'simple_wp_maintenance_main');
	add_settings_field('maintenance_title', __('Title','simple-wp-maintenance'), 'simple_wp_maintenance_title_input', 'simple-wp-maintenance', 'simple_wp_maintenance_main');
    add_settings_field('maintenance_message', __('Message','simple-wp-maintenance'), 'simple_wp_maintenance_message_input', 'simple-wp-maintenance', 'simple_wp_maintenance_main');
	add_settings_field('font', __('Font family','simple-wp-maintenance'), 'simple_wp_maintenance_font_input', 'simple-wp-maintenance', 'simple_wp_maintenance_main');
	add_settings_field('title_font_size', __('Title font size','simple-wp-maintenance'), 'simple_wp_maintenance_title_font_size_input', 'simple-wp-maintenance', 'simple_wp_maintenance_main');
	add_settings_field('message_font_size', __('Message font size','simple-wp-maintenance'), 'simple_wp_maintenance_message_font_size_input', 'simple-wp-maintenance', 'simple_wp_maintenance_main');
	add_settings_field('text_color', __('Font color','simple-wp-maintenance'), 'simple_wp_maintenance_text_color_input', 'simple-wp-maintenance', 'simple_wp_maintenance_main');
	add_settings_field('overlay_background_color', __('Overlay background color','simple-wp-maintenance'), 'simple_wp_maintenance_overlay_background_color_input', 'simple-wp-maintenance', 'simple_wp_maintenance_main');
    add_settings_field('maintenance_background', __('Background image','simple-wp-maintenance'), 'simple_wp_maintenance_background_input', 'simple-wp-maintenance', 'simple_wp_maintenance_main');

}

function simple_wp_maintenance_title_input() {
    $options = get_option('simple_wp_maintenance_options');
    $default_title = __('Maintenance Mode','simple-wp-maintenance');
    $title = isset($options['maintenance_title']) ? $options['maintenance_title'] : $default_title;
    echo '<input id="maintenance_title" name="simple_wp_maintenance_options[maintenance_title]" type="text" size="40" value="' . esc_attr($title) . '">';
}

function simple_wp_maintenance_text_color_input() {
    $options = get_option('simple_wp_maintenance_options');
    $text_color = isset($options['text_color']) ? $options['text_color'] : '#ffffff';
    echo '<input class="wp-color-picker-field" id="text_color" name="simple_wp_maintenance_options[text_color]" type="text" value="' . esc_attr($text_color) . '" data-alpha="true">';
}

function simple_wp_maintenance_title_font_size_input() {
    $options = get_option('simple_wp_maintenance_options');
    $title_font_size = isset($options['title_font_size']) ? $options['title_font_size'] : '48';
    echo '<input id="title_font_size" name="simple_wp_maintenance_options[title_font_size]" type="number" min="1" value="' . esc_attr($title_font_size) . '"> px';
}

function simple_wp_maintenance_message_font_size_input() {
    $options = get_option('simple_wp_maintenance_options');
    $message_font_size = isset($options['message_font_size']) ? $options['message_font_size'] : '16';
    echo '<input id="message_font_size" name="simple_wp_maintenance_options[message_font_size]" type="number" min="1" value="' . esc_attr($message_font_size) . '"> px';
}

add_action('admin_init', 'simple_wp_maintenance_settings_init');

// Einstellungsfelder und Abschnittstext
function simple_wp_maintenance_section_text() {
     _e('Enter your title, Message and background image.','simple-wp-maintenance');
}

function simple_wp_maintenance_mode_input() {
    $options = get_option('simple_wp_maintenance_options');
    echo '<label class="switch"><input id="maintenance_mode" name="simple_wp_maintenance_options[maintenance_mode]" type="checkbox" ' . checked(1, $options['maintenance_mode'], false) . ' value="1"><span class="slider round"></span></label>';
    echo '<label for="maintenance_mode" style="margin-left: 10px;">'.__('Activate maintencance mode','simple-wp-maintenance').'</label>';
}

function simple_wp_maintenance_font_input() {
    $options = get_option('simple_wp_maintenance_options');
    $font = isset($options['font']) ? $options['font'] : 'Arial, Verdana, sans-serif';
    echo '<input id="font" name="simple_wp_maintenance_options[font]" type="text" size="40" value="' . esc_attr($font) . '">';
}


function simple_wp_maintenance_message_input() {
    $options = get_option('simple_wp_maintenance_options');
    $default_message = __('page under maintenance','simple-wp-maintenance');
$message = isset($options['maintenance_message']) ? $options['maintenance_message'] : $default_message;
echo '<textarea id="maintenance_message" name="simple_wp_maintenance_options[maintenance_message]" rows="7" cols="40">' . esc_textarea($message) . '</textarea>';
}

function simple_wp_maintenance_background_input() {
$options = get_option('simple_wp_maintenance_options');
$image_url = isset($options['maintenance_background']) ? $options['maintenance_background'] : '';
echo '<input id="maintenance_background" name="simple_wp_maintenance_options[maintenance_background]" type="text" size="40" value="' . esc_attr($image_url) . '">';
echo '<input id="upload_image_button" type="button" class="button" value="'.__('select image','simple-wp-maintenance').'" />';
}

add_action('admin_enqueue_scripts', 'simple_wp_maintenance_enqueue_media');



function simple_wp_maintenance_overlay_background_color_input() {
    $options = get_option('simple_wp_maintenance_options');
    $overlay_background_color = isset($options['overlay_background_color']) ? $options['overlay_background_color'] : '';
    echo '<input class="wp-color-picker-field" id="overlay_background_color" name="simple_wp_maintenance_options[overlay_background_color]" type="text" value="' . esc_attr($overlay_background_color) . '" data-alpha="true">';
}


// Wartungsmodus aktivieren
function simple_wp_maintenance_mode() {
    if (is_admin()) {
        return;
    }

    $options = get_option('simple_wp_maintenance_options');

    if (!is_array($options) || empty($options['maintenance_mode'])) {
        return;
    }

    if (current_user_can('edit_themes') && is_user_logged_in()) {
        return;
    }

    $background_image_url = esc_url($options['maintenance_background']);
    $custom_message = esc_html($options['maintenance_message']);
    $custom_title = esc_html($options['maintenance_title']);
    $title = !empty($custom_title) ? $custom_title : __('Maintenance Mode','simple-wp-maintenance');


$overlay_background_color = isset($options['overlay_background_color']) ? esc_attr($options['overlay_background_color']) : 'rgba(128, 128, 128, 0.5)';



$font = esc_attr($options['font']);
$title_color = esc_attr($options['text_color']);
$title_size = esc_attr($options['title_font_size']);
$message_size = esc_attr($options['message_font_size']);



$output = '<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="utf-8">
    <title>' . esc_html($options['maintenance_title']) . '</title>
    <style>
        body, #error-page {
            margin: 0;
            border: none;
            padding: 0;
            max-width: none;
            box-shadow: none;
        }
        body {
            background-image: url("' . $background_image_url . '");
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center center;
            background-color: #333;
            height: 100vh;
            width: 100vw;
            font-family: ' .$font. ';
            text-align: center;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        html {
            height: 100%;
            width: 100%;
        }
        h1 {
           
            margin-bottom: 1rem;
        }
        #error-page {
            position: absolute;
            top: 0;
            left: 0;
            width: 100vw;
            height: 100vh;
        }
        #content {
            background-color: ' . $overlay_background_color . ';
            padding: 1rem 2rem;
            border-radius: 5px;
        }
        #content h1 { font-family: ' .$font. '; color: ' .$title_color. '; font-size: ' .$title_size. 'px; }
        #content p { font-family: ' .$font. '; color: ' .$title_color. '; font-size: ' .$message_size. 'px; }
    </style>
</head>
<body>
    <div id="content">
        <h1>' . esc_html($options['maintenance_title']) . '</h1>
        <p>' . $custom_message . '</p>
    </div>
</body>
</html>';



    wp_die($output, $title, ['response' => 503, 'back_link' => false]);
}

add_action('template_redirect', 'simple_wp_maintenance_mode');


function simple_wp_maintenance_admin_bar_notice() {
    global $wp_admin_bar;

    $options = get_option('simple_wp_maintenance_options');

    if ($options['maintenance_mode']) {
        $notice = '<span style="color: #FF0000;">'.__('Maintenance mode enabled','simple-wp-maintenance').'</span>';
        $args = array(
            'id' => 'simple_wp_maintenance_admin_notice',
            'title' => $notice,
            'href' => admin_url('options-general.php?page=simple-wp-maintenance')
        );
        $wp_admin_bar->add_node($args);
    }
}

add_action('admin_bar_menu', 'simple_wp_maintenance_admin_bar_notice', 100);



function simple_wp_maintenance_enqueue_media() {
    if (isset($_GET['page']) && $_GET['page'] == 'simple-wp-maintenance') {
        wp_enqueue_media();
        wp_register_script('simple_wp_maintenance_media', plugin_dir_url(__FILE__) . 'media-selector.js', array('jquery'));
        wp_enqueue_script('simple_wp_maintenance_media');
        wp_enqueue_style('wp-color-picker');
        wp_enqueue_script('wp-color-picker-alpha', plugin_dir_url(__FILE__) . 'wp-color-picker-alpha.min.js', array('wp-color-picker'), '1.0.0', true);
        wp_enqueue_script('simple_wp_maintenance_color_picker', plugin_dir_url(__FILE__) . 'color-picker.js', array('wp-color-picker', 'wp-color-picker-alpha'), false, true);
    }
}

add_action('admin_enqueue_scripts', 'simple_wp_maintenance_enqueue_media');

// Sprachen laden
function simple_wp_maintenance_load_textdomain() {
    load_plugin_textdomain('simple-wp-maintenance', false, basename(dirname(__FILE__)) . '/languages');
}

add_action('plugins_loaded', 'simple_wp_maintenance_load_textdomain');

