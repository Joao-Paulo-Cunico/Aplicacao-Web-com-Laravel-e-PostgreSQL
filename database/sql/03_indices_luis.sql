-- Indice
CREATE INDEX IF NOT EXISTS idx_luis_pagamento_ordem_servico
ON oficina_mecanica.pagamento (ordem_servico_id);

-- Indice
CREATE INDEX IF NOT EXISTS idx_luis_ordem_servico_peca
ON oficina_mecanica.ordem_servico_peca (peca_id);
