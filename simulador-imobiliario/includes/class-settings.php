 (cd "$(git rev-parse --show-toplevel)" && git apply --3way <<'EOF' 
diff --git a//dev/null b/simulador-imobiliario/includes/class-settings.php
index 0000000000000000000000000000000000000000..073fc880563da43ee06589899c1569213498c2d2 100644
--- a//dev/null
+++ b/simulador-imobiliario/includes/class-settings.php
@@ -0,0 +1,229 @@
+<?php
+/**
+ * Settings handler for Simulador Imobiliário.
+ *
+ * @package Simulador_Imobiliario
+ */
+
+if ( ! defined( 'ABSPATH' ) ) {
+    exit;
+}
+
+class SIMIMOB_Settings {
+
+    /**
+     * Instance holder.
+     *
+     * @var SIMIMOB_Settings|null
+     */
+    protected static $instance = null;
+
+    /**
+     * Option key.
+     */
+    const OPTION_KEY = 'simimob_settings';
+
+    /**
+     * Return singleton instance.
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
+        add_action( 'admin_init', array( $this, 'register_settings' ) );
+    }
+
+    /**
+     * Register settings using Settings API.
+     */
+    public function register_settings() {
+        register_setting(
+            'simimob_settings_group',
+            self::OPTION_KEY,
+            array(
+                'sanitize_callback' => array( $this, 'sanitize_settings' ),
+                'default'           => $this->get_defaults(),
+            )
+        );
+    }
+
+    /**
+     * Default settings values.
+     *
+     * @return array
+     */
+    public function get_defaults() {
+        return array(
+            'primary_color'             => '',
+            'typography'                => '',
+            'logo_pdf'                  => '',
+            'timezone'                  => get_option( 'timezone_string', 'America/Sao_Paulo' ),
+            'share_whatsapp'            => __( 'Olá {cliente}, segue a simulação para o empreendimento {empreendimento}. Saldo: {saldo}', 'simulador-imobiliario' ),
+            'share_email_subject'       => __( 'Simulação do empreendimento {empreendimento}', 'simulador-imobiliario' ),
+            'share_email_body'          => __( 'Olá {cliente}, confira a simulação do empreendimento {empreendimento}.', 'simulador-imobiliario' ),
+            'modalidades'               => array( 'A', 'B', 'C', 'D', 'E' ),
+            'modalidade_params'         => array(),
+            'permitir_pular_capacidade' => false,
+            'coeficientes'              => array(),
+            'intermediaria_limite'      => 10,
+            'entrada_minima'            => 0,
+            'entrada_maxima'            => '',
+            'mensal_minima'             => '',
+            'mensal_maxima'             => '',
+            'empreendimentos'           => array(),
+            'pdf_ativo'                 => true,
+            'pdf_header'                => '',
+            'pdf_footer'                => '',
+            'pdf_marca_dagua'           => '',
+            'rest_enabled'              => false,
+            'export_string'             => '',
+            'telemetria'                => false,
+            'limpeza'                   => false,
+        );
+    }
+
+    /**
+     * Sanitize settings input.
+     *
+     * @param array $input Raw input.
+     *
+     * @return array
+     */
+    public function sanitize_settings( $input ) {
+        $defaults = $this->get_defaults();
+        $output   = wp_parse_args( $input, $defaults );
+
+        $output['primary_color']             = sanitize_hex_color( $output['primary_color'] );
+        $output['typography']                = sanitize_text_field( $output['typography'] );
+        $output['logo_pdf']                  = esc_url_raw( $output['logo_pdf'] );
+        $output['timezone']                  = sanitize_text_field( $output['timezone'] );
+        $output['share_whatsapp']            = wp_kses_post( $output['share_whatsapp'] );
+        $output['share_email_subject']       = sanitize_text_field( $output['share_email_subject'] );
+        $output['share_email_body']          = wp_kses_post( $output['share_email_body'] );
+        $output['modalidades']               = array_map( 'sanitize_text_field', (array) $output['modalidades'] );
+        $output['permitir_pular_capacidade'] = simimob_to_bool( $output['permitir_pular_capacidade'] );
+        $output['intermediaria_limite']      = floatval( $output['intermediaria_limite'] );
+        $output['entrada_minima']            = floatval( $output['entrada_minima'] );
+        $output['entrada_maxima']            = '' !== $output['entrada_maxima'] ? floatval( $output['entrada_maxima'] ) : '';
+        $output['mensal_minima']             = '' !== $output['mensal_minima'] ? floatval( $output['mensal_minima'] ) : '';
+        $output['mensal_maxima']             = '' !== $output['mensal_maxima'] ? floatval( $output['mensal_maxima'] ) : '';
+        $output['pdf_ativo']                 = simimob_to_bool( $output['pdf_ativo'] );
+        $output['rest_enabled']              = simimob_to_bool( $output['rest_enabled'] );
+        $output['telemetria']                = simimob_to_bool( $output['telemetria'] );
+        $output['limpeza']                   = simimob_to_bool( $output['limpeza'] );
+        $output['pdf_header']                = wp_kses_post( $output['pdf_header'] );
+        $output['pdf_footer']                = wp_kses_post( $output['pdf_footer'] );
+        $output['pdf_marca_dagua']           = sanitize_text_field( $output['pdf_marca_dagua'] );
+
+        $output['modalidade_params'] = array();
+        if ( isset( $input['modalidade_params'] ) && is_array( $input['modalidade_params'] ) ) {
+            foreach ( $input['modalidade_params'] as $key => $params ) {
+                $output['modalidade_params'][ sanitize_text_field( $key ) ] = array(
+                    'ltv_max'       => isset( $params['ltv_max'] ) ? floatval( $params['ltv_max'] ) : 0,
+                    'renda_percent' => isset( $params['renda_percent'] ) ? floatval( $params['renda_percent'] ) : 0,
+                    'taxa_aa'       => isset( $params['taxa_aa'] ) ? floatval( $params['taxa_aa'] ) : 0,
+                    'taxa_am'       => isset( $params['taxa_am'] ) ? floatval( $params['taxa_am'] ) : 0,
+                    'prazo_max'     => isset( $params['prazo_max'] ) ? absint( $params['prazo_max'] ) : 0,
+                    'idade_max'     => isset( $params['idade_max'] ) ? absint( $params['idade_max'] ) : 80,
+                    'entrada_min'   => isset( $params['entrada_min'] ) ? floatval( $params['entrada_min'] ) : 0,
+                    'descricao'     => isset( $params['descricao'] ) ? sanitize_text_field( $params['descricao'] ) : '',
+                );
+            }
+        }
+
+        $output['coeficientes'] = array();
+        if ( isset( $input['coeficientes'] ) && is_array( $input['coeficientes'] ) ) {
+            foreach ( $input['coeficientes'] as $coef ) {
+                $output['coeficientes'][] = array(
+                    'faixa'       => isset( $coef['faixa'] ) ? sanitize_text_field( $coef['faixa'] ) : '',
+                    'idade'       => isset( $coef['idade'] ) ? sanitize_text_field( $coef['idade'] ) : '',
+                    'coeficiente' => isset( $coef['coeficiente'] ) ? floatval( $coef['coeficiente'] ) : 0,
+                );
+            }
+        }
+
+        $output['empreendimentos'] = simimob_sanitize_empreendimentos( isset( $input['empreendimentos'] ) ? $input['empreendimentos'] : array() );
+
+        if ( isset( $input['export_string'] ) && ! empty( $input['export_string'] ) ) {
+            $decoded = json_decode( wp_unslash( $input['export_string'] ), true );
+            if ( is_array( $decoded ) ) {
+                $output = wp_parse_args( $decoded, $output );
+            }
+        }
+
+        $output['export_string'] = '';
+
+        return $output;
+    }
+
+    /**
+     * Get options merged with defaults.
+     *
+     * @return array
+     */
+    public static function get_options() {
+        $instance = self::get_instance();
+        $defaults = $instance->get_defaults();
+        $options  = get_option( self::OPTION_KEY, array() );
+
+        return wp_parse_args( $options, $defaults );
+    }
+
+    /**
+     * Public settings for front-end.
+     *
+     * @return array
+     */
+    public static function get_public_settings() {
+        $options = self::get_options();
+
+        return array(
+            'primaryColor'            => ! empty( $options['primary_color'] ) ? $options['primary_color'] : '',
+            'permitirPularCapacidade' => (bool) $options['permitir_pular_capacidade'],
+            'intermediariaLimite'     => floatval( $options['intermediaria_limite'] ),
+            'entradaMinima'           => floatval( $options['entrada_minima'] ),
+            'entradaMaxima'           => '' === $options['entrada_maxima'] ? '' : floatval( $options['entrada_maxima'] ),
+            'mensalMinima'            => '' === $options['mensal_minima'] ? '' : floatval( $options['mensal_minima'] ),
+            'mensalMaxima'            => '' === $options['mensal_maxima'] ? '' : floatval( $options['mensal_maxima'] ),
+            'modalidades'             => $options['modalidades'],
+            'modalidadeParams'        => $options['modalidade_params'],
+            'coeficientes'            => $options['coeficientes'],
+            'pdfAtivo'                => (bool) $options['pdf_ativo'],
+            'shareWhatsapp'           => $options['share_whatsapp'],
+            'shareEmailSubject'       => $options['share_email_subject'],
+            'shareEmailBody'          => $options['share_email_body'],
+            'empreendimentos'         => $options['empreendimentos'],
+        );
+    }
+
+    /**
+     * Activation handler.
+     */
+    public static function activate() {
+        $instance = self::get_instance();
+        $defaults = $instance->get_defaults();
+        $options  = get_option( self::OPTION_KEY, array() );
+
+        if ( empty( $options ) ) {
+            update_option( self::OPTION_KEY, $defaults );
+        }
+    }
+
+    /**
+     * Remove options when uninstall if requested.
+     */
+    public static function uninstall() {
+        $options = self::get_options();
+        if ( ! empty( $options['limpeza'] ) ) {
+            delete_option( self::OPTION_KEY );
+        }
+    }
+}
 
EOF
)
