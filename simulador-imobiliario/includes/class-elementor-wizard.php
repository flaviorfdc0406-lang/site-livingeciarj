 (cd "$(git rev-parse --show-toplevel)" && git apply --3way <<'EOF' 
diff --git a//dev/null b/simulador-imobiliario/includes/class-elementor-wizard.php
index 0000000000000000000000000000000000000000..56c35d5099ae71489d0c536cce9376aff0ed0d5c 100644
--- a//dev/null
+++ b/simulador-imobiliario/includes/class-elementor-wizard.php
@@ -0,0 +1,66 @@
+<?php
+/**
+ * Elementor widget for unified wizard.
+ */
+
+if ( ! defined( 'ABSPATH' ) ) {
+    exit;
+}
+
+use Elementor\Widget_Base;
+use Elementor\Controls_Manager;
+
+class SIMIMOB_Elementor_Wizard extends Widget_Base {
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
+        return 'simimob-wizard';
+    }
+
+    public function get_title() {
+        return __( 'Simulador Imobiliário (Wizard)', 'simulador-imobiliario' );
+    }
+
+    public function get_icon() {
+        return 'eicon-tabs';
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
+            'exibir_obras',
+            array(
+                'label'        => __( 'Incluir simulador de obras', 'simulador-imobiliario' ),
+                'type'         => Controls_Manager::SWITCHER,
+                'default'      => 'yes',
+                'return_value' => 'yes',
+            )
+        );
+        $this->end_controls_section();
+    }
+
+    protected function render() {
+        echo do_shortcode( '[simulador_imobiliario]' );
+    }
+}
 
EOF
)
