-- Calcula o total de uma OS sem depender de preços atuais de serviços ou peças.
-- Cada item usa o valor_unitario copiado no momento de sua inclusão.
CREATE OR REPLACE FUNCTION oficina_mecanica.fn_calcular_total_os(p_ordem_id INTEGER)
RETURNS NUMERIC
LANGUAGE sql
STABLE
AS $$
    SELECT COALESCE(os.valor_mao_obra, 0)
        + COALESCE((
            SELECT SUM(oss.quantidade * oss.valor_unitario - COALESCE(oss.desconto, 0))
            FROM oficina_mecanica.ordem_servico_servico AS oss
            WHERE oss.ordem_servico_id = os.id_ordem_servico
        ), 0)
        + COALESCE((
            SELECT SUM(osp.quantidade * osp.valor_unitario - COALESCE(osp.desconto, 0))
            FROM oficina_mecanica.ordem_servico_peca AS osp
            WHERE osp.ordem_servico_id = os.id_ordem_servico
        ), 0)
    FROM oficina_mecanica.ordem_servico AS os
    WHERE os.id_ordem_servico = p_ordem_id;
$$;
