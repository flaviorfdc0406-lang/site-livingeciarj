 (cd "$(git rev-parse --show-toplevel)" && git apply --3way <<'EOF' 
diff --git a//dev/null b/simulador-imobiliario/includes/class-elementor-obras.php
index 0000000000000000000000000000000000000000..294984e547b4d172e444c486ae74f2af0742d2fd 100644
--- a//dev/null
+++ b/simulador-imobiliario/includes/class-elementor-obras.php
@@ -0,0 +1,64 @@
+<?php
+/**
+ * Elementor widget for obras simulator.
+ */
+
+if ( ! defined( 'ABSPATH' ) ) {
+    exit;
+}
+
+use Elementor\Widget_Base;
+use Elementor\Controls_Manager;
+
+class SIMIMOB_Elementor_Obras extends Widget_Base {
+
+    public static function get_instance() {
+        static $registered = false;
+        if ( ! $registered ) {
+            add_action( 'elementor/widgets/register', array( __CLASS__, 'register_widget' ) );
+            $registered = true;
+        }
+        return new self();
+    }
+
+    public static function register_widget( $widgets_manager ) {
+        $widgets_manager->register( new self() );
+    }
+
+    public function get_name() {
+        return 'simimob-obras';
+    }
+
+    public function get_title() {
+        return __( 'Simulador de Saldo de Obras', 'simulador-imobiliario' );
+    }
+
+    public function get_icon() {
+        return 'eicon-library-open';
+    }
+
+    public function get_categories() {
+        return array( 'general' );
+    }
+
+    protected function register_controls() {
+        $this->start_controls_section(
+            'section_content',
+            array(
+                'label' => __( 'Conteúdo', 'simulador-imobiliario' ),
+            )
+        );
+        $this->add_control(
+            'empreendimento_default',
+            array(
+                'label' => __( 'Empreendimento padrão', 'simulador-imobiliario' ),
+                'type'  => Controls_Manager::TEXT,
+            )
+        );
+        $this->end_controls_section();
+    }
+
+    protected function render() {
+        echo do_shortcode( '[simulador_obras]' );
+    }
+}
 
EOF
)
