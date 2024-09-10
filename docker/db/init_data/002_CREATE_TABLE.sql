-- reasonテーブルの作成
CREATE TABLE IF NOT EXISTS reasons(
    id BIGINT,
    reason VARCHAR(255),
    FOREIGN KEY(id) REFERENCES contacts(id) ON DELETE CASCADE
);