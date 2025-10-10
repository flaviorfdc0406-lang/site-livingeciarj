 (cd "$(git rev-parse --show-toplevel)" && git apply --3way <<'EOF' 
diff --git a//dev/null b/simulador-imobiliario/templates/partials/modalidades.php
index 0000000000000000000000000000000000000000..8f43c30186cb8408f27bfa42ffb7c73b7b604e07 100644
--- a//dev/null
+++ b/simulador-imobiliario/templates/partials/modalidades.php
@@ -0,0 +1,16 @@
+<?php
+$settings     = SIMIMOB_Settings::get_public_settings();
+$modalidades  = $settings['modalidades'];
+$parametros   = $settings['modalidadeParams'];
+?>
+<h3><?php esc_html_e( 'Escolha a modalidade', 'simulador-imobiliario' ); ?></h3>
+<?php foreach ( $modalidades as $index => $modalidade ) :
+    $descricao = isset( $parametros[ $modalidade ]['descricao'] ) ? $parametros[ $modalidade ]['descricao'] : '';
+    ?>
+    <div class="simimob-field">
+        <label>
+            <input type="radio" name="modalidade" value="<?php echo esc_attr( $modalidade ); ?>" <?php checked( 0 === $index ); ?> />
+            <strong><?php echo esc_html( $modalidade ); ?></strong> — <?php echo esc_html( $descricao ); ?>
+        </label>
+    </div>
+<?php endforeach; ?>
 
EOF
)
