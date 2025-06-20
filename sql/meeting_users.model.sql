CREATE TABLE IF NOT EXISTS meeting_users (
    meeting_id INTEGER NOT NULL REFERENCES meetings (id),
    user_id INTEGER NOT NULL REFERENCES users (id),
    PRIMARY KEY (meeting_id, user_id)
);
