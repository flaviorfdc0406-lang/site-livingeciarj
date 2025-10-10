 (cd "$(git rev-parse --show-toplevel)" && git apply --3way <<'EOF' 
diff --git a//dev/null b/simulador-imobiliario/includes/class-elementor-financiamento.php
index 0000000000000000000000000000000000000000..2f956e04b6c4addb691d31d58a7eec2be89eafea 100644
--- a//dev/null
+++ b/simulador-imobiliario/includes/class-elementor-financiamento.php
@@ -0,0 +1,74 @@
+<?php
+/**
+ * Elementor widget for financing simulator.
+ */
+
+if ( ! defined( 'ABSPATH' ) ) {
+    exit;
+}
+
+use Elementor\Widget_Base;
+use Elementor\Controls_Manager;
+
+class SIMIMOB_Elementor_Financiamento extends Widget_Base {
+
+    /**
+     * Singleton registration.
+     */
+    public static function get_instance() {
+        static $registered = false;
+        if ( ! $registered ) {
+            add_action( 'elementor/widgets/register', array( __CLASS__, 'register_widget' ) );
+            $registered = true;
+        }
+        return new self();
+    }
+
+    /**
+     * Register widget.
+     */
+    public static function register_widget( $widgets_manager ) {
+        $widgets_manager->register( new self() );
+    }
+
+    public function get_name() {
+        return 'simimob-financiamento';
+    }
+
+    public function get_title() {
+        return __( 'Simulador de Financiamento', 'simulador-imobiliario' );
+    }
+
+    public function get_icon() {
+        return 'eicon-calculator';
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
+            'mostrar_capacidade',
+            array(
+                'label'        => __( 'Exibir etapa de capacidade de crédito', 'simulador-imobiliario' ),
+                'type'         => Controls_Manager::SWITCHER,
+                'label_on'     => __( 'Sim', 'simulador-imobiliario' ),
+                'label_off'    => __( 'Não', 'simulador-imobiliario' ),
+                'return_value' => 'yes',
+                'default'      => 'yes',
+            )
+        );
+        $this->end_controls_section();
+    }
+
+    protected function render() {
+        echo do_shortcode( '[simulador_financiamento]' );
+    }
+}
 
EOF
)
  
