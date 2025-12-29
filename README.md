# BaaS Transaction Backend

API Restful para pagamentos simplificados, permitindo depósitos e transferências entre usuários comuns e lojistas.

## 🚀 Sobre o Projeto

Este projeto é um desafio técnico que simula uma plataforma de pagamentos (Banking as a Service). O objetivo é garantir a integridade das transações financeiras, concorrência e consistência de dados.

### Tecnologias Utilizadas

- **Linguagem:** PHP 8.2
- **Framework:** Lumen (Laravel)
- **Banco de Dados:** MySQL 8.0
- **Infraestrutura:** Docker & Docker Compose
- **Servidor Web:** Nginx

## ⚙️ Pré-requisitos

Para executar este projeto, você precisa apenas ter instalado em sua máquina:

- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/)

## 🛠️ Instalação e Execução

Siga os passos abaixo para rodar a aplicação em ambiente de desenvolvimento:

1. **Clone o repositório**
   ```bash
   git clone https://github.com/relson/baas-transaction-backend.git
   cd baas-transaction-backend
   ```

2. **Configure o ambiente**

   Copie o arquivo de exemplo `.env.example` para um novo arquivo chamado `.env`. Este arquivo conterá as variáveis de ambiente da sua aplicação.
   ```bash
   cp .env.example .env
   ```
   > ⚠️ **Importante:** O arquivo `.env` já está configurado para o ambiente Docker. Não é necessário alterar as credenciais do banco de dados.

3. **Inicie os containers**

   Utilize o Docker Compose para construir e iniciar todos os serviços necessários.
   ```bash
   docker-compose up -d --build
   ```

4. **Instale as dependências**

   Acesse o container da aplicação e execute o Composer para instalar as dependências do PHP.
   ```bash
   docker-compose exec app composer install
   ```

5. **Execute as migrações**

   Crie as tabelas no banco de dados executando o Artisan, a ferramenta de linha de comando do Lumen.
   ```bash
   docker-compose exec app php artisan migrate
   ```

Com estes passos, a API estará em execução e pronta para receber requisições em `http://localhost:8000`.

## 📚 Documentação da API

A documentação completa da API, gerada com Swagger, está disponível enquanto a aplicação estiver em execução.

Você pode acessá-la no seguinte endereço:

[http://localhost:8000/api/documentation](http://localhost:8000/api/documentation)

Para atualizar a documentação após fazer alterações nas anotações do código, execute o comando:
```bash
docker-compose exec app php artisan l5-swagger:generate
```
