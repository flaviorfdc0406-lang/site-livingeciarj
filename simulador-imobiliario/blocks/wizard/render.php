 (cd "$(git rev-parse --show-toplevel)" && git apply --3way <<'EOF' 
diff --git a//dev/null b/simulador-imobiliario/blocks/wizard/render.php
index 0000000000000000000000000000000000000000..a0468edc13c16ccf40ad93e979cf74d9c31703fe 100644
--- a//dev/null
+++ b/simulador-imobiliario/blocks/wizard/render.php
@@ -0,0 +1,2 @@
+<?php
+return do_shortcode( '[simulador_imobiliario]' );
 
EOF
)
