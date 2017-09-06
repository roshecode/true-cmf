USE truecmf;

DROP TABLE IF EXISTS currencies;
CREATE TABLE currencies
(
  id INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  name NVARCHAR(255) NOT NULL,
  code VARCHAR(3) NOT NULL,
  symbol VARCHAR(1)
);

DROP TABLE IF EXISTS categories;
CREATE TABLE categories
(
  id INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  name NVARCHAR(255) NOT NULL
);

DROP TABLE IF EXISTS products;
CREATE TABLE products
(
  id INT UNSIGNED PRIMARY KEY NOT NULL AUTO_INCREMENT,
  name NVARCHAR(255) NOT NULL,
  image_src NVARCHAR(2048),
  description TEXT,
  short_description TINYTEXT,
  price DOUBLE NOT NULL,
  rating TINYINT UNSIGNED,
  currency_id INT UNSIGNED NOT NULL,
  category_id INT UNSIGNED NOT NULL,
  CONSTRAINT products_currencies_id_fk FOREIGN KEY (currency_id) REFERENCES currencies (id),
  CONSTRAINT products_categories_id_fk FOREIGN KEY (category_id) REFERENCES categories (id)
);
ALTER TABLE products COMMENT = 'Products';
