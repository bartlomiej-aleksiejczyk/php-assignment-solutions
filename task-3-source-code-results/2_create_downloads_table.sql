CREATE TABLE downloads (
    download_id BIGINT NOT NULL,
    user_id BIGINT NOT NULL,
    ts DATETIME NOT NULL,
    rev TINYINT NOT NULL,
    source_app VARCHAR(255) NOT NULL,
    server VARCHAR(255) NOT NULL,
    PRIMARY KEY (download_id, user_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);
