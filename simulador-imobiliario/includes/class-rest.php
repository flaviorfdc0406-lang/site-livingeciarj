 (cd "$(git rev-parse --show-toplevel)" && git apply --3way <<'EOF' 
diff --git a//dev/null b/simulador-imobiliario/includes/class-rest.php
index 0000000000000000000000000000000000000000..6ce2fb55e5c94c6b16d6d7b5a4cbdac749d96a6b 100644
--- a//dev/null
+++ b/simulador-imobiliario/includes/class-rest.php
@@ -0,0 +1,178 @@
+<?php
+/**
+ * REST endpoints for Simulador Imobiliário.
+ *
+ * @package Simulador_Imobiliario
+ */
+
+if ( ! defined( 'ABSPATH' ) ) {
+    exit;
+}
+
+class SIMIMOB_REST {
+
+    /**
+     * Instance.
+     *
+     * @var SIMIMOB_REST|null
+     */
+    protected static $instance = null;
+
+    /**
+     * Namespace.
+     */
+    const REST_NAMESPACE = 'simimob/v1';
+
+    /**
+     * Get singleton instance.
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
+        add_action( 'rest_api_init', array( $this, 'register_routes' ) );
+    }
+
+    /**
+     * Register REST routes.
+     */
+    public function register_routes() {
+        $options = SIMIMOB_Settings::get_options();
+        if ( empty( $options['rest_enabled'] ) ) {
+            return;
+        }
+
+        register_rest_route(
+            self::REST_NAMESPACE,
+            '/financiamento/simular',
+            array(
+                'methods'             => WP_REST_Server::CREATABLE,
+                'callback'            => array( $this, 'simulate_financiamento' ),
+                'permission_callback' => '__return_true',
+                'args'                => array(
+                    'modalidade' => array(
+                        'required' => true,
+                        'sanitize_callback' => 'sanitize_text_field',
+                    ),
+                    'renda' => array(
+                        'required' => true,
+                        'sanitize_callback' => 'floatval',
+                    ),
+                    'idade' => array(
+                        'required' => true,
+                        'sanitize_callback' => 'absint',
+                    ),
+                    'valor_imovel' => array(
+                        'required' => true,
+                        'sanitize_callback' => 'floatval',
+                    ),
+                    'prazo' => array(
+                        'required' => false,
+                        'sanitize_callback' => 'absint',
+                    ),
+                ),
+            )
+        );
+
+        register_rest_route(
+            self::REST_NAMESPACE,
+            '/obras/simular',
+            array(
+                'methods'             => WP_REST_Server::CREATABLE,
+                'callback'            => array( $this, 'simulate_obras' ),
+                'permission_callback' => '__return_true',
+                'args'                => array(
+                    'valor_imovel'        => array( 'required' => true, 'sanitize_callback' => 'floatval' ),
+                    'valor_financiamento' => array( 'required' => true, 'sanitize_callback' => 'floatval' ),
+                    'entrega'             => array( 'required' => true, 'sanitize_callback' => 'sanitize_text_field' ),
+                ),
+            )
+        );
+
+        register_rest_route(
+            self::REST_NAMESPACE,
+            '/config',
+            array(
+                'methods'             => WP_REST_Server::READABLE,
+                'callback'            => array( $this, 'get_config' ),
+                'permission_callback' => '__return_true',
+            )
+        );
+    }
+
+    /**
+     * Financing simulation endpoint.
+     */
+    public function simulate_financiamento( WP_REST_Request $request ) {
+        $modalidade = $request->get_param( 'modalidade' );
+        $renda      = (float) $request->get_param( 'renda' );
+        $idade      = (int) $request->get_param( 'idade' );
+        $valor      = (float) $request->get_param( 'valor_imovel' );
+        $prazo      = (int) $request->get_param( 'prazo' );
+
+        $params = SIMIMOB_Settings::get_public_settings();
+        $modalParams = isset( $params['modalidadeParams'][ $modalidade ] ) ? $params['modalidadeParams'][ $modalidade ] : array();
+        $ltv = isset( $modalParams['ltv_max'] ) ? floatval( $modalParams['ltv_max'] ) / 100 : 0.8;
+        $rendaPercent = isset( $modalParams['renda_percent'] ) ? floatval( $modalParams['renda_percent'] ) / 100 : 0.3;
+        $taxaAm = isset( $modalParams['taxa_am'] ) ? floatval( $modalParams['taxa_am'] ) / 100 : 0.008;
+        $prazoMax = isset( $modalParams['prazo_max'] ) ? (int) $modalParams['prazo_max'] : 360;
+        $idadeMax = isset( $modalParams['idade_max'] ) ? (int) $modalParams['idade_max'] : 80;
+        $entradaMin = isset( $modalParams['entrada_min'] ) ? floatval( $modalParams['entrada_min'] ) / 100 : 0;
+
+        $prazo = $prazo ? min( $prazo, $prazoMax ) : $prazoMax;
+        $pmtMax = $renda * $rendaPercent;
+        $credito = simimob_calc_pv( $taxaAm, $prazo, -$pmtMax );
+        $credito = min( $credito, $valor * $ltv );
+        $entrada = $valor * $entradaMin;
+
+        $idadeFim = $idade + ( $prazo / 12 );
+        $idadeValida = $idadeFim <= $idadeMax;
+
+        return rest_ensure_response(
+            array(
+                'credito' => $credito,
+                'parcela' => simimob_calc_pmt( $taxaAm, $prazo, $credito ),
+                'prazo'   => $prazo,
+                'taxa'    => $taxaAm,
+                'entrada' => $entrada,
+                'idade_valida' => $idadeValida,
+            )
+        );
+    }
+
+    /**
+     * Obras simulation endpoint.
+     */
+    public function simulate_obras( WP_REST_Request $request ) {
+        $valorImovel        = (float) $request->get_param( 'valor_imovel' );
+        $valorFinanciamento = (float) $request->get_param( 'valor_financiamento' );
+        $entrega            = $request->get_param( 'entrega' );
+
+        $saldo = max( 0, $valorImovel - $valorFinanciamento );
+        $meses = simimob_months_until( $entrega );
+
+        return rest_ensure_response(
+            array(
+                'saldo'        => $saldo,
+                'meses'        => $meses,
+                'semestrais'   => floor( $meses / 6 ),
+                'anuais'       => floor( $meses / 12 ),
+            )
+        );
+    }
+
+    /**
+     * Public config endpoint.
+     */
+    public function get_config() {
+        return rest_ensure_response( SIMIMOB_Settings::get_public_settings() );
+    }
+}
 
EOF
)
