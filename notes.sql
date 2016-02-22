--  check table exists
SELECT EXISTS (
	SELECT 1 
	FROM   pg_catalog.pg_class c
	JOIN   pg_catalog.pg_namespace n ON n.oid = c.relnamespace
	WHERE  n.nspname = 'public'
	AND    c.relname = 'vhffs_letsencrypt'
	AND    c.relkind = 'r'    -- only tables(?)
) AS exists;


--  create table
CREATE TABLE vhffs_letsencrypt (
	httpd_id integer NOT NULL,
	certificate_date date DEFAULT NULL,
	error_log text DEFAULT NULL
);
ALTER TABLE ONLY vhffs_letsencrypt
	ADD CONSTRAINT vhffs_letsencrypt_pkey PRIMARY KEY (httpd_id);
ALTER TABLE ONLY vhffs_letsencrypt
	ADD CONSTRAINT fk_vhffs_letsencrypt_vhffs_httpd FOREIGN KEY (httpd_id) REFERENCES vhffs_httpd(httpd_id);


--  insert or update
BEGIN;

LOCK TABLE vhffs_letsencrypt IN EXCLUSIVE MODE;

--SELECT	COUNT(*)
--FROM	vhffs_letsencrypt
--WHERE	httpd_id = 5;

UPDATE vhffs_letsencrypt
SET certificate_date = '2015-09-05',
	error_log = NULL
WHERE httpd_id = 5;

INSERT INTO vhffs_letsencrypt (httpd_id, certificate_date, error_log)
VALUES (5, '2015-09-05', 'TEST');

COMMIT;
