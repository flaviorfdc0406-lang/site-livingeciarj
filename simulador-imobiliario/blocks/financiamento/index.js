 (cd "$(git rev-parse --show-toplevel)" && git apply --3way <<'EOF' 
diff --git a//dev/null b/simulador-imobiliario/blocks/financiamento/index.js
index 0000000000000000000000000000000000000000..bf0d5ae1cb4ce60bd21f915b5f1aaa79eb3ab859 100644
--- a//dev/null
+++ b/simulador-imobiliario/blocks/financiamento/index.js
@@ -0,0 +1,13 @@
+( function( wp ) {
+    const { registerBlockType } = wp.blocks;
+    const { createElement: el } = wp.element;
+
+    registerBlockType( 'simimob/financiamento', {
+        edit: function() {
+            return el( 'div', { className: 'simimob-block-preview' }, 'Simulador de Financiamento' );
+        },
+        save: function() {
+            return null;
+        }
+    } );
+} )( window.wp );
 
EOF
)
