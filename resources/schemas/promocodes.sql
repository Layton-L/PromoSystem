CREATE TABLE IF NOT EXISTS promocodes (
    promocode      TEXT    NOT     NULL,
    amount         INTEGER NOT     NULL,
    count_uses     INTEGER DEFAULT 0,
    max_count_uses INTEGER NOT     NULL,
    action_time    INTEGER NOT     NULL
);