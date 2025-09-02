# 🚀 API CRUD de Usuários

## 📌 Sobre o Projeto
Este projeto é uma API RESTful desenvolvida em PHP utilizando CodeIgniter 4 para gerenciamento de usuários.  
Permite operações CRUD (GET, POST, PUT, DELETE) com dados armazenados em MySQL.  
As respostas são retornadas em formato **JSON**, com **validação de entradas**, **tratamento de erros** e **autenticação básica de API via filtro**.

---

## ⚙️ Funcionalidades
- ✅ Criar usuários (`POST /api/users`)
- ✅ Listar usuários (`GET /api/users`)
- ✅ Listar usuários ativos (`GET /api/users/active`)
- ✅ Detalhar usuário (`GET /api/users/{id}`)
- ✅ Atualizar usuário (`PUT/PATCH /api/users/{id}`)
- ✅ Deletar usuário (`DELETE /api/users/{id}`)
- 🔒 Autenticação de API por filtro (`apiauth`)

---

## 🛠️ Instalação e Configuração

### 1. Clone o repositório
```bash
git clone https://github.com/JulioCavenaghi/desafio_conecta
cd desafio_conecta
```

### 2. Instale as dependências
```bash
composer install
```

### 3. Copie o arquivo env de exemplo e renomeie para .env
```bash
cp env .env
```

### 4. Edite as variáveis de ambiente no .env:
```env
app.baseURL = 'http://localhost:8080/'
API_KEY = 'sua_senha_api'
database.default.hostname = seu_host
database.default.database = seu_banco
database.default.username = seu_usuario
database.default.password = sua_senha
database.default.DBDriver = MySQLi
```

### 5. Crie o banco de dados
```sql
CREATE DATABASE nome_do_banco CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 6. Execute as migrations
```bash
php spark migrate
```

### 7. Inicie o servidor
```bash
php spark serve
```
A API estará disponível em: http://localhost:8080/api/

## 📖 Endpoints

➕ Criar Usuário
```curl
curl -X POST http://localhost:8080/api/users \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer SUA_API_KEY" \
  -d '{
    "name": "João Silva",
    "email": "joao@email.com",
    "password": "minhasenha",
    "password_confirm": "minhasenha",
    "status": 1
  }'

```

📋 Listar Todos os Usuários
```curl
curl -X GET http://localhost:8080/api/users \
  -H "Authorization: Bearer SUA_API_KEY"
```

📋 Listar Usuários Ativos
```curl
curl -X GET http://localhost:8080/api/users/active \
  -H "Authorization: Bearer SUA_API_KEY"
```

🔍 Detalhar Usuário (por ID)
```curl
curl -X GET http://localhost:8080/api/users/1 \
  -H "Authorization: Bearer SUA_API_KEY"
```

✏️ Atualizar Usuário (PUT)
```curl
curl -X PUT http://localhost:8080/api/users/1 \
  -H "Content-Type: application/json" \
  -H "Authorization: Bearer SUA_API_KEY" \
  -d '{
    "name": "João Silva Atualizado",
    "email": "joao.silva@email.com",
    "status": 0
  }'
```

❌ Deletar Usuário
```curl
curl -X DELETE http://localhost:8080/api/users/1 \
  -H "Authorization: Bearer SUA_API_KEY"
```




