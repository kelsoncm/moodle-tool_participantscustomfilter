# Configuração & Build AMD — moodle-tool_participantscustomfilter

## Compilação do `filter.js` (Grunt)

Caso realize alterações no código fonte JavaScript dentro de `amd/src/`:

```bash
cd /var/www/html
npm install
cd /var/www/html/admin/tool/participantscustomfilter/amd
npx grunt amd -v --force
```

Limpe o cache JS do Moodle em **Administração do site → Desenvolvimento → Limpar caches**.
