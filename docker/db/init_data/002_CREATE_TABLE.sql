-- reasonテーブルの作成
CREATE TABLE IF NOT EXISTS reasons(
    contact_id BIGINT,
    reason VARCHAR(255),
    FOREIGN KEY(id) REFERENCES contacts(id) ON DELETE CASCADE
);