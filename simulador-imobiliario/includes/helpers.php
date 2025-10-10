 (cd "$(git rev-parse --show-toplevel)" && git apply --3way <<'EOF' 
diff --git a//dev/null b/simulador-imobiliario/includes/helpers.php
index 0000000000000000000000000000000000000000..3d3a06d7b2e2238cb88283c17af3256f6699142d 100644
--- a//dev/null
+++ b/simulador-imobiliario/includes/helpers.php
@@ -0,0 +1,171 @@
+<?php
+/**
+ * Helper functions for Simulador Imobiliário.
+ *
+ * @package Simulador_Imobiliario
+ */
+
+if ( ! defined( 'ABSPATH' ) ) {
+    exit;
+}
+
+/**
+ * Get plugin option with default.
+ *
+ * @param string $key Option key.
+ * @param mixed  $default Default value.
+ *
+ * @return mixed
+ */
+function simimob_get_option( $key, $default = false ) {
+    $options = get_option( 'simimob_settings', array() );
+
+    if ( isset( $options[ $key ] ) ) {
+        return $options[ $key ];
+    }
+
+    return $default;
+}
+
+/**
+ * Sanitize currency value (BRL).
+ *
+ * @param string $value Value.
+ * @return float
+ */
+function simimob_sanitize_currency( $value ) {
+    $value = preg_replace( '/[^0-9,.-]/', '', (string) $value );
+    $value = str_replace( '.', '', $value );
+    $value = str_replace( ',', '.', $value );
+
+    return (float) $value;
+}
+
+/**
+ * Format currency to BRL.
+ *
+ * @param float $value Value.
+ * @return string
+ */
+function simimob_format_currency( $value ) {
+    return 'R$ ' . number_format( (float) $value, 2, ',', '.' );
+}
+
+/**
+ * Calculate PMT (annuity payment).
+ *
+ * @param float $rate Monthly interest rate.
+ * @param int   $nper Number of payments.
+ * @param float $pv Present value.
+ *
+ * @return float
+ */
+function simimob_calc_pmt( $rate, $nper, $pv ) {
+    if ( 0.0 === (float) $rate ) {
+        return ( $pv / $nper ) * -1;
+    }
+
+    $pmt = ( $rate * $pv ) / ( 1 - pow( 1 + $rate, -$nper ) );
+
+    return -1 * $pmt;
+}
+
+/**
+ * Calculate PV (present value) from PMT.
+ *
+ * @param float $rate Monthly interest rate.
+ * @param int   $nper Number of payments.
+ * @param float $pmt Payment amount.
+ *
+ * @return float
+ */
+function simimob_calc_pv( $rate, $nper, $pmt ) {
+    if ( 0.0 === (float) $rate ) {
+        return -1 * $pmt * $nper;
+    }
+
+    $pv = $pmt * ( 1 - pow( 1 + $rate, -$nper ) ) / $rate;
+
+    return -1 * $pv;
+}
+
+/**
+ * Calculate months between now and target date (inclusive current month).
+ *
+ * @param string $target YYYY-MM.
+ *
+ * @return int
+ */
+function simimob_months_until( $target ) {
+    try {
+        $tz_string = simimob_get_option( 'timezone', get_option( 'timezone_string', 'America/Sao_Paulo' ) );
+        $timezone  = $tz_string ? new DateTimeZone( $tz_string ) : wp_timezone();
+        $current = new DateTimeImmutable( 'first day of this month', $timezone );
+        $target_dt = DateTimeImmutable::createFromFormat( 'Y-m', $target, $timezone );
+        if ( ! $target_dt ) {
+            return 0;
+        }
+        $target_dt = $target_dt->setTime( 0, 0 )->modify( 'first day of this month' );
+        $interval  = $current->diff( $target_dt );
+        $months    = ( (int) $interval->y * 12 ) + (int) $interval->m;
+        return max( 0, $months ) + 1; // inclusive month current
+    } catch ( Exception $e ) {
+        return 0;
+    }
+}
+
+/**
+ * Return empreendimentos catalog.
+ *
+ * @return array
+ */
+function simimob_get_empreendimentos() {
+    $data = simimob_get_option( 'empreendimentos', array() );
+
+    if ( ! is_array( $data ) ) {
+        $data = array();
+    }
+
+    return $data;
+}
+
+/**
+ * Prepare sanitized empreendimentos.
+ *
+ * @param array $input Raw input.
+ *
+ * @return array
+ */
+function simimob_sanitize_empreendimentos( $input ) {
+    $output = array();
+
+    if ( ! is_array( $input ) ) {
+        return $output;
+    }
+
+    foreach ( $input as $item ) {
+        if ( empty( $item['nome'] ) ) {
+            continue;
+        }
+
+        $output[] = array(
+            'nome'          => sanitize_text_field( $item['nome'] ),
+            'prosoluto'     => isset( $item['prosoluto'] ) ? floatval( $item['prosoluto'] ) : 0,
+            'meses_obra'    => isset( $item['meses_obra'] ) ? absint( $item['meses_obra'] ) : 0,
+            'entrega_padrao'=> isset( $item['entrega_padrao'] ) ? sanitize_text_field( $item['entrega_padrao'] ) : '',
+            'politica'      => isset( $item['politica'] ) ? wp_kses_post( $item['politica'] ) : '',
+        );
+    }
+
+    return $output;
+}
+
+/**
+ * Normalize boolean value from options.
+ *
+ * @param mixed $value Value.
+ * @return bool
+ */
+function simimob_to_bool( $value ) {
+    return ! empty( $value ) && 'false' !== $value && '0' !== $value;
+}
 
EOF
)
