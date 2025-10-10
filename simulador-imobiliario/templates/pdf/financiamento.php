 (cd "$(git rev-parse --show-toplevel)" && git apply --3way <<'EOF' 
diff --git a//dev/null b/simulador-imobiliario/templates/pdf/financiamento.php
index 0000000000000000000000000000000000000000..091d4fdebe8f357b53dbb5973d48fbae3d56d806 100644
--- a//dev/null
+++ b/simulador-imobiliario/templates/pdf/financiamento.php
@@ -0,0 +1,14 @@
+<?php
+/**
+ * PDF template placeholder for financing.
+ */
+?>
+<div class="simimob-print-header">
+    <?php echo wp_kses_post( simimob_get_option( 'pdf_header', '' ) ); ?>
+</div>
+<div class="simimob-print-body">
+    <h1><?php esc_html_e( 'Simulação de Financiamento', 'simulador-imobiliario' ); ?></h1>
+</div>
+<div class="simimob-print-footer">
+    <?php echo wp_kses_post( simimob_get_option( 'pdf_footer', '' ) ); ?>
+</div>
 
EOF
)
