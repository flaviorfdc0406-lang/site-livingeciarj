 (cd "$(git rev-parse --show-toplevel)" && git apply --3way <<'EOF' 
diff --git a//dev/null b/simulador-imobiliario/templates/wizard.php
index 0000000000000000000000000000000000000000..3673279af257c904b1b4486be953ef3dbbc54b7d 100644
--- a//dev/null
+++ b/simulador-imobiliario/templates/wizard.php
@@ -0,0 +1,53 @@
+<?php
+/**
+ * Unified wizard template.
+ */
+$settings = SIMIMOB_Settings::get_public_settings();
+?>
+<div class="simimob-simulador" data-type="wizard" data-label-credito="<?php esc_attr_e( 'Crédito máximo', 'simulador-imobiliario' ); ?>" data-label-parcela="<?php esc_attr_e( 'Parcela estimada', 'simulador-imobiliario' ); ?>" data-label-prazo="<?php esc_attr_e( 'Prazo', 'simulador-imobiliario' ); ?>" data-label-taxa="<?php esc_attr_e( 'Taxa', 'simulador-imobiliario' ); ?>" data-label-entrada="<?php esc_attr_e( 'Entrada mínima', 'simulador-imobiliario' ); ?>" data-label-idade="<?php esc_attr_e( 'Idade/prazo excedem a política', 'simulador-imobiliario' ); ?>" data-label-saldo="<?php esc_attr_e( 'Saldo de obras', 'simulador-imobiliario' ); ?>" data-label-meses="<?php esc_attr_e( 'Meses até a entrega', 'simulador-imobiliario' ); ?>" data-label-semestrais="<?php esc_attr_e( 'Semestrais possíveis', 'simulador-imobiliario' ); ?>" data-label-anuais="<?php esc_attr_e( 'Anuais possíveis', 'simulador-imobiliario' ); ?>" data-mensagem-intermediaria="<?php echo esc_attr__( 'Esta parcela intermediária está muito superior ao valor das parcelas mensais, a política comercial do vendedor não permite uma diferença entre parcelas tão discrepante, aumente o valor da parcela mensal para enquadrar a proposta a política comercial', 'simulador-imobiliario' ); ?>">
+    <div class="simimob-stepper">
+        <button type="button" aria-current="step"><?php esc_html_e( 'Modalidade', 'simulador-imobiliario' ); ?></button>
+        <button type="button"><?php esc_html_e( 'Capacidade', 'simulador-imobiliario' ); ?></button>
+        <button type="button"><?php esc_html_e( 'Imóvel', 'simulador-imobiliario' ); ?></button>
+        <button type="button"><?php esc_html_e( 'Saldo de obras', 'simulador-imobiliario' ); ?></button>
+        <button type="button"><?php esc_html_e( 'Resumo', 'simulador-imobiliario' ); ?></button>
+    </div>
+    <div class="simimob-step active">
+        <?php include SIMIMOB_PLUGIN_DIR . 'templates/partials/modalidades.php'; ?>
+        <div class="simimob-actions">
+            <button type="button" data-next-step><?php esc_html_e( 'Continuar', 'simulador-imobiliario' ); ?></button>
+        </div>
+    </div>
+    <div class="simimob-step">
+        <?php include SIMIMOB_PLUGIN_DIR . 'templates/partials/capacidade.php'; ?>
+        <div class="simimob-actions">
+            <button type="button" data-prev-step><?php esc_html_e( 'Voltar', 'simulador-imobiliario' ); ?></button>
+            <button type="button" data-next-step><?php esc_html_e( 'Continuar', 'simulador-imobiliario' ); ?></button>
+        </div>
+    </div>
+    <div class="simimob-step">
+        <?php include SIMIMOB_PLUGIN_DIR . 'templates/partials/imovel.php'; ?>
+        <div class="simimob-actions">
+            <button type="button" data-prev-step><?php esc_html_e( 'Voltar', 'simulador-imobiliario' ); ?></button>
+            <button type="button" data-next-step><?php esc_html_e( 'Continuar', 'simulador-imobiliario' ); ?></button>
+        </div>
+    </div>
+    <div class="simimob-step">
+        <?php include SIMIMOB_PLUGIN_DIR . 'templates/partials/obras.php'; ?>
+        <div class="simimob-actions">
+            <button type="button" data-prev-step><?php esc_html_e( 'Voltar', 'simulador-imobiliario' ); ?></button>
+            <button type="button" data-next-step><?php esc_html_e( 'Resumo', 'simulador-imobiliario' ); ?></button>
+        </div>
+    </div>
+    <div class="simimob-step">
+        <h3><?php esc_html_e( 'Resumo final', 'simulador-imobiliario' ); ?></h3>
+        <div class="simimob-summary">
+            <p><?php esc_html_e( 'Veja os resultados da simulação completa. Utilize as ações para compartilhar.', 'simulador-imobiliario' ); ?></p>
+        </div>
+        <div class="simimob-actions">
+            <button type="button" data-prev-step><?php esc_html_e( 'Voltar', 'simulador-imobiliario' ); ?></button>
+            <button type="button" data-share="whatsapp"><?php esc_html_e( 'Compartilhar no WhatsApp', 'simulador-imobiliario' ); ?></button>
+            <button type="button" data-share="email"><?php esc_html_e( 'Enviar por e-mail', 'simulador-imobiliario' ); ?></button>
+        </div>
+    </div>
+</div>
 
EOF
)
