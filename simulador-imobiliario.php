 (cd "$(git rev-parse --show-toplevel)" && git apply --3way <<'EOF' 
diff --git a//dev/null b/simulador-imobiliario.php
index 0000000000000000000000000000000000000000..7946164731d243c944a6cb2b1fa64e266d1a7470 100644
--- a//dev/null
+++ b/simulador-imobiliario.php
@@ -0,0 +1,57 @@
+<?php
+/**
+ * Plugin Name: Simulador Imobiliário - Financiamento & Obras
+ * Plugin URI: https://example.com/
+ * Description: Simuladores integrados de financiamento e saldo de obras com shortcodes, blocos Gutenberg e widgets Elementor.
+ * Version: 1.0.3
+ * Author: OpenAI Codex
+ * Author URI: https://example.com/
+ * Text Domain: simulador-imobiliario
+ * Domain Path: /simulador-imobiliario/languages
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
+if ( ! defined( 'SIMIMOB_VERSION' ) ) {
+    define( 'SIMIMOB_VERSION', '1.0.3' );
+}
+
+$simimob_dir      = trailingslashit( plugin_dir_path( __FILE__ ) ) . 'simulador-imobiliario/';
+$simimob_dir_url  = trailingslashit( plugin_dir_url( __FILE__ ) ) . 'simulador-imobiliario/';
+$simimob_bootstrap = $simimob_dir . 'simulador-imobiliario.php';
+
+if ( file_exists( $simimob_bootstrap ) ) {
+    if ( ! defined( 'SIMIMOB_PLUGIN_DIR' ) ) {
+        define( 'SIMIMOB_PLUGIN_DIR', $simimob_dir );
+    }
+
+    if ( ! defined( 'SIMIMOB_PLUGIN_URL' ) ) {
+        define( 'SIMIMOB_PLUGIN_URL', $simimob_dir_url );
+    }
+
+    require_once $simimob_bootstrap;
+} else {
+    if ( ! function_exists( 'simimob_missing_bootstrap_notice' ) ) {
+        /**
+         * Display an admin notice when the packaged plugin directory cannot be located.
+         */
+        function simimob_missing_bootstrap_notice() {
+            printf(
+                '<div class="notice notice-error"><p>%s</p></div>',
+                esc_html__(
+                    'O plugin Simulador Imobiliário foi instalado sem a pasta interna "simulador-imobiliario". Faça o download do arquivo ZIP oficial (simulador-imobiliario-v1.0.3.zip) e instale-o novamente.',
+                    'simulador-imobiliario'
+                )
+            );
+        }
+    }
+
+    add_action( 'admin_notices', 'simimob_missing_bootstrap_notice' );
+    add_action( 'network_admin_notices', 'simimob_missing_bootstrap_notice' );
+}
 
EOF
)
