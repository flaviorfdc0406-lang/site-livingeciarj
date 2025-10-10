 (cd "$(git rev-parse --show-toplevel)" && git apply --3way <<'EOF' 
diff --git a//dev/null b/simulador-imobiliario/templates/partials/imovel.php
index 0000000000000000000000000000000000000000..e17a1f0db452ce57147cf998cc843fc39ba671e1 100644
--- a//dev/null
+++ b/simulador-imobiliario/templates/partials/imovel.php
@@ -0,0 +1,20 @@
+<div class="simimob-field">
+    <label for="wizard-valor-imovel"><?php esc_html_e( 'Valor do imóvel', 'simulador-imobiliario' ); ?></label>
+    <input data-mask="currency" type="text" id="wizard-valor-imovel" name="obras_valor_imovel" />
+</div>
+<div class="simimob-field">
+    <label for="wizard-valor-financiamento"><?php esc_html_e( 'Valor do financiamento', 'simulador-imobiliario' ); ?></label>
+    <input data-mask="currency" type="text" id="wizard-valor-financiamento" name="obras_valor_financiamento" />
+</div>
+<div class="simimob-field">
+    <label for="wizard-entrega"><?php esc_html_e( 'Entrega (mês/ano)', 'simulador-imobiliario' ); ?></label>
+    <input type="month" id="wizard-entrega" name="obras_entrega" />
+</div>
+<div class="simimob-field">
+    <label for="wizard-empreendimento"><?php esc_html_e( 'Empreendimento', 'simulador-imobiliario' ); ?></label>
+    <input type="text" id="wizard-empreendimento" name="obras_empreendimento" />
+</div>
+<div class="simimob-field">
+    <label for="wizard-unidade"><?php esc_html_e( 'Unidade', 'simulador-imobiliario' ); ?></label>
+    <input type="text" id="wizard-unidade" name="obras_unidade" />
+</div>
 
EOF
)
