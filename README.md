# moodle-tool_participantscustomfilter
Plugin Moodle admin/tool que adiciona um filtro por campos personalizados do perfil do usuário na página de listagem de participantes (/enrol/index.php)


Para forçar o build do `filter.js`.

```bash
cd /var/www/html ; npm install
cd /var/www/html/admin/tool/participantscustomfilter/amd ; npx grunt amd -v --force
```