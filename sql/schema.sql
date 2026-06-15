DROP DATABASE IF EXISTS yeticave;

CREATE DATABASE IF NOT EXISTS yeticave
    DEFAULT CHARACTER SET utf8mb4
    DEFAULT COLLATE utf8mb4_0900_ai_ci;

USE yeticave;

CREATE TABLE IF NOT EXISTS `user` (
    id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    created_at    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    email         VARCHAR(128) NOT NULL,
    name          VARCHAR(128) NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    contact_info  TEXT         NOT NULL,

    UNIQUE KEY uq_email (email)
);

CREATE TABLE IF NOT EXISTS `category` (

    id   INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(64)  NOT NULL,
    slug VARCHAR(128) NOT NULL,

    UNIQUE KEY uq_name (name),
    UNIQUE KEY uq_slug (slug)
);

CREATE TABLE IF NOT EXISTS `lot` (
    id              INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    created_at      TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    title           VARCHAR(128) NOT NULL,
    description     TEXT         NOT NULL,
    img_url         VARCHAR(255) NOT NULL,
    start_price     INT UNSIGNED NOT NULL,
    expire_date     DATETIME     NOT NULL,
    bid_step        INT UNSIGNED NOT NULL,

    author_id       INT UNSIGNED NOT NULL,
    category_id     INT UNSIGNED NOT NULL,
    winner_bid_id   INT UNSIGNED NULL,
    winner_notified INT TINYINT  NULL,


    INDEX idx_author_id     (author_id),
    INDEX idx_expire_date   (expire_date),
    INDEX idx_winner_bid_id (winner_bid_id),

    FULLTEXT KEY ft_title_description (title, description),

    CONSTRAINT fk_author_id   FOREIGN KEY (author_id)   REFERENCES `user`     (id),
    CONSTRAINT fk_category_id FOREIGN KEY (category_id) REFERENCES `category` (id)
);

CREATE TABLE IF NOT EXISTS `bid` (
    id         INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    created_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
    amount     INT UNSIGNED NOT NULL,
    user_id    INT UNSIGNED NOT NULL,
    lot_id     INT UNSIGNED NOT NULL,

    INDEX idx_user_id    (user_id),
    INDEX idx_created_at (created_at),

    CONSTRAINT fk_user_id FOREIGN KEY (user_id) REFERENCES `user` (id),
    CONSTRAINT fk_lot_id  FOREIGN KEY (lot_id)  REFERENCES `lot`  (id)
);

ALTER TABLE `lot`
ADD CONSTRAINT fk_winner_bid_id FOREIGN KEY (winner_bid_id) REFERENCES `bid` (id);
