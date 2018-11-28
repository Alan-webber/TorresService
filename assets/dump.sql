begin work;

--
-- PostgreSQL database dump
--

-- Dumped from database version 9.3.23
-- Dumped by pg_dump version 9.5.5

-- Started on 2018-11-22 20:23:18

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;
SET row_security = off;

--
-- TOC entry 1 (class 3079 OID 11750)
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

-- CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- TOC entry 2114 (class 0 OID 0)
-- Dependencies: 1
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

-- COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


--
-- TOC entry 2 (class 3079 OID 51281)
-- Name: unaccent; Type: EXTENSION; Schema: -; Owner: 
--

-- CREATE EXTENSION IF NOT EXISTS unaccent WITH SCHEMA public;


--
-- TOC entry 2115 (class 0 OID 0)
-- Dependencies: 2
-- Name: EXTENSION unaccent; Type: COMMENT; Schema: -; Owner: 
--

-- COMMENT ON EXTENSION unaccent IS 'text search dictionary that removes accents';


SET search_path = public, pg_catalog;

--
-- TOC entry 205 (class 1255 OID 42601)
-- Name: incrementa_visualizacao(); Type: FUNCTION; Schema: public; Owner: postgres
--

CREATE FUNCTION incrementa_visualizacao() RETURNS trigger
    LANGUAGE plpgsql
    AS $$
BEGIN 
	UPDATE servico
	SET numero_visualizacoes = numero_visualizacoes + 1
	WHERE id_servico = NEW.id_servico;

	RETURN NEW;
END;
$$;


ALTER FUNCTION public.incrementa_visualizacao() OWNER TO admtorresserv;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- TOC entry 177 (class 1259 OID 33963)
-- Name: categoria_servico; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE categoria_servico (
    id_categoria_servico bigint NOT NULL,
    nome_categoria_servico character varying NOT NULL,
    icon_name character varying NOT NULL
);


ALTER TABLE categoria_servico OWNER TO admtorresserv;

--
-- TOC entry 176 (class 1259 OID 33961)
-- Name: categoria_servico_id_categoria_servico_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE categoria_servico_id_categoria_servico_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE categoria_servico_id_categoria_servico_seq OWNER TO admtorresserv;

--
-- TOC entry 2116 (class 0 OID 0)
-- Dependencies: 176
-- Name: categoria_servico_id_categoria_servico_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE categoria_servico_id_categoria_servico_seq OWNED BY categoria_servico.id_categoria_servico;


--
-- TOC entry 190 (class 1259 OID 42605)
-- Name: cidade; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE cidade (
    id_cidade bigint NOT NULL,
    cidade character varying NOT NULL,
    uf character(2) NOT NULL
);


ALTER TABLE cidade OWNER TO admtorresserv;

--
-- TOC entry 189 (class 1259 OID 42603)
-- Name: cidade_id_cidade_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE cidade_id_cidade_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE cidade_id_cidade_seq OWNER TO admtorresserv;

--
-- TOC entry 2117 (class 0 OID 0)
-- Dependencies: 189
-- Name: cidade_id_cidade_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE cidade_id_cidade_seq OWNED BY cidade.id_cidade;


--
-- TOC entry 196 (class 1259 OID 51222)
-- Name: mensagem_item; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE mensagem_item (
    id_mensagem_item bigint NOT NULL,
    id_orcamento bigint NOT NULL,
    data_envio timestamp without time zone DEFAULT now() NOT NULL,
    mensagem_enviada_pelo_profissional boolean DEFAULT false NOT NULL,
    mensagem character varying NOT NULL,
    mensagem_visualizada boolean DEFAULT false NOT NULL
);


ALTER TABLE mensagem_item OWNER TO admtorresserv;

--
-- TOC entry 195 (class 1259 OID 51220)
-- Name: mensagem_item_id_mensagem_item_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE mensagem_item_id_mensagem_item_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE mensagem_item_id_mensagem_item_seq OWNER TO admtorresserv;

--
-- TOC entry 2118 (class 0 OID 0)
-- Dependencies: 195
-- Name: mensagem_item_id_mensagem_item_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE mensagem_item_id_mensagem_item_seq OWNED BY mensagem_item.id_mensagem_item;


--
-- TOC entry 193 (class 1259 OID 50914)
-- Name: orcamento; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE orcamento (
    id_orcamento bigint NOT NULL,
    id_usuario bigint NOT NULL,
    id_servico bigint NOT NULL,
    data_realizado timestamp without time zone DEFAULT now() NOT NULL,
    necessidade character varying NOT NULL,
    prazo character varying NOT NULL,
    nome character varying NOT NULL,
    email character varying NOT NULL,
    telefone character varying,
    id_cidade bigint NOT NULL,
    endereco character varying NOT NULL,
    cep character varying NOT NULL,
    visualizado boolean DEFAULT false NOT NULL,
    finalizado boolean DEFAULT false NOT NULL
);


ALTER TABLE orcamento OWNER TO admtorresserv;

--
-- TOC entry 192 (class 1259 OID 50912)
-- Name: orcamento_id_orcamento_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE orcamento_id_orcamento_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE orcamento_id_orcamento_seq OWNER TO admtorresserv;

--
-- TOC entry 2119 (class 0 OID 0)
-- Dependencies: 192
-- Name: orcamento_id_orcamento_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE orcamento_id_orcamento_seq OWNED BY orcamento.id_orcamento;


--
-- TOC entry 179 (class 1259 OID 33974)
-- Name: servico; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE servico (
    id_servico bigint NOT NULL,
    id_usuario bigint NOT NULL,
    titulo_servico character varying NOT NULL,
    descricao_servico character varying NOT NULL,
    id_categoria_servico bigint NOT NULL,
    numero_visualizacoes integer DEFAULT 0,
    inativo boolean DEFAULT false,
    data_servico timestamp without time zone DEFAULT now(),
    dias_semana character varying NOT NULL,
    hora_inicial character varying,
    hora_final character varying
);


ALTER TABLE servico OWNER TO admtorresserv;

--
-- TOC entry 185 (class 1259 OID 42502)
-- Name: servico_denunciado; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE servico_denunciado (
    id_servico_denunciado bigint NOT NULL,
    id_servico bigint NOT NULL,
    id_usuario bigint NOT NULL,
    motivo_denuncia character varying NOT NULL,
    data_hora_denuncia timestamp without time zone DEFAULT now()
);


ALTER TABLE servico_denunciado OWNER TO admtorresserv;

--
-- TOC entry 184 (class 1259 OID 42500)
-- Name: servico_denunciado_id_servico_denunciado_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE servico_denunciado_id_servico_denunciado_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE servico_denunciado_id_servico_denunciado_seq OWNER TO admtorresserv;

--
-- TOC entry 2120 (class 0 OID 0)
-- Dependencies: 184
-- Name: servico_denunciado_id_servico_denunciado_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE servico_denunciado_id_servico_denunciado_seq OWNED BY servico_denunciado.id_servico_denunciado;


--
-- TOC entry 181 (class 1259 OID 34045)
-- Name: servico_favorito; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE servico_favorito (
    id_servico_favorito bigint NOT NULL,
    id_usuario bigint NOT NULL,
    id_servico bigint NOT NULL
);


ALTER TABLE servico_favorito OWNER TO admtorresserv;

--
-- TOC entry 180 (class 1259 OID 34043)
-- Name: servico_favorito_id_servico_favorito_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE servico_favorito_id_servico_favorito_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE servico_favorito_id_servico_favorito_seq OWNER TO admtorresserv;

--
-- TOC entry 2121 (class 0 OID 0)
-- Dependencies: 180
-- Name: servico_favorito_id_servico_favorito_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE servico_favorito_id_servico_favorito_seq OWNED BY servico_favorito.id_servico_favorito;


--
-- TOC entry 183 (class 1259 OID 34110)
-- Name: servico_fotos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE servico_fotos (
    id_servico_fotos bigint NOT NULL,
    id_servico bigint,
    url character varying NOT NULL
);


ALTER TABLE servico_fotos OWNER TO admtorresserv;

--
-- TOC entry 182 (class 1259 OID 34108)
-- Name: servico_fotos_id_servico_fotos_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE servico_fotos_id_servico_fotos_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE servico_fotos_id_servico_fotos_seq OWNER TO admtorresserv;

--
-- TOC entry 2122 (class 0 OID 0)
-- Dependencies: 182
-- Name: servico_fotos_id_servico_fotos_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE servico_fotos_id_servico_fotos_seq OWNED BY servico_fotos.id_servico_fotos;


--
-- TOC entry 178 (class 1259 OID 33972)
-- Name: servico_id_servico_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE servico_id_servico_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE servico_id_servico_seq OWNER TO admtorresserv;

--
-- TOC entry 2123 (class 0 OID 0)
-- Dependencies: 178
-- Name: servico_id_servico_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE servico_id_servico_seq OWNED BY servico.id_servico;


--
-- TOC entry 198 (class 1259 OID 51248)
-- Name: servico_nota; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE servico_nota (
    id_servico_nota bigint NOT NULL,
    id_servico bigint,
    id_usuario bigint,
    nota integer NOT NULL
);


ALTER TABLE servico_nota OWNER TO admtorresserv;

--
-- TOC entry 197 (class 1259 OID 51246)
-- Name: servico_nota_id_servico_nota_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE servico_nota_id_servico_nota_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE servico_nota_id_servico_nota_seq OWNER TO admtorresserv;

--
-- TOC entry 2124 (class 0 OID 0)
-- Dependencies: 197
-- Name: servico_nota_id_servico_nota_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE servico_nota_id_servico_nota_seq OWNED BY servico_nota.id_servico_nota;


--
-- TOC entry 188 (class 1259 OID 42584)
-- Name: servico_visualizacoes; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE servico_visualizacoes (
    id_servico_visualizacoes bigint NOT NULL,
    id_servico bigint NOT NULL,
    id_usuario bigint NOT NULL,
    data_hora timestamp without time zone DEFAULT now()
);


ALTER TABLE servico_visualizacoes OWNER TO admtorresserv;

--
-- TOC entry 187 (class 1259 OID 42582)
-- Name: servico_visualizacoes_id_servico_visualizacoes_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE servico_visualizacoes_id_servico_visualizacoes_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE servico_visualizacoes_id_servico_visualizacoes_seq OWNER TO admtorresserv;

--
-- TOC entry 2125 (class 0 OID 0)
-- Dependencies: 187
-- Name: servico_visualizacoes_id_servico_visualizacoes_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE servico_visualizacoes_id_servico_visualizacoes_seq OWNED BY servico_visualizacoes.id_servico_visualizacoes;


--
-- TOC entry 173 (class 1259 OID 33879)
-- Name: usuario; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE usuario (
    id_usuario bigint NOT NULL,
    nome_usuario character varying NOT NULL,
    login_usuario character varying NOT NULL,
    senha_usuario character varying NOT NULL,
    email_usuario character varying,
    acesso_usuario character varying NOT NULL,
    data_cadastro timestamp without time zone DEFAULT now(),
    bloqueado boolean DEFAULT false NOT NULL
);


ALTER TABLE usuario OWNER TO admtorresserv;

--
-- TOC entry 172 (class 1259 OID 33877)
-- Name: usuario_id_usuario_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE usuario_id_usuario_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE usuario_id_usuario_seq OWNER TO admtorresserv;

--
-- TOC entry 2126 (class 0 OID 0)
-- Dependencies: 172
-- Name: usuario_id_usuario_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE usuario_id_usuario_seq OWNED BY usuario.id_usuario;


--
-- TOC entry 175 (class 1259 OID 33941)
-- Name: usuario_perfil; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE usuario_perfil (
    id_usuario_perfil bigint NOT NULL,
    id_usuario bigint NOT NULL,
    pessoa_fisica_juridica boolean DEFAULT true NOT NULL,
    cpf character varying,
    rg character varying,
    cnpj character varying,
    inscricao_estadual character varying,
    telefone1 character varying NOT NULL,
    telefone2 character varying,
    endereco character varying NOT NULL,
    complemento character varying,
    cep character varying NOT NULL,
    numero integer NOT NULL,
    profissao character varying,
    funcao character varying,
    avaliacao_positiva integer DEFAULT 0,
    avaliacao_negativa integer DEFAULT 0,
    foto_perfil character varying,
    id_cidade bigint NOT NULL,
    CONSTRAINT pessoa_fisica_juridica_chk CHECK (((((pessoa_fisica_juridica IS TRUE) AND (cpf IS NOT NULL)) AND (rg IS NOT NULL)) OR (((pessoa_fisica_juridica IS FALSE) AND (cnpj IS NOT NULL)) AND (inscricao_estadual IS NOT NULL))))
);


ALTER TABLE usuario_perfil OWNER TO admtorresserv;

--
-- TOC entry 174 (class 1259 OID 33939)
-- Name: usuario_perfil_id_usuario_perfil_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE usuario_perfil_id_usuario_perfil_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE usuario_perfil_id_usuario_perfil_seq OWNER TO admtorresserv;

--
-- TOC entry 2127 (class 0 OID 0)
-- Dependencies: 174
-- Name: usuario_perfil_id_usuario_perfil_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE usuario_perfil_id_usuario_perfil_seq OWNED BY usuario_perfil.id_usuario_perfil;


--
-- TOC entry 191 (class 1259 OID 42672)
-- Name: vw_detalhes_servico; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW vw_detalhes_servico AS
 SELECT servico.id_usuario AS id_profisional,
    categoria_servico.id_categoria_servico,
    categoria_servico.nome_categoria_servico,
    categoria_servico.icon_name,
    servico.id_usuario,
    servico.id_servico,
    servico.titulo_servico,
    servico.descricao_servico,
    servico.data_servico,
    servico.numero_visualizacoes,
    servico.dias_semana,
    servico.hora_inicial,
    servico.hora_final,
    usuario.nome_usuario,
    usuario.email_usuario,
    usuario_perfil.pessoa_fisica_juridica,
    usuario_perfil.cpf,
    usuario_perfil.cnpj,
    usuario_perfil.inscricao_estadual,
    usuario_perfil.telefone1,
    usuario_perfil.telefone2,
    usuario_perfil.cep,
    usuario_perfil.id_cidade,
    usuario_perfil.profissao,
    usuario_perfil.funcao,
    usuario_perfil.avaliacao_positiva,
    usuario_perfil.avaliacao_negativa,
    usuario_perfil.foto_perfil,
    ( SELECT (((cidade.cidade)::text || ' - '::text) || (cidade.uf)::text)
           FROM cidade
          WHERE (cidade.id_cidade = usuario_perfil.id_cidade)) AS cidade,
    usuario.data_cadastro
   FROM (((categoria_servico
     JOIN servico USING (id_categoria_servico))
     JOIN usuario USING (id_usuario))
     JOIN usuario_perfil USING (id_usuario))
  WHERE (servico.inativo IS FALSE);


ALTER TABLE vw_detalhes_servico OWNER TO admtorresserv;

--
-- TOC entry 186 (class 1259 OID 42577)
-- Name: vw_listar_servicos_categoria; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW vw_listar_servicos_categoria AS
 SELECT categoria_servico.id_categoria_servico,
    servico.id_servico,
    servico.titulo_servico,
    servico.descricao_servico,
    servico.data_servico,
    servico.numero_visualizacoes,
    usuario.nome_usuario,
    usuario_perfil.pessoa_fisica_juridica,
    usuario_perfil.cpf,
    usuario_perfil.cnpj,
    usuario_perfil.inscricao_estadual,
    usuario.data_cadastro
   FROM (((categoria_servico
     JOIN servico USING (id_categoria_servico))
     JOIN usuario USING (id_usuario))
     JOIN usuario_perfil USING (id_usuario))
  WHERE (servico.inativo IS FALSE);


ALTER TABLE vw_listar_servicos_categoria OWNER TO admtorresserv;

--
-- TOC entry 194 (class 1259 OID 51183)
-- Name: vw_orcamento; Type: VIEW; Schema: public; Owner: postgres
--

CREATE VIEW vw_orcamento AS
 SELECT orcamento.id_orcamento,
    orcamento.id_usuario AS id_usuario_realizou_orcamento,
    orcamento.id_servico,
    orcamento.data_realizado AS data_realizado_orcamento,
    orcamento.necessidade,
    orcamento.prazo,
    orcamento.nome AS nome_usuario_realizou_orcamento,
    orcamento.email AS email_usuario_realizou_orcamento,
    orcamento.telefone AS telefone_usuario_realizou_orcamento,
    orcamento.id_cidade AS id_cidade_usuario_realizou_orcamento,
    orcamento.endereco AS endereco_usuario_realizou_orcamento,
    orcamento.cep AS cep_usuario_realizou_orcamento,
    orcamento.visualizado AS orcamento_visualizado,
    orcamento.finalizado AS orcamento_finalizado,
    servico.id_usuario AS id_profissional,
    servico.id_categoria_servico,
    servico.titulo_servico,
    cidade.cidade,
    cidade.uf
   FROM (((orcamento
     JOIN servico USING (id_servico))
     JOIN usuario ON ((usuario.id_usuario = servico.id_usuario)))
     JOIN cidade ON ((orcamento.id_cidade = cidade.id_cidade)))
  ORDER BY orcamento.data_realizado DESC;


ALTER TABLE vw_orcamento OWNER TO admtorresserv;

--
-- TOC entry 1926 (class 2604 OID 33966)
-- Name: id_categoria_servico; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY categoria_servico ALTER COLUMN id_categoria_servico SET DEFAULT nextval('categoria_servico_id_categoria_servico_seq'::regclass);


--
-- TOC entry 1937 (class 2604 OID 42608)
-- Name: id_cidade; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY cidade ALTER COLUMN id_cidade SET DEFAULT nextval('cidade_id_cidade_seq'::regclass);


--
-- TOC entry 1942 (class 2604 OID 51225)
-- Name: id_mensagem_item; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY mensagem_item ALTER COLUMN id_mensagem_item SET DEFAULT nextval('mensagem_item_id_mensagem_item_seq'::regclass);


--
-- TOC entry 1938 (class 2604 OID 50917)
-- Name: id_orcamento; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY orcamento ALTER COLUMN id_orcamento SET DEFAULT nextval('orcamento_id_orcamento_seq'::regclass);


--
-- TOC entry 1927 (class 2604 OID 33977)
-- Name: id_servico; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY servico ALTER COLUMN id_servico SET DEFAULT nextval('servico_id_servico_seq'::regclass);


--
-- TOC entry 1933 (class 2604 OID 42505)
-- Name: id_servico_denunciado; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY servico_denunciado ALTER COLUMN id_servico_denunciado SET DEFAULT nextval('servico_denunciado_id_servico_denunciado_seq'::regclass);


--
-- TOC entry 1931 (class 2604 OID 34048)
-- Name: id_servico_favorito; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY servico_favorito ALTER COLUMN id_servico_favorito SET DEFAULT nextval('servico_favorito_id_servico_favorito_seq'::regclass);


--
-- TOC entry 1932 (class 2604 OID 34113)
-- Name: id_servico_fotos; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY servico_fotos ALTER COLUMN id_servico_fotos SET DEFAULT nextval('servico_fotos_id_servico_fotos_seq'::regclass);


--
-- TOC entry 1946 (class 2604 OID 51251)
-- Name: id_servico_nota; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY servico_nota ALTER COLUMN id_servico_nota SET DEFAULT nextval('servico_nota_id_servico_nota_seq'::regclass);


--
-- TOC entry 1935 (class 2604 OID 42587)
-- Name: id_servico_visualizacoes; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY servico_visualizacoes ALTER COLUMN id_servico_visualizacoes SET DEFAULT nextval('servico_visualizacoes_id_servico_visualizacoes_seq'::regclass);


--
-- TOC entry 1918 (class 2604 OID 33882)
-- Name: id_usuario; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuario ALTER COLUMN id_usuario SET DEFAULT nextval('usuario_id_usuario_seq'::regclass);


--
-- TOC entry 1921 (class 2604 OID 33944)
-- Name: id_usuario_perfil; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuario_perfil ALTER COLUMN id_usuario_perfil SET DEFAULT nextval('usuario_perfil_id_usuario_perfil_seq'::regclass);


--
-- TOC entry 1956 (class 2606 OID 33971)
-- Name: categoria_servico_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY categoria_servico
    ADD CONSTRAINT categoria_servico_pkey PRIMARY KEY (id_categoria_servico);


--
-- TOC entry 1972 (class 2606 OID 42613)
-- Name: cidade_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY cidade
    ADD CONSTRAINT cidade_pkey PRIMARY KEY (id_cidade);


--
-- TOC entry 1976 (class 2606 OID 51232)
-- Name: mensagem_item_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY mensagem_item
    ADD CONSTRAINT mensagem_item_pkey PRIMARY KEY (id_mensagem_item);


--
-- TOC entry 1974 (class 2606 OID 50925)
-- Name: orcamento_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY orcamento
    ADD CONSTRAINT orcamento_pkey PRIMARY KEY (id_orcamento);


--
-- TOC entry 1968 (class 2606 OID 42510)
-- Name: servico_denunciado_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY servico_denunciado
    ADD CONSTRAINT servico_denunciado_pkey PRIMARY KEY (id_servico_denunciado);


--
-- TOC entry 1960 (class 2606 OID 34062)
-- Name: servico_favorito_id_usuario_id_servico_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY servico_favorito
    ADD CONSTRAINT servico_favorito_id_usuario_id_servico_key UNIQUE (id_usuario, id_servico);


--
-- TOC entry 1962 (class 2606 OID 34050)
-- Name: servico_favorito_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY servico_favorito
    ADD CONSTRAINT servico_favorito_pkey PRIMARY KEY (id_servico_favorito);


--
-- TOC entry 1964 (class 2606 OID 34118)
-- Name: servico_fotos_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY servico_fotos
    ADD CONSTRAINT servico_fotos_pkey PRIMARY KEY (id_servico_fotos);


--
-- TOC entry 1966 (class 2606 OID 42325)
-- Name: servico_fotos_url_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY servico_fotos
    ADD CONSTRAINT servico_fotos_url_key UNIQUE (url);


--
-- TOC entry 1978 (class 2606 OID 51253)
-- Name: servico_nota_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY servico_nota
    ADD CONSTRAINT servico_nota_pkey PRIMARY KEY (id_servico_nota);


--
-- TOC entry 1958 (class 2606 OID 33982)
-- Name: servico_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY servico
    ADD CONSTRAINT servico_pkey PRIMARY KEY (id_servico);


--
-- TOC entry 1970 (class 2606 OID 42590)
-- Name: servico_visualizacoes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY servico_visualizacoes
    ADD CONSTRAINT servico_visualizacoes_pkey PRIMARY KEY (id_servico_visualizacoes);


--
-- TOC entry 1948 (class 2606 OID 33889)
-- Name: usuario_login_usuario_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuario
    ADD CONSTRAINT usuario_login_usuario_key UNIQUE (login_usuario);


--
-- TOC entry 1952 (class 2606 OID 33960)
-- Name: usuario_perfil_id_usuario_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuario_perfil
    ADD CONSTRAINT usuario_perfil_id_usuario_key UNIQUE (id_usuario);


--
-- TOC entry 1954 (class 2606 OID 33952)
-- Name: usuario_perfil_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuario_perfil
    ADD CONSTRAINT usuario_perfil_pkey PRIMARY KEY (id_usuario_perfil);


--
-- TOC entry 1950 (class 2606 OID 33887)
-- Name: usuario_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuario
    ADD CONSTRAINT usuario_pkey PRIMARY KEY (id_usuario);


--
-- TOC entry 1996 (class 2620 OID 42602)
-- Name: incrementa_visualizacao; Type: TRIGGER; Schema: public; Owner: postgres
--

CREATE TRIGGER incrementa_visualizacao AFTER INSERT ON public.servico_visualizacoes FOR EACH ROW EXECUTE PROCEDURE incrementa_visualizacao();


--
-- TOC entry 1993 (class 2606 OID 51233)
-- Name: mensagem_item_id_orcamento_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY mensagem_item
    ADD CONSTRAINT mensagem_item_id_orcamento_fkey FOREIGN KEY (id_orcamento) REFERENCES orcamento(id_orcamento) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 1992 (class 2606 OID 50936)
-- Name: orcamento_id_cidade_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY orcamento
    ADD CONSTRAINT orcamento_id_cidade_fkey FOREIGN KEY (id_cidade) REFERENCES cidade(id_cidade);


--
-- TOC entry 1991 (class 2606 OID 50931)
-- Name: orcamento_id_servico_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY orcamento
    ADD CONSTRAINT orcamento_id_servico_fkey FOREIGN KEY (id_servico) REFERENCES servico(id_servico);


--
-- TOC entry 1990 (class 2606 OID 50926)
-- Name: orcamento_id_usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY orcamento
    ADD CONSTRAINT orcamento_id_usuario_fkey FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario);


--
-- TOC entry 1986 (class 2606 OID 42511)
-- Name: servico_denunciado_id_servico_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY servico_denunciado
    ADD CONSTRAINT servico_denunciado_id_servico_fkey FOREIGN KEY (id_servico) REFERENCES servico(id_servico) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 1987 (class 2606 OID 42516)
-- Name: servico_denunciado_id_usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY servico_denunciado
    ADD CONSTRAINT servico_denunciado_id_usuario_fkey FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 1983 (class 2606 OID 42412)
-- Name: servico_favorito_id_servico_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY servico_favorito
    ADD CONSTRAINT servico_favorito_id_servico_fkey FOREIGN KEY (id_servico) REFERENCES servico(id_servico) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 1984 (class 2606 OID 42417)
-- Name: servico_favorito_id_usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY servico_favorito
    ADD CONSTRAINT servico_favorito_id_usuario_fkey FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 1985 (class 2606 OID 42407)
-- Name: servico_fotos_id_servico_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY servico_fotos
    ADD CONSTRAINT servico_fotos_id_servico_fkey FOREIGN KEY (id_servico) REFERENCES servico(id_servico) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 1982 (class 2606 OID 33988)
-- Name: servico_id_categoria_servico_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY servico
    ADD CONSTRAINT servico_id_categoria_servico_fkey FOREIGN KEY (id_categoria_servico) REFERENCES categoria_servico(id_categoria_servico);


--
-- TOC entry 1981 (class 2606 OID 33983)
-- Name: servico_id_usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY servico
    ADD CONSTRAINT servico_id_usuario_fkey FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario);


--
-- TOC entry 1994 (class 2606 OID 51254)
-- Name: servico_nota_id_servico_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY servico_nota
    ADD CONSTRAINT servico_nota_id_servico_fkey FOREIGN KEY (id_servico) REFERENCES servico(id_servico) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 1995 (class 2606 OID 51259)
-- Name: servico_nota_id_usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY servico_nota
    ADD CONSTRAINT servico_nota_id_usuario_fkey FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 1988 (class 2606 OID 42591)
-- Name: servico_visualizacoes_id_servico_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY servico_visualizacoes
    ADD CONSTRAINT servico_visualizacoes_id_servico_fkey FOREIGN KEY (id_servico) REFERENCES servico(id_servico);


--
-- TOC entry 1989 (class 2606 OID 42596)
-- Name: servico_visualizacoes_id_usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY servico_visualizacoes
    ADD CONSTRAINT servico_visualizacoes_id_usuario_fkey FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario);


--
-- TOC entry 1980 (class 2606 OID 42636)
-- Name: usuario_perfil_id_cidade_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuario_perfil
    ADD CONSTRAINT usuario_perfil_id_cidade_fkey FOREIGN KEY (id_cidade) REFERENCES cidade(id_cidade);


--
-- TOC entry 1979 (class 2606 OID 33953)
-- Name: usuario_perfil_id_usuario_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY usuario_perfil
    ADD CONSTRAINT usuario_perfil_id_usuario_fkey FOREIGN KEY (id_usuario) REFERENCES usuario(id_usuario) ON UPDATE CASCADE ON DELETE CASCADE;


--
-- TOC entry 2113 (class 0 OID 0)
-- Dependencies: 7
-- Name: public; Type: ACL; Schema: -; Owner: postgres
--

REVOKE ALL ON SCHEMA public FROM PUBLIC;
REVOKE ALL ON SCHEMA public FROM postgres;
GRANT ALL ON SCHEMA public TO postgres;
GRANT ALL ON SCHEMA public TO PUBLIC;


-- Completed on 2018-11-22 20:23:18

--
-- PostgreSQL database dump complete
--


