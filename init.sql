CREATE DATABASE IF NOT EXISTS symfony_db;
CREATE DATABASE IF NOT EXISTS symfony_test_db;
GRANT ALL PRIVILEGES ON symfony_db.* TO 'symfony'@'%';
GRANT ALL PRIVILEGES ON symfony_test_db.* TO 'symfony'@'%';
FLUSH PRIVILEGES;