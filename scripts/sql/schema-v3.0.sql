
CREATE TABLE products (
  id serial,
  uuid UUID NOT NULL,
  name text,
  image_href text,
  PRIMARY KEY (id)
);

CREATE TABLE networks (
  id serial,
  uuid UUID NOT NULL,
  name text,
  PRIMARY KEY (id)
);


CREATE TABLE offers (
  id serial,
  uuid UUID NOT NULL,
  incentive int,
  product_id int,
  network_id int,
  PRIMARY KEY (id) 
);


CREATE TABLE ad_text (
  id serial,
  uuid UUID NOT NULL,
  text text NOT NULL,
  PRIMARY KEY (id)
);

CREATE TABLE ad_image (
  id serial,
  uuid UUID NOT NULL,
  image text NOT NULL,
  PRIMARY KEY (id)
);


CREATE TABLE ad_cta (
  id serial,
  uuid UUID NOT NULL,
  text text,
  color varchar(32),
  element text,
  PRIMARY KEY (id)
);


CREATE TABLE articles (
  id serial,
  uuid UUID NOT NULL,
  num_slots INT,
  template_file text NOT NULL,
  PRIMARY KEY (id)
);

ALTER TABLE ONLY offers ADD CONSTRAINT offers_product_id_fkey FOREIGN KEY (product_id) REFERENCES products(id);
ALTER TABLE ONLY offers ADD CONSTRAINT offers_network_id_fkey FOREIGN KEY (network_id) REFERENCES networks(id);

--  CREATE TABLE slots (
  --  id serial,
  --  uuid UUID NOT NULL,
  --  offer_id 
--  )

--  ALTER TABLE ONLY landers
    --  ADD CONSTRAINT landers_param_id_fkey FOREIGN KEY (param_id) REFERENCES ae_parameters(id);

