const http = require('http');
const fs = require('fs').promises;
const path = require('path');

const PORT = process.env.PORT || 5173;
const PUBLIC_DIR = __dirname;

const MIME_TYPES = {
  '.html': 'text/html; charset=utf-8',
  '.css': 'text/css; charset=utf-8',
  '.js': 'application/javascript; charset=utf-8',
  '.json': 'application/json; charset=utf-8',
  '.png': 'image/png',
  '.jpg': 'image/jpeg',
  '.jpeg': 'image/jpeg',
  '.gif': 'image/gif',
  '.svg': 'image/svg+xml',
  '.ico': 'image/x-icon',
  '.webp': 'image/webp',
  '.woff': 'font/woff',
  '.woff2': 'font/woff2',
  '.ttf': 'font/ttf',
  '.txt': 'text/plain; charset=utf-8'
};

async function serveFile(filePath) {
  const resolvedPath = path.join(PUBLIC_DIR, filePath);
  const data = await fs.readFile(resolvedPath);
  return data;
}

const server = http.createServer(async (req, res) => {
  try {
    const urlPath = decodeURI(req.url.split('?')[0]);
    const relativePath = urlPath === '/' ? '/index.html' : urlPath;
    const safePath = path.normalize(relativePath).replace(/^\.\/+/, '');
    const extension = path.extname(safePath);
    const contentType = MIME_TYPES[extension] || 'application/octet-stream';

    const fileBuffer = await serveFile(safePath);

    res.writeHead(200, { 'Content-Type': contentType });
    res.end(fileBuffer);
  } catch (error) {
    if (error.code === 'ENOENT') {
      res.writeHead(404, { 'Content-Type': 'text/plain; charset=utf-8' });
      res.end('404 - Arquivo não encontrado');
      return;
    }

    console.error('Erro ao atender a requisição:', error);
    res.writeHead(500, { 'Content-Type': 'text/plain; charset=utf-8' });
    res.end('500 - Erro interno do servidor');
  }
});

server.listen(PORT, () => {
  console.log(`Servidor local da Living Eciarj em execução: http://localhost:${PORT}`);
});
