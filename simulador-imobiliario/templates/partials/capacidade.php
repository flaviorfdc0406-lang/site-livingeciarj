 (cd "$(git rev-parse --show-toplevel)" && git apply --3way <<'EOF' 
diff --git a//dev/null b/simulador-imobiliario/templates/partials/capacidade.php
index 0000000000000000000000000000000000000000..aaf79c7b3e33c0c998a3b454bcbd5fe8dbb078df 100644
--- a//dev/null
+++ b/simulador-imobiliario/templates/partials/capacidade.php
@@ -0,0 +1,17 @@
+<div class="simimob-field">
+    <label for="wizard-renda"><?php esc_html_e( 'Renda familiar bruta mensal', 'simulador-imobiliario' ); ?></label>
+    <input data-mask="currency" type="text" id="wizard-renda" name="renda_familiar" />
+</div>
+<div class="simimob-field">
+    <label for="wizard-idade"><?php esc_html_e( 'Idade do comprador', 'simulador-imobiliario' ); ?></label>
+    <input type="number" id="wizard-idade" name="idade" />
+</div>
+<div class="simimob-field">
+    <label for="wizard-valor"><?php esc_html_e( 'Valor do imóvel', 'simulador-imobiliario' ); ?></label>
+    <input data-mask="currency" type="text" id="wizard-valor" name="valor_imovel" />
+</div>
+<div class="simimob-field">
+    <label for="wizard-prazo"><?php esc_html_e( 'Prazo pretendido (meses)', 'simulador-imobiliario' ); ?></label>
+    <input type="number" id="wizard-prazo" name="prazo" />
+</div>
+<div class="simimob-summary simimob-capacidade-resultado"></div>
 
EOF
)
