<?php
/*
Plugin Name: WP QrCode
Plugin URI:  http://www.easecloud.cn/
Description: WordPress QrCode
Version:     0.1
Author:      Alfred
Author URI:  http://www.huangwenchao.com.cn/
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Domain Path: null
Text Domain: wp_qrcode
*/

define('WQR_DOMAIN', 'wp_qrcode');

/**
 * Multi-language supports.
 */
add_action('plugins_loaded', function() {
    load_plugin_textdomain(
        WQR_DOMAIN,
        false,
        plugin_basename(dirname(__FILE__)).'/languages'
    );
});

add_action('wp_ajax_qrcode', 'wp_ajax_render_qrcode');
add_action('wp_ajax_nopriv_qrcode', 'wp_ajax_render_qrcode');
function wp_ajax_render_qrcode() {
    ob_clean();
    include 'phpqrcode/qrlib.php';
    $url = esc_url_raw($_GET['qrcode']);
    $level = @intval($_GET['qrcode']) ?: 0; // 0 - 3
    $size = @intval($_GET['size']) ?: 3;
    $margin = @intval($_GET['margin']) ?: 4;
    QRcode::png($url, false, $level, $size, $margin);
    exit;
}

/**
 * Returns the qrcode image url for a specified link url given.
 * @param $link
 * @return string|void
 */
function get_qrcode_image_url($link, $level=0, $size=3, $margin=2) {
    return admin_url(
        'admin-ajax.php?action=qrcode&qrcode='.esc_url_raw($link).
        "level=$level&size=$size&margin=$margin"
    );
}