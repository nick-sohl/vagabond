-- ============================================================
-- bearer tokens for the mobile app
-- POST /api/v1/auth/login   -> insert a row
-- POST /api/v1/auth/logout  -> delete the row
-- ============================================================
CREATE TABLE IF NOT EXISTS api_tokens (
    token      TEXT    PRIMARY KEY,
    user_id    INTEGER NOT NULL,
    created_at TEXT    NOT NULL DEFAULT current_timestamp,

    FOREIGN KEY (user_id) REFERENCES users(id)
        ON DELETE CASCADE ON UPDATE CASCADE
);

CREATE INDEX IF NOT EXISTS idx_api_tokens_user
    ON api_tokens(user_id);
