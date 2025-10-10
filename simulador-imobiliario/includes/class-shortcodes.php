 (cd "$(git rev-parse --show-toplevel)" && git apply --3way <<'EOF' 
diff --git a//dev/null b/simulador-imobiliario/includes/class-shortcodes.php
index 0000000000000000000000000000000000000000..5d53034e1612672649f63b28cb3413057255e8ab 100644
--- a//dev/null
+++ b/simulador-imobiliario/includes/class-shortcodes.php
@@ -0,0 +1,76 @@
+<?php
+/**
+ * Shortcodes for Simulador Imobiliário.
+ *
+ * @package Simulador_Imobiliario
+ */
+
+if ( ! defined( 'ABSPATH' ) ) {
+    exit;
+}
+
+class SIMIMOB_Shortcodes {
+
+    /**
+     * Instance.
+     *
+     * @var SIMIMOB_Shortcodes|null
+     */
+    protected static $instance = null;
+
+    /**
+     * Get instance.
+     */
+    public static function get_instance() {
+        if ( null === self::$instance ) {
+            self::$instance = new self();
+        }
+
+        return self::$instance;
+    }
+
+    /**
+     * Constructor.
+     */
+    private function __construct() {
+        add_shortcode( 'simulador_financiamento', array( $this, 'render_financiamento' ) );
+        add_shortcode( 'simulador_obras', array( $this, 'render_obras' ) );
+        add_shortcode( 'simulador_imobiliario', array( $this, 'render_wizard' ) );
+    }
+
+    /**
+     * Render financing template.
+     */
+    public function render_financiamento( $atts = array(), $content = '' ) {
+        wp_enqueue_style( 'simimob-front' );
+        wp_enqueue_script( 'simimob-front' );
+        ob_start();
+        $data = shortcode_atts( array(), $atts, 'simulador_financiamento' );
+        include SIMIMOB_PLUGIN_DIR . 'templates/financiamento.php';
+        return ob_get_clean();
+    }
+
+    /**
+     * Render obras template.
+     */
+    public function render_obras( $atts = array(), $content = '' ) {
+        wp_enqueue_style( 'simimob-front' );
+        wp_enqueue_script( 'simimob-front' );
+        ob_start();
+        $data = shortcode_atts( array(), $atts, 'simulador_obras' );
+        include SIMIMOB_PLUGIN_DIR . 'templates/obras.php';
+        return ob_get_clean();
+    }
+
+    /**
+     * Render wizard template.
+     */
+    public function render_wizard( $atts = array(), $content = '' ) {
+        wp_enqueue_style( 'simimob-front' );
+        wp_enqueue_script( 'simimob-front' );
+        ob_start();
+        $data = shortcode_atts( array(), $atts, 'simulador_imobiliario' );
+        include SIMIMOB_PLUGIN_DIR . 'templates/wizard.php';
+        return ob_get_clean();
+    }
+}
 
EOF
)
