 (cd "$(git rev-parse --show-toplevel)" && git apply --3way <<'EOF' 
diff --git a//dev/null b/simulador-imobiliario/blocks/financiamento/render.php
index 0000000000000000000000000000000000000000..5fd981d2cb61bebb881f2315e83115a322609f11 100644
--- a//dev/null
+++ b/simulador-imobiliario/blocks/financiamento/render.php
@@ -0,0 +1,2 @@
+<?php
+return do_shortcode( '[simulador_financiamento]' );
 
EOF
)
