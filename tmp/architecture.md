# VENDOR
- id    INT         NOT NULL PRIMARY KEY //AUTO_INCREMENT
- name  VARCHAR(50) NOT NULL

# CATEGORIES
- id    INT             NOT NULL PRIMARY KEY //AUTO_INCREMENT
- name  NVARCHAR(100)   NOT NULL

# PRODUCTS
- id            INT         NOT NULL PRIMARY KEY AUTO_INCREMENT
- code          VARCHAR(50) NOT NULL
- vendor_id     INT                  FOREIGN KEY
- category_id   INT                  FOREIGN KEY
- name          NVARCHAR(255)
- description   TEXT
- price         DOUBLE
