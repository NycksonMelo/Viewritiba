# Viewritiba

Plataforma web para divulgação, descoberta e gerenciamento de eventos presenciais em Curitiba e Região Metropolitana. O sistema centraliza a relação entre organizadores de eventos e participantes, viabilizando controle de sessões, perfis sociais, publicação e moderação de eventos com armazenamento persistente em banco de dados relacional.

---

## Sumário

- [Visão Geral](#visão-geral)
- [Funcionalidades](#funcionalidades)
  - [Módulo do Organizador](#módulo-do-organizador)
  - [Módulo de Eventos](#módulo-de-eventos)
  - [Módulo do Participante](#módulo-do-participante)
- [Arquitetura de Diretórios](#arquitetura-de-diretórios)
- [Tecnologias Utilizadas](#tecnologias-utilizadas)
- [Banco de Dados](#banco-de-dados)
  - [Estrutura das Tabelas](#estrutura-das-tabelas)
  - [Ordem de Importação](#ordem-de-importação)
- [Instalação e Configuração Local](#instalação-e-configuração-local)
- [Fluxo de Execução e Rotas](#fluxo-de-execução-e-rotas)
- [Segurança e Boas Práticas](#segurança-e-boas-práticas)

---

## Visão Geral

O **Viewritiba** foi concebido para suprir a carência de um canal centralizado voltado a eventos locais e comunitários na capital paranaense. A plataforma adota uma abordagem desacoplada entre a camada de apresentação (HTML5, CSS3 e JavaScript assíncrono via Fetch API) e os serviços de back-end (endpoints PHP que processam requisições e retornam respostas estruturadas em JSON).

---

## Funcionalidades

### Módulo do Organizador
- **Cadastro e Autenticação:** Registro com validação de unicidade de documento (CPF/CNPJ) e e-mail. Armazenamento seguro de senhas utilizando hash criptográfico nativo.
- **Controle de Sessão:** Gerenciamento de login e encerramento de sessão via `$_SESSION` do PHP com controle de acesso a páginas restritas (`valida_sessao.php`).
- **Gestão de Perfil:** Atualização de dados cadastrais (nome, telefone, biografia/redes sociais).
- **Segurança de Conta:** Fluxo de troca de senha com exigência e validação da senha atual antes de autorizar a redefinição.
- **Painel de Controle:** Dashboard integrado que direciona o organizador aos seus eventos e configurações de perfil.

### Módulo de Eventos
- **Criação de Eventos:** Cadastro com título, descrição detalhada, data/hora e localização física. Associação automática ao organizador logado na sessão ativa, dispensando inputs manuais de chave estrangeira.
- **Listagem e Consulta:** Exibição dos eventos vinculados ao organizador autenticado e visualização detalhada de dados do evento.
- **Edição e Exclusão:** Atualização e remoção de registros respeitando a integridade relacional.

### Módulo do Participante
- **Cadastro de Participantes:** Registro com validações de dados pessoais, e-mail e regras de complexidade de senha.
- **Autenticação:** Login dedicado para participantes com validação em endpoints assíncronos.

---

## Arquitetura de Diretórios

```
ViewRitiba/
├── crud_organizador/          # Módulo de organizadores
│   ├── home/                  # Telas de dashboard, perfil e edição
│   ├── js/                    # Scripts de consumo assíncrono (Fetch API)
│   ├── login/                 # Telas de login e recuperação
│   ├── php/                   # Endpoints de API e controle de sessão
│   └── sql/                   # DDL da tabela organizador
├── crud_evento/               # Módulo de eventos
│   ├── home/                  # Formulários de cadastro, edição e listagem
│   ├── js/                    # Lógica de integração assíncrona
│   ├── php/                   # Endpoints de persistência e consulta
│   └── sql/                   # DDL da tabela evento
├── crud_participante/         # Módulo de participantes
│   ├── home/                  # Interface do participante
│   ├── js/                    # Scripts de formulários e login
│   ├── php/                   # Endpoints da entidade participante
│   └── css/                   # Estilização do módulo
├── css/                       # Folhas de estilo compartilhadas
├── documentos/                # Especificação de requisitos (BES) e documentação
├── index.html                 # Ponto de entrada (redirecionamento da aplicação)
├── README.md                  # Documentação técnica do repositório
└── .gitignore                 # Arquivos e pastas ignorados pelo Git
```

---

## Tecnologias Utilizadas

- **Front-end:** HTML5 semântico, Vanilla CSS (design responsivo, estilização customizada sem frameworks externos) e JavaScript ES6+ (requisições assíncronas com `fetch`).
- **Back-end:** PHP 8.x nativo estruturado em endpoints que respondem via protocolo HTTP com payload `application/json`.
- **Banco de Dados:** MySQL / MariaDB utilizando a extensão `mysqli` com prepared statements.
- **Servidor Web:** Apache (ambiente XAMPP / WampServer / LAMP).

---

## Banco de Dados

O banco de dados se chama `viewritiba` e opera sob o motor relacional InnoDB com charset `utf8mb4`.

### Estrutura das Tabelas

#### Tabela `organizador`
```sql
CREATE TABLE organizador (
  id INT(11) NOT NULL AUTO_INCREMENT,
  nome VARCHAR(100) NOT NULL,
  documento VARCHAR(20) NOT NULL,
  email VARCHAR(100) NOT NULL,
  telefone VARCHAR(20) NOT NULL,
  bio TEXT DEFAULT NULL,
  senha VARCHAR(255) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY (documento),
  UNIQUE KEY (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

#### Tabela `evento`
```sql
CREATE TABLE IF NOT EXISTS evento (
  id INT NOT NULL AUTO_INCREMENT,
  titulo VARCHAR(255) NOT NULL,
  descricao TEXT NOT NULL,
  data_hora DATETIME NOT NULL,
  local VARCHAR(255) NOT NULL,
  id_organizador INT NOT NULL,
  PRIMARY KEY (id),
  KEY idx_evento_organizador_data (id_organizador, data_hora),
  CONSTRAINT fk_evento_organizador 
    FOREIGN KEY (id_organizador) REFERENCES organizador(id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

#### Tabela `participante`
```sql
CREATE TABLE IF NOT EXISTS participante (
  id INT NOT NULL AUTO_INCREMENT,
  nome VARCHAR(100) NOT NULL,
  documento VARCHAR(20) NOT NULL,
  email VARCHAR(100) NOT NULL,
  telefone VARCHAR(20) NOT NULL,
  senha VARCHAR(255) NOT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY (documento),
  UNIQUE KEY (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
```

### Ordem de Importação

1. Execute o script `crud_organizador/sql/organizador.sql` (cria a base e a tabela de organizadores).
2. Execute o script `crud_evento/sql/evento.sql` (cria a tabela de eventos associada).
3. Caso utilize o módulo de participantes, crie a tabela `participante` conforme a DDL acima.

---

## Instalação e Configuração Local

### Pré-requisitos
- **PHP** >= 8.0
- **MySQL** ou **MariaDB** >= 5.7 / 10.4
- **Servidor Web** Apache ativo (ex.: [XAMPP](https://www.apachefriends.org/))
- **Git** instalado no sistema operacional

### Passo a Passo

1. **Clonar o Repositório:**
   Clone o projeto diretamente no diretório raiz do servidor web local (`htdocs` no XAMPP):
   ```bash
   cd C:\xampp\htdocs
   git clone https://github.com/NycksonMelo/Viewritiba.git ViewRitiba
   ```

2. **Inicializar o Ambiente:**
   - Inicie os módulos **Apache** e **MySQL** pelo painel de controle do XAMPP.

3. **Importar o Banco de Dados:**
   - Acesse o phpMyAdmin em `http://localhost/phpmyadmin/`.
   - Crie o banco `viewritiba` ou execute os arquivos `.sql` disponíveis nas pastas `crud_organizador/sql/` e `crud_evento/sql/`.

4. **Verificar Parâmetros de Conexão:**
   - Certifique-se de que os arquivos `conexao.php` em cada módulo (`crud_organizador/php/conexao.php`, `crud_evento/php/conexao.php` e `crud_participante/php/conexao.php`) correspondem às credenciais do seu MySQL local:
     ```php
     $servidor = 'localhost';
     $usuario = 'root';
     $senha = '';
     $banco = 'viewritiba';
     ```

5. **Acessar a Aplicação:**
   - Abra o navegador e acerte a URL:
     ```
     http://localhost/ViewRitiba/
     ```

---

## Fluxo de Execução e Rotas

| Rota / Recurso | Descrição |
| :--- | :--- |
| `/index.html` | Redirecionamento principal para a landing page da aplicação. |
| `/crud_organizador/home/MainMenu.html` | Página de boas-vindas com opções de login e cadastro. |
| `/crud_organizador/login/login.html` | Formulário de autenticação do organizador. |
| `/crud_organizador/home/organizador_novo.html` | Formulário de criação de conta do organizador. |
| `/crud_organizador/home/painel.html` | Dashboard principal (requer sessão ativa). |
| `/crud_organizador/home/perfil_organizador.html` | Visualização pública e dados do organizador. |
| `/crud_organizador/home/organizador_alterar.html` | Edição cadastral e alteração de senha. |
| `/crud_evento/home/eventos.html` | Listagem dos eventos cadastrados. |
| `/crud_evento/home/evento_novo.html` | Cadastro de novos eventos. |
| `/crud_evento/home/visualizar_evento.html` | Detalhes de um evento específico. |

---

## Segurança e Boas Práticas

- **Prevenção a SQL Injection:** Utilização de `mysqli::prepare()` e vinculação de parâmetros (`bind_param`) em todas as operações de escrita e leitura dinâmica.
- **Armazenamento Seguro de Senhas:** Hashing obrigatório através de `password_hash($senha, PASSWORD_DEFAULT)` e conferência via `password_verify()`.
- **Isolamento de Credenciais na Sessão:** A identificação do usuário em operações restritas (ex.: publicação de eventos) é resgatada da sessão segura no servidor (`$_SESSION['organizador_id']`), impedindo que o cliente forje o identificador de autor.
- **Comunicação Padronizada:** Todas as rotas de backend enviam cabeçalho `Content-Type: application/json; charset=utf-8` com envelope estruturado (`status`, `mensagem`, `data`).
