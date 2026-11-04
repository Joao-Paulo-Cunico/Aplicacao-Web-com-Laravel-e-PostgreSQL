-- ATENÇÃO: este é um teste temporário para o banco de desenvolvimento de João Paulo.

CREATE OR REPLACE FUNCTION oficina_mecanica.fn_teste_baixar_estoque_os()
RETURNS TRIGGER
LANGUAGE plpgsql
AS $$
BEGIN
    UPDATE oficina_mecanica.peca
    SET estoque = estoque - NEW.quantidade
    WHERE id_peca = NEW.peca_id
      AND estoque >= NEW.quantidade;

    IF NOT FOUND THEN
        RAISE EXCEPTION 'Estoque insuficiente para a peça informada.';
    END IF;

    RETURN NEW;
END;
$$;

DROP TRIGGER IF EXISTS trg_teste_baixar_estoque_os
ON oficina_mecanica.ordem_servico_peca;

CREATE TRIGGER trg_teste_baixar_estoque_os
AFTER INSERT ON oficina_mecanica.ordem_servico_peca
FOR EACH ROW
EXECUTE FUNCTION oficina_mecanica.fn_teste_baixar_estoque_os();
