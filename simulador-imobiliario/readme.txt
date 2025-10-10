 (cd "$(git rev-parse --show-toplevel)" && git apply --3way <<'EOF' 
diff --git a//dev/null b/simulador-imobiliario/readme.txt
index 0000000000000000000000000000000000000000..a1c6175e7e2dce43d7806b16b81b5c6e36228805 100644
--- a//dev/null
+++ b/simulador-imobiliario/readme.txt
@@ -0,0 +1,36 @@
+=== Simulador Imobiliário — Financiamento & Obras ===
+Contributors: openai-codex
+Tags: real estate, financing, calculator, elementor, gutenberg
+Requires at least: 5.8
+Tested up to: 6.4
+Stable tag: 1.0.0
+License: GPLv2 or later
+License URI: https://www.gnu.org/licenses/gpl-2.0.html
+
+Plugin de simuladores imobiliários para financiamento e saldo de obras com shortcodes, blocos e widgets Elementor.
+
+== Description ==
+
+* Shortcodes: `[simulador_financiamento]`, `[simulador_obras]`, `[simulador_imobiliario]`
+* Blocos Gutenberg dinâmicos correspondentes aos simuladores.
+* Widgets Elementor para Houzez/Elementor.
+* Configurações avançadas de cores, textos, políticas comerciais e PDF.
+
+== Installation ==
+
+1. Envie o arquivo `simulador-imobiliario.zip` para Plugins > Adicionar novo > Enviar plugin.
+2. Ative o plugin.
+3. Acesse *Simulador Imobiliário* no menu do WordPress para configurar cores, modalidades, políticas e compartilhamento.
+
+== Frequently Asked Questions ==
+
+= Como utilizo os simuladores nas páginas? =
+Use os shortcodes informados acima, insira os blocos Gutenberg ou arraste os widgets nas páginas Elementor.
+
+= Como exportar/importar configurações? =
+Na aba *Avançado* do menu do plugin há botões de exportação (JSON) e campo para importar.
+
+== Changelog ==
+
+= 1.0.0 =
+* Versão inicial.
 
EOF
)
