 (cd "$(git rev-parse --show-toplevel)" && git apply --3way <<'EOF' 
diff --git a/README.md b/README.md
index dd842caf359ff46ffc361ca1cde513e15b16a1e7..5ee0b83dc2c3d5961305feaa3514c73e91095045 100644
--- a/README.md
+++ b/README.md
@@ -1,2 +1,26 @@
 # site-livingeciarj
-Repositório para projetos relacionados ao site
+
+Repositório para projetos relacionados ao site.
+
+## Download do plugin
+
+O pacote instalável do plugin **Simulador Imobiliário - Financiamento & Obras** está disponível na raiz deste repositório como [`simulador-imobiliario-v1.0.3.zip`](simulador-imobiliario-v1.0.3.zip).
+
+### Como baixar diretamente do GitHub
+
+1. Acesse a URL bruta do arquivo ZIP, por exemplo:
+
+   ```
+   https://github.com/<usuario>/<repositorio>/raw/<branch>/simulador-imobiliario-v1.0.3.zip
+   ```
+
+   Substitua `<usuario>`, `<repositorio>` e `<branch>` conforme o projeto.
+2. Faça o upload do arquivo em **Plugins → Adicionar novo → Enviar plugin** no painel WordPress.
+
+### Instalando a partir do ZIP do repositório
+
+Se preferir usar o botão **Code → Download ZIP** do GitHub, o WordPress agora reconhecerá o pacote automaticamente. O arquivo de carregamento (`simulador-imobiliario.php`) na raiz do repositório aponta para o diretório principal do plugin (`/simulador-imobiliario`). Assim, mesmo com o diretório extra criado pelo GitHub (`<repositorio>-main`), o instalador localiza o cabeçalho do plugin corretamente e você não precisa enviar arquivos manualmente um a um.
+
+> **Dica:** ao clicar no link do ZIP pelo navegador, pode aparecer uma página “em branco” (é apenas o navegador mostrando o arquivo binário). Use `Salvar link como…` ou acrescente `?raw=1` ao final da URL para forçar o download.
+
+Após o envio, o plugin aparecerá com o nome **Simulador Imobiliário - Financiamento & Obras** na lista de plugins do WordPress.
 
EOF
)
