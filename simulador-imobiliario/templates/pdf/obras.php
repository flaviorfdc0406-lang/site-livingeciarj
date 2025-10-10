 (cd "$(git rev-parse --show-toplevel)" && git apply --3way <<'EOF' 
diff --git a//dev/null b/simulador-imobiliario/templates/pdf/obras.php
index 0000000000000000000000000000000000000000..8a0ec26f01bc955f8edf9851ac45914829de1ecc 100644
--- a//dev/null
+++ b/simulador-imobiliario/templates/pdf/obras.php
@@ -0,0 +1,14 @@
+<?php
+/**
+ * PDF template placeholder for obras.
+ */
+?>
+<div class="simimob-print-header">
+    <?php echo wp_kses_post( simimob_get_option( 'pdf_header', '' ) ); ?>
+</div>
+<div class="simimob-print-body">
+    <h1><?php esc_html_e( 'Simulação de Saldo de Obras', 'simulador-imobiliario' ); ?></h1>
+</div>
+<div class="simimob-print-footer">
+    <?php echo wp_kses_post( simimob_get_option( 'pdf_footer', '' ) ); ?>
+</div>
 
EOF
)
