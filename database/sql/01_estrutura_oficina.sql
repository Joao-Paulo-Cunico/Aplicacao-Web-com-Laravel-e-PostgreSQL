--
-- PostgreSQL database dump
--

\restrict bvqSbUCCnN0GBrwFjv9Wmu5LQ2lMtSckHVU4eye4T9Ldu81wXnNSK0d7nZ1lwqg

-- Dumped from database version 18.6 (fd34222)
-- Dumped by pg_dump version 18.4

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET transaction_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

--
-- Name: oficina_mecanica; Type: SCHEMA; Schema: -; Owner: -
--

CREATE SCHEMA oficina_mecanica;


SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: cliente; Type: TABLE; Schema: oficina_mecanica; Owner: -
--

CREATE TABLE oficina_mecanica.cliente (
    id_cliente integer NOT NULL,
    nome character varying(350) NOT NULL,
    cpf character varying(11) NOT NULL,
    telefone character varying(11) NOT NULL,
    email character varying(300) NOT NULL,
    data_cadastro date NOT NULL,
    ativo boolean DEFAULT true NOT NULL,
    create_at date NOT NULL,
    updated_at date NOT NULL
);


--
-- Name: marca; Type: TABLE; Schema: oficina_mecanica; Owner: -
--

CREATE TABLE oficina_mecanica.marca (
    id_marca integer NOT NULL,
    nome character varying(150) NOT NULL
);


--
-- Name: mecanico; Type: TABLE; Schema: oficina_mecanica; Owner: -
--

CREATE TABLE oficina_mecanica.mecanico (
    id_mecanico integer NOT NULL,
    nome character varying(300) NOT NULL,
    cpf character varying(11) NOT NULL,
    telefone character varying(11) NOT NULL,
    especilidade character varying(150) NOT NULL,
    data_admissao date NOT NULL,
    ativo boolean DEFAULT true NOT NULL
);


--
-- Name: modelo; Type: TABLE; Schema: oficina_mecanica; Owner: -
--

CREATE TABLE oficina_mecanica.modelo (
    id_modelo integer NOT NULL,
    marca_id integer NOT NULL,
    nome character varying(75) NOT NULL,
    ano_inicio date NOT NULL,
    ano_fim date
);


--
-- Name: ordem_servico; Type: TABLE; Schema: oficina_mecanica; Owner: -
--

CREATE TABLE oficina_mecanica.ordem_servico (
    id_ordem_servico integer NOT NULL,
    veiculo_id integer NOT NULL,
    mecanico_id integer NOT NULL,
    data_abertura date NOT NULL,
    data_fechamento date,
    status character varying(20) NOT NULL,
    quilometragem_entrada bigint NOT NULL,
    observacoes character varying(300),
    valor_mao_obra numeric(10,2) NOT NULL,
    create_at date NOT NULL,
    update_at date NOT NULL
);


--
-- Name: ordem_servico_peca; Type: TABLE; Schema: oficina_mecanica; Owner: -
--

CREATE TABLE oficina_mecanica.ordem_servico_peca (
    ordem_servico_id integer NOT NULL,
    peca_id integer NOT NULL,
    quantidade integer NOT NULL,
    valor_unitario numeric(10,2) NOT NULL,
    desconto numeric(10,2) DEFAULT 0 NOT NULL
);


--
-- Name: ordem_servico_servico; Type: TABLE; Schema: oficina_mecanica; Owner: -
--

CREATE TABLE oficina_mecanica.ordem_servico_servico (
    ordem_servico_id integer NOT NULL,
    servico_id integer NOT NULL,
    quantidade bigint NOT NULL,
    valor_unitario numeric(10,2) NOT NULL,
    desconto numeric(10,2)
);


--
-- Name: pagamento; Type: TABLE; Schema: oficina_mecanica; Owner: -
--

CREATE TABLE oficina_mecanica.pagamento (
    id_pagamento integer NOT NULL,
    ordem_servico_id integer NOT NULL,
    data_pagamento date NOT NULL,
    valor numeric(10,2) NOT NULL,
    forma_pagamento character varying(25) NOT NULL,
    status character varying(30) NOT NULL
);


--
-- Name: peca; Type: TABLE; Schema: oficina_mecanica; Owner: -
--

CREATE TABLE oficina_mecanica.peca (
    id_peca integer NOT NULL,
    nome character varying(150) NOT NULL,
    descricao character varying(500) NOT NULL,
    preco_custo numeric(10,2) NOT NULL,
    preco_venda numeric(10,2) NOT NULL,
    estoque integer DEFAULT 0 NOT NULL,
    ativo boolean DEFAULT true NOT NULL
);


--
-- Name: servico; Type: TABLE; Schema: oficina_mecanica; Owner: -
--

CREATE TABLE oficina_mecanica.servico (
    id_servico integer NOT NULL,
    nome character varying(100) NOT NULL,
    descricao character varying(500) NOT NULL,
    preco_base numeric(10,2) NOT NULL,
    tempo_estimado integer NOT NULL,
    ativo boolean DEFAULT true NOT NULL
);


--
-- Name: veiculo; Type: TABLE; Schema: oficina_mecanica; Owner: -
--

CREATE TABLE oficina_mecanica.veiculo (
    id_veiculo integer NOT NULL,
    cliente_id integer NOT NULL,
    modelo_id integer NOT NULL,
    placa character varying(7) NOT NULL,
    ano date NOT NULL,
    cor character varying(20) NOT NULL,
    quilometragem bigint NOT NULL,
    data_cadastro date NOT NULL,
    ativo boolean DEFAULT true NOT NULL
);


--
-- Name: vw_joaopaulo_desempenho_mecanicos; Type: VIEW; Schema: oficina_mecanica; Owner: -
--

CREATE VIEW oficina_mecanica.vw_joaopaulo_desempenho_mecanicos AS
SELECT
    NULL::integer AS id_mecanico,
    NULL::character varying(300) AS nome_mecanico,
    NULL::character varying(150) AS especialidade,
    NULL::bigint AS total_ordem_servico,
    NULL::numeric AS total_mao_obra_gerado,
    NULL::numeric AS valor_medio_mao_obra;


--
-- Name: vw_joaopaulo_ordem_servico_relatorio; Type: VIEW; Schema: oficina_mecanica; Owner: -
--

CREATE VIEW oficina_mecanica.vw_joaopaulo_ordem_servico_relatorio AS
SELECT
    NULL::integer AS id_ordem_servico,
    NULL::date AS data_abertura,
    NULL::date AS data_fechamento,
    NULL::character varying(350) AS nome_cliente,
    NULL::character varying(75) AS nome_veiculo,
    NULL::character varying(7) AS placa_carro,
    NULL::character varying(300) AS nome_mecanico,
    NULL::character varying(20) AS status,
    NULL::numeric(10,2) AS valor_mao_obra,
    NULL::numeric AS valor_pecas,
    NULL::numeric AS valor_total;


--
-- Name: vw_joaopaulo_relatorio_clientes_mais_gastaram; Type: VIEW; Schema: oficina_mecanica; Owner: -
--

CREATE VIEW oficina_mecanica.vw_joaopaulo_relatorio_clientes_mais_gastaram AS
SELECT
    NULL::integer AS id_cliente,
    NULL::character varying(350) AS nome_cliente,
    NULL::character varying(11) AS cpf,
    NULL::bigint AS quantidade_ordens_servico,
    NULL::numeric AS total_pago;


--
-- Name: vw_joaopaulo_servicos_mais_realizados; Type: VIEW; Schema: oficina_mecanica; Owner: -
--

CREATE VIEW oficina_mecanica.vw_joaopaulo_servicos_mais_realizados AS
 SELECT se.id_servico,
    se.nome,
    count(oss.servico_id) AS quantidade_realizada,
    round(avg(oss.valor_unitario), 2) AS valor_medio_cobrado,
    sum(((oss.valor_unitario * (oss.quantidade)::numeric) - COALESCE(oss.desconto, (0)::numeric))) AS faturamento_servicos
   FROM (((oficina_mecanica.servico se
     JOIN oficina_mecanica.ordem_servico_servico oss ON ((oss.servico_id = se.id_servico)))
     JOIN oficina_mecanica.ordem_servico os ON ((os.id_ordem_servico = oss.ordem_servico_id)))
     JOIN oficina_mecanica.pagamento pa ON ((pa.ordem_servico_id = os.id_ordem_servico)))
  WHERE ((os.status)::text = 'Finalizada'::text)
  GROUP BY se.id_servico, se.nome
  ORDER BY se.id_servico;


--
-- Name: cliente cliente_pkey; Type: CONSTRAINT; Schema: oficina_mecanica; Owner: -
--

ALTER TABLE ONLY oficina_mecanica.cliente
    ADD CONSTRAINT cliente_pkey PRIMARY KEY (id_cliente);


--
-- Name: vm_joaopaulo_relatorio_clientes_acima_media; Type: MATERIALIZED VIEW; Schema: oficina_mecanica; Owner: -
--

CREATE MATERIALIZED VIEW oficina_mecanica.vm_joaopaulo_relatorio_clientes_acima_media AS
 SELECT c.id_cliente,
    c.nome,
    c.cpf,
    sum(pa.valor) AS total_gasto_cliente
   FROM (((oficina_mecanica.cliente c
     JOIN oficina_mecanica.veiculo ve ON ((ve.cliente_id = c.id_cliente)))
     JOIN oficina_mecanica.ordem_servico os ON ((os.veiculo_id = ve.id_veiculo)))
     JOIN oficina_mecanica.pagamento pa ON ((pa.ordem_servico_id = os.id_ordem_servico)))
  WHERE ((pa.status)::text = 'PAGO'::text)
  GROUP BY c.id_cliente
 HAVING (sum(pa.valor) > ( SELECT avg(gastos_clientes.total_gasto_cliente) AS avg
           FROM ( SELECT c_1.id_cliente,
                    sum(pa_1.valor) AS total_gasto_cliente
                   FROM (((oficina_mecanica.cliente c_1
                     JOIN oficina_mecanica.veiculo ve_1 ON ((ve_1.cliente_id = c_1.id_cliente)))
                     JOIN oficina_mecanica.ordem_servico os_1 ON ((os_1.veiculo_id = ve_1.id_veiculo)))
                     JOIN oficina_mecanica.pagamento pa_1 ON ((pa_1.ordem_servico_id = os_1.id_ordem_servico)))
                  GROUP BY c_1.id_cliente) gastos_clientes))
  ORDER BY (sum(pa.valor)) DESC
  WITH NO DATA;


--
-- Name: marca marca_pkey; Type: CONSTRAINT; Schema: oficina_mecanica; Owner: -
--

ALTER TABLE ONLY oficina_mecanica.marca
    ADD CONSTRAINT marca_pkey PRIMARY KEY (id_marca);


--
-- Name: mecanico mecanico_pkey; Type: CONSTRAINT; Schema: oficina_mecanica; Owner: -
--

ALTER TABLE ONLY oficina_mecanica.mecanico
    ADD CONSTRAINT mecanico_pkey PRIMARY KEY (id_mecanico);


--
-- Name: modelo modelo_pkey; Type: CONSTRAINT; Schema: oficina_mecanica; Owner: -
--

ALTER TABLE ONLY oficina_mecanica.modelo
    ADD CONSTRAINT modelo_pkey PRIMARY KEY (id_modelo);


--
-- Name: ordem_servico ordem_servico_pkey; Type: CONSTRAINT; Schema: oficina_mecanica; Owner: -
--

ALTER TABLE ONLY oficina_mecanica.ordem_servico
    ADD CONSTRAINT ordem_servico_pkey PRIMARY KEY (id_ordem_servico);


--
-- Name: pagamento pagamento_pkey; Type: CONSTRAINT; Schema: oficina_mecanica; Owner: -
--

ALTER TABLE ONLY oficina_mecanica.pagamento
    ADD CONSTRAINT pagamento_pkey PRIMARY KEY (id_pagamento);


--
-- Name: peca peca_pkey; Type: CONSTRAINT; Schema: oficina_mecanica; Owner: -
--

ALTER TABLE ONLY oficina_mecanica.peca
    ADD CONSTRAINT peca_pkey PRIMARY KEY (id_peca);


--
-- Name: servico servico_pkey; Type: CONSTRAINT; Schema: oficina_mecanica; Owner: -
--

ALTER TABLE ONLY oficina_mecanica.servico
    ADD CONSTRAINT servico_pkey PRIMARY KEY (id_servico);


--
-- Name: cliente uk_cliente_cpf; Type: CONSTRAINT; Schema: oficina_mecanica; Owner: -
--

ALTER TABLE ONLY oficina_mecanica.cliente
    ADD CONSTRAINT uk_cliente_cpf UNIQUE (cpf);


--
-- Name: marca uk_marca_nome; Type: CONSTRAINT; Schema: oficina_mecanica; Owner: -
--

ALTER TABLE ONLY oficina_mecanica.marca
    ADD CONSTRAINT uk_marca_nome UNIQUE (nome);


--
-- Name: mecanico uk_mecanico_cpf; Type: CONSTRAINT; Schema: oficina_mecanica; Owner: -
--

ALTER TABLE ONLY oficina_mecanica.mecanico
    ADD CONSTRAINT uk_mecanico_cpf UNIQUE (cpf);


--
-- Name: veiculo uk_veiculo_placa; Type: CONSTRAINT; Schema: oficina_mecanica; Owner: -
--

ALTER TABLE ONLY oficina_mecanica.veiculo
    ADD CONSTRAINT uk_veiculo_placa UNIQUE (placa);


--
-- Name: veiculo veiculo_pkey; Type: CONSTRAINT; Schema: oficina_mecanica; Owner: -
--

ALTER TABLE ONLY oficina_mecanica.veiculo
    ADD CONSTRAINT veiculo_pkey PRIMARY KEY (id_veiculo);


--
-- Name: idx_joaopaulo_ordem_servico_data; Type: INDEX; Schema: oficina_mecanica; Owner: -
--

CREATE INDEX idx_joaopaulo_ordem_servico_data ON oficina_mecanica.ordem_servico USING btree (data_abertura);


--
-- Name: idx_joaopaulo_ordem_servico_mecanico; Type: INDEX; Schema: oficina_mecanica; Owner: -
--

CREATE INDEX idx_joaopaulo_ordem_servico_mecanico ON oficina_mecanica.ordem_servico USING btree (mecanico_id);


--
-- Name: vw_joaopaulo_desempenho_mecanicos _RETURN; Type: RULE; Schema: oficina_mecanica; Owner: -
--

CREATE OR REPLACE VIEW oficina_mecanica.vw_joaopaulo_desempenho_mecanicos AS
 SELECT me.id_mecanico,
    me.nome AS nome_mecanico,
    me.especilidade AS especialidade,
    count(os.mecanico_id) AS total_ordem_servico,
    sum(os.valor_mao_obra) AS total_mao_obra_gerado,
    round(avg(os.valor_mao_obra), 2) AS valor_medio_mao_obra
   FROM (oficina_mecanica.mecanico me
     JOIN oficina_mecanica.ordem_servico os ON ((os.mecanico_id = me.id_mecanico)))
  WHERE ((os.status)::text = 'Finalizada'::text)
  GROUP BY me.id_mecanico
  ORDER BY (sum(os.valor_mao_obra)) DESC;


--
-- Name: vw_joaopaulo_ordem_servico_relatorio _RETURN; Type: RULE; Schema: oficina_mecanica; Owner: -
--

CREATE OR REPLACE VIEW oficina_mecanica.vw_joaopaulo_ordem_servico_relatorio AS
 SELECT os.id_ordem_servico,
    os.data_abertura,
    os.data_fechamento,
    c.nome AS nome_cliente,
    mo.nome AS nome_veiculo,
    ve.placa AS placa_carro,
    me.nome AS nome_mecanico,
    os.status,
    os.valor_mao_obra,
    sum(((osp.valor_unitario * (osp.quantidade)::numeric) - osp.desconto)) AS valor_pecas,
    (os.valor_mao_obra + sum(((osp.valor_unitario * (osp.quantidade)::numeric) - osp.desconto))) AS valor_total
   FROM (((((oficina_mecanica.ordem_servico os
     JOIN oficina_mecanica.ordem_servico_peca osp ON ((osp.ordem_servico_id = os.id_ordem_servico)))
     JOIN oficina_mecanica.veiculo ve ON ((ve.id_veiculo = os.veiculo_id)))
     JOIN oficina_mecanica.mecanico me ON ((me.id_mecanico = os.mecanico_id)))
     JOIN oficina_mecanica.cliente c ON ((c.id_cliente = ve.cliente_id)))
     JOIN oficina_mecanica.modelo mo ON ((mo.id_modelo = ve.modelo_id)))
  GROUP BY os.id_ordem_servico, c.nome, mo.nome, ve.placa, me.nome, os.status, os.valor_mao_obra
  ORDER BY os.data_abertura DESC;


--
-- Name: vw_joaopaulo_relatorio_clientes_mais_gastaram _RETURN; Type: RULE; Schema: oficina_mecanica; Owner: -
--

CREATE OR REPLACE VIEW oficina_mecanica.vw_joaopaulo_relatorio_clientes_mais_gastaram AS
 SELECT c.id_cliente,
    c.nome AS nome_cliente,
    c.cpf,
    count(os.id_ordem_servico) AS quantidade_ordens_servico,
    sum(pa.valor) AS total_pago
   FROM (((oficina_mecanica.cliente c
     JOIN oficina_mecanica.veiculo ve ON ((ve.cliente_id = c.id_cliente)))
     JOIN oficina_mecanica.ordem_servico os ON ((os.veiculo_id = ve.id_veiculo)))
     JOIN oficina_mecanica.pagamento pa ON ((pa.ordem_servico_id = os.id_ordem_servico)))
  WHERE ((pa.status)::text = 'PAGO'::text)
  GROUP BY c.id_cliente
  ORDER BY (sum(pa.valor)) DESC;


--
-- Name: modelo fk_modelo_marca; Type: FK CONSTRAINT; Schema: oficina_mecanica; Owner: -
--

ALTER TABLE ONLY oficina_mecanica.modelo
    ADD CONSTRAINT fk_modelo_marca FOREIGN KEY (marca_id) REFERENCES oficina_mecanica.marca(id_marca) ON DELETE RESTRICT;


--
-- Name: ordem_servico fk_os_mecanico; Type: FK CONSTRAINT; Schema: oficina_mecanica; Owner: -
--

ALTER TABLE ONLY oficina_mecanica.ordem_servico
    ADD CONSTRAINT fk_os_mecanico FOREIGN KEY (mecanico_id) REFERENCES oficina_mecanica.mecanico(id_mecanico);


--
-- Name: ordem_servico fk_os_veiculo; Type: FK CONSTRAINT; Schema: oficina_mecanica; Owner: -
--

ALTER TABLE ONLY oficina_mecanica.ordem_servico
    ADD CONSTRAINT fk_os_veiculo FOREIGN KEY (veiculo_id) REFERENCES oficina_mecanica.veiculo(id_veiculo);


--
-- Name: ordem_servico_peca fk_osp_ordem_servico; Type: FK CONSTRAINT; Schema: oficina_mecanica; Owner: -
--

ALTER TABLE ONLY oficina_mecanica.ordem_servico_peca
    ADD CONSTRAINT fk_osp_ordem_servico FOREIGN KEY (ordem_servico_id) REFERENCES oficina_mecanica.ordem_servico(id_ordem_servico) ON DELETE CASCADE;


--
-- Name: ordem_servico_peca fk_osp_peca; Type: FK CONSTRAINT; Schema: oficina_mecanica; Owner: -
--

ALTER TABLE ONLY oficina_mecanica.ordem_servico_peca
    ADD CONSTRAINT fk_osp_peca FOREIGN KEY (peca_id) REFERENCES oficina_mecanica.peca(id_peca) ON DELETE CASCADE;


--
-- Name: ordem_servico_servico fk_oss_ordem_servico; Type: FK CONSTRAINT; Schema: oficina_mecanica; Owner: -
--

ALTER TABLE ONLY oficina_mecanica.ordem_servico_servico
    ADD CONSTRAINT fk_oss_ordem_servico FOREIGN KEY (ordem_servico_id) REFERENCES oficina_mecanica.ordem_servico(id_ordem_servico) ON DELETE CASCADE;


--
-- Name: ordem_servico_servico fk_oss_servico; Type: FK CONSTRAINT; Schema: oficina_mecanica; Owner: -
--

ALTER TABLE ONLY oficina_mecanica.ordem_servico_servico
    ADD CONSTRAINT fk_oss_servico FOREIGN KEY (servico_id) REFERENCES oficina_mecanica.servico(id_servico);


--
-- Name: pagamento fk_pagamento_os; Type: FK CONSTRAINT; Schema: oficina_mecanica; Owner: -
--

ALTER TABLE ONLY oficina_mecanica.pagamento
    ADD CONSTRAINT fk_pagamento_os FOREIGN KEY (ordem_servico_id) REFERENCES oficina_mecanica.ordem_servico(id_ordem_servico);


--
-- Name: veiculo fk_veiculo_cliente; Type: FK CONSTRAINT; Schema: oficina_mecanica; Owner: -
--

ALTER TABLE ONLY oficina_mecanica.veiculo
    ADD CONSTRAINT fk_veiculo_cliente FOREIGN KEY (cliente_id) REFERENCES oficina_mecanica.cliente(id_cliente) ON DELETE RESTRICT;


--
-- Name: veiculo fk_veiculo_modelo; Type: FK CONSTRAINT; Schema: oficina_mecanica; Owner: -
--

ALTER TABLE ONLY oficina_mecanica.veiculo
    ADD CONSTRAINT fk_veiculo_modelo FOREIGN KEY (modelo_id) REFERENCES oficina_mecanica.modelo(id_modelo);


--
-- PostgreSQL database dump complete
--

\unrestrict bvqSbUCCnN0GBrwFjv9Wmu5LQ2lMtSckHVU4eye4T9Ldu81wXnNSK0d7nZ1lwqg

