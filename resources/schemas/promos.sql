CREATE TABLE IF NOT EXISTS promos (
    promo          TEXT    NOT     NULL,
    uses_count     INTEGER DEFAULT 0,
    max_uses_count INTEGER NOT     NULL,
    max_uses_time  INTEGER NOT     NULL,
    amount         INTEGER NOT     NULL
);