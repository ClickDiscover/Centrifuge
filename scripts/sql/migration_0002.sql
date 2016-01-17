ALTER TABLE products
  ADD COLUMN source VARCHAR(255) DEFAULT 'network',
  ADD COLUMN vertical VARCHAR(255) DEFAULT 'diet';

CREATE TABLE fb_pixels (
  keyword VARCHAR(255) NOT NULL,
  fb_id VARCHAR(255) NOT NULL
);
