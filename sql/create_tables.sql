CREATE TABLE public.statistic
(
    id bigserial NOT NULL,
    ip character varying(16),
    browser character varying(128),
    url_from character varying(256),
    url_to character varying(256),
    os character varying(32),
    created_at timestamp(0) without time zone,
    PRIMARY KEY (id)
)
WITH (
    OIDS = FALSE
);

ALTER TABLE public.statistic
    OWNER to postgres;