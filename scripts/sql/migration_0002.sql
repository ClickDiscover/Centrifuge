ALTER TABLE products
  ADD COLUMN source VARCHAR(255) DEFAULT 'network',
  ADD COLUMN vertical VARCHAR(255) DEFAULT 'diet';
