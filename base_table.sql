/*
SQL FOR TABLE musicas
*/
CREATE TABLE musicas (
id varchar(36),
name varchar(255),
deleted tinyint(1),
date_created datetime,
user_created varchar(36),
date_modified datetime,
user_modified varchar(36),
link varchar(255),
origem varchar(255),
codigo varchar(6),
md5 varchar(255),
tipo varchar(255),
PRIMARY KEY (id)
) ENGINE = InnoDB;

-- SQL INDEX FOR TABLE musicas
CREATE INDEX id_dele0 ON musicas (id, deleted );
CREATE INDEX name_dele1 ON musicas (name, deleted );
CREATE INDEX tipo_dele2 ON musicas (tipo, deleted );

/*
SQL FOR TABLE musicas_favorites
*/
CREATE TABLE musicas_favorites (
id varchar(36),
name varchar(255),
deleted tinyint(1),
date_created datetime,
user_created varchar(36),
date_modified datetime,
user_modified varchar(36),
musica_id varchar(36),
PRIMARY KEY (id)
) ENGINE = InnoDB;

-- SQL INDEX FOR TABLE musicas_favorites
CREATE INDEX id_dele0 ON musicas_favorites (id, deleted );
CREATE INDEX name_dele1 ON musicas_favorites (name, deleted );
CREATE INDEX usua_dele2 ON musicas_favorites (user_created, deleted );

/*
SQL FOR TABLE musicas_fila
*/
CREATE TABLE musicas_fila (
id varchar(36),
name varchar(255),
deleted tinyint(1),
date_created datetime,
user_created varchar(36),
date_modified datetime,
user_modified varchar(36),
musica_id varchar(36),
status varchar(255),
PRIMARY KEY (id)
) ENGINE = InnoDB;

-- SQL INDEX FOR TABLE musicas_fila
CREATE INDEX id_dele0 ON musicas_fila (id, deleted );
CREATE INDEX name_dele1 ON musicas_fila (name, deleted );
CREATE INDEX stat_dele2 ON musicas_fila (status, deleted );