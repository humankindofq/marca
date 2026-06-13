CREATE DATABASE IF NOT EXISTS marca_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE marca_db;

-- Категория товаров
CREATE TABLE categories (
                            id INT AUTO_INCREMENT PRIMARY KEY,
                            name VARCHAR(255) NOT NULL,
                            slug VARCHAR(255) NOT NULL,
                            description TEXT
);

-- Товары
CREATE TABLE products (
                          id INT AUTO_INCREMENT PRIMARY KEY,
                          category_id INT,
                          name VARCHAR(255) NOT NULL,
                          slug VARCHAR(255) NOT NULL,
                          description TEXT,
                          price DECIMAL(10, 2) NOT NULL,
                          unit VARCHAR(50) DEFAULT 'шт',
                          image VARCHAR(255),
                          specifications JSON,
                          in_stock BOOLEAN DEFAULT TRUE,
                          created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Услуги
CREATE TABLE services (
                          id INT AUTO_INCREMENT PRIMARY KEY,
                          name VARCHAR(255) NOT NULL,
                          description TEXT,
                          price DECIMAL(10, 2),
                          icon VARCHAR(255)
);

-- Заказы
CREATE TABLE orders (
                        id INT AUTO_INCREMENT PRIMARY KEY,
                        customer_name VARCHAR(255) NOT NULL,
                        customer_phone VARCHAR(50) NOT NULL,
                        customer_email VARCHAR(255),
                        delivery_address TEXT,
                        total_amount DECIMAL(10, 2) NOT NULL,
                        status ENUM('new', 'processing', 'completed', 'cancelled') DEFAULT 'new',
                        comment TEXT,
                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Позиции заказа
CREATE TABLE order_items (
                             id INT AUTO_INCREMENT PRIMARY KEY,
                             order_id INT NOT NULL,
                             product_id INT,
                             product_name VARCHAR(255) NOT NULL,
                             quantity INT NOT NULL,
                             price DECIMAL(10, 2) NOT NULL,
                             FOREIGN KEY (order_id) REFERENCES orders(id) ON DELETE CASCADE
);

-- Добавляем категории
INSERT INTO categories (name, slug, description) VALUES
                                                     ('Фундаментные блоки', 'fundamentnye-bloki', 'Блоки для устройства фундаментов'),
                                                     ('Плиты перекрытия', 'plita-perekrytiya', 'Железобетонные плиты перекрытия'),
                                                     ('Кольца колодезные', 'kolodetsnye-koltsa', 'Бетонные кольца для колодцев'),
                                                     ('Дорожные плиты', 'dorozhnye-plita', 'Плиты для дорожного покрытия'),
                                                     ('Товарный бетон', 'tovarny-beton', 'Бетон различных марок');

-- Добавляем товары
INSERT INTO products (category_id, name, slug, description, price, unit, specifications) VALUES
                                                                                             (1, 'Блок ФБС 24.4.6', 'fbs-24-4-6', 'Фундаментный блок стеновой', 3500.00, 'шт', '{"weight": "700 кг", "dimensions": "2380x400x580 мм", "concrete_class": "B7.5"}'),
                                                                                             (1, 'Блок ФБС 12.3.6', 'fbs-12-3-6', 'Фундаментный блок стеновой', 1800.00, 'шт', '{"weight": "350 кг", "dimensions": "1180x300x580 мм", "concrete_class": "B7.5"}'),
                                                                                             (2, 'Плита ПК 60.15', 'pk-60-15', 'Плита перекрытия многопустотная', 4200.00, 'шт', '{"weight": "1900 кг", "dimensions": "5980x1490x220 мм", "concrete_class": "B15"}'),
                                                                                             (2, 'Плита ПК 30.10', 'pk-30-10', 'Плита перекрытия многопустотная', 2100.00, 'шт', '{"weight": "800 кг", "dimensions": "2980x990x220 мм", "concrete_class": "B15"}'),
                                                                                             (3, 'Кольцо КС 10.9', 'ks-10-9', 'Кольцо стеновое', 2800.00, 'шт', '{"weight": "600 кг", "dimensions": "Ø1000x900 мм", "concrete_class": "B15"}'),
                                                                                             (3, 'Кольцо КС 15.9', 'ks-15-9', 'Кольцо стеновое', 4500.00, 'шт', '{"weight": "900 кг", "dimensions": "Ø1500x900 мм", "concrete_class": "B15"}'),
                                                                                             (4, 'Плита ПАГ 14', 'pag-14', 'Плита аэродромная', 8500.00, 'шт', '{"weight": "5100 кг", "dimensions": "6000x2000x140 мм", "concrete_class": "B30"}'),
                                                                                             (5, 'Бетон М200', 'beton-m200', 'Товарный бетон', 3800.00, 'м³', '{"strength": "200 кгс/см²", "mobility": "П3"}'),
                                                                                             (5, 'Бетон М300', 'beton-m300', 'Товарный бетон', 4200.00, 'м³', '{"strength": "300 кгс/см²", "mobility": "П3"}'),
                                                                                             (5, 'Бетон М400', 'beton-m400', 'Товарный бетон', 4800.00, 'м³', '{"strength": "400 кгс/см²", "mobility": "П3"}');

-- Услуги
INSERT INTO services (name, description, price, icon) VALUES
                                                          ('Доставка манипулятором', 'Доставка и разгрузка продукции', 8000.00, 'truck'),
                                                          ('Погрузка', 'Погрузка продукции на транспорт', 1500.00, 'loading'),
                                                          ('Разгрузка', 'Разгрузка продукции на объекте', 2000.00, 'unloading');