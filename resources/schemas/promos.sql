CREATE TABLE IF NOT EXISTS promos (
    promo       TEXT    NOT     NULL,
    uses        INTEGER DEFAULT 0,
    max_uses    INTEGER NOT     NULL,
    action_time INTEGER NOT     NULL,
    amount      INTEGER NOT     NULL
);