 (cd "$(git rev-parse --show-toplevel)" && git apply --3way <<'EOF' 
diff --git a//dev/null b/simulador-imobiliario/templates/obras.php
index 0000000000000000000000000000000000000000..cc019f35fdd881babc9433f86ec105ee01c91e31 100644
--- a//dev/null
+++ b/simulador-imobiliario/templates/obras.php
@@ -0,0 +1,129 @@
+<?php
+/**
+ * Template for obras simulator.
+ */
+$settings = SIMIMOB_Settings::get_public_settings();
+?>
+<div class="simimob-simulador" data-type="obras" data-label-saldo="<?php esc_attr_e( 'Saldo de obras', 'simulador-imobiliario' ); ?>" data-label-meses="<?php esc_attr_e( 'Meses até a entrega', 'simulador-imobiliario' ); ?>" data-label-semestrais="<?php esc_attr_e( 'Semestrais possíveis', 'simulador-imobiliario' ); ?>" data-label-anuais="<?php esc_attr_e( 'Anuais possíveis', 'simulador-imobiliario' ); ?>" data-mensagem-intermediaria="<?php echo esc_attr__( 'Esta parcela intermediária está muito superior ao valor das parcelas mensais, a política comercial do vendedor não permite uma diferença entre parcelas tão discrepante, aumente o valor da parcela mensal para enquadrar a proposta a política comercial', 'simulador-imobiliario' ); ?>">
+    <div class="simimob-stepper">
+        <button type="button" aria-current="step"><?php esc_html_e( 'Dados iniciais', 'simulador-imobiliario' ); ?></button>
+        <button type="button"><?php esc_html_e( 'Resultado', 'simulador-imobiliario' ); ?></button>
+        <button type="button"><?php esc_html_e( 'Identificação', 'simulador-imobiliario' ); ?></button>
+        <button type="button"><?php esc_html_e( 'Distribuição', 'simulador-imobiliario' ); ?></button>
+        <button type="button"><?php esc_html_e( 'Resumo final', 'simulador-imobiliario' ); ?></button>
+    </div>
+    <div class="simimob-step active">
+        <div class="simimob-field">
+            <label><?php esc_html_e( 'Empreendimento', 'simulador-imobiliario' ); ?></label>
+            <input type="text" name="obras_empreendimento" placeholder="<?php esc_attr_e( 'Ex.: Living Parque', 'simulador-imobiliario' ); ?>" />
+        </div>
+        <div class="simimob-field">
+            <label><?php esc_html_e( 'Unidade', 'simulador-imobiliario' ); ?></label>
+            <input type="text" name="obras_unidade" placeholder="<?php esc_attr_e( 'Ex.: Bloco X • Unidade 01/601', 'simulador-imobiliario' ); ?>" />
+        </div>
+        <div class="simimob-field">
+            <label><?php esc_html_e( 'Entrega (mês/ano)', 'simulador-imobiliario' ); ?></label>
+            <input type="month" name="obras_entrega" />
+        </div>
+        <div class="simimob-field">
+            <label><?php esc_html_e( 'Valor do imóvel', 'simulador-imobiliario' ); ?></label>
+            <input data-mask="currency" type="text" name="obras_valor_imovel" />
+        </div>
+        <div class="simimob-field">
+            <label><?php esc_html_e( 'Valor do financiamento', 'simulador-imobiliario' ); ?></label>
+            <input data-mask="currency" type="text" name="obras_valor_financiamento" />
+        </div>
+        <div class="simimob-actions">
+            <button type="button" data-next-step><?php esc_html_e( 'Continuar', 'simulador-imobiliario' ); ?></button>
+        </div>
+    </div>
+    <div class="simimob-step">
+        <h3><?php esc_html_e( 'Resultado', 'simulador-imobiliario' ); ?></h3>
+        <div class="simimob-summary simimob-obras-resumo"></div>
+        <div class="simimob-actions">
+            <button type="button" data-prev-step><?php esc_html_e( 'Voltar', 'simulador-imobiliario' ); ?></button>
+            <button type="button" data-next-step><?php esc_html_e( 'Continuar', 'simulador-imobiliario' ); ?></button>
+        </div>
+    </div>
+    <div class="simimob-step">
+        <div class="simimob-field">
+            <label><?php esc_html_e( 'Nome do corretor(a)', 'simulador-imobiliario' ); ?></label>
+            <input type="text" name="corretor_nome" />
+        </div>
+        <div class="simimob-field">
+            <label><?php esc_html_e( 'E-mail do corretor', 'simulador-imobiliario' ); ?></label>
+            <input type="email" name="corretor_email" />
+        </div>
+        <div class="simimob-field">
+            <label><?php esc_html_e( 'Imobiliária', 'simulador-imobiliario' ); ?></label>
+            <input type="text" name="imobiliaria" />
+        </div>
+        <div class="simimob-field">
+            <label><?php esc_html_e( 'Nome do cliente', 'simulador-imobiliario' ); ?></label>
+            <input type="text" name="cliente_nome" />
+        </div>
+        <div class="simimob-actions">
+            <button type="button" data-prev-step><?php esc_html_e( 'Voltar', 'simulador-imobiliario' ); ?></button>
+            <button type="button" data-next-step><?php esc_html_e( 'Continuar', 'simulador-imobiliario' ); ?></button>
+        </div>
+    </div>
+    <div class="simimob-step">
+        <h3><?php esc_html_e( 'Distribuição do saldo', 'simulador-imobiliario' ); ?></h3>
+        <p class="simimob-aviso-intermediaria" style="display:none;color:#c0392b;font-size:0.85rem;"></p>
+        <div class="simimob-field">
+            <label><?php esc_html_e( 'Sinal (R$)', 'simulador-imobiliario' ); ?></label>
+            <input data-mask="currency" type="text" name="obras_sinal" />
+        </div>
+        <div class="simimob-field">
+            <label><?php esc_html_e( 'Mensal (R$)', 'simulador-imobiliario' ); ?></label>
+            <input data-mask="currency" type="text" name="obras_mensal" />
+        </div>
+        <div class="simimob-field">
+            <label><?php esc_html_e( 'Tipo de intermediárias', 'simulador-imobiliario' ); ?></label>
+            <select name="obras_tipo_intermediaria">
+                <option value="semestral"><?php esc_html_e( 'Semestrais', 'simulador-imobiliario' ); ?></option>
+                <option value="anual"><?php esc_html_e( 'Anuais', 'simulador-imobiliario' ); ?></option>
+            </select>
+        </div>
+        <div class="simimob-field">
+            <label><?php esc_html_e( 'Intermediária (R$)', 'simulador-imobiliario' ); ?></label>
+            <input data-mask="currency" type="text" name="obras_intermediaria" />
+        </div>
+        <div class="simimob-field">
+            <label><?php esc_html_e( 'Início das intermediárias', 'simulador-imobiliario' ); ?></label>
+            <input type="month" name="obras_inicio_intermediaria" />
+        </div>
+        <div class="simimob-summary">
+            <p><?php esc_html_e( 'Total sinal:', 'simulador-imobiliario' ); ?> <span class="simimob-total-sinal">R$ 0,00</span></p>
+            <p><?php esc_html_e( 'Total mensais:', 'simulador-imobiliario' ); ?> <span class="simimob-total-mensal">R$ 0,00</span></p>
+            <p><?php esc_html_e( 'Total intermediárias:', 'simulador-imobiliario' ); ?> <span class="simimob-total-intermediaria">R$ 0,00</span></p>
+            <p><?php esc_html_e( 'Valor total de pagamentos:', 'simulador-imobiliario' ); ?> <span class="simimob-total-geral">R$ 0,00</span></p>
+            <p><?php esc_html_e( 'Saldo em aberto:', 'simulador-imobiliario' ); ?> <span class="simimob-saldo-aberto">R$ 0,00</span></p>
+        </div>
+        <div class="simimob-actions">
+            <button type="button" data-prev-step><?php esc_html_e( 'Voltar', 'simulador-imobiliario' ); ?></button>
+            <button type="button" data-next-step><?php esc_html_e( 'Continuar', 'simulador-imobiliario' ); ?></button>
+        </div>
+    </div>
+    <div class="simimob-step">
+        <h3><?php esc_html_e( 'Resumo final', 'simulador-imobiliario' ); ?></h3>
+        <table class="simimob-cronograma">
+            <thead>
+                <tr>
+                    <th><?php esc_html_e( 'Vencimento', 'simulador-imobiliario' ); ?></th>
+                    <th><?php esc_html_e( 'Tipo', 'simulador-imobiliario' ); ?></th>
+                    <th><?php esc_html_e( 'Valor', 'simulador-imobiliario' ); ?></th>
+                </tr>
+            </thead>
+            <tbody></tbody>
+        </table>
+        <div class="simimob-actions">
+            <button type="button" data-prev-step><?php esc_html_e( 'Voltar', 'simulador-imobiliario' ); ?></button>
+            <?php if ( ! empty( $settings['pdfAtivo'] ) ) : ?>
+                <button type="button" data-action="print"><?php esc_html_e( 'Baixar PDF', 'simulador-imobiliario' ); ?></button>
+            <?php endif; ?>
+            <button type="button" data-share="whatsapp"><?php esc_html_e( 'Compartilhar no WhatsApp', 'simulador-imobiliario' ); ?></button>
+            <button type="button" data-share="email"><?php esc_html_e( 'Enviar por e-mail', 'simulador-imobiliario' ); ?></button>
+        </div>
+    </div>
+</div>
 
EOF
)
