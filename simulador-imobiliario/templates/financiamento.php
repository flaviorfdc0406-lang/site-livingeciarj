 (cd "$(git rev-parse --show-toplevel)" && git apply --3way <<'EOF' 
diff --git a//dev/null b/simulador-imobiliario/templates/financiamento.php
index 0000000000000000000000000000000000000000..d4fcdf4e58fa307115a47149ff3930af881e41e3 100644
--- a//dev/null
+++ b/simulador-imobiliario/templates/financiamento.php
@@ -0,0 +1,93 @@
+<?php
+/**
+ * Front template for financing simulator.
+ *
+ * @var array $data Shortcode attributes.
+ */
+
+$settings = SIMIMOB_Settings::get_public_settings();
+$modalidades = $settings['modalidades'];
+$modalidade_params = $settings['modalidadeParams'];
+?>
+<div class="simimob-simulador" data-type="financiamento" data-label-credito="<?php esc_attr_e( 'Crédito máximo', 'simulador-imobiliario' ); ?>" data-label-parcela="<?php esc_attr_e( 'Parcela estimada', 'simulador-imobiliario' ); ?>" data-label-prazo="<?php esc_attr_e( 'Prazo', 'simulador-imobiliario' ); ?>" data-label-taxa="<?php esc_attr_e( 'Taxa', 'simulador-imobiliario' ); ?>" data-label-entrada="<?php esc_attr_e( 'Entrada mínima', 'simulador-imobiliario' ); ?>" data-label-idade="<?php esc_attr_e( 'Idade/prazo excedem a política', 'simulador-imobiliario' ); ?>">
+    <div class="simimob-stepper">
+        <button type="button" aria-current="step"><?php esc_html_e( 'Modalidade', 'simulador-imobiliario' ); ?></button>
+        <button type="button"><?php esc_html_e( 'Capacidade de crédito', 'simulador-imobiliario' ); ?></button>
+        <button type="button"><?php esc_html_e( 'Dados do imóvel', 'simulador-imobiliario' ); ?></button>
+        <button type="button"><?php esc_html_e( 'Resumo', 'simulador-imobiliario' ); ?></button>
+    </div>
+    <div class="simimob-step active">
+        <h3><?php esc_html_e( 'Escolha a modalidade', 'simulador-imobiliario' ); ?></h3>
+        <?php foreach ( $modalidades as $index => $modalidade ) :
+            $params = isset( $modalidade_params[ $modalidade ] ) ? $modalidade_params[ $modalidade ] : array();
+            $descricao = isset( $params['descricao'] ) ? $params['descricao'] : '';
+            ?>
+            <div class="simimob-field">
+                <label>
+                    <input type="radio" name="modalidade" value="<?php echo esc_attr( $modalidade ); ?>" <?php checked( 0 === $index ); ?> />
+                    <strong><?php echo esc_html( $modalidade ); ?></strong> — <?php echo esc_html( $descricao ); ?>
+                </label>
+            </div>
+        <?php endforeach; ?>
+        <div class="simimob-actions">
+            <button type="button" data-next-step><?php esc_html_e( 'Continuar', 'simulador-imobiliario' ); ?></button>
+        </div>
+    </div>
+    <div class="simimob-step">
+        <h3><?php esc_html_e( 'Capacidade de crédito', 'simulador-imobiliario' ); ?></h3>
+        <div class="simimob-field">
+            <label for="simimob-renda"><?php esc_html_e( 'Renda familiar bruta mensal', 'simulador-imobiliario' ); ?></label>
+            <input data-mask="currency" type="text" id="simimob-renda" name="renda_familiar" />
+        </div>
+        <div class="simimob-field">
+            <label for="simimob-idade"><?php esc_html_e( 'Idade do comprador', 'simulador-imobiliario' ); ?></label>
+            <input type="number" id="simimob-idade" name="idade" />
+        </div>
+        <div class="simimob-field">
+            <label for="simimob-valor-imovel"><?php esc_html_e( 'Valor do imóvel', 'simulador-imobiliario' ); ?></label>
+            <input data-mask="currency" type="text" id="simimob-valor-imovel" name="valor_imovel" />
+        </div>
+        <div class="simimob-field">
+            <label for="simimob-prazo"><?php esc_html_e( 'Prazo pretendido (meses)', 'simulador-imobiliario' ); ?></label>
+            <input type="number" id="simimob-prazo" name="prazo" />
+        </div>
+        <div class="simimob-summary simimob-capacidade-resultado"></div>
+        <div class="simimob-actions">
+            <button type="button" data-prev-step><?php esc_html_e( 'Voltar', 'simulador-imobiliario' ); ?></button>
+            <button type="button" data-next-step><?php esc_html_e( 'Usar estes parâmetros', 'simulador-imobiliario' ); ?></button>
+        </div>
+    </div>
+    <div class="simimob-step">
+        <h3><?php esc_html_e( 'Dados do imóvel', 'simulador-imobiliario' ); ?></h3>
+        <div class="simimob-field">
+            <label for="simimob-financiamento-valor"><?php esc_html_e( 'Valor do financiamento', 'simulador-imobiliario' ); ?></label>
+            <input data-mask="currency" type="text" id="simimob-financiamento-valor" name="financiamento_valor" />
+        </div>
+        <div class="simimob-field">
+            <label for="simimob-entrega"><?php esc_html_e( 'Entrega (mês/ano)', 'simulador-imobiliario' ); ?></label>
+            <input type="month" id="simimob-entrega" name="entrega" />
+        </div>
+        <div class="simimob-field">
+            <label for="simimob-empreendimento"><?php esc_html_e( 'Empreendimento', 'simulador-imobiliario' ); ?></label>
+            <input type="text" id="simimob-empreendimento" name="empreendimento" placeholder="<?php esc_attr_e( 'Ex.: Living Parque', 'simulador-imobiliario' ); ?>" />
+        </div>
+        <div class="simimob-field">
+            <label for="simimob-unidade"><?php esc_html_e( 'Unidade', 'simulador-imobiliario' ); ?></label>
+            <input type="text" id="simimob-unidade" name="unidade" placeholder="<?php esc_attr_e( 'Ex.: Bloco X • Unidade 01/601', 'simulador-imobiliario' ); ?>" />
+        </div>
+        <div class="simimob-actions">
+            <button type="button" data-prev-step><?php esc_html_e( 'Voltar', 'simulador-imobiliario' ); ?></button>
+            <button type="button" data-next-step><?php esc_html_e( 'Continuar', 'simulador-imobiliario' ); ?></button>
+        </div>
+    </div>
+    <div class="simimob-step">
+        <h3><?php esc_html_e( 'Resumo do financiamento', 'simulador-imobiliario' ); ?></h3>
+        <div class="simimob-summary">
+            <p><?php esc_html_e( 'Revise os dados antes de seguir para o saldo de obras.', 'simulador-imobiliario' ); ?></p>
+            <p><?php esc_html_e( 'Modalidade selecionada será utilizada para cálculos adicionais.', 'simulador-imobiliario' ); ?></p>
+        </div>
+        <div class="simimob-actions">
+            <button type="button" data-prev-step><?php esc_html_e( 'Voltar', 'simulador-imobiliario' ); ?></button>
+        </div>
+    </div>
+</div>
 
EOF
)
