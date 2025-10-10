 (cd "$(git rev-parse --show-toplevel)" && git apply --3way <<'EOF' 
diff --git a//dev/null b/simulador-imobiliario/blocks/obras/index.js
index 0000000000000000000000000000000000000000..dd2a087702c734ef153794efe7a96f20b063e2d6 100644
--- a//dev/null
+++ b/simulador-imobiliario/blocks/obras/index.js
@@ -0,0 +1,13 @@
+( function( wp ) {
+    const { registerBlockType } = wp.blocks;
+    const { createElement: el } = wp.element;
+
+    registerBlockType( 'simimob/obras', {
+        edit: function() {
+            return el( 'div', { className: 'simimob-block-preview' }, 'Simulador de Saldo de Obras' );
+        },
+        save: function() {
+            return null;
+        }
+    } );
+} )( window.wp );
 
EOF
)
