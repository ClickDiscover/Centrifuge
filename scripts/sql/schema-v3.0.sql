
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


CREATE TABLE slots (
  id serial,
  uuid UUID NOT NULL,
  article_id INT,
  type INT,
  enabled BOOLEAN,
  PRIMARY KEY (id)
);


CREATE TABLE creatives (
  id serial,
  uuid UUID NOT NULL,
  text_id INT,
  image_id INT,
  cta_id INT,
  PRIMARY KEY (id)
);



ALTER TABLE ONLY slots ADD CONSTRAINT slots_articles_id_fkey FOREIGN KEY (article_id) REFERENCES articles(id);
ALTER TABLE ONLY creatives ADD CONSTRAINT creatives_text_id_fkey FOREIGN KEY (text_id) REFERENCES ad_text(id);
ALTER TABLE ONLY creatives ADD CONSTRAINT creatives_image_id_fkey FOREIGN KEY (image_id) REFERENCES ad_image(id);
ALTER TABLE ONLY creatives ADD CONSTRAINT creatives_cta_id_fkey FOREIGN KEY (cta_id) REFERENCES ad_cta(id);


CREATE TABLE traffickings (
  id serial,
  uuid UUID NOT NULL,
  offer_id INT,
  slot_id INT,
  PRIMARY KEY(id)
);

ALTER TABLE ONLY traffickings ADD CONSTRAINT traffickings_offer_id_fkey FOREIGN KEY (offer_id) REFERENCES offers(id);
ALTER TABLE ONLY traffickings ADD CONSTRAINT traffickings_slot_id_fkey FOREIGN KEY (slot_id) REFERENCES slots(id);

CREATE TABLE offers_creatives (
  id serial,
  uuid UUID NOT NULL,
  offer_id INT,
  creative_id INT,
  PRIMARY KEY(id)
);

ALTER TABLE ONLY offers_creatives ADD CONSTRAINT offers_creatives_offer_id_fkey FOREIGN KEY (offer_id) REFERENCES offers(id);
ALTER TABLE ONLY offers_creatives ADD CONSTRAINT offers_creatives_creative_id_fkey FOREIGN KEY (creative_id) REFERENCES creatives(id);
