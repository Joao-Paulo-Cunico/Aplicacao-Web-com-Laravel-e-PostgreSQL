# Backup e Restauracao da Oficina Mecanica


## Tecnologias utilizadas
- PostgreSQL 18.4
- Neon Database
- Laravel 13
- pg_dump e pg_restore

## Backup realizado
Foi gerado um backup da branch de desenvolvimento do Neon,
utilizando o formato customizado do PostgreSQL.

Arquivo: oficina_dev_2026-11-03.backup
Tamanho: 952037 bytes
Schema: oficina_mecanica

O backup foi armazenado fora do repositorio Git.

## Teste de restauracao
Foi criado um banco separado chamado:

oficina_restauracao_teste

O backup foi restaurado utilizando o pg_restore,
com --single-transaction e --exit-on-error.

O comando terminou com codigo 0, indicando sucesso.

## Dados recuperados

| Tabela | Registros |
|---|---:|
| cliente | 2000 |
| peca | 300 |
| pagamento | 7905 |
| ordem_servico | 10000 |

## Rotinas recuperadas

Functions:
- fn_total_pago_ordem
- fn_consultar_estoque

Procedures:
- sp_repor_estoque
- sp_atualizar_status_pagamento

Triggers:
- trg_luis_validar_estoque
- trg_luis_auditar_pagamento

Os indices criados tambem constam no backup.

## Conclusao
O backup foi gerado, inspecionado e restaurado com sucesso
em um banco separado.

Conseguimos recuperar os registros, as rotinas e a estrutura
do sistema sem sobrescrever o banco principal utilizado
pela aplicacao Laravel.
