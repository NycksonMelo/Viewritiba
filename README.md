# Viewritiba
Plataforma Web de Eventos Presenciais  Rede social para a divulgação, descoberta e avaliação de eventos em Curitiba e região. Conecta organizadores e participantes em um só lugar, permitindo interações como confirmação de presença, comentários, busca por tags e notas pós-evento que constroem a reputação do organizador.

## Banco de dados

O CRUD de organizadores e o CRUD de eventos compartilham a sessão do organizador e o banco `viewritiba`. Em uma instalação nova, importe primeiro `crud_organizador/sql/organizador.sql` e depois `crud_evento/sql/evento.sql` no MySQL. Se a tabela `organizador` já existir, importe apenas o script de eventos.

Os eventos são associados automaticamente ao organizador autenticado; o formulário não solicita o ID do organizador.

A página pública fica disponível em `index.html` e apresenta as opções de cadastro e acesso. Após o login do organizador, a navegação começa no painel (`crud_organizador/home/painel.html`), com links para eventos e perfil.
