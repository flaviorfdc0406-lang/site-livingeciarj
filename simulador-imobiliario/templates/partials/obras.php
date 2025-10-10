 (cd "$(git rev-parse --show-toplevel)" && git apply --3way <<'EOF' 
diff --git a//dev/null b/simulador-imobiliario/templates/partials/obras.php
index 0000000000000000000000000000000000000000..18e64a61a4ca2a53e9e1a1af37d7b9d80cb53fd0 100644
--- a//dev/null
+++ b/simulador-imobiliario/templates/partials/obras.php
@@ -0,0 +1,31 @@
+<div class="simimob-summary simimob-obras-resumo"></div>
+<div class="simimob-field">
+    <label><?php esc_html_e( 'Sinal (R$)', 'simulador-imobiliario' ); ?></label>
+    <input data-mask="currency" type="text" name="obras_sinal" />
+</div>
+<div class="simimob-field">
+    <label><?php esc_html_e( 'Mensal (R$)', 'simulador-imobiliario' ); ?></label>
+    <input data-mask="currency" type="text" name="obras_mensal" />
+</div>
+<div class="simimob-field">
+    <label><?php esc_html_e( 'Tipo de intermediárias', 'simulador-imobiliario' ); ?></label>
+    <select name="obras_tipo_intermediaria">
+        <option value="semestral"><?php esc_html_e( 'Semestrais', 'simulador-imobiliario' ); ?></option>
+        <option value="anual"><?php esc_html_e( 'Anuais', 'simulador-imobiliario' ); ?></option>
+    </select>
+</div>
+<div class="simimob-field">
+    <label><?php esc_html_e( 'Intermediária (R$)', 'simulador-imobiliario' ); ?></label>
+    <input data-mask="currency" type="text" name="obras_intermediaria" />
+</div>
+<div class="simimob-field">
+    <label><?php esc_html_e( 'Início das intermediárias', 'simulador-imobiliario' ); ?></label>
+    <input type="month" name="obras_inicio_intermediaria" />
+</div>
+<div class="simimob-summary">
+    <p><?php esc_html_e( 'Total sinal:', 'simulador-imobiliario' ); ?> <span class="simimob-total-sinal">R$ 0,00</span></p>
+    <p><?php esc_html_e( 'Total mensais:', 'simulador-imobiliario' ); ?> <span class="simimob-total-mensal">R$ 0,00</span></p>
+    <p><?php esc_html_e( 'Total intermediárias:', 'simulador-imobiliario' ); ?> <span class="simimob-total-intermediaria">R$ 0,00</span></p>
+    <p><?php esc_html_e( 'Valor total de pagamentos:', 'simulador-imobiliario' ); ?> <span class="simimob-total-geral">R$ 0,00</span></p>
+    <p><?php esc_html_e( 'Saldo em aberto:', 'simulador-imobiliario' ); ?> <span class="simimob-saldo-aberto">R$ 0,00</span></p>
+</div>
 
EOF
)
