Real Motor — Sistema de Gerenciamento de Oficina Mecânica
Centro Universitário Campo Real | Engenharia de Software | 2026
Disciplinas: Desenvolvimento Back-end e Banco de Dados
DOCUMENTO DE ACESSO RESTRITO À BANCA. Este README contém credenciais de acesso ao banco de dados. Envie-o diretamente ao professor e não o publique no GitHub ou em repositório público.

## Requisitos do Sistema

- PHP 8.5.9
- Laravel Framework 13.38.0
- Composer 2.10.3
- PostgreSQL (Neon)
- Git
- Extensão PHP pdo_pgsql habilitada

1. Apresentação
O Real Motor é um sistema web para gerenciamento de uma oficina mecânica. A aplicação foi desenvolvida em PHP/Laravel, com páginas em Blade, banco de dados PostgreSQL hospedado no Neon e uma API REST para peças.

O sistema oferece gerenciamento de clientes, marcas, modelos, veículos, mecânicos, serviços, peças, ordens de serviço e pagamentos.
2. Repositório
https://github.com/Joao-Paulo-Cunico/Aplicacao-Web-com-Laravel-e-PostgreSQL

A versão a ser avaliada deve estar atualizada na branch main.
3. Pré-requisitos
- PHP compatível com a versão do Laravel do projeto, com extensões pdo_pgsql, pgsql e demais extensões requeridas pelo framework;
- Composer;
- Git;
- Acesso à internet para conexão ao banco Neon.
4. Instalação e execução
4.1. Clonar o projeto
git clone https://github.com/Joao-Paulo-Cunico/Aplicacao-Web-com-Laravel-e-PostgreSQL.git
cd Aplicacao-Web-com-Laravel-e-PostgreSQL
Importante: execute os próximos comandos na pasta que contém o arquivo artisan e o composer.json. Se esses arquivos estiverem em uma subpasta, entre nela antes de continuar.

4.2. Instalar dependências
composer install

4.3. Criar o arquivo de ambiente
No PowerShell (Windows):
Copy-Item .env.example .env
No Linux/macOS:
cp .env.example .env
Depois, configure o .env com os valores da seção 5 e gere a chave da aplicação:
php artisan key:generate

4.4. Limpar o cache e iniciar
php artisan optimize:clear
php artisan serve
Acesse http://127.0.0.1:8000.

Caso a porta 8000 esteja ocupada:
php artisan serve --port=8001
Nesse caso, acesse http://127.0.0.1:8001.

5. Configuração do banco PostgreSQL (Neon)
As credenciais abaixo foram fornecidas pela equipe para acesso da banca. Copie as linhas para a seção correspondente do arquivo .env local:
DB_CONNECTION=pgsql
DB_HOST=ep-royal-cloud-be2xuw6b.c-9.us-east-2.aws.neon.tech
DB_PORT=5432
DB_DATABASE=neondb
DB_USERNAME=neondb_owner
DB_PASSWORD=npg_J4LH1KdSNbhe
DB_SSLMODE=require
DB_SCHEMA=oficina_mecanica
O banco utiliza o schema oficina_mecanica. A configuração de schema deve ser respeitada conforme o projeto Laravel. A conexão depende de o banco estar acessível e as credenciais permanecerem válidas.

Não execute comandos destrutivos no banco compartilhado, como php artisan migrate:fresh, php artisan migrate:reset ou php artisan db:wipe. A estrutura e os dados já foram preparados pela equipe.

Segurança: neondb_owner é uma conta proprietária. Não inclua estas credenciais em commits, capturas públicas, issues ou materiais disponibilizados abertamente. Recomenda-se utilizar uma conta específica de avaliação e trocar a senha depois da banca.

6. Funcionalidades disponíveis
Módulo	Principais funcionalidades
Clientes	Listagem, cadastro, consulta, edição, exclusão e reativação
Marcas	Cadastro, consulta, edição e exclusão, com proteção de vínculos
Modelos	Cadastro, consulta, edição e exclusão, com proteção de vínculos
Veículos	Cadastro, consulta, edição, desativação e reativação
Mecânicos	Cadastro e gerenciamento de profissionais
Serviços	Cadastro e gerenciamento de serviços
Peças	Cadastro, gerenciamento e reposição de estoque
Ordens de serviço	Abertura, consulta, inclusão de serviços e peças, fechamento e cancelamento
Pagamentos	Cadastro, consulta, edição e exclusão




As telas de gerenciamento também incluem recursos de pesquisa, filtros e paginação, conforme o módulo.
7. Roteiro sugerido para a avaliação
1. Abrir a página inicial do sistema.
2. Acessar Clientes e consultar ou cadastrar um registro de teste.
3. Acessar Marcas, Modelos e Veículos para verificar os cadastros e seus relacionamentos.
4. Consultar os módulos Mecânicos, Serviços e Peças.
5. Abrir uma Ordem de Serviço, associando veículo e mecânico.
6. Incluir serviços e peças, conferir os valores e verificar as opções de finalização/cancelamento.
7. Acessar Pagamentos e consultar ou registrar um pagamento de teste.
8. Testar os campos de pesquisa, filtros e paginação nas listagens.
Atenção: os registros são armazenados no banco compartilhado. Evite alterar ou excluir dados existentes durante a avaliação; utilize registros de teste.
8. API REST de peças
Método	Endpoint	Finalidade
GET	/api/pecas	Listar peças
GET	/api/pecas/{peca}	Consultar uma peça
POST	/api/pecas	Cadastrar peça
PUT	/api/pecas/{peca}	Atualizar peça
DELETE	/api/pecas/{peca}	Excluir peça


Os endpoints podem ser testados no Postman, utilizando como endereço base http://127.0.0.1:8000 ou a porta efetivamente usada pelo servidor.
9. Verificações técnicas
Para listar as rotas registradas:
php artisan route:list
Para executar testes automatizados somente após confirmar que o ambiente de testes está isolado do banco compartilhado:
php artisan test
O registro de 75 rotas foi observado durante a verificação local do projeto em novembro de 2026. Esse número pode variar após novas alterações.
10. Integrantes
- Léo Pedro Burei: Clientes, Marcas, Modelos e Veículos; pesquisas, filtros e padronização visual das interfaces.
- João Paulo Cunico: Mecânicos, Serviços, Ordens de Serviço e regras operacionais.
- Luis Rodrigo Molon: Peças, Pagamentos, API REST, recursos SQL e procedimentos de backup.
Real Motor — Projeto acadêmico, 2026.