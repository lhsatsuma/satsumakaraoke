/*
SQL FOR TABLE arquivos
*/
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

-- SQL INDEX FOR TABLE arquivos
CREATE INDEX id_dele0 ON arquivos (id, deletado );
CREATE INDEX nome_dele1 ON arquivos (nome, deletado );
CREATE INDEX tipo_dele2 ON arquivos (tipo, deletado );

/*
SQL FOR TABLE grupos
*/
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

-- SQL INDEX FOR TABLE grupos
CREATE INDEX id_dele0 ON grupos (id, deletado );
CREATE INDEX nome_dele1 ON grupos (nome, deletado );
CREATE INDEX ativ_dele2 ON grupos (ativo, deletado );

/*
SQL FOR TABLE menus
*/
CREATE TABLE menus (
id int NOT NULL AUTO_INCREMENT,
nome varchar(255),
ativo tinyint(1) DEFAULT TRUE,
ordem int DEFAULT '1',
tipo varchar(255) DEFAULT '1',
menu_pai varchar(36),
url_base varchar(255),
icon varchar(30) DEFAULT 'fas fa-list',
label varchar(30),
perm varchar(36),
deletado tinyint(1),
data_criacao datetime,
usuario_criacao varchar(36),
data_modificacao datetime,
usuario_modificacao varchar(36),
PRIMARY KEY (id)
) ENGINE = InnoDB;

-- SQL INDEX FOR TABLE menus
CREATE INDEX id_dele0 ON menus (id, deletado );
CREATE INDEX nome_ativ_tipo_dele1 ON menus (nome, ativo, tipo, deletado );
CREATE INDEX ativ_tipo_dele2 ON menus (ativo, tipo, deletado );

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

/*
SQL FOR TABLE parametros
*/
CREATE TABLE parametros (
id int NOT NULL AUTO_INCREMENT,
nome varchar(255),
codigo varchar(255),
descricao text,
valor varchar(255),
deletado tinyint(1),
data_criacao datetime,
usuario_criacao varchar(36),
data_modificacao datetime,
usuario_modificacao varchar(36),
PRIMARY KEY (id)
) ENGINE = InnoDB;

-- SQL INDEX FOR TABLE parametros
CREATE INDEX id_dele0 ON parametros (id, deletado );
CREATE INDEX codi_dele1 ON parametros (codigo, deletado );

/*
SQL FOR TABLE permissao
*/
CREATE TABLE permissao (
id int NOT NULL AUTO_INCREMENT,
nome varchar(255),
deletado tinyint(1),
data_criacao datetime,
usuario_criacao varchar(36),
data_modificacao datetime,
usuario_modificacao varchar(36),
PRIMARY KEY (id)
) ENGINE = InnoDB;

-- SQL INDEX FOR TABLE permissao
CREATE INDEX id_dele0 ON permissao (id, deletado );
CREATE INDEX nome_dele1 ON permissao (nome, deletado );

/*
SQL FOR TABLE permissao_grupo
*/
CREATE TABLE permissao_grupo (
id int NOT NULL AUTO_INCREMENT,
permissao varchar(36),
grupo varchar(36),
nivel int,
deletado tinyint(1),
data_criacao datetime,
usuario_criacao varchar(36),
data_modificacao datetime,
usuario_modificacao varchar(36),
PRIMARY KEY (id)
) ENGINE = InnoDB;

-- SQL INDEX FOR TABLE permissao_grupo
CREATE INDEX id_dele0 ON permissao_grupo (id, deletado );
CREATE INDEX perm_grup_dele1 ON permissao_grupo (permissao, grupo, deletado );
CREATE INDEX grup_dele2 ON permissao_grupo (grupo, deletado );

/*
SQL FOR TABLE preferencias_usuario
*/
CREATE TABLE preferencias_usuario (
id varchar(36),
nome varchar(255),
valor text,
deletado tinyint(1),
data_criacao datetime,
usuario_criacao varchar(36),
data_modificacao datetime,
usuario_modificacao varchar(36),
PRIMARY KEY (id)
) ENGINE = InnoDB;

-- SQL INDEX FOR TABLE preferencias_usuario
CREATE INDEX id_dele0 ON preferencias_usuario (id, deletado );
CREATE INDEX usua_dele1 ON preferencias_usuario (usuario_criacao, deletado );
CREATE INDEX usua_nome_dele2 ON preferencias_usuario (usuario_criacao, nome, deletado );

/*
SQL FOR TABLE usuarios
*/
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
telefone varchar(255),
celular varchar(255),
PRIMARY KEY (id)
) ENGINE = InnoDB;

-- SQL INDEX FOR TABLE usuarios
CREATE INDEX id_dele0 ON usuarios (id, deletado );
CREATE INDEX tipo_dele1 ON usuarios (tipo, deletado );


/*
* END OF BASE TABLES 
*/

INSERT INTO permissao (id, nome, deletado, data_criacao, usuario_criacao, data_modificacao, usuario_modificacao) VALUES
(1, 'Cadastro de Usuários', 0, '2021-09-20 17:21:37', 'asd-1s1s-3bnmvhj', '2021-09-20 17:21:37', 'asd-1s1s-3bnmvhj'),
(2, 'Cadastro de Grupos', 0, '2021-09-20 17:21:48', 'asd-1s1s-3bnmvhj', '2021-09-20 17:21:48', 'asd-1s1s-3bnmvhj'),
(3, 'Cadastro de Permissões', 0, '2021-09-20 17:21:55', 'asd-1s1s-3bnmvhj', '2021-09-20 17:21:55', 'asd-1s1s-3bnmvhj'),
(4, 'Permissões do Grupo', 0, '2021-09-20 17:22:05', 'asd-1s1s-3bnmvhj', '2021-09-20 17:22:05', 'asd-1s1s-3bnmvhj'),
(5, 'Painel Admin', 0, '2021-09-20 17:22:12', 'asd-1s1s-3bnmvhj', '2021-09-20 17:22:12', 'asd-1s1s-3bnmvhj'),
(6, 'Admin - Utilidades', 0, '2021-09-20 17:22:22', 'asd-1s1s-3bnmvhj', '2021-09-20 17:22:22', 'asd-1s1s-3bnmvhj'),
(7, 'Cadastro de Arquivos', 0, '2021-09-20 17:22:22', 'asd-1s1s-3bnmvhj', '2021-09-20 17:22:22', 'asd-1s1s-3bnmvhj'),
(8, 'Cadastro de Parâmetros', 0, '2021-09-20 17:22:22', 'asd-1s1s-3bnmvhj', '2021-09-20 17:22:22', 'asd-1s1s-3bnmvhj'),
(9, 'Cadastro de Menus', 0, '2021-10-25 20:25:00', 'asd-1s1s-3bnmvhj', '2021-10-25 20:25:00', 'asd-1s1s-3bnmvhj');

INSERT INTO grupos (id, nome, ativo, deletado, data_criacao, usuario_criacao, data_modificacao, usuario_modificacao) VALUES
(1, 'Administrador do Sistema', 1, 0, '2021-09-19 22:07:10', 'asd-1s1s-3bnmvhj', '2021-09-19 22:07:10', 'asd-1s1s-3bnmvhj'),
(2, 'Regular', 1, 0, '2021-09-19 22:07:19', 'asd-1s1s-3bnmvhj', '2021-09-19 22:07:19', 'asd-1s1s-3bnmvhj');

INSERT INTO parametros (id, nome, codigo, descricao, valor, deletado, data_criacao, usuario_criacao, data_modificacao, usuario_modificacao) VALUES ('0', 'Criar Conta pela tela de login', 'enable_create_user_login', '0 - Desabilitado\r\n1 - Habilitado', '1', '0', '2021-09-26 21:04:50', 'asd-1s1s-3bnmvhj', '2021-09-26 21:09:56', 'asd-1s1s-3bnmvhj');

INSERT INTO usuarios (id, nome, deletado, data_criacao, usuario_criacao, data_modificacao, usuario_modificacao, email, senha, tipo, last_ip, last_connected, hash_esqueci_senha, ultima_troca_senha, dark_mode, status) VALUES ('asd-1s1s-3bnmvhj', 'Luis', 0, '2020-09-18 09:36:16', 'asd-1s1s-3bnmvhj', '2021-09-19 22:30:44', NULL, 'minoruluis@gmail.com', '81dc9bdb52d04dc20036dbd8313ed055', '1', '192.168.1.175', '2021-09-19 22:30:44', NULL, '2021-08-07 10:59:55', 1, 'ativo'); --Default pass 1234