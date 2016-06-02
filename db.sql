USE avtomagazin;
DROP TABLE products;
DROP TABLE categories;
DROP TABLE vendor;

USE avtomagazin;
CREATE TABLE vendor
(
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL
);

CREATE TABLE categories
(
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    name NVARCHAR(100) NOT NULL
);

CREATE TABLE products
(
    id INT PRIMARY KEY NOT NULL AUTO_INCREMENT,
    code VARCHAR(50) NOT NULL,
    vendor_id INT,
    category_id INT,
    name NVARCHAR(255),
    description TEXT,
    price DOUBLE,
    CONSTRAINT products_vendor_id_fk FOREIGN KEY (vendor_id) REFERENCES vendor (id),
    CONSTRAINT products_categories_id_fk FOREIGN KEY (category_id) REFERENCES categories (id)
);
