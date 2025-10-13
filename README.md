# site-livingeciarj

Simulação estática para validar o layout e as principais interações do futuro site da Living Eciarj.

## Como testar localmente

1. Instale as dependências padrão do Node.js (qualquer versão LTS funciona).
2. No terminal, navegue até a pasta do projeto e execute:
   ```bash
   npm install # nenhuma dependência adicional, cria apenas o package-lock opcional
   npm start
   ```
3. Abra o navegador na URL de teste `http://localhost:5173` para validar a simulação.
4. Explore as seções para validar conteúdo, navegação, responsividade e interações (módulos, carrossel, formulários e modais).
5. Utilize o seletor de tema (claro/escuro) para conferir contraste e consistência visual.

## Estrutura

- `index.html`: marcação principal da simulação.
- `styles.css`: estilos globais e responsivos.
- `script.js`: interações simuladas (menu, módulos, carrossel, formulários e modais).
