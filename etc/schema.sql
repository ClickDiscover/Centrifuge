--
-- PostgreSQL database dump
--

SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

--
-- Name: plpgsql; Type: EXTENSION; Schema: -; Owner: 
--

CREATE EXTENSION IF NOT EXISTS plpgsql WITH SCHEMA pg_catalog;


--
-- Name: EXTENSION plpgsql; Type: COMMENT; Schema: -; Owner: 
--

COMMENT ON EXTENSION plpgsql IS 'PL/pgSQL procedural language';


SET search_path = public, pg_catalog;

--
-- Name: offer_type; Type: TYPE; Schema: public; Owner: centrifuge
--

CREATE TYPE offer_type AS ENUM (
    'network',
    'adexchange'
);


ALTER TYPE offer_type OWNER TO centrifuge;

SET default_tablespace = '';

SET default_with_oids = false;

--
-- Name: ae_parameters; Type: TABLE; Schema: public; Owner: centrifuge; Tablespace: 
--

CREATE TABLE ae_parameters (
    id integer NOT NULL,
    affiliate_id integer NOT NULL,
    vertical character varying(64) NOT NULL,
    country character varying(64) NOT NULL,
    extra jsonb,
    name text
);


ALTER TABLE ae_parameters OWNER TO centrifuge;

--
-- Name: ae_parameters_id_seq; Type: SEQUENCE; Schema: public; Owner: centrifuge
--

CREATE SEQUENCE ae_parameters_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE ae_parameters_id_seq OWNER TO centrifuge;

--
-- Name: ae_parameters_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: centrifuge
--

ALTER SEQUENCE ae_parameters_id_seq OWNED BY ae_parameters.id;


--
-- Name: landers; Type: TABLE; Schema: public; Owner: centrifuge; Tablespace: 
--

CREATE TABLE landers (
    id integer NOT NULL,
    website_id integer,
    offer offer_type,
    product1_id integer,
    product2_id integer,
    param_id integer,
    notes text,
    tracking_tags character varying(64)[]
);


ALTER TABLE landers OWNER TO centrifuge;

--
-- Name: landers_id_seq; Type: SEQUENCE; Schema: public; Owner: centrifuge
--

CREATE SEQUENCE landers_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE landers_id_seq OWNER TO centrifuge;

--
-- Name: landers_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: centrifuge
--

ALTER SEQUENCE landers_id_seq OWNED BY landers.id;


--
-- Name: products; Type: TABLE; Schema: public; Owner: centrifuge; Tablespace: 
--

CREATE TABLE products (
    id integer NOT NULL,
    name text,
    image_url text
);


ALTER TABLE products OWNER TO centrifuge;

--
-- Name: products_id_seq; Type: SEQUENCE; Schema: public; Owner: centrifuge
--

CREATE SEQUENCE products_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE products_id_seq OWNER TO centrifuge;

--
-- Name: products_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: centrifuge
--

ALTER SEQUENCE products_id_seq OWNED BY products.id;


--
-- Name: routes; Type: TABLE; Schema: public; Owner: centrifuge; Tablespace: 
--

CREATE TABLE routes (
    id integer NOT NULL,
    url text NOT NULL,
    lander_id integer NOT NULL
);


ALTER TABLE routes OWNER TO centrifuge;

--
-- Name: routes_id_seq; Type: SEQUENCE; Schema: public; Owner: centrifuge
--

CREATE SEQUENCE routes_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE routes_id_seq OWNER TO centrifuge;

--
-- Name: routes_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: centrifuge
--

ALTER SEQUENCE routes_id_seq OWNED BY routes.id;


--
-- Name: websites; Type: TABLE; Schema: public; Owner: centrifuge; Tablespace: 
--

CREATE TABLE websites (
    id integer NOT NULL,
    name text,
    template character varying(255),
    assets character varying(255)
);


ALTER TABLE websites OWNER TO centrifuge;

--
-- Name: websites_id_seq; Type: SEQUENCE; Schema: public; Owner: centrifuge
--

CREATE SEQUENCE websites_id_seq
    START WITH 1
    INCREMENT BY 1
    NO MINVALUE
    NO MAXVALUE
    CACHE 1;


ALTER TABLE websites_id_seq OWNER TO centrifuge;

--
-- Name: websites_id_seq; Type: SEQUENCE OWNED BY; Schema: public; Owner: centrifuge
--

ALTER SEQUENCE websites_id_seq OWNED BY websites.id;


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: centrifuge
--

ALTER TABLE ONLY ae_parameters ALTER COLUMN id SET DEFAULT nextval('ae_parameters_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: centrifuge
--

ALTER TABLE ONLY landers ALTER COLUMN id SET DEFAULT nextval('landers_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: centrifuge
--

ALTER TABLE ONLY products ALTER COLUMN id SET DEFAULT nextval('products_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: centrifuge
--

ALTER TABLE ONLY routes ALTER COLUMN id SET DEFAULT nextval('routes_id_seq'::regclass);


--
-- Name: id; Type: DEFAULT; Schema: public; Owner: centrifuge
--

ALTER TABLE ONLY websites ALTER COLUMN id SET DEFAULT nextval('websites_id_seq'::regclass);


--
-- Name: ae_parameters_pkey; Type: CONSTRAINT; Schema: public; Owner: centrifuge; Tablespace: 
--

ALTER TABLE ONLY ae_parameters
    ADD CONSTRAINT ae_parameters_pkey PRIMARY KEY (id);


--
-- Name: landers_pkey; Type: CONSTRAINT; Schema: public; Owner: centrifuge; Tablespace: 
--

ALTER TABLE ONLY landers
    ADD CONSTRAINT landers_pkey PRIMARY KEY (id);


--
-- Name: products_pkey; Type: CONSTRAINT; Schema: public; Owner: centrifuge; Tablespace: 
--

ALTER TABLE ONLY products
    ADD CONSTRAINT products_pkey PRIMARY KEY (id);


--
-- Name: routes_pkey; Type: CONSTRAINT; Schema: public; Owner: centrifuge; Tablespace: 
--

ALTER TABLE ONLY routes
    ADD CONSTRAINT routes_pkey PRIMARY KEY (id);


--
-- Name: websites_pkey; Type: CONSTRAINT; Schema: public; Owner: centrifuge; Tablespace: 
--

ALTER TABLE ONLY websites
    ADD CONSTRAINT websites_pkey PRIMARY KEY (id);


--
-- Name: landers_param_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: centrifuge
--

ALTER TABLE ONLY landers
    ADD CONSTRAINT landers_param_id_fkey FOREIGN KEY (param_id) REFERENCES ae_parameters(id);


--
-- Name: landers_product1_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: centrifuge
--

ALTER TABLE ONLY landers
    ADD CONSTRAINT landers_product1_id_fkey FOREIGN KEY (product1_id) REFERENCES products(id);


--
-- Name: landers_product2_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: centrifuge
--

ALTER TABLE ONLY landers
    ADD CONSTRAINT landers_product2_id_fkey FOREIGN KEY (product2_id) REFERENCES products(id);


--
-- Name: landers_website_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: centrifuge
--

ALTER TABLE ONLY landers
    ADD CONSTRAINT landers_website_id_fkey FOREIGN KEY (website_id) REFERENCES websites(id);


--
-- Name: routes_lander_id_fkey; Type: FK CONSTRAINT; Schema: public; Owner: centrifuge
--

ALTER TABLE ONLY routes
    ADD CONSTRAINT routes_lander_id_fkey FOREIGN KEY (lander_id) REFERENCES landers(id);



--
-- PostgreSQL database dump complete
--

