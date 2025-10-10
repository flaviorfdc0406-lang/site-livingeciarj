 (cd "$(git rev-parse --show-toplevel)" && git apply --3way <<'EOF' 
diff --git a//dev/null b/simulador-imobiliario/simulador-imobiliario.php
index 0000000000000000000000000000000000000000..64f3c87dca7233712a39458e772563ba2c1d8970 100644
--- a//dev/null
+++ b/simulador-imobiliario/simulador-imobiliario.php
@@ -0,0 +1,116 @@
+<?php
+/**
+ * Plugin Name: Simulador Imobiliário — Financiamento & Obras
+ * Plugin URI: https://example.com/
+ * Description: Simuladores integrados de financiamento e saldo de obras com shortcodes, blocos Gutenberg e widgets Elementor.
+ * Version: 1.0.0
+ * Author: OpenAI Codex
+ * Author URI: https://example.com/
+ * Text Domain: simulador-imobiliario
+ * Domain Path: /languages
+ */
+
+if ( ! defined( 'ABSPATH' ) ) {
+    exit;
+}
+
+if ( ! defined( 'SIMIMOB_PLUGIN_FILE' ) ) {
+    define( 'SIMIMOB_PLUGIN_FILE', __FILE__ );
+}
+
+if ( ! defined( 'SIMIMOB_PLUGIN_DIR' ) ) {
+    define( 'SIMIMOB_PLUGIN_DIR', plugin_dir_path( SIMIMOB_PLUGIN_FILE ) );
+}
+
+if ( ! defined( 'SIMIMOB_PLUGIN_URL' ) ) {
+    define( 'SIMIMOB_PLUGIN_URL', plugin_dir_url( SIMIMOB_PLUGIN_FILE ) );
+}
+
+require_once SIMIMOB_PLUGIN_DIR . 'includes/helpers.php';
+require_once SIMIMOB_PLUGIN_DIR . 'includes/class-settings.php';
+require_once SIMIMOB_PLUGIN_DIR . 'includes/class-admin.php';
+require_once SIMIMOB_PLUGIN_DIR . 'includes/class-shortcodes.php';
+require_once SIMIMOB_PLUGIN_DIR . 'includes/class-rest.php';
+
+/**
+ * Initialize plugin functionality.
+ */
+function simimob_init() {
+    load_plugin_textdomain( 'simulador-imobiliario', false, dirname( plugin_basename( SIMIMOB_PLUGIN_FILE ) ) . '/languages' );
+
+    SIMIMOB_Settings::get_instance();
+    SIMIMOB_Admin::get_instance();
+    SIMIMOB_Shortcodes::get_instance();
+    SIMIMOB_REST::get_instance();
+
+    if ( class_exists( 'Elementor\Plugin' ) ) {
+        require_once SIMIMOB_PLUGIN_DIR . 'includes/class-elementor-financiamento.php';
+        require_once SIMIMOB_PLUGIN_DIR . 'includes/class-elementor-obras.php';
+        require_once SIMIMOB_PLUGIN_DIR . 'includes/class-elementor-wizard.php';
+
+        SIMIMOB_Elementor_Financiamento::get_instance();
+        SIMIMOB_Elementor_Obras::get_instance();
+        SIMIMOB_Elementor_Wizard::get_instance();
+    }
+
+    simimob_register_blocks();
+}
+add_action( 'plugins_loaded', 'simimob_init' );
+
+/**
+ * Register front-end assets and block assets.
+ */
+function simimob_register_assets() {
+    wp_register_style( 'simimob-front', SIMIMOB_PLUGIN_URL . 'assets/css/front.css', array(), '1.0.0' );
+    wp_register_script( 'simimob-front', SIMIMOB_PLUGIN_URL . 'assets/js/front.js', array(), '1.0.0', true );
+
+    wp_register_script( 'simimob-financiamento-block', SIMIMOB_PLUGIN_URL . 'blocks/financiamento/index.js', array( 'wp-blocks', 'wp-element' ), '1.0.0', true );
+    wp_register_script( 'simimob-obras-block', SIMIMOB_PLUGIN_URL . 'blocks/obras/index.js', array( 'wp-blocks', 'wp-element' ), '1.0.0', true );
+    wp_register_script( 'simimob-wizard-block', SIMIMOB_PLUGIN_URL . 'blocks/wizard/index.js', array( 'wp-blocks', 'wp-element' ), '1.0.0', true );
+
+    wp_register_style( 'simimob-block-editor', SIMIMOB_PLUGIN_URL . 'assets/css/block-editor.css', array(), '1.0.0' );
+
+    $config = SIMIMOB_Settings::get_public_settings();
+    wp_localize_script( 'simimob-front', 'SIMIMOB_CONFIG', $config );
+}
+add_action( 'init', 'simimob_register_assets' );
+
+/**
+ * Register Gutenberg blocks.
+ */
+function simimob_register_blocks() {
+    if ( ! function_exists( 'register_block_type' ) ) {
+        return;
+    }
+
+    $blocks = array( 'financiamento', 'obras', 'wizard' );
+
+    foreach ( $blocks as $block ) {
+        register_block_type( SIMIMOB_PLUGIN_DIR . 'blocks/' . $block );
+    }
+}
+
+/**
+ * Plugin activation callback.
+ */
+function simimob_activate() {
+    SIMIMOB_Settings::get_instance();
+    SIMIMOB_Settings::activate();
+}
+register_activation_hook( SIMIMOB_PLUGIN_FILE, 'simimob_activate' );
+
+/**
+ * Plugin deactivation callback.
+ */
+function simimob_deactivate() {
+    // Placeholder for future use.
+}
+register_deactivation_hook( SIMIMOB_PLUGIN_FILE, 'simimob_deactivate' );
+
+/**
+ * Plugin uninstall callback.
+ */
+function simimob_uninstall() {
+    SIMIMOB_Settings::uninstall();
+}
+register_uninstall_hook( SIMIMOB_PLUGIN_FILE, 'simimob_uninstall' );
 
EOF
)
