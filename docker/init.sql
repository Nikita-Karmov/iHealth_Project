-- Создание таблиц с вашей точной структурой
CREATE TABLE users (
    user_id SERIAL PRIMARY KEY,
    email VARCHAR UNIQUE,
    password_hash VARCHAR,
    full_name VARCHAR,
    created_at TIMESTAMP,
    updated_at TIMESTAMP
);

CREATE TABLE IF NOT EXISTS categories (
    category_id SERIAL PRIMARY KEY,
    name VARCHAR NOT NULL,
    description TEXT,  
    slug VARCHAR UNIQUE NOT NULL,
    type VARCHAR NOT NULL,
    parent_category_id INT NULL REFERENCES categories(category_id)
);

CREATE TABLE products (
    product_id SERIAL PRIMARY KEY,
    name VARCHAR,
    description TEXT,
    price DECIMAL,
    stock_quantity INT,
    image_url VARCHAR,
    created_at TIMESTAMP
);

CREATE TABLE product_category (
    product_id INT REFERENCES products(product_id),
    category_id INT REFERENCES categories(category_id),
    PRIMARY KEY (product_id, category_id)
);

-- Остальные таблицы в точности как у вас
CREATE TABLE carts (
    cart_id SERIAL PRIMARY KEY,
    user_id INT REFERENCES users(user_id),
    is_active BOOLEAN,
    created_at TIMESTAMP
);

CREATE TABLE cart_items (
    cart_item_id SERIAL PRIMARY KEY,
    cart_id INT REFERENCES carts(cart_id),
    product_id INT REFERENCES products(product_id),
    quantity INT
);

CREATE TABLE wishlists (
    wishlist_id SERIAL PRIMARY KEY,
    user_id INT REFERENCES users(user_id),
    product_id INT REFERENCES products(product_id),
    added_at TIMESTAMP,
    UNIQUE (user_id, product_id)
);

CREATE TABLE orders (
    order_id SERIAL PRIMARY KEY,
    user_id INT REFERENCES users(user_id),
    total_amount DECIMAL,
    status VARCHAR, -- 'создан', 'оплачен', 'доставлен'
    created_at TIMESTAMP
);

CREATE TABLE order_items (
    order_item_id SERIAL PRIMARY KEY,
    order_id INT REFERENCES orders(order_id),
    product_id INT REFERENCES products(product_id),
    quantity INT,
    price_at_purchase DECIMAL
);

CREATE TABLE articles (
    article_id SERIAL PRIMARY KEY,
    title VARCHAR,
    content TEXT,
    main_category_id INT REFERENCES categories(category_id),
    image_url VARCHAR,
    published_at TIMESTAMP,
    is_featured BOOLEAN
);

CREATE TABLE subscriptions (
    subscription_id SERIAL PRIMARY KEY,
    email VARCHAR UNIQUE,
    subscribed_at TIMESTAMP
);

-- Начальные данные для категорий (адаптировано под вашу структуру)
INSERT INTO categories (name, slug, type) VALUES
('Омега-3', 'omega3', 'основная'),
('Железо', 'zhelezo', 'основная'),
('Магний', 'magnesium', 'основная'),
('Витамин D', 'vitamin-d', 'основная'),
('Витамин C', 'vitamin-c', 'основная'),
('Комплекс для женщин', 'forwoman', 'тематическая'),
('Комплекс для мужчин', 'forman', 'тематическая')
