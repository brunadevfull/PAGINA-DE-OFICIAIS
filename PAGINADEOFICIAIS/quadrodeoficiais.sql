--
-- PostgreSQL database dump
--

-- Dumped from database version 12.19 (Ubuntu 12.19-0ubuntu0.20.04.1)
-- Dumped by pg_dump version 12.19 (Ubuntu 12.19-0ubuntu0.20.04.1)

SET statement_timeout = 0;
SET lock_timeout = 0;
SET idle_in_transaction_session_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SELECT pg_catalog.set_config('search_path', '', false);
SET check_function_bodies = false;
SET xmloption = content;
SET client_min_messages = warning;
SET row_security = off;

SET default_tablespace = '';

SET default_table_access_method = heap;

--
-- Name: postos; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.postos (
    id integer NOT NULL,
    descricao character varying(255) NOT NULL,
    imagem character varying(255) NOT NULL
);


ALTER TABLE public.postos OWNER TO postgres;

--
-- Name: graduacoes_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.graduacoes_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.graduacoes_id_seq OWNER TO postgres;

--
-- Name: graduacoes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.graduacoes_id_seq OWNED BY public.postos.id;


--
-- Name: oficiais; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.oficiais (
    id integer NOT NULL,
    nome character varying(255) NOT NULL,
    posto_id integer NOT NULL,
    status character varying(5) NOT NULL,
    localizacao integer NOT NULL,
    CONSTRAINT oficiais_status_check CHECK (((status)::text = ANY ((ARRAY['bordo'::character varying, 'terra'::character varying])::text[])))
);


ALTER TABLE public.oficiais OWNER TO postgres;

--
-- Name: oficiais_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.oficiais_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.oficiais_id_seq OWNER TO postgres;

--
-- Name: oficiais_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.oficiais_id_seq OWNED BY public.oficiais.id;


--
-- Name: users; Type: TABLE; Schema: public; Owner: postgres
--

CREATE TABLE public.users (
    id integer NOT NULL,
    username character varying(255) NOT NULL,
    password character varying(255) NOT NULL,
    is_admin boolean DEFAULT false NOT NULL
);


ALTER TABLE public.users OWNER TO postgres;

--
-- Name: users_id_seq; Type: SEQUENCE; Schema: public; Owner: postgres
--

CREATE SEQUENCE public.users_id_seq
    AS integer
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE public.users_id_seq OWNER TO postgres;

--
-- Name: users_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: postgres
--

ALTER SEQUENCE public.users_id_seq OWNED BY public.users.id;


--
-- Name: oficiais id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.oficiais ALTER COLUMN id SET DEFAULT nextval('public.oficiais_id_seq'::regclass);


--
-- Name: postos id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.postos ALTER COLUMN id SET DEFAULT nextval('public.graduacoes_id_seq'::regclass);


--
-- Name: users id; Type: DEFAULT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users ALTER COLUMN id SET DEFAULT nextval('public.users_id_seq'::regclass);


--
-- Data for Name: oficiais; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.oficiais (id, nome, posto_id, status, localizacao) FROM stdin;
1	KLEBER	2	bordo	1
9	REGINA GRISI	5	bordo	10
10	WÍLLAM	6	bordo	11
19	ALLISON	12	bordo	20
20	GUSTAVO	12	bordo	21
21	VIANA	13	bordo	22
22	PINA TRIGO	13	bordo	23
23	MÁRCIO MARTINS	14	bordo	24
24	MATTOS	14	bordo	25
6	ELAINE A.	4	bordo	6
7	CAMILA	5	bordo	7
8	AZEVEDO	5	bordo	8
16	GREICE	9	bordo	17
2	COSENDEY	2	bordo	2
3	PAULA BALLARD	3	bordo	3
5	ROGÉRIO R.	4	bordo	5
25	MACHADO	14	bordo	26
26	EIRAS	15	bordo	27
27	EDER HYPOLITO	16	bordo	28
28	VERISSIMO	17	bordo	29
13	MATEUS BARBOSA	4	bordo	14
14	DUTRA LIMA	7	bordo	15
15	NATHALIA D.	8	bordo	16
11	THIAGO SILVA	7	bordo	12
12	YAGO	7	bordo	13
17	LARISSA C.	10	bordo	18
18	KARINE	10	bordo	19
99	REJANE AMARAL	4	bordo	4
\.


--
-- Data for Name: postos; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.postos (id, descricao, imagem) FROM stdin;
1	CMG (IM)	imagens/cmg_im.png
2	CF (IM)	imagens/cf_im.png
3	CF (T)	imagens/cf_t.png
4	CC (T)	imagens/cc_t.png
5	CC (IM)	imagens/cc_im.png
6	CT (T)	imagens/ct_t.png
7	CT (IM)	imagens/ct_im.png
8	CT (QC-IM)	imagens/ct_qc_im.png
9	CT (RM2-T)	imagens/ct_rm2_t.png
10	1T (RM2-T)	imagens/1t_c.png
11	1T (T)	imagens/1t_t.png
12	1T (IM)	imagens/1t_im.png
13	2T (RM2-T)	imagens/2t_c.png
14	2T (AA)	imagens/2t_aa.png
15	CMG (RM1-T)	imagens/CMG_T_RM1.png
16	CF (RM1-T)	imagens/CF_T_RM1.png
17	CC (RM1-T)	imagens/CC_T_RM1.png
\.


--
-- Data for Name: users; Type: TABLE DATA; Schema: public; Owner: postgres
--

COPY public.users (id, username, password, is_admin) FROM stdin;
81	usuario	$2y$10$ropZk79Jya9SOxbx4ItBE.TvGMD12O.BpX2kHD699e8IXpdIopfAm	f
1	admin	$2y$10$n9HLNrellnnEOPMRir9EV.o/l3pfnsdWaVKu2rven3agHsI3gB7Gy	t
6	admin2	$2y$10$1ieLMdOysnw8k4/3rD9e0uNxBi7izmNn5OWdVoBRC6KJyoXO6DQHu	t
\.


--
-- Name: graduacoes_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.graduacoes_id_seq', 17, true);


--
-- Name: oficiais_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.oficiais_id_seq', 160, true);


--
-- Name: users_id_seq; Type: SEQUENCE SET; Schema: public; Owner: postgres
--

SELECT pg_catalog.setval('public.users_id_seq', 81, true);


--
-- Name: postos graduacoes_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.postos
    ADD CONSTRAINT graduacoes_pkey PRIMARY KEY (id);


--
-- Name: oficiais oficiais_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.oficiais
    ADD CONSTRAINT oficiais_pkey PRIMARY KEY (id);


--
-- Name: users users_pkey; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_pkey PRIMARY KEY (id);


--
-- Name: users users_username_key; Type: CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.users
    ADD CONSTRAINT users_username_key UNIQUE (username);


--
-- Name: oficiais oficiais_graduacao_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: postgres
--

ALTER TABLE ONLY public.oficiais
    ADD CONSTRAINT oficiais_graduacao_id_fkey FOREIGN KEY (posto_id) REFERENCES public.postos(id);


--
-- PostgreSQL database dump complete
--

