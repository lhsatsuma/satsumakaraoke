CREATE TABLE arquivos (
id varchar(36),
nome varchar(255),
arquivo varchar(255),
mimetype varchar(255),
tipo varchar(255),
registro varchar(36),
tabela varchar(255),
campo varchar(255),
deletado tinyint(1),
data_criacao datetime,
usuario_criacao varchar(36),
data_modificacao datetime,
usuario_modificacao varchar(36),
PRIMARY KEY (id)
) ENGINE = InnoDB;
CREATE INDEX id_dele0 ON arquivos (id, deletado );
CREATE INDEX nome_dele1 ON arquivos (nome, deletado );
CREATE INDEX tipo_dele2 ON arquivos (tipo, deletado );
CREATE TABLE grupos (
id int NOT NULL AUTO_INCREMENT,
nome varchar(255),
ativo tinyint(1) DEFAULT TRUE,
deletado tinyint(1),
data_criacao datetime,
usuario_criacao varchar(36),
data_modificacao datetime,
usuario_modificacao varchar(36),
PRIMARY KEY (id)
) ENGINE = InnoDB;
CREATE INDEX id_dele0 ON grupos (id, deletado );
CREATE INDEX nome_dele1 ON grupos (nome, deletado );
CREATE INDEX ativ_dele2 ON grupos (ativo, deletado );
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
CREATE INDEX id_dele0 ON musicas (id, deletado );
CREATE INDEX nome_dele1 ON musicas (nome, deletado );
CREATE INDEX tipo_dele2 ON musicas (tipo, deletado );
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
CREATE INDEX id_dele0 ON musicas_favorites (id, deletado );
CREATE INDEX nome_dele1 ON musicas_favorites (nome, deletado );
CREATE INDEX usua_dele2 ON musicas_favorites (usuario_criacao, deletado );
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
CREATE INDEX id_dele0 ON musicas_fila (id, deletado );
CREATE INDEX nome_dele1 ON musicas_fila (nome, deletado );
CREATE INDEX stat_dele2 ON musicas_fila (status, deletado );
CREATE TABLE permissao (
id int NOT NULL AUTO_INCREMENT,
nome varchar(255),
cod_permissao int,
deletado tinyint(1),
data_criacao datetime,
usuario_criacao varchar(36),
data_modificacao datetime,
usuario_modificacao varchar(36),
PRIMARY KEY (id)
) ENGINE = InnoDB;
CREATE INDEX id_dele0 ON permissao (id, deletado );
CREATE INDEX nome_dele1 ON permissao (nome, deletado );
CREATE INDEX codp_dele2 ON permissao (cod_permissao, deletado );
CREATE TABLE permissao_grupo (
id int NOT NULL AUTO_INCREMENT,
permissao varchar(36),
grupo varchar(36),
deletado tinyint(1),
data_criacao datetime,
usuario_criacao varchar(36),
data_modificacao datetime,
usuario_modificacao varchar(36),
PRIMARY KEY (id)
) ENGINE = InnoDB;
CREATE INDEX id_dele0 ON permissao_grupo (id, deletado );
CREATE INDEX perm_grup_dele1 ON permissao_grupo (permissao, grupo, deletado );
CREATE INDEX grup_dele2 ON permissao_grupo (grupo, deletado );
CREATE TABLE usuarios (
id varchar(36),
nome varchar(255),
deletado tinyint(1),
data_criacao datetime,
usuario_criacao varchar(36),
data_modificacao datetime,
usuario_modificacao varchar(36),
email varchar(255),
senha varchar(255),
status varchar(255) DEFAULT 'ativo',
tipo varchar(36),
last_ip varchar(255),
last_connected datetime,
hash_esqueci_senha varchar(255),
ultima_troca_senha datetime,
dark_mode tinyint(1),
PRIMARY KEY (id)
) ENGINE = InnoDB;
CREATE INDEX id_dele0 ON usuarios (id, deletado );
CREATE INDEX tipo_dele1 ON usuarios (tipo, deletado );