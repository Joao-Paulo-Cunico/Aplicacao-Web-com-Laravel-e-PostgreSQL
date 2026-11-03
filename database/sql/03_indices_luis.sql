-- Indice  Busca de pagamentos por ordem de servico
CREATE INDEX IF NOT EXISTS idx_luis_pagamento_ordem_servico
ON oficina_mecanica.pagamento (ordem_servico_id);

-- Indice Verificacao de pecas utilizadas em ordens de servico
CREATE INDEX IF NOT EXISTS idx_luis_ordem_servico_peca
ON oficina_mecanica.ordem_servico_peca (peca_id);
