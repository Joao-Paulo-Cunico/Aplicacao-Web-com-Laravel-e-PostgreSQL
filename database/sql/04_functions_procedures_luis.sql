-- FUNCTION 

CREATE OR REPLACE FUNCTION oficina_mecanica.fn_total_pago_ordem(
    p_ordem_id INTEGER
)
RETURNS NUMERIC
LANGUAGE plpgsql
AS $$
DECLARE
    v_total NUMERIC;
BEGIN
    SELECT COALESCE(SUM(valor), 0)
    INTO v_total
    FROM oficina_mecanica.pagamento
    WHERE ordem_servico_id = p_ordem_id
      AND status = 'PAGO';

    RETURN v_total;
END;
$$;

-- Consulta o estoque atual de uma peca

CREATE OR REPLACE FUNCTION oficina_mecanica.fn_consultar_estoque(
    p_peca_id INTEGER
)
RETURNS INTEGER
LANGUAGE plpgsql
AS $$
DECLARE
    v_estoque INTEGER;
BEGIN
    SELECT estoque
    INTO v_estoque
    FROM oficina_mecanica.peca
    WHERE id_peca = p_peca_id;

    RETURN v_estoque;
END;
$$;

-- PROCEDURE 

CREATE OR REPLACE PROCEDURE oficina_mecanica.sp_repor_estoque(
    p_peca_id INTEGER,
    p_quantidade INTEGER
)
LANGUAGE plpgsql
AS $$
BEGIN
    -- Impede quantidade zero ou negativa
    IF p_quantidade IS NULL OR p_quantidade <= 0 THEN
        RAISE EXCEPTION 'A quantidade deve ser maior que zero';
    END IF;

    UPDATE oficina_mecanica.peca
    SET estoque = estoque + p_quantidade
    WHERE id_peca = p_peca_id;

    IF NOT FOUND THEN
        RAISE EXCEPTION 'Peca nao encontrada';
    END IF;
END;
$$;


CREATE OR REPLACE PROCEDURE oficina_mecanica.sp_atualizar_status_pagamento(
    p_pagamento_id INTEGER,
    p_status VARCHAR
)
LANGUAGE plpgsql
AS $$
BEGIN
    IF p_status IS NULL OR
       p_status NOT IN ('PAGO', 'PENDENTE', 'CANCELADO') THEN
        RAISE EXCEPTION 'Status de pagamento invalido';
    END IF;

    UPDATE oficina_mecanica.pagamento
    SET status = p_status
    WHERE id_pagamento = p_pagamento_id;

    IF NOT FOUND THEN
        RAISE EXCEPTION 'Pagamento nao encontrado';
    END IF;
END;
$$;
