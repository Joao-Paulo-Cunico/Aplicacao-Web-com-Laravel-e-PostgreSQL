-- Finaliza somente uma OS que ainda esteja em andamento.
CREATE OR REPLACE PROCEDURE oficina_mecanica.sp_finalizar_os(p_ordem_id INTEGER)
LANGUAGE plpgsql
AS $$
DECLARE
    v_status VARCHAR(20);
    v_data_abertura DATE;
BEGIN
    SELECT status, data_abertura
    INTO v_status, v_data_abertura
    FROM oficina_mecanica.ordem_servico
    WHERE id_ordem_servico = p_ordem_id
    FOR UPDATE;

    IF NOT FOUND THEN
        RAISE EXCEPTION 'Ordem de serviço não encontrada.';
    END IF;

    IF v_status <> 'Em andamento' THEN
        RAISE EXCEPTION 'Apenas ordens de serviço em andamento podem ser finalizadas.';
    END IF;

    IF CURRENT_DATE < v_data_abertura THEN
        RAISE EXCEPTION 'A data de fechamento não pode ser anterior à data de abertura.';
    END IF;

    UPDATE oficina_mecanica.ordem_servico
    SET status = 'Finalizada',
        data_fechamento = CURRENT_DATE
    WHERE id_ordem_servico = p_ordem_id;
END;
$$;
