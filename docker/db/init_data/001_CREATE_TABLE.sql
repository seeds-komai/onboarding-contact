-- contactsテールブルを作成
CREATE TABLE IF NOT EXISTS contacts (
    id BIGINT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(255) NOT NULL,
    namerb VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    gender VARCHAR(255) NOT NULL,
    top_postalcode CHAR(3) NOT NULL,
    bottom_postalcode CHAR(4) NOT NULL,
    prefecture VARCHAR(255) NOT NULL,
    town VARCHAR(255) NOT NULL,
    housenumber VARCHAR(255) NOT NULL,
    building VARCHAR(255),
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);