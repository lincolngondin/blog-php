CREATE TABLE posts (
    id uuid primary key,
    title text,
    content text,
    author text,
    user_id varchar(12),
    creation_time timestamp,
    last_update timestamp
);

CREATE TABLE users (
    id varchar(12),
    name text primary key,
    password text,
    creation_time timestamp
);
