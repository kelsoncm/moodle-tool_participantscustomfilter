# Visão Geral — moodle-tool_participantscustomfilter

O **`moodle-tool_participantscustomfilter`** é um plugin de administração para Moodle que estende as opções de busca na página de participantes dos cursos (`/enrol/index.php`), permitindo **filtragem por campos personalizados do perfil do usuário**.

---

## 🚀 Principais Recursos

- **Filtro por Campos Customizados de Perfil**: Adiciona dropdowns e campos de busca baseados nos campos de perfil cadastrados na plataforma.
- **Módulo AMD JS Integrado**: Lógica JavaScript cliente desenvolvida com AMD/Grunt (`filter.js`).
- **Integração Nativa à Listagem de Participantes**: Intercepta e aplica filtros diretamente na query dos participantes do curso.

---

## 📚 Tópicos da Documentação

- 📦 **[Instalação](installation.md)** — Como colocar o plugin em `/admin/tool/participantscustomfilter`.
- ⚙️ **[Compilação do AMD (`filter.js`)](configuration.md)** — Instruções de build com Grunt e Npm.
- 📖 **[Guia de Uso](usage.md)** — Como filtrar participantes na listagem do curso.
