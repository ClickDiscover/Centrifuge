name()
-- PostgreSQL database dump
--



SET statement_timeout = 0;
SET lock_timeout = 0;
SET client_encoding = 'UTF8';
SET standard_conforming_strings = on;
SET check_function_bodies = false;
SET client_min_messages = warning;

SET search_path = public, pg_catalog;

CREATE TABLE geos (
    id serial,
    name VARCHAR(255),
    country VARCHAR(255) NOT NULL,
    locale VARCHAR(255) NOT NULL DEFAULT 'en',
    data jsonb DEFAULT '{}'::jsonb,
    variables jsonb DEFAULT '{}'::jsonb,
    PRIMARY KEY (id)
);

ALTER TABLE geos OWNER TO centrifuge;
ALTER TABLE geos_id_seq OWNER TO centrifuge;

INSERT INTO geos (name, country, locale, data, variables) VALUES ('United States', 'US', 'en_US', '{"alt.name": "America", "unit.format": "long", "unit.length": "centimeter", "unit.weight": "pound"}', '{}');
ALTER TABLE ONLY geos ALTER COLUMN id SET DEFAULT nextval('geos_id_seq'::regclass);


INSERT INTO geos (country, name, locale, data) VALUES ('CA', 'Canada', 'en_CA', '{"unit.length": "centimeter", "unit.weight": "kilogram"}');
INSERT INTO geos (country, name, locale, data) VALUES ('GB', 'Great Britan', 'en_GB', '{"unit.length": "centimeter", "unit.weight": "kilogram"}');
INSERT INTO geos (country, name, locale, data) VALUES ('AU', 'Australia', 'en_AU', '{"unit.length": "centimeter", "unit.weight": "kilogram"}');
INSERT INTO geos (country, name, locale, data) VALUES ('ZA', 'South Africa', 'en_ZA', '{"pronoun": "South African", "unit.length": "centimeter", "unit.weight": "kilogram"}');
INSERT INTO geos (country, name, locale, data) VALUES ('IT', 'Italy', 'it', '{"unit.length": "centimeter", "unit.weight": "kilogram"}');


ALTER TABLE landers
  DROP COLUMN tracking,
  ADD COLUMN geo_id int DEFAULT 1,
  ADD COLUMN active boolean DEFAULT 'TRUE',
  ADD COLUMN template_vars jsonb DEFAULT '{}',
  ADD FOREIGN KEY (geo_id) REFERENCES geos(id);

