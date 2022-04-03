/*
SQL FOR TABLE arquivos
*/
CREATE TABLE arquivos (
id varchar(36),
name varchar(255),
arquivo varchar(255),
mimetype varchar(255),
tipo varchar(255),
registro varchar(36),
tabela varchar(255),
campo varchar(255),
deleted tinyint(1),
date_created datetime,
user_created varchar(36),
date_modified datetime,
user_modified varchar(36),
PRIMARY KEY (id)
) ENGINE = InnoDB;

-- SQL INDEX FOR TABLE arquivos
CREATE INDEX id_dele0 ON arquivos (id, deleted );
CREATE INDEX name_dele1 ON arquivos (name, deleted );
CREATE INDEX tipo_dele2 ON arquivos (tipo, deleted );

/*
SQL FOR TABLE grupos
*/
CREATE TABLE grupos (
id int NOT NULL AUTO_INCREMENT,
name varchar(255),
ativo tinyint(1) DEFAULT TRUE,
deleted tinyint(1),
date_created datetime,
user_created varchar(36),
date_modified datetime,
user_modified varchar(36),
PRIMARY KEY (id)
) ENGINE = InnoDB;

-- SQL INDEX FOR TABLE grupos
CREATE INDEX id_dele0 ON grupos (id, deleted );
CREATE INDEX name_dele1 ON grupos (name, deleted );
CREATE INDEX ativ_dele2 ON grupos (ativo, deleted );

/*
SQL FOR TABLE menus
*/
CREATE TABLE menus (
id int NOT NULL AUTO_INCREMENT,
name varchar(255),
ativo tinyint(1) DEFAULT TRUE,
ordem int DEFAULT '1',
tipo varchar(255) DEFAULT '1',
menu_pai varchar(36),
url_base varchar(255),
icon varchar(30) DEFAULT 'fas fa-list',
label varchar(30),
perm varchar(36),
deleted tinyint(1),
date_created datetime,
user_created varchar(36),
date_modified datetime,
user_modified varchar(36),
PRIMARY KEY (id)
) ENGINE = InnoDB;

-- SQL INDEX FOR TABLE menus
CREATE INDEX id_dele0 ON menus (id, deleted );
CREATE INDEX name_ativ_tipo_dele1 ON menus (name, ativo, tipo, deleted );
CREATE INDEX ativ_tipo_dele2 ON menus (ativo, tipo, deleted );

/*
SQL FOR TABLE parametros
*/
CREATE TABLE parametros (
id int NOT NULL AUTO_INCREMENT,
name varchar(255),
codigo varchar(255),
descricao text,
valor varchar(255),
deleted tinyint(1),
date_created datetime,
user_created varchar(36),
date_modified datetime,
user_modified varchar(36),
PRIMARY KEY (id)
) ENGINE = InnoDB;

-- SQL INDEX FOR TABLE parametros
CREATE INDEX id_dele0 ON parametros (id, deleted );
CREATE INDEX codi_dele1 ON parametros (codigo, deleted );

/*
SQL FOR TABLE permissao
*/
CREATE TABLE permissao (
id int NOT NULL AUTO_INCREMENT,
name varchar(255),
deleted tinyint(1),
date_created datetime,
user_created varchar(36),
date_modified datetime,
user_modified varchar(36),
PRIMARY KEY (id)
) ENGINE = InnoDB;

-- SQL INDEX FOR TABLE permissao
CREATE INDEX id_dele0 ON permissao (id, deleted );
CREATE INDEX name_dele1 ON permissao (name, deleted );

/*
SQL FOR TABLE permissao_grupo
*/
CREATE TABLE permissao_grupo (
id int NOT NULL AUTO_INCREMENT,
permissao varchar(36),
grupo varchar(36),
nivel int,
deleted tinyint(1),
date_created datetime,
user_created varchar(36),
date_modified datetime,
user_modified varchar(36),
PRIMARY KEY (id)
) ENGINE = InnoDB;

-- SQL INDEX FOR TABLE permissao_grupo
CREATE INDEX id_dele0 ON permissao_grupo (id, deleted );
CREATE INDEX perm_grup_dele1 ON permissao_grupo (permissao, grupo, deleted );
CREATE INDEX grup_dele2 ON permissao_grupo (grupo, deleted );

/*
SQL FOR TABLE preferencias_usuario
*/
CREATE TABLE preferencias_usuario (
id varchar(36),
name varchar(255),
valor text,
deleted tinyint(1),
date_created datetime,
user_created varchar(36),
date_modified datetime,
user_modified varchar(36),
PRIMARY KEY (id)
) ENGINE = InnoDB;

-- SQL INDEX FOR TABLE preferencias_usuario
CREATE INDEX id_dele0 ON preferencias_usuario (id, deleted );
CREATE INDEX user_dele1 ON preferencias_usuario (user_created, deleted );
CREATE INDEX user_name_dele2 ON preferencias_usuario (user_created, name, deleted );

/*
SQL FOR TABLE usuarios
*/
CREATE TABLE usuarios (
id varchar(36),
name varchar(255),
deleted tinyint(1),
date_created datetime,
user_created varchar(36),
date_modified datetime,
user_modified varchar(36),
email varchar(255),
senha varchar(255),
status varchar(255) DEFAULT 'ativo',
tipo varchar(36),
last_ip varchar(255),
last_connected datetime,
hash_esqueci_senha varchar(255),
ultima_troca_senha datetime,
dark_mode tinyint(1),
telefone varchar(255),
celular varchar(255),
PRIMARY KEY (id)
) ENGINE = InnoDB;

-- SQL INDEX FOR TABLE usuarios
CREATE INDEX id_dele0 ON usuarios (id, deleted );
CREATE INDEX tipo_dele1 ON usuarios (tipo, deleted );


/*
* END OF BASE TABLES 
*/

INSERT INTO permissao (id, name, deleted, date_created, user_created, date_modified, user_modified) VALUES
(1, 'Cadastro de Usuários', 0, '2021-09-20 17:21:37', '1', '2021-09-20 17:21:37', '1'),
(2, 'Cadastro de Grupos', 0, '2021-09-20 17:21:48', '1', '2021-09-20 17:21:48', '1'),
(3, 'Cadastro de Permissões', 0, '2021-09-20 17:21:55', '1', '2021-09-20 17:21:55', '1'),
(4, 'Permissões do Grupo', 0, '2021-09-20 17:22:05', '1', '2021-09-20 17:22:05', '1'),
(5, 'Painel Admin', 0, '2021-09-20 17:22:12', '1', '2021-09-20 17:22:12', '1'),
(6, 'Admin - Utilidades', 0, '2021-09-20 17:22:22', '1', '2021-09-20 17:22:22', '1'),
(7, 'Cadastro de Arquivos', 0, '2021-09-20 17:22:22', '1', '2021-09-20 17:22:22', '1'),
(8, 'Cadastro de Parâmetros', 0, '2021-09-20 17:22:22', '1', '2021-09-20 17:22:22', '1'),
(9, 'Cadastro de Menus', 0, '2021-10-25 20:25:00', '1', '2021-10-25 20:25:00', '1');

INSERT INTO grupos (id, name, ativo, deleted, date_created, user_created, date_modified, user_modified) VALUES
(1, 'Administrador do Sistema', 1, 0, '2021-09-19 22:07:10', '1', '2021-09-19 22:07:10', '1'),
(2, 'Regular', 1, 0, '2021-09-19 22:07:19', '1', '2021-09-19 22:07:19', '1');

INSERT INTO parametros (id, name, codigo, descricao, valor, deleted, date_created, user_created, date_modified, user_modified) VALUES ('0', 'Criar Conta pela tela de login', 'enable_create_user_login', '0 - Desabilitado\r\n1 - Habilitado', '1', '0', '2021-09-26 21:04:50', '1', '2021-09-26 21:09:56', '1');

-- Default pass 1234
INSERT INTO usuarios (id, name, deleted, date_created, user_created, date_modified, user_modified, email, senha, tipo, last_ip, last_connected, hash_esqueci_senha, ultima_troca_senha, dark_mode, status) VALUES ('1', 'Admin', 0, '2020-09-18 09:36:16', '1', '2021-09-19 22:30:44', NULL, 'admin@admin.com', '81dc9bdb52d04dc20036dbd8313ed055', '1', '192.168.1.175', '2021-09-19 22:30:44', NULL, '2021-08-07 10:59:55', 1, 'ativo');