/*
SQL FOR TABLE musicas
*/
CREATE TABLE musicas (
id varchar(36),
nome varchar(255),
deletado tinyint(1),
data_criacao datetime,
usuario_criacao varchar(36),
data_modificacao datetime,
usuario_modificacao varchar(36),
link varchar(255),
origem varchar(255),
codigo varchar(6),
md5 varchar(255),
tipo varchar(255),
PRIMARY KEY (id)
) ENGINE = InnoDB;

-- SQL INDEX FOR TABLE musicas
CREATE INDEX id_dele0 ON musicas (id, deletado );
CREATE INDEX nome_dele1 ON musicas (nome, deletado );
CREATE INDEX tipo_dele2 ON musicas (tipo, deletado );

/*
SQL FOR TABLE musicas_favorites
*/
CREATE TABLE musicas_favorites (
id varchar(36),
nome varchar(255),
deletado tinyint(1),
data_criacao datetime,
usuario_criacao varchar(36),
data_modificacao datetime,
usuario_modificacao varchar(36),
musica_id varchar(36),
PRIMARY KEY (id)
) ENGINE = InnoDB;

-- SQL INDEX FOR TABLE musicas_favorites
CREATE INDEX id_dele0 ON musicas_favorites (id, deletado );
CREATE INDEX nome_dele1 ON musicas_favorites (nome, deletado );
CREATE INDEX usua_dele2 ON musicas_favorites (usuario_criacao, deletado );

/*
SQL FOR TABLE musicas_fila
*/
CREATE TABLE musicas_fila (
id varchar(36),
nome varchar(255),
deletado tinyint(1),
data_criacao datetime,
usuario_criacao varchar(36),
data_modificacao datetime,
usuario_modificacao varchar(36),
musica_id varchar(36),
status varchar(255),
PRIMARY KEY (id)
) ENGINE = InnoDB;

-- SQL INDEX FOR TABLE musicas_fila
CREATE INDEX id_dele0 ON musicas_fila (id, deletado );
CREATE INDEX nome_dele1 ON musicas_fila (nome, deletado );
CREATE INDEX stat_dele2 ON musicas_fila (status, deletado );