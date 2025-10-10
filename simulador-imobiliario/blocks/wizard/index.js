 (cd "$(git rev-parse --show-toplevel)" && git apply --3way <<'EOF' 
diff --git a//dev/null b/simulador-imobiliario/blocks/wizard/index.js
index 0000000000000000000000000000000000000000..06eaba89041dfda97bc30758da2071cf28fec900 100644
--- a//dev/null
+++ b/simulador-imobiliario/blocks/wizard/index.js
@@ -0,0 +1,13 @@
+( function( wp ) {
+    const { registerBlockType } = wp.blocks;
+    const { createElement: el } = wp.element;
+
+    registerBlockType( 'simimob/wizard', {
+        edit: function() {
+            return el( 'div', { className: 'simimob-block-preview' }, 'Simulador Imobiliário — Wizard' );
+        },
+        save: function() {
+            return null;
+        }
+    } );
+} )( window.wp );
 
EOF
)
