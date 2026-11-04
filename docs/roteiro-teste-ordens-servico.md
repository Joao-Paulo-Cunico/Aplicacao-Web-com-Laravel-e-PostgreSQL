# Roteiro de testes — ordens de serviço

Execute os scripts de `database/sql/joao-paulo/` no SQL Editor da branch de desenvolvimento do Neon antes dos testes que usam function, procedure e trigger.

| Teste | Passos | Esperado | Observado |
|---|---|---|---|
| Abrir OS | Acesse `/ordens-servico/create`, selecione veículo e mecânico ativos e envie dados válidos. | OS criada com status `Em andamento`. | |
| Filtro por status | Em `/ordens-servico`, filtre cada status disponível. | A lista mostra apenas OS do status selecionado. | |
| Adicionar serviço | Em OS em andamento, adicione o mesmo serviço duas vezes. | A quantidade e o desconto são somados; valor unitário é preservado. | |
| Adicionar peça e baixa | Em OS em andamento, inclua peça com estoque suficiente. | Item é incluído e a trigger reduz o estoque. | |
| Estoque insuficiente | Tente incluir quantidade maior que o estoque. | Validação impede a inclusão. | |
| Peça repetida | Tente incluir novamente uma peça já presente na mesma OS. | Validação impede a duplicidade. | |
| Finalizar OS | Finalize uma OS em andamento. | Status passa a `Finalizada` e `data_fechamento` recebe a data atual. | |
| Cancelar OS | Cancele uma OS em andamento que contenha peças. | Status passa a `Cancelada`, data é gravada e peças são devolvidas ao estoque. | |
| OS finalizada | Tente adicionar serviço, peça, finalizar ou cancelar uma OS finalizada. | Todas as alterações são bloqueadas. | |
| Mecânico inativo | Tente abrir OS com mecânico inativo. | A abertura é bloqueada. | |
| Total PHP × function SQL | Compare o total na tela com `SELECT oficina_mecanica.fn_calcular_total_os(id)`. | Os valores são idênticos. | |
