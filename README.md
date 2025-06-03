# 🏋️ Sistema de Academia FMUFIT - PHP MVC

Este projeto é um sistema web para academias desenvolvido com PHP puro, utilizando o padrão MVC (Model-View-Controller). Ele permite o cadastro de alunos, gerenciamento de planos, treinos personalizados e autenticação diferenciada para alunos e administradores.

---

## 🚀 Funcionalidades

- Cadastro e login de alunos
- Escolha de planos: **PIX**, **Cartão de Crédito** (fictício) e **Plano Experimental** de 15 dias
- Validação automática de acesso com base no plano ativo
- Geração e salvamento de treinos personalizados
- Edição de perfil do aluno
- Área restrita para aluno com painel funcional
- Separação clara entre backend (PHP) e frontend (HTML/CSS/JS)

---

## 🧱 Tecnologias Utilizadas

- PHP (sem framework)
- MySQL
- HTML5, CSS3 e JavaScript
- Estrutura MVC simples e organizada
- Git para versionamento

---

## 🛠️ Como Instalar

1. **Clone o repositório:**

```bash
git clone https://github.com/seuusuario/seu-repositorio.git
```

2. **Importe o banco de dados:**
   - Use o arquivo `fmu.gym.sql` na raiz do projeto para criar as tabelas no MySQL.

3. **Configure a conexão com o banco:**
   - Edite o arquivo `/includes/db.php` com seus dados de acesso ao MySQL.

4. **Execute o projeto:**
   - Utilize um servidor local como **XAMPP**, **WAMP** ou **Laragon**.
   - Acesse o projeto via navegador (ex: `http://localhost/sistema-academia/`).

---

## 📁 Estrutura de Pastas

```
/
├── aluno/               # Telas e scripts da área do aluno
├── controllers/         # Controladores (lógica das ações)
├── models/              # Modelos do banco de dados
├── views/               # Páginas HTML/PHP
├── includes/            # Arquivos compartilhados (db.php, functions.php)
├── public/              # Recursos públicos (imagens, CSS, JS)
├── banco.sql            # Script de criação do banco de dados
└── index.php            # Página inicial
```

---

## 📌 Observações

- O plano experimental dura **15 dias** a partir do cadastro do aluno.
- Os treinos são definidos por tipo (hipertrofia, emagrecimento, condicionamento).
- O sistema pode ser expandido para incluir professores, avaliações físicas e mais.

---

## 📄 Licença

Este projeto é de uso educacional e livre para modificações pessoais.