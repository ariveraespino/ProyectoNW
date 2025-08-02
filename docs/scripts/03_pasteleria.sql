CREATE DATABASE Pasteleria
    DEFAULT CHARACTER SET = 'utf8mb4';

    CREATE USER 'Pasteleria'@'%' IDENTIFIED BY 'Pasteleria';
  GRANT SELECT, INSERT, UPDATE, DELETE ON Pasteleria.* TO 'Pasteleria'@'%';
use Pasteleria

  CREATE TABLE
    usuario (
        usercod bigint(10) NOT NULL AUTO_INCREMENT,
        useremail varchar(80) DEFAULT NULL,
        username varchar(80) DEFAULT NULL,
        userpswd varchar(128) DEFAULT NULL,
        userfching datetime DEFAULT NULL,
        userpswdest char(3) DEFAULT NULL,
        userpswdexp datetime DEFAULT NULL,
        userest char(3) DEFAULT NULL,
        useractcod varchar(128) DEFAULT NULL,
        userpswdchg varchar(128) DEFAULT NULL,
        usertipo char(3) DEFAULT NULL COMMENT 'Tipo de Usuario, Normal, Consultor o Cliente',
        PRIMARY KEY (usercod),
        UNIQUE KEY useremail_UNIQUE (useremail),
        KEY usertipo (
            usertipo,
            useremail,
            usercod,
            userest
        )
    ) ENGINE = InnoDB AUTO_INCREMENT = 1 DEFAULT CHARSET = utf8;


    CREATE TABLE
    roles (
        rolescod varchar(128) NOT NULL,
        rolesdsc varchar(45) DEFAULT NULL,
        rolesest char(3) DEFAULT NULL,
        PRIMARY KEY (rolescod)
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8;

    CREATE TABLE
    roles_usuarios (
        usercod bigint(10) NOT NULL,
        rolescod varchar(128) NOT NULL,
        roleuserest char(3) DEFAULT NULL,
        roleuserfch datetime DEFAULT NULL,
        roleuserexp datetime DEFAULT NULL,
        PRIMARY KEY (usercod, rolescod),
        KEY rol_usuario_key_idx (rolescod),
        CONSTRAINT rol_usuario_key FOREIGN KEY (rolescod) REFERENCES roles (rolescod) ON DELETE NO ACTION ON UPDATE NO ACTION,
        CONSTRAINT usuario_rol_key FOREIGN KEY (usercod) REFERENCES usuario (usercod) ON DELETE NO ACTION ON UPDATE NO ACTION
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8;


CREATE TABLE
    funciones (
        fncod varchar(255) NOT NULL,
        fndsc varchar(255) DEFAULT NULL,
        fnest char(3) DEFAULT NULL,
        fntyp char(3) DEFAULT NULL,
        PRIMARY KEY (fncod)
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8;

    CREATE TABLE
    funciones_roles (
        rolescod varchar(128) NOT NULL,
        fncod varchar(255) NOT NULL,
        fnrolest char(3) DEFAULT NULL,
        fnexp datetime DEFAULT NULL,
        PRIMARY KEY (rolescod, fncod),
        KEY rol_funcion_key_idx (fncod),
        CONSTRAINT funcion_rol_key FOREIGN KEY (rolescod) REFERENCES roles (rolescod) ON DELETE NO ACTION ON UPDATE NO ACTION,
        CONSTRAINT rol_funcion_key FOREIGN KEY (fncod) REFERENCES funciones (fncod) ON DELETE NO ACTION ON UPDATE NO ACTION
    ) ENGINE = InnoDB DEFAULT CHARSET = utf8;


CREATE TABLE pasteles(  
    pastel_id int NOT NULL PRIMARY KEY AUTO_INCREMENT,
    precio INT,
    nombre VARCHAR(255),
    tipo VARCHAR(20),
    descripcion VARCHAR(255),
    url_img VARCHAR(255),
    cantidad int
) COMMENT 'Pasteles';

CREATE TABLE
    `carretilla` (
        `usercod` BIGINT(10) NOT NULL,
        `pastel_id` int(11) NOT NULL,
        `crrctd` INT(5) NOT NULL,
        `crrprc` DECIMAL(12, 2) NOT NULL,
        `crrfching` DATETIME NOT NULL,
        PRIMARY KEY (`usercod`, `pastel_id`),
        INDEX `pastel_id_idx` (`pastel_id` ASC),
        CONSTRAINT `carretilla_user_key` FOREIGN KEY (`usercod`) REFERENCES `usuario` (`usercod`) ON DELETE NO ACTION ON UPDATE NO ACTION,
        CONSTRAINT `carretilla_prd_key` FOREIGN KEY (`pastel_id`) REFERENCES `pasteles` (`pastel_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
    );

CREATE TABLE
    `carretillaanon` (
        `anoncod` varchar(128) NOT NULL,
        `pastel_id` int(18) NOT NULL,
        `crrctd` int(5) NOT NULL,
        `crrprc` decimal(12, 2) NOT NULL,
        `crrfching` datetime NOT NULL,
        PRIMARY KEY (`anoncod`, `pastel_id`),
        KEY `pastel_id_idx` (`pastel_id`),
        CONSTRAINT `carretillaanon_prd_key` FOREIGN KEY (`pastel_id`) REFERENCES `pasteles` (`pastel_id`) ON DELETE NO ACTION ON UPDATE NO ACTION
    );

CREATE TABLE pedidos (
    pedidos_id INT NOT NULL PRIMARY KEY AUTO_INCREMENT,
    usercod BIGINT(10) NOT NULL,
    pastel_id BIGINT(10) NOT NULL,
    ubicacion_id BIGINT(10) NOT NULL,
    cantidad INT,
    estado CHAR(3),
    
    CONSTRAINT pedidos_usu_key FOREIGN KEY (usercod)
        REFERENCES usuario (usercod)
        ON DELETE NO ACTION ON UPDATE NO ACTION,

    CONSTRAINT pedidos_pasteles_key FOREIGN KEY (pastel_id)
        REFERENCES pasteles (pastel_id)
        ON DELETE NO ACTION ON UPDATE NO ACTION,

    CONSTRAINT pedidos_ubicacion_key FOREIGN KEY (ubicacion_id)
        REFERENCES ubicacion (ubicacion_id)
        ON DELETE NO ACTION ON UPDATE NO ACTION
) COMMENT = 'Pedidos';

CREATE TABLE ubicacion(  
    ubicacion_id BIGINT(10) NOT NULL AUTO_INCREMENT,
    referencia VARCHAR(255),
    usercod BIGINT,
    PRIMARY KEY(ubicacion_id),
    CONSTRAINT ubi_usuario_key FOREIGN KEY (usercod) REFERENCES usuario (usercod) ON DELETE NO ACTION ON UPDATE NO ACTION
) COMMENT 'Ubicacion';

CREATE TABLE factura(  
    ID_factura BIGINT(10) NOT NULL AUTO_INCREMENT,
    orden_ID VARCHAR(255),
    comprador VARCHAR(255),
    estado VARCHAR(20),
    total_bruto DECIMAL(12, 2),
    usercod BIGINT,

    PRIMARY KEY (ID_factura),
    CONSTRAINT fk_usuario_factura FOREIGN KEY (usercod) 
        REFERENCES usuario(usercod) 
        ON DELETE NO ACTION 
        ON UPDATE NO ACTION
);