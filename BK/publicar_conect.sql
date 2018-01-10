/*
 Navicat Premium Data Transfer

 Source Server         : Produccion Publicar
 Source Server Type    : MySQL
 Source Server Version : 50635
 Source Host           : publicar.cxnqbhtfidut.us-west-2.rds.amazonaws.com
 Source Database       : publicar_conect

 Target Server Type    : MySQL
 Target Server Version : 50635
 File Encoding         : utf-8

 Date: 01/02/2018 13:40:08 PM
*/

SET NAMES utf8;
SET FOREIGN_KEY_CHECKS = 0;

-- ----------------------------
--  Table structure for `basica_agileestado`
-- ----------------------------
DROP TABLE IF EXISTS `basica_agileestado`;
CREATE TABLE `basica_agileestado` (
  `agileestado_id` int(11) NOT NULL AUTO_INCREMENT,
  `agileestado_nombre` varchar(255) NOT NULL,
  PRIMARY KEY (`agileestado_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Records of `basica_agileestado`
-- ----------------------------
BEGIN;
INSERT INTO `basica_agileestado` VALUES ('1', 'Sin enviar'), ('2', 'Envio exitoso'), ('3', 'Carga fallida');
COMMIT;

-- ----------------------------
--  Table structure for `basica_campo`
-- ----------------------------
DROP TABLE IF EXISTS `basica_campo`;
CREATE TABLE `basica_campo` (
  `campo_id` int(11) NOT NULL AUTO_INCREMENT,
  `campo_nombre` varchar(255) NOT NULL,
  `campo_titulo` varchar(255) NOT NULL,
  `campo_hidden` bit(1) NOT NULL DEFAULT b'0',
  `tabla_id` int(11) NOT NULL,
  `tipocampo_id` int(11) NOT NULL,
  `campo_funcion` varchar(255) NOT NULL,
  `campo_value` varchar(255) NOT NULL,
  `campo_ordena` int(11) NOT NULL,
  `campo_oblicatorio` bit(1) NOT NULL DEFAULT b'0',
  PRIMARY KEY (`campo_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Records of `basica_campo`
-- ----------------------------
BEGIN;
INSERT INTO `basica_campo` VALUES ('1', 'dominio_id', '', b'1', '7', '1', '', '{{getDominio}}', '1', b'1'), ('2', 'mes', '', b'0', '7', '3', 'cargarHtmlSelect', '', '2', b'1'), ('3', '', '', b'0', '7', '2', '', 'Exportar', '3', b'0');
COMMIT;

-- ----------------------------
--  Table structure for `basica_cargo`
-- ----------------------------
DROP TABLE IF EXISTS `basica_cargo`;
CREATE TABLE `basica_cargo` (
  `cargo_id` int(11) NOT NULL AUTO_INCREMENT,
  `cargo_nombre` varchar(255) NOT NULL,
  `dominio_id` int(11) NOT NULL,
  `cargo_grupo` bit(1) NOT NULL,
  PRIMARY KEY (`cargo_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Records of `basica_cargo`
-- ----------------------------
BEGIN;
INSERT INTO `basica_cargo` VALUES ('1', 'Asesor Presencial de Renovación', '1', b'0'), ('2', 'Asesor Presencial de Venta Nueva', '1', b'0'), ('3', 'Asesores Contact Center Renovación', '2', b'0'), ('4', 'Asesores Contact Center Venta Nueva', '2', b'0'), ('5', 'Key Account Manager', '1', b'0'), ('6', 'Supervisor Call de Renovación', '2', b'1'), ('7', 'Supervisor Call de Venta Nueva', '2', b'1'), ('8', 'Jefe', '1', b'1');
COMMIT;

-- ----------------------------
--  Table structure for `basica_cargomenu`
-- ----------------------------
DROP TABLE IF EXISTS `basica_cargomenu`;
CREATE TABLE `basica_cargomenu` (
  `cargomenu_id` int(11) NOT NULL AUTO_INCREMENT,
  `cargomenu_nombre` varchar(255) NOT NULL,
  `dominio_id` int(11) NOT NULL,
  PRIMARY KEY (`cargomenu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=9 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- ----------------------------
--  Records of `basica_cargomenu`
-- ----------------------------
BEGIN;
INSERT INTO `basica_cargomenu` VALUES ('1', 'Acumulacion por ventas', '1'), ('2', 'Acumulacion por operaciones', '1'), ('3', 'Acumulacion por gestion humana', '1'), ('4', 'Cumplimiento grupal', '1'), ('5', 'Acumulacion por ventas', '2'), ('6', 'Acumulacion por operaciones', '2'), ('7', 'Acumulacion por gestion humana', '2'), ('8', 'Cumplimiento grupal', '2');
COMMIT;

-- ----------------------------
--  Table structure for `basica_cargosubmenu`
-- ----------------------------
DROP TABLE IF EXISTS `basica_cargosubmenu`;
CREATE TABLE `basica_cargosubmenu` (
  `cargosubmenu_id` int(11) NOT NULL AUTO_INCREMENT,
  `cargosubmenu_nombre` varchar(255) NOT NULL,
  `cargomenu_id` int(11) NOT NULL,
  `cargosubmenu_tipo` int(11) NOT NULL,
  PRIMARY KEY (`cargosubmenu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- ----------------------------
--  Records of `basica_cargosubmenu`
-- ----------------------------
BEGIN;
INSERT INTO `basica_cargosubmenu` VALUES ('1', 'Cumplimiento Presupuesto Ventas ', '1', '1'), ('2', 'Cumplimiento Presupuesto Venta Nueva', '1', '2'), ('3', 'Cumplimiento de Meta de Citas ', '2', '3'), ('4', 'Evaluación de Conocimiento', '3', '4'), ('5', 'Cumplimiento del Grupo', '4', '5'), ('6', 'Cumplimiento Presupuesto Ventas ', '5', '1'), ('7', 'Cumplimiento Presupuesto Venta Nueva', '5', '2'), ('8', 'Cumplimiento de Meta de Llamadas', '6', '3'), ('9', 'Evaluación de Conocimiento', '7', '4'), ('10', 'Cumplimiento del Grupo', '8', '5');
COMMIT;

-- ----------------------------
--  Table structure for `basica_categoria_menu`
-- ----------------------------
DROP TABLE IF EXISTS `basica_categoria_menu`;
CREATE TABLE `basica_categoria_menu` (
  `categoria_menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria_menu_nombre` varchar(255) NOT NULL,
  `estado_id` int(11) NOT NULL,
  `menu_ordenar` int(11) NOT NULL,
  PRIMARY KEY (`categoria_menu_id`),
  KEY `estado_id` (`estado_id`),
  CONSTRAINT `basica_categoria_menu_ibfk_1` FOREIGN KEY (`estado_id`) REFERENCES `basica_estado` (`estado_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Records of `basica_categoria_menu`
-- ----------------------------
BEGIN;
INSERT INTO `basica_categoria_menu` VALUES ('1', 'Admin', '1', '1');
COMMIT;

-- ----------------------------
--  Table structure for `basica_categoria_submenu`
-- ----------------------------
DROP TABLE IF EXISTS `basica_categoria_submenu`;
CREATE TABLE `basica_categoria_submenu` (
  `categoria_submenu_id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria_submenu_nombre` varchar(255) NOT NULL,
  `estado_id` int(11) NOT NULL,
  `categoria_submenu_icono` varchar(255) NOT NULL,
  PRIMARY KEY (`categoria_submenu_id`),
  KEY `estado_id` (`estado_id`),
  CONSTRAINT `basica_categoria_submenu_ibfk_1` FOREIGN KEY (`estado_id`) REFERENCES `basica_estado` (`estado_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Records of `basica_categoria_submenu`
-- ----------------------------
BEGIN;
INSERT INTO `basica_categoria_submenu` VALUES ('1', 'Carga Masiva', '1', 'fa-tasks'), ('2', 'Carga unitaria', '1', 'fa-tasks'), ('3', 'Reportes', '1', 'fa-tasks'), ('4', 'Job', '1', 'fa-tasks');
COMMIT;

-- ----------------------------
--  Table structure for `basica_ciudad`
-- ----------------------------
DROP TABLE IF EXISTS `basica_ciudad`;
CREATE TABLE `basica_ciudad` (
  `ciudad_id` int(11) NOT NULL AUTO_INCREMENT,
  `ciudad_nombre` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `regional_id` int(11) DEFAULT NULL,
  `departamento_id` int(11) DEFAULT NULL,
  `ciudad_borrado` bit(1) DEFAULT b'0',
  `estado_id` int(11) NOT NULL,
  PRIMARY KEY (`ciudad_id`),
  KEY `pk_departamento` (`departamento_id`),
  CONSTRAINT `basica_ciudad_ibfk_1` FOREIGN KEY (`departamento_id`) REFERENCES `basica_departamento` (`departamento_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1116 DEFAULT CHARSET=utf8 COLLATE=utf8_bin ROW_FORMAT=COMPACT;

-- ----------------------------
--  Records of `basica_ciudad`
-- ----------------------------
BEGIN;
INSERT INTO `basica_ciudad` VALUES ('1', 'EL ENCANTO', null, '1', b'0', '1'), ('2', 'LA CHORRERA', null, '1', b'0', '1'), ('3', 'LA PEDRERA', null, '1', b'0', '1'), ('4', 'LA VICTORIA', null, '1', b'0', '1'), ('5', 'LETICIA', null, '1', b'0', '1'), ('6', 'MIRITI', null, '1', b'0', '1'), ('7', 'PUERTO ALEGRIA', null, '1', b'0', '1'), ('8', 'PUERTO ARICA', null, '1', b'0', '1'), ('9', 'PUERTO NARIÑO', null, '1', b'0', '1'), ('10', 'PUERTO SANTANDER', null, '1', b'0', '1'), ('11', 'TURAPACA', null, '1', b'0', '1'), ('12', 'ABEJORRAL', null, '2', b'0', '1'), ('13', 'ABRIAQUI', null, '2', b'0', '1'), ('14', 'ALEJANDRIA', null, '2', b'0', '1'), ('15', 'AMAGA', null, '2', b'0', '1'), ('16', 'AMALFI', null, '2', b'0', '1'), ('17', 'ANDES', null, '2', b'0', '1'), ('18', 'ANGELOPOLIS', null, '2', b'0', '1'), ('19', 'ANGOSTURA', null, '2', b'0', '1'), ('20', 'ANORI', null, '2', b'0', '1'), ('21', 'ANTIOQUIA', null, '2', b'0', '1'), ('22', 'ANZA', null, '2', b'0', '1'), ('23', 'APARTADO', null, '2', b'0', '1'), ('24', 'ARBOLETES', null, '2', b'0', '1'), ('25', 'ARGELIA', null, '2', b'0', '1'), ('26', 'ARMENIA', null, '2', b'0', '1'), ('27', 'BARBOSA', null, '2', b'0', '1'), ('28', 'BELLO', null, '2', b'0', '1'), ('29', 'BELMIRA', null, '2', b'0', '1'), ('30', 'BETANIA', null, '2', b'0', '1'), ('31', 'BETULIA', null, '2', b'0', '1'), ('32', 'BOLIVAR', null, '2', b'0', '1'), ('33', 'BRICEÑO', null, '2', b'0', '1'), ('34', 'BURITICA', null, '2', b'0', '1'), ('35', 'CACERES', null, '2', b'0', '1'), ('36', 'CAICEDO', null, '2', b'0', '1'), ('37', 'CALDAS', null, '2', b'0', '1'), ('38', 'CAMPAMENTO', null, '2', b'0', '1'), ('39', 'CANASGORDAS', null, '2', b'0', '1'), ('40', 'CARACOLI', null, '2', b'0', '1'), ('41', 'CARAMANTA', null, '2', b'0', '1'), ('42', 'CAREPA', null, '2', b'0', '1'), ('43', 'CARMEN DE VIBORAL', null, '2', b'0', '1'), ('44', 'CAROLINA DEL PRINCIPE', null, '2', b'0', '1'), ('45', 'CAUCASIA', null, '2', b'0', '1'), ('46', 'CHIGORODO', null, '2', b'0', '1'), ('47', 'CISNEROS', null, '2', b'0', '1'), ('48', 'COCORNA', null, '2', b'0', '1'), ('49', 'CONCEPCION', null, '2', b'0', '1'), ('50', 'CONCORDIA', null, '2', b'0', '1'), ('51', 'COPACABANA', null, '2', b'0', '1'), ('52', 'DABEIBA', null, '2', b'0', '1'), ('53', 'DONMATIAS', null, '2', b'0', '1'), ('54', 'EBEJICO', null, '2', b'0', '1'), ('55', 'EL BAGRE', null, '2', b'0', '1'), ('56', 'EL PENOL', null, '2', b'0', '1'), ('57', 'EL RETIRO', null, '2', b'0', '1'), ('58', 'ENTRERRIOS', null, '2', b'0', '1'), ('59', 'ENVIGADO', null, '2', b'0', '1'), ('60', 'FREDONIA', null, '2', b'0', '1'), ('61', 'FRONTINO', null, '2', b'0', '1'), ('62', 'GIRALDO', null, '2', b'0', '1'), ('63', 'GIRARDOTA', null, '2', b'0', '1'), ('64', 'GOMEZ PLATA', null, '2', b'0', '1'), ('65', 'GRANADA', null, '2', b'0', '1'), ('66', 'GUADALUPE', null, '2', b'0', '1'), ('67', 'GUARNE', null, '2', b'0', '1'), ('68', 'GUATAQUE', null, '2', b'0', '1'), ('69', 'HELICONIA', null, '2', b'0', '1'), ('70', 'HISPANIA', null, '2', b'0', '1'), ('71', 'ITAGUI', null, '2', b'0', '1'), ('72', 'ITUANGO', null, '2', b'0', '1'), ('73', 'JARDIN', null, '2', b'0', '1'), ('74', 'JERICO', null, '2', b'0', '1'), ('75', 'LA CEJA', null, '2', b'0', '1'), ('76', 'LA ESTRELLA', null, '2', b'0', '1'), ('77', 'LA PINTADA', null, '2', b'0', '1'), ('78', 'LA UNION', null, '2', b'0', '1'), ('79', 'LIBORINA', null, '2', b'0', '1'), ('80', 'MACEO', null, '2', b'0', '1'), ('81', 'MARINILLA', null, '2', b'0', '1'), ('82', 'MEDELLIN', null, '2', b'0', '1'), ('83', 'MONTEBELLO', null, '2', b'0', '1'), ('84', 'MURINDO', null, '2', b'0', '1'), ('85', 'MUTATA', null, '2', b'0', '1'), ('86', 'NARINO', null, '2', b'0', '1'), ('87', 'NECHI', null, '2', b'0', '1'), ('88', 'NECOCLI', null, '2', b'0', '1'), ('89', 'OLAYA', null, '2', b'0', '1'), ('90', 'PEQUE', null, '2', b'0', '1'), ('91', 'PUEBLORRICO', null, '2', b'0', '1'), ('92', 'PUERTO BERRIO', null, '2', b'0', '1'), ('93', 'PUERTO NARE', null, '2', b'0', '1'), ('94', 'PUERTO TRIUNFO', null, '2', b'0', '1'), ('95', 'REMEDIOS', null, '2', b'0', '1'), ('96', 'RIONEGRO', null, '2', b'0', '1'), ('97', 'SABANALARGA', null, '2', b'0', '1'), ('98', 'SABANETA', null, '2', b'0', '1'), ('99', 'SALGAR', null, '2', b'0', '1'), ('100', 'SAN ANDRES DE CUERQUIA', null, '2', b'0', '1'), ('101', 'SAN CARLOS', null, '2', b'0', '1'), ('102', 'SAN FRANCISCO', null, '2', b'0', '1'), ('103', 'SAN JERONIMO', null, '2', b'0', '1'), ('104', 'SAN JOSE DE LA MONTAÑA', null, '2', b'0', '1'), ('105', 'SAN JUAN DE URABA', null, '2', b'0', '1'), ('106', 'SAN LUIS', null, '2', b'0', '1'), ('107', 'SAN PEDRO DE LOS MILAGROS', null, '2', b'0', '1'), ('108', 'SAN PEDRO DE URABA', null, '2', b'0', '1'), ('109', 'SAN RAFAEL', null, '2', b'0', '1'), ('110', 'SAN ROQUE', null, '2', b'0', '1'), ('111', 'SAN VICENTE', null, '2', b'0', '1'), ('112', 'SANTA BARBARA', null, '2', b'0', '1'), ('113', 'SANTA ROSA DE OSOS', null, '2', b'0', '1'), ('114', 'SANTO DOMINGO', null, '2', b'0', '1'), ('115', 'SANTUARIO', null, '2', b'0', '1'), ('116', 'SEGOVIA', null, '2', b'0', '1'), ('117', 'SONSON', null, '2', b'0', '1'), ('118', 'SOPETRAN', null, '2', b'0', '1'), ('119', 'TAMESIS', null, '2', b'0', '1'), ('120', 'TARAZA', null, '2', b'0', '1'), ('121', 'TARSO', null, '2', b'0', '1'), ('122', 'TITIRIBI', null, '2', b'0', '1'), ('123', 'TOLEDO', null, '2', b'0', '1'), ('124', 'TURBO', null, '2', b'0', '1'), ('125', 'URAMITA', null, '2', b'0', '1'), ('126', 'URRAO', null, '2', b'0', '1'), ('127', 'VALDIVIA', null, '2', b'0', '1'), ('128', 'VALPARAISO', null, '2', b'0', '1'), ('129', 'VEGACHI', null, '2', b'0', '1'), ('130', 'VENECIA', null, '2', b'0', '1'), ('131', 'VIGIA DEL FUERTE', null, '2', b'0', '1'), ('132', 'YALI', null, '2', b'0', '1'), ('133', 'YARUMAL', null, '2', b'0', '1'), ('134', 'YOLOMBO', null, '2', b'0', '1'), ('135', 'YONDO', null, '2', b'0', '1'), ('136', 'ZARAGOZA', null, '2', b'0', '1'), ('137', 'ARAUCA', null, '3', b'0', '1'), ('138', 'ARAUQUITA', null, '3', b'0', '1'), ('139', 'CRAVO NORTE', null, '3', b'0', '1'), ('140', 'FORTUL', null, '3', b'0', '1'), ('141', 'PUERTO RONDON', null, '3', b'0', '1'), ('142', 'SARAVENA', null, '3', b'0', '1'), ('143', 'TAME', null, '3', b'0', '1'), ('144', 'BARANOA', null, '4', b'0', '1'), ('145', 'BARRANQUILLA', null, '4', b'0', '1'), ('146', 'CAMPO DE LA CRUZ', null, '4', b'0', '1'), ('147', 'CANDELARIA', null, '4', b'0', '1'), ('148', 'GALAPA', null, '4', b'0', '1'), ('149', 'JUAN DE ACOSTA', null, '4', b'0', '1'), ('150', 'LURUACO', null, '4', b'0', '1'), ('151', 'MALAMBO', null, '4', b'0', '1'), ('152', 'MANATI', null, '4', b'0', '1'), ('153', 'PALMAR DE VARELA', null, '4', b'0', '1'), ('154', 'PIOJO', null, '4', b'0', '1'), ('155', 'POLO NUEVO', null, '4', b'0', '1'), ('156', 'PONEDERA', null, '4', b'0', '1'), ('157', 'PUERTO COLOMBIA', null, '4', b'0', '1'), ('158', 'REPELON', null, '4', b'0', '1'), ('159', 'SABANAGRANDE', null, '4', b'0', '1'), ('160', 'SABANALARGA', null, '4', b'0', '1'), ('161', 'SANTA LUCIA', null, '4', b'0', '1'), ('162', 'SANTO TOMAS', null, '4', b'0', '1'), ('163', 'SOLEDAD', null, '4', b'0', '1'), ('164', 'SUAN', null, '4', b'0', '1'), ('165', 'TUBARA', null, '4', b'0', '1'), ('166', 'USIACURI', null, '4', b'0', '1'), ('167', 'ACHI', null, '5', b'0', '1'), ('168', 'ALTOS DEL ROSARIO', null, '5', b'0', '1'), ('169', 'ARENAL', null, '5', b'0', '1'), ('170', 'ARJONA', null, '5', b'0', '1'), ('171', 'ARROYOHONDO', null, '5', b'0', '1'), ('172', 'BARRANCO DE LOBA', null, '5', b'0', '1'), ('173', 'BRAZUELO DE PAPAYAL', null, '5', b'0', '1'), ('174', 'CALAMAR', null, '5', b'0', '1'), ('175', 'CANTAGALLO', null, '5', b'0', '1'), ('176', 'Cartagena', null, '5', b'0', '1'), ('177', 'CICUCO', null, '5', b'0', '1'), ('178', 'CLEMENCIA', null, '5', b'0', '1'), ('179', 'CORDOBA', null, '5', b'0', '1'), ('180', 'EL CARMEN DE BOLIVAR', null, '5', b'0', '1'), ('181', 'EL GUAMO', null, '5', b'0', '1'), ('182', 'EL PENION', null, '5', b'0', '1'), ('183', 'HATILLO DE LOBA', null, '5', b'0', '1'), ('184', 'MAGANGUE', null, '5', b'0', '1'), ('185', 'MAHATES', null, '5', b'0', '1'), ('186', 'MARGARITA', null, '5', b'0', '1'), ('187', 'MARIA LA BAJA', null, '5', b'0', '1'), ('188', 'MONTECRISTO', null, '5', b'0', '1'), ('189', 'MORALES', null, '5', b'0', '1'), ('190', 'MORALES', null, '5', b'0', '1'), ('191', 'NOROSI', null, '5', b'0', '1'), ('192', 'PINILLOS', null, '5', b'0', '1'), ('193', 'REGIDOR', null, '5', b'0', '1'), ('194', 'RIO VIEJO', null, '5', b'0', '1'), ('195', 'SAN CRISTOBAL', null, '5', b'0', '1'), ('196', 'SAN ESTANISLAO', null, '5', b'0', '1'), ('197', 'SAN FERNANDO', null, '5', b'0', '1'), ('198', 'SAN JACINTO', null, '5', b'0', '1'), ('199', 'SAN JACINTO DEL CAUCA', null, '5', b'0', '1'), ('200', 'SAN JUAN DE NEPOMUCENO', null, '5', b'0', '1'), ('201', 'SAN MARTIN DE LOBA', null, '5', b'0', '1'), ('202', 'SAN PABLO', null, '5', b'0', '1'), ('203', 'SAN PABLO NORTE', null, '5', b'0', '1'), ('204', 'SANTA CATALINA', null, '5', b'0', '1'), ('205', 'SANTA CRUZ DE MOMPOX', null, '5', b'0', '1'), ('206', 'SANTA ROSA', null, '5', b'0', '1'), ('207', 'SANTA ROSA DEL SUR', null, '5', b'0', '1'), ('208', 'SIMITI', null, '5', b'0', '1'), ('209', 'SOPLAVIENTO', null, '5', b'0', '1'), ('210', 'TALAIGUA NUEVO', null, '5', b'0', '1'), ('211', 'TUQUISIO', null, '5', b'0', '1'), ('212', 'TURBACO', null, '5', b'0', '1'), ('213', 'TURBANA', null, '5', b'0', '1'), ('214', 'VILLANUEVA', null, '5', b'0', '1'), ('215', 'ZAMBRANO', null, '5', b'0', '1'), ('216', 'AQUITANIA', null, '6', b'0', '1'), ('217', 'ARCABUCO', null, '6', b'0', '1'), ('218', 'BELÉN', null, '6', b'0', '1'), ('219', 'BERBEO', null, '6', b'0', '1'), ('220', 'BETÉITIVA', null, '6', b'0', '1'), ('221', 'BOAVITA', null, '6', b'0', '1'), ('222', 'BOYACÁ', null, '6', b'0', '1'), ('223', 'BRICEÑO', null, '6', b'0', '1'), ('224', 'BUENAVISTA', null, '6', b'0', '1'), ('225', 'BUSBANZÁ', null, '6', b'0', '1'), ('226', 'CALDAS', null, '6', b'0', '1'), ('227', 'CAMPO HERMOSO', null, '6', b'0', '1'), ('228', 'CERINZA', null, '6', b'0', '1'), ('229', 'CHINAVITA', null, '6', b'0', '1'), ('230', 'CHIQUINQUIRÁ', null, '6', b'0', '1'), ('231', 'CHÍQUIZA', null, '6', b'0', '1'), ('232', 'CHISCAS', null, '6', b'0', '1'), ('233', 'CHITA', null, '6', b'0', '1'), ('234', 'CHITARAQUE', null, '6', b'0', '1'), ('235', 'CHIVATÁ', null, '6', b'0', '1'), ('236', 'CIÉNEGA', null, '6', b'0', '1'), ('237', 'CÓMBITA', null, '6', b'0', '1'), ('238', 'COPER', null, '6', b'0', '1'), ('239', 'CORRALES', null, '6', b'0', '1'), ('240', 'COVARACHÍA', null, '6', b'0', '1'), ('241', 'CUBARA', null, '6', b'0', '1'), ('242', 'CUCAITA', null, '6', b'0', '1'), ('243', 'CUITIVA', null, '6', b'0', '1'), ('244', 'DUITAMA', null, '6', b'0', '1'), ('245', 'EL COCUY', null, '6', b'0', '1'), ('246', 'EL ESPINO', null, '6', b'0', '1'), ('247', 'FIRAVITOBA', null, '6', b'0', '1'), ('248', 'FLORESTA', null, '6', b'0', '1'), ('249', 'GACHANTIVÁ', null, '6', b'0', '1'), ('250', 'GÁMEZA', null, '6', b'0', '1'), ('251', 'GARAGOA', null, '6', b'0', '1'), ('252', 'GUACAMAYAS', null, '6', b'0', '1'), ('253', 'GÜICÁN', null, '6', b'0', '1'), ('254', 'IZA', null, '6', b'0', '1'), ('255', 'JENESANO', null, '6', b'0', '1'), ('256', 'JERICÓ', null, '6', b'0', '1'), ('257', 'LA UVITA', null, '6', b'0', '1'), ('258', 'LA VICTORIA', null, '6', b'0', '1'), ('259', 'LABRANZA GRANDE', null, '6', b'0', '1'), ('260', 'MACANAL', null, '6', b'0', '1'), ('261', 'MARIPÍ', null, '6', b'0', '1'), ('262', 'MIRAFLORES', null, '6', b'0', '1'), ('263', 'MONGUA', null, '6', b'0', '1'), ('264', 'MONGUÍ', null, '6', b'0', '1'), ('265', 'MONIQUIRÁ', null, '6', b'0', '1'), ('266', 'MOTAVITA', null, '6', b'0', '1'), ('267', 'MUZO', null, '6', b'0', '1'), ('268', 'NOBSA', null, '6', b'0', '1'), ('269', 'NUEVO COLÓN', null, '6', b'0', '1'), ('270', 'OICATÁ', null, '6', b'0', '1'), ('271', 'OTANCHE', null, '6', b'0', '1'), ('272', 'PACHAVITA', null, '6', b'0', '1'), ('273', 'PÁEZ', null, '6', b'0', '1'), ('274', 'PAIPA', null, '6', b'0', '1'), ('275', 'PAJARITO', null, '6', b'0', '1'), ('276', 'PANQUEBA', null, '6', b'0', '1'), ('277', 'PAUNA', null, '6', b'0', '1'), ('278', 'PAYA', null, '6', b'0', '1'), ('279', 'PAZ DE RÍO', null, '6', b'0', '1'), ('280', 'PESCA', null, '6', b'0', '1'), ('281', 'PISBA', null, '6', b'0', '1'), ('282', 'PUERTO BOYACA', null, '6', b'0', '1'), ('283', 'QUÍPAMA', null, '6', b'0', '1'), ('284', 'RAMIRIQUÍ', null, '6', b'0', '1'), ('285', 'RÁQUIRA', null, '6', b'0', '1'), ('286', 'RONDÓN', null, '6', b'0', '1'), ('287', 'SABOYÁ', null, '6', b'0', '1'), ('288', 'SÁCHICA', null, '6', b'0', '1'), ('289', 'SAMACÁ', null, '6', b'0', '1'), ('290', 'SAN EDUARDO', null, '6', b'0', '1'), ('291', 'SAN JOSÉ DE PARE', null, '6', b'0', '1'), ('292', 'SAN LUÍS DE GACENO', null, '6', b'0', '1'), ('293', 'SAN MATEO', null, '6', b'0', '1'), ('294', 'SAN MIGUEL DE SEMA', null, '6', b'0', '1'), ('295', 'SAN PABLO DE BORBUR', null, '6', b'0', '1'), ('296', 'SANTA MARÍA', null, '6', b'0', '1'), ('297', 'SANTA ROSA DE VITERBO', null, '6', b'0', '1'), ('298', 'SANTA SOFÍA', null, '6', b'0', '1'), ('299', 'SANTANA', null, '6', b'0', '1'), ('300', 'SATIVANORTE', null, '6', b'0', '1'), ('301', 'SATIVASUR', null, '6', b'0', '1'), ('302', 'SIACHOQUE', null, '6', b'0', '1'), ('303', 'SOATÁ', null, '6', b'0', '1'), ('304', 'SOCHA', null, '6', b'0', '1'), ('305', 'SOCOTÁ', null, '6', b'0', '1'), ('306', 'SOGAMOSO', null, '6', b'0', '1'), ('307', 'SORA', null, '6', b'0', '1'), ('308', 'SORACÁ', null, '6', b'0', '1'), ('309', 'SOTAQUIRÁ', null, '6', b'0', '1'), ('310', 'SUSACÓN', null, '6', b'0', '1'), ('311', 'SUTARMACHÁN', null, '6', b'0', '1'), ('312', 'TASCO', null, '6', b'0', '1'), ('313', 'TIBANÁ', null, '6', b'0', '1'), ('314', 'TIBASOSA', null, '6', b'0', '1'), ('315', 'TINJACÁ', null, '6', b'0', '1'), ('316', 'TIPACOQUE', null, '6', b'0', '1'), ('317', 'TOCA', null, '6', b'0', '1'), ('318', 'TOGÜÍ', null, '6', b'0', '1'), ('319', 'TÓPAGA', null, '6', b'0', '1'), ('320', 'TOTA', null, '6', b'0', '1'), ('321', 'TUNJA', null, '6', b'0', '1'), ('322', 'TUNUNGUÁ', null, '6', b'0', '1'), ('323', 'TURMEQUÉ', null, '6', b'0', '1'), ('324', 'TUTA', null, '6', b'0', '1'), ('325', 'TUTAZÁ', null, '6', b'0', '1'), ('326', 'UMBITA', null, '6', b'0', '1'), ('327', 'VENTA QUEMADA', null, '6', b'0', '1'), ('328', 'VILLA DE LEYVA', null, '6', b'0', '1'), ('329', 'VIRACACHÁ', null, '6', b'0', '1'), ('330', 'ZETAQUIRA', null, '6', b'0', '1'), ('331', 'AGUADAS', null, '7', b'0', '1'), ('332', 'ANSERMA', null, '7', b'0', '1'), ('333', 'ARANZAZU', null, '7', b'0', '1'), ('334', 'BELALCAZAR', null, '7', b'0', '1'), ('335', 'CHINCHINÁ', null, '7', b'0', '1'), ('336', 'FILADELFIA', null, '7', b'0', '1'), ('337', 'LA DORADA', null, '7', b'0', '1'), ('338', 'LA MERCED', null, '7', b'0', '1'), ('339', 'MANIZALES', null, '7', b'0', '1'), ('340', 'MANZANARES', null, '7', b'0', '1'), ('341', 'MARMATO', null, '7', b'0', '1'), ('342', 'MARQUETALIA', null, '7', b'0', '1'), ('343', 'MARULANDA', null, '7', b'0', '1'), ('344', 'NEIRA', null, '7', b'0', '1'), ('345', 'NORCASIA', null, '7', b'0', '1'), ('346', 'PACORA', null, '7', b'0', '1'), ('347', 'PALESTINA', null, '7', b'0', '1'), ('348', 'PENSILVANIA', null, '7', b'0', '1'), ('349', 'RIOSUCIO', null, '7', b'0', '1'), ('350', 'RISARALDA', null, '7', b'0', '1'), ('351', 'SALAMINA', null, '7', b'0', '1'), ('352', 'SAMANA', null, '7', b'0', '1'), ('353', 'SAN JOSE', null, '7', b'0', '1'), ('354', 'SUPÍA', null, '7', b'0', '1'), ('355', 'VICTORIA', null, '7', b'0', '1'), ('356', 'VILLAMARÍA', null, '7', b'0', '1'), ('357', 'VITERBO', null, '7', b'0', '1'), ('358', 'ALBANIA', null, '8', b'0', '1'), ('359', 'BELÉN ANDAQUIES', null, '8', b'0', '1'), ('360', 'CARTAGENA DEL CHAIRA', null, '8', b'0', '1'), ('361', 'CURILLO', null, '8', b'0', '1'), ('362', 'EL DONCELLO', null, '8', b'0', '1'), ('363', 'EL PAUJIL', null, '8', b'0', '1'), ('364', 'FLORENCIA', null, '8', b'0', '1'), ('365', 'LA MONTAÑITA', null, '8', b'0', '1'), ('366', 'MILÁN', null, '8', b'0', '1'), ('367', 'MORELIA', null, '8', b'0', '1'), ('368', 'PUERTO RICO', null, '8', b'0', '1'), ('369', 'SAN  VICENTE DEL CAGUAN', null, '8', b'0', '1'), ('370', 'SAN JOSÉ DE FRAGUA', null, '8', b'0', '1'), ('371', 'SOLANO', null, '8', b'0', '1'), ('372', 'SOLITA', null, '8', b'0', '1'), ('373', 'VALPARAÍSO', null, '8', b'0', '1'), ('374', 'AGUAZUL', null, '9', b'0', '1'), ('375', 'CHAMEZA', null, '9', b'0', '1'), ('376', 'HATO COROZAL', null, '9', b'0', '1'), ('377', 'LA SALINA', null, '9', b'0', '1'), ('378', 'MANÍ', null, '9', b'0', '1'), ('379', 'MONTERREY', null, '9', b'0', '1'), ('380', 'NUNCHIA', null, '9', b'0', '1'), ('381', 'OROCUE', null, '9', b'0', '1'), ('382', 'PAZ DE ARIPORO', null, '9', b'0', '1'), ('383', 'PORE', null, '9', b'0', '1'), ('384', 'RECETOR', null, '9', b'0', '1'), ('385', 'SABANA LARGA', null, '9', b'0', '1'), ('386', 'SACAMA', null, '9', b'0', '1'), ('387', 'SAN LUIS DE PALENQUE', null, '9', b'0', '1'), ('388', 'TAMARA', null, '9', b'0', '1'), ('389', 'TAURAMENA', null, '9', b'0', '1'), ('390', 'TRINIDAD', null, '9', b'0', '1'), ('391', 'VILLANUEVA', null, '9', b'0', '1'), ('392', 'YOPAL', null, '9', b'0', '1'), ('393', 'ALMAGUER', null, '10', b'0', '1'), ('394', 'ARGELIA', null, '10', b'0', '1'), ('395', 'BALBOA', null, '10', b'0', '1'), ('396', 'BOLÍVAR', null, '10', b'0', '1'), ('397', 'BUENOS AIRES', null, '10', b'0', '1'), ('398', 'CAJIBIO', null, '10', b'0', '1'), ('399', 'CALDONO', null, '10', b'0', '1'), ('400', 'CALOTO', null, '10', b'0', '1'), ('401', 'CORINTO', null, '10', b'0', '1'), ('402', 'EL TAMBO', null, '10', b'0', '1'), ('403', 'FLORENCIA', null, '10', b'0', '1'), ('404', 'GUAPI', null, '10', b'0', '1'), ('405', 'INZA', null, '10', b'0', '1'), ('406', 'JAMBALÓ', null, '10', b'0', '1'), ('407', 'LA SIERRA', null, '10', b'0', '1'), ('408', 'LA VEGA', null, '10', b'0', '1'), ('409', 'LÓPEZ', null, '10', b'0', '1'), ('410', 'MERCADERES', null, '10', b'0', '1'), ('411', 'MIRANDA', null, '10', b'0', '1'), ('412', 'MORALES', null, '10', b'0', '1'), ('413', 'PADILLA', null, '10', b'0', '1'), ('414', 'PÁEZ', null, '10', b'0', '1'), ('415', 'PATIA (EL BORDO)', null, '10', b'0', '1'), ('416', 'PIAMONTE', null, '10', b'0', '1'), ('417', 'PIENDAMO', null, '10', b'0', '1'), ('418', 'POPAYÁN', null, '10', b'0', '1'), ('419', 'PUERTO TEJADA', null, '10', b'0', '1'), ('420', 'PURACE', null, '10', b'0', '1'), ('421', 'ROSAS', null, '10', b'0', '1'), ('422', 'SAN SEBASTIÁN', null, '10', b'0', '1'), ('423', 'SANTA ROSA', null, '10', b'0', '1'), ('424', 'SANTANDER DE QUILICHAO', null, '10', b'0', '1'), ('425', 'SILVIA', null, '10', b'0', '1'), ('426', 'SOTARA', null, '10', b'0', '1'), ('427', 'SUÁREZ', null, '10', b'0', '1'), ('428', 'SUCRE', null, '10', b'0', '1'), ('429', 'TIMBÍO', null, '10', b'0', '1'), ('430', 'TIMBIQUÍ', null, '10', b'0', '1'), ('431', 'TORIBIO', null, '10', b'0', '1'), ('432', 'TOTORO', null, '10', b'0', '1'), ('433', 'VILLA RICA', null, '10', b'0', '1'), ('434', 'AGUACHICA', null, '11', b'0', '1'), ('435', 'AGUSTÍN CODAZZI', null, '11', b'0', '1'), ('436', 'ASTREA', null, '11', b'0', '1'), ('437', 'BECERRIL', null, '11', b'0', '1'), ('438', 'BOSCONIA', null, '11', b'0', '1'), ('439', 'CHIMICHAGUA', null, '11', b'0', '1'), ('440', 'CHIRIGUANÁ', null, '11', b'0', '1'), ('441', 'CURUMANÍ', null, '11', b'0', '1'), ('442', 'EL COPEY', null, '11', b'0', '1'), ('443', 'EL PASO', null, '11', b'0', '1'), ('444', 'GAMARRA', null, '11', b'0', '1'), ('445', 'GONZÁLEZ', null, '11', b'0', '1'), ('446', 'LA GLORIA', null, '11', b'0', '1'), ('447', 'LA JAGUA IBIRICO', null, '11', b'0', '1'), ('448', 'MANAURE BALCÓN DEL CESAR', null, '11', b'0', '1'), ('449', 'PAILITAS', null, '11', b'0', '1'), ('450', 'PELAYA', null, '11', b'0', '1'), ('451', 'PUEBLO BELLO', null, '11', b'0', '1'), ('452', 'RÍO DE ORO', null, '11', b'0', '1'), ('453', 'ROBLES (LA PAZ)', null, '11', b'0', '1'), ('454', 'SAN ALBERTO', null, '11', b'0', '1'), ('455', 'SAN DIEGO', null, '11', b'0', '1'), ('456', 'SAN MARTÍN', null, '11', b'0', '1'), ('457', 'TAMALAMEQUE', null, '11', b'0', '1'), ('458', 'VALLEDUPAR', null, '11', b'0', '1'), ('459', 'ACANDI', null, '12', b'0', '1'), ('460', 'ALTO BAUDO (PIE DE PATO)', null, '12', b'0', '1'), ('461', 'ATRATO', null, '12', b'0', '1'), ('462', 'BAGADO', null, '12', b'0', '1'), ('463', 'BAHIA SOLANO (MUTIS)', null, '12', b'0', '1'), ('464', 'BAJO BAUDO (PIZARRO)', null, '12', b'0', '1'), ('465', 'BOJAYA (BELLAVISTA)', null, '12', b'0', '1'), ('466', 'CANTON DE SAN PABLO', null, '12', b'0', '1'), ('467', 'CARMEN DEL DARIEN', null, '12', b'0', '1'), ('468', 'CERTEGUI', null, '12', b'0', '1'), ('469', 'CONDOTO', null, '12', b'0', '1'), ('470', 'EL CARMEN', null, '12', b'0', '1'), ('471', 'ISTMINA', null, '12', b'0', '1'), ('472', 'JURADO', null, '12', b'0', '1'), ('473', 'LITORAL DEL SAN JUAN', null, '12', b'0', '1'), ('474', 'LLORO', null, '12', b'0', '1'), ('475', 'MEDIO ATRATO', null, '12', b'0', '1'), ('476', 'MEDIO BAUDO (BOCA DE PEPE)', null, '12', b'0', '1'), ('477', 'MEDIO SAN JUAN', null, '12', b'0', '1'), ('478', 'NOVITA', null, '12', b'0', '1'), ('479', 'NUQUI', null, '12', b'0', '1'), ('480', 'QUIBDO', null, '12', b'0', '1'), ('481', 'RIO IRO', null, '12', b'0', '1'), ('482', 'RIO QUITO', null, '12', b'0', '1'), ('483', 'RIOSUCIO', null, '12', b'0', '1'), ('484', 'SAN JOSE DEL PALMAR', null, '12', b'0', '1'), ('485', 'SIPI', null, '12', b'0', '1'), ('486', 'TADO', null, '12', b'0', '1'), ('487', 'UNGUIA', null, '12', b'0', '1'), ('488', 'UNIÓN PANAMERICANA', null, '12', b'0', '1'), ('489', 'AYAPEL', null, '13', b'0', '1'), ('490', 'BUENAVISTA', null, '13', b'0', '1'), ('491', 'CANALETE', null, '13', b'0', '1'), ('492', 'CERETÉ', null, '13', b'0', '1'), ('493', 'CHIMA', null, '13', b'0', '1'), ('494', 'CHINÚ', null, '13', b'0', '1'), ('495', 'CIENAGA DE ORO', null, '13', b'0', '1'), ('496', 'COTORRA', null, '13', b'0', '1'), ('497', 'LA APARTADA', null, '13', b'0', '1'), ('498', 'LORICA', null, '13', b'0', '1'), ('499', 'LOS CÓRDOBAS', null, '13', b'0', '1'), ('500', 'MOMIL', null, '13', b'0', '1'), ('501', 'MONTELÍBANO', null, '13', b'0', '1'), ('502', 'MONTERÍA', null, '13', b'0', '1'), ('503', 'MOÑITOS', null, '13', b'0', '1'), ('504', 'PLANETA RICA', null, '13', b'0', '1'), ('505', 'PUEBLO NUEVO', null, '13', b'0', '1'), ('506', 'PUERTO ESCONDIDO', null, '13', b'0', '1'), ('507', 'PUERTO LIBERTADOR', null, '13', b'0', '1'), ('508', 'PURÍSIMA', null, '13', b'0', '1'), ('509', 'SAHAGÚN', null, '13', b'0', '1'), ('510', 'SAN ANDRÉS SOTAVENTO', null, '13', b'0', '1'), ('511', 'SAN ANTERO', null, '13', b'0', '1'), ('512', 'SAN BERNARDO VIENTO', null, '13', b'0', '1'), ('513', 'SAN CARLOS', null, '13', b'0', '1'), ('514', 'SAN PELAYO', null, '13', b'0', '1'), ('515', 'TIERRALTA', null, '13', b'0', '1'), ('516', 'VALENCIA', null, '13', b'0', '1'), ('517', 'AGUA DE DIOS', null, '14', b'0', '1'), ('518', 'ALBAN', null, '14', b'0', '1'), ('519', 'ANAPOIMA', null, '14', b'0', '1'), ('520', 'ANOLAIMA', null, '14', b'0', '1'), ('521', 'ARBELAEZ', null, '14', b'0', '1'), ('522', 'BELTRÁN', null, '14', b'0', '1'), ('523', 'BITUIMA', null, '14', b'0', '1'), ('525', 'BOJACÁ', null, '14', b'0', '1'), ('526', 'CABRERA', null, '14', b'0', '1'), ('527', 'CACHIPAY', null, '14', b'0', '1'), ('528', 'CAJICÁ', null, '14', b'0', '1'), ('529', 'CAPARRAPÍ', null, '14', b'0', '1'), ('530', 'CAQUEZA', null, '14', b'0', '1'), ('531', 'CARMEN DE CARUPA', null, '14', b'0', '1'), ('532', 'CHAGUANÍ', null, '14', b'0', '1'), ('533', 'CHIA', null, '14', b'0', '1'), ('534', 'CHIPAQUE', null, '14', b'0', '1'), ('535', 'CHOACHÍ', null, '14', b'0', '1'), ('536', 'CHOCONTÁ', null, '14', b'0', '1'), ('537', 'COGUA', null, '14', b'0', '1'), ('538', 'COTA', null, '14', b'0', '1'), ('539', 'CUCUNUBÁ', null, '14', b'0', '1'), ('540', 'EL COLEGIO', null, '14', b'0', '1'), ('541', 'EL PEÑÓN', null, '14', b'0', '1'), ('542', 'EL ROSAL1', null, '14', b'0', '1'), ('543', 'FACATATIVA', null, '14', b'0', '1'), ('544', 'FÓMEQUE', null, '14', b'0', '1'), ('545', 'FOSCA', null, '14', b'0', '1'), ('546', 'FUNZA', null, '14', b'0', '1'), ('547', 'FÚQUENE', null, '14', b'0', '1'), ('548', 'FUSAGASUGA', null, '14', b'0', '1'), ('549', 'GACHALÁ', null, '14', b'0', '1'), ('550', 'GACHANCIPÁ', null, '14', b'0', '1'), ('551', 'GACHETA', null, '14', b'0', '1'), ('552', 'GAMA', null, '14', b'0', '1'), ('553', 'GIRARDOT', null, '14', b'0', '1'), ('554', 'GRANADA2', null, '14', b'0', '1'), ('555', 'GUACHETÁ', null, '14', b'0', '1'), ('556', 'GUADUAS', null, '14', b'0', '1'), ('557', 'GUASCA', null, '14', b'0', '1'), ('558', 'GUATAQUÍ', null, '14', b'0', '1'), ('559', 'GUATAVITA', null, '14', b'0', '1'), ('560', 'GUAYABAL DE SIQUIMA', null, '14', b'0', '1'), ('561', 'GUAYABETAL', null, '14', b'0', '1'), ('562', 'GUTIÉRREZ', null, '14', b'0', '1'), ('563', 'JERUSALÉN', null, '14', b'0', '1'), ('564', 'JUNÍN', null, '14', b'0', '1'), ('565', 'LA CALERA', null, '14', b'0', '1'), ('566', 'LA MESA', null, '14', b'0', '1'), ('567', 'LA PALMA', null, '14', b'0', '1'), ('568', 'LA PEÑA', null, '14', b'0', '1'), ('569', 'LA VEGA', null, '14', b'0', '1'), ('570', 'LENGUAZAQUE', null, '14', b'0', '1'), ('571', 'MACHETÁ', null, '14', b'0', '1'), ('572', 'MADRID', null, '14', b'0', '1'), ('573', 'MANTA', null, '14', b'0', '1'), ('574', 'MEDINA', null, '14', b'0', '1'), ('575', 'MOSQUERA', null, '14', b'0', '1'), ('576', 'NARIÑO', null, '14', b'0', '1'), ('577', 'NEMOCÓN', null, '14', b'0', '1'), ('578', 'NILO', null, '14', b'0', '1'), ('579', 'NIMAIMA', null, '14', b'0', '1'), ('580', 'NOCAIMA', null, '14', b'0', '1'), ('581', 'OSPINA PÉREZ', null, '14', b'0', '1'), ('582', 'PACHO', null, '14', b'0', '1'), ('583', 'PAIME', null, '14', b'0', '1'), ('584', 'PANDI', null, '14', b'0', '1'), ('585', 'PARATEBUENO', null, '14', b'0', '1'), ('586', 'PASCA', null, '14', b'0', '1'), ('587', 'PUERTO SALGAR', null, '14', b'0', '1'), ('588', 'PULÍ', null, '14', b'0', '1'), ('589', 'QUEBRADANEGRA', null, '14', b'0', '1'), ('590', 'QUETAME', null, '14', b'0', '1'), ('591', 'QUIPILE', null, '14', b'0', '1'), ('592', 'RAFAEL REYES', null, '14', b'0', '1'), ('593', 'RICAURTE', null, '14', b'0', '1'), ('594', 'SAN  ANTONIO DEL  TEQUENDAMA', null, '14', b'0', '1'), ('595', 'SAN BERNARDO', null, '14', b'0', '1'), ('596', 'SAN CAYETANO', null, '14', b'0', '1'), ('597', 'SAN FRANCISCO', null, '14', b'0', '1'), ('598', 'SAN JUAN DE RIOSECO', null, '14', b'0', '1'), ('599', 'SASAIMA', null, '14', b'0', '1'), ('600', 'SESQUILÉ', null, '14', b'0', '1'), ('601', 'SIBATÉ', null, '14', b'0', '1'), ('602', 'SILVANIA', null, '14', b'0', '1'), ('603', 'SIMIJACA', null, '14', b'0', '1'), ('604', 'SOACHA', null, '14', b'0', '1'), ('605', 'SOPO', null, '14', b'0', '1'), ('606', 'SUBACHOQUE', null, '14', b'0', '1'), ('607', 'SUESCA', null, '14', b'0', '1'), ('608', 'SUPATÁ', null, '14', b'0', '1'), ('609', 'SUSA', null, '14', b'0', '1'), ('610', 'SUTATAUSA', null, '14', b'0', '1'), ('611', 'TABIO', null, '14', b'0', '1'), ('612', 'TAUSA', null, '14', b'0', '1'), ('613', 'TENA', null, '14', b'0', '1'), ('614', 'TENJO', null, '14', b'0', '1'), ('615', 'TIBACUY', null, '14', b'0', '1'), ('616', 'TIBIRITA', null, '14', b'0', '1'), ('617', 'TOCAIMA', null, '14', b'0', '1'), ('618', 'TOCANCIPÁ', null, '14', b'0', '1'), ('619', 'TOPAIPÍ', null, '14', b'0', '1'), ('620', 'UBALÁ', null, '14', b'0', '1'), ('621', 'UBAQUE', null, '14', b'0', '1'), ('622', 'UBATÉ', null, '14', b'0', '1'), ('623', 'UNE', null, '14', b'0', '1'), ('624', 'UTICA', null, '14', b'0', '1'), ('625', 'VERGARA', null, '14', b'0', '1'), ('626', 'VIANI', null, '14', b'0', '1'), ('627', 'VILLA GOMEZ', null, '14', b'0', '1'), ('628', 'VILLA PINZÓN', null, '14', b'0', '1'), ('629', 'VILLETA', null, '14', b'0', '1'), ('630', 'VIOTA', null, '14', b'0', '1'), ('631', 'YACOPÍ', null, '14', b'0', '1'), ('632', 'ZIPACÓN', null, '14', b'0', '1'), ('633', 'ZIPAQUIRÁ', null, '14', b'0', '1'), ('634', 'BARRANCO MINAS', null, '15', b'0', '1'), ('635', 'CACAHUAL', null, '15', b'0', '1'), ('636', 'INÍRIDA', null, '15', b'0', '1'), ('637', 'LA GUADALUPE', null, '15', b'0', '1'), ('638', 'MAPIRIPANA', null, '15', b'0', '1'), ('639', 'MORICHAL', null, '15', b'0', '1'), ('640', 'PANA PANA', null, '15', b'0', '1'), ('641', 'PUERTO COLOMBIA', null, '15', b'0', '1'), ('642', 'SAN FELIPE', null, '15', b'0', '1'), ('643', 'CALAMAR', null, '16', b'0', '1'), ('644', 'EL RETORNO', null, '16', b'0', '1'), ('645', 'MIRAFLOREZ', null, '16', b'0', '1'), ('646', 'SAN JOSÉ DEL GUAVIARE', null, '16', b'0', '1'), ('647', 'ACEVEDO', null, '17', b'0', '1'), ('648', 'AGRADO', null, '17', b'0', '1'), ('649', 'AIPE', null, '17', b'0', '1'), ('650', 'ALGECIRAS', null, '17', b'0', '1'), ('651', 'ALTAMIRA', null, '17', b'0', '1'), ('652', 'BARAYA', null, '17', b'0', '1'), ('653', 'CAMPO ALEGRE', null, '17', b'0', '1'), ('654', 'COLOMBIA', null, '17', b'0', '1'), ('655', 'ELIAS', null, '17', b'0', '1'), ('656', 'GARZÓN', null, '17', b'0', '1'), ('657', 'GIGANTE', null, '17', b'0', '1'), ('658', 'GUADALUPE', null, '17', b'0', '1'), ('659', 'HOBO', null, '17', b'0', '1'), ('660', 'IQUIRA', null, '17', b'0', '1'), ('661', 'ISNOS', null, '17', b'0', '1'), ('662', 'LA ARGENTINA', null, '17', b'0', '1'), ('663', 'LA PLATA', null, '17', b'0', '1'), ('664', 'NATAGA', null, '17', b'0', '1'), ('665', 'NEIVA', null, '17', b'0', '1'), ('666', 'OPORAPA', null, '17', b'0', '1'), ('667', 'PAICOL', null, '17', b'0', '1'), ('668', 'PALERMO', null, '17', b'0', '1'), ('669', 'PALESTINA', null, '17', b'0', '1'), ('670', 'PITAL', null, '17', b'0', '1'), ('671', 'PITALITO', null, '17', b'0', '1'), ('672', 'RIVERA', null, '17', b'0', '1'), ('673', 'SALADO BLANCO', null, '17', b'0', '1'), ('674', 'SAN AGUSTÍN', null, '17', b'0', '1'), ('675', 'SANTA MARIA', null, '17', b'0', '1'), ('676', 'SUAZA', null, '17', b'0', '1'), ('677', 'TARQUI', null, '17', b'0', '1'), ('678', 'TELLO', null, '17', b'0', '1'), ('679', 'TERUEL', null, '17', b'0', '1'), ('680', 'TESALIA', null, '17', b'0', '1'), ('681', 'TIMANA', null, '17', b'0', '1'), ('682', 'VILLAVIEJA', null, '17', b'0', '1'), ('683', 'YAGUARA', null, '17', b'0', '1'), ('684', 'ALBANIA', null, '18', b'0', '1'), ('685', 'BARRANCAS', null, '18', b'0', '1'), ('686', 'DIBULLA', null, '18', b'0', '1'), ('687', 'DISTRACCIÓN', null, '18', b'0', '1'), ('688', 'EL MOLINO', null, '18', b'0', '1'), ('689', 'FONSECA', null, '18', b'0', '1'), ('690', 'HATO NUEVO', null, '18', b'0', '1'), ('691', 'LA JAGUA DEL PILAR', null, '18', b'0', '1'), ('692', 'MAICAO', null, '18', b'0', '1'), ('693', 'MANAURE', null, '18', b'0', '1'), ('694', 'RIOHACHA', null, '18', b'0', '1'), ('695', 'SAN JUAN DEL CESAR', null, '18', b'0', '1'), ('696', 'URIBIA', null, '18', b'0', '1'), ('697', 'URUMITA', null, '18', b'0', '1'), ('698', 'VILLANUEVA', null, '18', b'0', '1'), ('699', 'ALGARROBO', null, '19', b'0', '1'), ('700', 'ARACATACA', null, '19', b'0', '1'), ('701', 'ARIGUANI', null, '19', b'0', '1'), ('702', 'CERRO SAN ANTONIO', null, '19', b'0', '1'), ('703', 'CHIVOLO', null, '19', b'0', '1'), ('704', 'CIENAGA', null, '19', b'0', '1'), ('705', 'CONCORDIA', null, '19', b'0', '1'), ('706', 'EL BANCO', null, '19', b'0', '1'), ('707', 'EL PIÑON', null, '19', b'0', '1'), ('708', 'EL RETEN', null, '19', b'0', '1'), ('709', 'FUNDACION', null, '19', b'0', '1'), ('710', 'GUAMAL', null, '19', b'0', '1'), ('711', 'NUEVA GRANADA', null, '19', b'0', '1'), ('712', 'PEDRAZA', null, '19', b'0', '1'), ('713', 'PIJIÑO DEL CARMEN', null, '19', b'0', '1'), ('714', 'PIVIJAY', null, '19', b'0', '1'), ('715', 'PLATO', null, '19', b'0', '1'), ('716', 'PUEBLO VIEJO', null, '19', b'0', '1'), ('717', 'REMOLINO', null, '19', b'0', '1'), ('718', 'SABANAS DE SAN ANGEL', null, '19', b'0', '1'), ('719', 'SALAMINA', null, '19', b'0', '1'), ('720', 'SAN SEBASTIAN DE BUENAVISTA', null, '19', b'0', '1'), ('721', 'SAN ZENON', null, '19', b'0', '1'), ('722', 'SANTA ANA', null, '19', b'0', '1'), ('723', 'SANTA BARBARA DE PINTO', null, '19', b'0', '1'), ('724', 'SANTA MARTA', null, '19', b'0', '1'), ('725', 'SITIONUEVO', null, '19', b'0', '1'), ('726', 'TENERIFE', null, '19', b'0', '1'), ('727', 'ZAPAYAN', null, '19', b'0', '1'), ('728', 'ZONA BANANERA', null, '19', b'0', '1'), ('729', 'ACACIAS', null, '20', b'0', '1'), ('730', 'BARRANCA DE UPIA', null, '20', b'0', '1'), ('731', 'CABUYARO', null, '20', b'0', '1'), ('732', 'CASTILLA LA NUEVA', null, '20', b'0', '1'), ('733', 'CUBARRAL', null, '20', b'0', '1'), ('734', 'CUMARAL', null, '20', b'0', '1'), ('735', 'EL CALVARIO', null, '20', b'0', '1'), ('736', 'EL CASTILLO', null, '20', b'0', '1'), ('737', 'EL DORADO', null, '20', b'0', '1'), ('738', 'FUENTE DE ORO', null, '20', b'0', '1'), ('739', 'GRANADA', null, '20', b'0', '1'), ('740', 'GUAMAL', null, '20', b'0', '1'), ('741', 'LA MACARENA', null, '20', b'0', '1'), ('742', 'LA URIBE', null, '20', b'0', '1'), ('743', 'LEJANÍAS', null, '20', b'0', '1'), ('744', 'MAPIRIPÁN', null, '20', b'0', '1'), ('745', 'MESETAS', null, '20', b'0', '1'), ('746', 'PUERTO CONCORDIA', null, '20', b'0', '1'), ('747', 'PUERTO GAITÁN', null, '20', b'0', '1'), ('748', 'PUERTO LLERAS', null, '20', b'0', '1'), ('749', 'PUERTO LÓPEZ', null, '20', b'0', '1'), ('750', 'PUERTO RICO', null, '20', b'0', '1'), ('751', 'RESTREPO', null, '20', b'0', '1'), ('752', 'SAN  JUAN DE ARAMA', null, '20', b'0', '1'), ('753', 'SAN CARLOS GUAROA', null, '20', b'0', '1'), ('754', 'SAN JUANITO', null, '20', b'0', '1'), ('755', 'SAN MARTÍN', null, '20', b'0', '1'), ('756', 'VILLAVICENCIO', null, '20', b'0', '1'), ('757', 'VISTA HERMOSA', null, '20', b'0', '1'), ('758', 'ALBAN', null, '21', b'0', '1'), ('759', 'ALDAÑA', null, '21', b'0', '1'), ('760', 'ANCUYA', null, '21', b'0', '1'), ('761', 'ARBOLEDA', null, '21', b'0', '1'), ('762', 'BARBACOAS', null, '21', b'0', '1'), ('763', 'BELEN', null, '21', b'0', '1'), ('764', 'BUESACO', null, '21', b'0', '1'), ('765', 'CHACHAGUI', null, '21', b'0', '1'), ('766', 'COLON (GENOVA)', null, '21', b'0', '1'), ('767', 'CONSACA', null, '21', b'0', '1'), ('768', 'CONTADERO', null, '21', b'0', '1'), ('769', 'CORDOBA', null, '21', b'0', '1'), ('770', 'CUASPUD', null, '21', b'0', '1'), ('771', 'CUMBAL', null, '21', b'0', '1'), ('772', 'CUMBITARA', null, '21', b'0', '1'), ('773', 'EL CHARCO', null, '21', b'0', '1'), ('774', 'EL PEÑOL', null, '21', b'0', '1'), ('775', 'EL ROSARIO', null, '21', b'0', '1'), ('776', 'EL TABLÓN', null, '21', b'0', '1'), ('777', 'EL TAMBO', null, '21', b'0', '1'), ('778', 'FUNES', null, '21', b'0', '1'), ('779', 'GUACHUCAL', null, '21', b'0', '1'), ('780', 'GUAITARILLA', null, '21', b'0', '1'), ('781', 'GUALMATAN', null, '21', b'0', '1'), ('782', 'ILES', null, '21', b'0', '1'), ('783', 'IMUES', null, '21', b'0', '1'), ('784', 'IPIALES', null, '21', b'0', '1'), ('785', 'LA CRUZ', null, '21', b'0', '1'), ('786', 'LA FLORIDA', null, '21', b'0', '1'), ('787', 'LA LLANADA', null, '21', b'0', '1'), ('788', 'LA TOLA', null, '21', b'0', '1'), ('789', 'LA UNION', null, '21', b'0', '1'), ('790', 'LEIVA', null, '21', b'0', '1'), ('791', 'LINARES', null, '21', b'0', '1'), ('792', 'LOS ANDES', null, '21', b'0', '1'), ('793', 'MAGUI', null, '21', b'0', '1'), ('794', 'MALLAMA', null, '21', b'0', '1'), ('795', 'MOSQUEZA', null, '21', b'0', '1'), ('796', 'NARIÑO', null, '21', b'0', '1'), ('797', 'OLAYA HERRERA', null, '21', b'0', '1'), ('798', 'OSPINA', null, '21', b'0', '1'), ('799', 'PASTO', null, '21', b'0', '1'), ('800', 'PIZARRO', null, '21', b'0', '1'), ('801', 'POLICARPA', null, '21', b'0', '1'), ('802', 'POTOSI', null, '21', b'0', '1'), ('803', 'PROVIDENCIA', null, '21', b'0', '1'), ('804', 'PUERRES', null, '21', b'0', '1'), ('805', 'PUPIALES', null, '21', b'0', '1'), ('806', 'RICAURTE', null, '21', b'0', '1'), ('807', 'ROBERTO PAYAN', null, '21', b'0', '1'), ('808', 'SAMANIEGO', null, '21', b'0', '1'), ('809', 'SAN BERNARDO', null, '21', b'0', '1'), ('810', 'SAN LORENZO', null, '21', b'0', '1'), ('811', 'SAN PABLO', null, '21', b'0', '1'), ('812', 'SAN PEDRO DE CARTAGO', null, '21', b'0', '1'), ('813', 'SANDONA', null, '21', b'0', '1'), ('814', 'SANTA BARBARA', null, '21', b'0', '1'), ('815', 'SANTACRUZ', null, '21', b'0', '1'), ('816', 'SAPUYES', null, '21', b'0', '1'), ('817', 'TAMINANGO', null, '21', b'0', '1'), ('818', 'TANGUA', null, '21', b'0', '1'), ('819', 'TUMACO', null, '21', b'0', '1'), ('820', 'TUQUERRES', null, '21', b'0', '1'), ('821', 'YACUANQUER', null, '21', b'0', '1'), ('822', 'ABREGO', null, '22', b'0', '1'), ('823', 'ARBOLEDAS', null, '22', b'0', '1'), ('824', 'BOCHALEMA', null, '22', b'0', '1'), ('825', 'BUCARASICA', null, '22', b'0', '1'), ('826', 'CÁCHIRA', null, '22', b'0', '1'), ('827', 'CÁCOTA', null, '22', b'0', '1'), ('828', 'CHINÁCOTA', null, '22', b'0', '1'), ('829', 'CHITAGÁ', null, '22', b'0', '1'), ('830', 'CONVENCIÓN', null, '22', b'0', '1'), ('831', 'CÚCUTA', null, '22', b'0', '1'), ('832', 'CUCUTILLA', null, '22', b'0', '1'), ('833', 'DURANIA', null, '22', b'0', '1'), ('834', 'EL CARMEN', null, '22', b'0', '1'), ('835', 'EL TARRA', null, '22', b'0', '1'), ('836', 'EL ZULIA', null, '22', b'0', '1'), ('837', 'GRAMALOTE', null, '22', b'0', '1'), ('838', 'HACARI', null, '22', b'0', '1'), ('839', 'HERRÁN', null, '22', b'0', '1'), ('840', 'LA ESPERANZA', null, '22', b'0', '1'), ('841', 'LA PLAYA', null, '22', b'0', '1'), ('842', 'LABATECA', null, '22', b'0', '1'), ('843', 'LOS PATIOS', null, '22', b'0', '1'), ('844', 'LOURDES', null, '22', b'0', '1'), ('845', 'MUTISCUA', null, '22', b'0', '1'), ('846', 'OCAÑA', null, '22', b'0', '1'), ('847', 'PAMPLONA', null, '22', b'0', '1'), ('848', 'PAMPLONITA', null, '22', b'0', '1'), ('849', 'PUERTO SANTANDER', null, '22', b'0', '1'), ('850', 'RAGONVALIA', null, '22', b'0', '1'), ('851', 'SALAZAR', null, '22', b'0', '1'), ('852', 'SAN CALIXTO', null, '22', b'0', '1'), ('853', 'SAN CAYETANO', null, '22', b'0', '1'), ('854', 'SANTIAGO', null, '22', b'0', '1'), ('855', 'SARDINATA', null, '22', b'0', '1'), ('856', 'SILOS', null, '22', b'0', '1'), ('857', 'TEORAMA', null, '22', b'0', '1'), ('858', 'TIBÚ', null, '22', b'0', '1'), ('859', 'TOLEDO', null, '22', b'0', '1'), ('860', 'VILLA CARO', null, '22', b'0', '1'), ('861', 'VILLA DEL ROSARIO', null, '22', b'0', '1'), ('862', 'COLÓN', null, '23', b'0', '1'), ('863', 'MOCOA', null, '23', b'0', '1'), ('864', 'ORITO', null, '23', b'0', '1'), ('865', 'PUERTO ASÍS', null, '23', b'0', '1'), ('866', 'PUERTO CAYCEDO', null, '23', b'0', '1'), ('867', 'PUERTO GUZMÁN', null, '23', b'0', '1'), ('868', 'PUERTO LEGUÍZAMO', null, '23', b'0', '1'), ('869', 'SAN FRANCISCO', null, '23', b'0', '1'), ('870', 'SAN MIGUEL', null, '23', b'0', '1'), ('871', 'SANTIAGO', null, '23', b'0', '1'), ('872', 'SIBUNDOY', null, '23', b'0', '1'), ('873', 'VALLE DEL GUAMUEZ', null, '23', b'0', '1'), ('874', 'VILLAGARZÓN', null, '23', b'0', '1'), ('875', 'ARMENIA', null, '24', b'0', '1'), ('876', 'BUENAVISTA', null, '24', b'0', '1'), ('877', 'CALARCÁ', null, '24', b'0', '1'), ('878', 'CIRCASIA', null, '24', b'0', '1'), ('879', 'CÓRDOBA', null, '24', b'0', '1'), ('880', 'FILANDIA', null, '24', b'0', '1'), ('881', 'GÉNOVA', null, '24', b'0', '1'), ('882', 'LA TEBAIDA', null, '24', b'0', '1'), ('883', 'MONTENEGRO', null, '24', b'0', '1'), ('884', 'PIJAO', null, '24', b'0', '1'), ('885', 'QUIMBAYA', null, '24', b'0', '1'), ('886', 'SALENTO', null, '24', b'0', '1'), ('887', 'APIA', null, '25', b'0', '1'), ('888', 'BALBOA', null, '25', b'0', '1'), ('889', 'BELÉN DE UMBRÍA', null, '25', b'0', '1'), ('890', 'DOS QUEBRADAS', null, '25', b'0', '1'), ('891', 'GUATICA', null, '25', b'0', '1'), ('892', 'LA CELIA', null, '25', b'0', '1'), ('893', 'LA VIRGINIA', null, '25', b'0', '1'), ('894', 'MARSELLA', null, '25', b'0', '1'), ('895', 'MISTRATO', null, '25', b'0', '1'), ('896', 'PEREIRA', null, '25', b'0', '1'), ('897', 'PUEBLO RICO', null, '25', b'0', '1'), ('898', 'QUINCHÍA', null, '25', b'0', '1'), ('899', 'SANTA ROSA DE CABAL', null, '25', b'0', '1'), ('900', 'SANTUARIO', null, '25', b'0', '1'), ('901', 'PROVIDENCIA', null, '26', b'0', '1'), ('902', 'SAN ANDRES', null, '26', b'0', '1'), ('903', 'SANTA CATALINA', null, '26', b'0', '1'), ('904', 'AGUADA', null, '27', b'0', '1'), ('905', 'ALBANIA', null, '27', b'0', '1'), ('906', 'ARATOCA', null, '27', b'0', '1'), ('907', 'BARBOSA', null, '27', b'0', '1'), ('908', 'BARICHARA', null, '27', b'0', '1'), ('909', 'BARRANCABERMEJA', null, '27', b'0', '1'), ('910', 'BETULIA', null, '27', b'0', '1'), ('911', 'BOLÍVAR', null, '27', b'0', '1'), ('912', 'BUCARAMANGA', null, '27', b'0', '1'), ('913', 'CABRERA', null, '27', b'0', '1'), ('914', 'CALIFORNIA', null, '27', b'0', '1'), ('915', 'CAPITANEJO', null, '27', b'0', '1'), ('916', 'CARCASI', null, '27', b'0', '1'), ('917', 'CEPITA', null, '27', b'0', '1'), ('918', 'CERRITO', null, '27', b'0', '1'), ('919', 'CHARALÁ', null, '27', b'0', '1'), ('920', 'CHARTA', null, '27', b'0', '1'), ('921', 'CHIMA', null, '27', b'0', '1'), ('922', 'CHIPATÁ', null, '27', b'0', '1'), ('923', 'CIMITARRA', null, '27', b'0', '1'), ('924', 'CONCEPCIÓN', null, '27', b'0', '1'), ('925', 'CONFINES', null, '27', b'0', '1'), ('926', 'CONTRATACIÓN', null, '27', b'0', '1'), ('927', 'COROMORO', null, '27', b'0', '1'), ('928', 'CURITÍ', null, '27', b'0', '1'), ('929', 'EL CARMEN', null, '27', b'0', '1'), ('930', 'EL GUACAMAYO', null, '27', b'0', '1'), ('931', 'EL PEÑÓN', null, '27', b'0', '1'), ('932', 'EL PLAYÓN', null, '27', b'0', '1'), ('933', 'ENCINO', null, '27', b'0', '1'), ('934', 'ENCISO', null, '27', b'0', '1'), ('935', 'FLORIÁN', null, '27', b'0', '1'), ('936', 'FLORIDABLANCA', null, '27', b'0', '1'), ('937', 'GALÁN', null, '27', b'0', '1'), ('938', 'GAMBITA', null, '27', b'0', '1'), ('939', 'GIRÓN', null, '27', b'0', '1'), ('940', 'GUACA', null, '27', b'0', '1'), ('941', 'GUADALUPE', null, '27', b'0', '1'), ('942', 'GUAPOTA', null, '27', b'0', '1'), ('943', 'GUAVATÁ', null, '27', b'0', '1'), ('944', 'GUEPSA', null, '27', b'0', '1'), ('945', 'HATO', null, '27', b'0', '1'), ('946', 'JESÚS MARIA', null, '27', b'0', '1'), ('947', 'JORDÁN', null, '27', b'0', '1'), ('948', 'LA BELLEZA', null, '27', b'0', '1'), ('949', 'LA PAZ', null, '27', b'0', '1'), ('950', 'LANDAZURI', null, '27', b'0', '1'), ('951', 'LEBRIJA', null, '27', b'0', '1'), ('952', 'LOS SANTOS', null, '27', b'0', '1'), ('953', 'MACARAVITA', null, '27', b'0', '1'), ('954', 'MÁLAGA', null, '27', b'0', '1'), ('955', 'MATANZA', null, '27', b'0', '1'), ('956', 'MOGOTES', null, '27', b'0', '1'), ('957', 'MOLAGAVITA', null, '27', b'0', '1'), ('958', 'OCAMONTE', null, '27', b'0', '1'), ('959', 'OIBA', null, '27', b'0', '1'), ('960', 'ONZAGA', null, '27', b'0', '1'), ('961', 'PALMAR', null, '27', b'0', '1'), ('962', 'PALMAS DEL SOCORRO', null, '27', b'0', '1'), ('963', 'PÁRAMO', null, '27', b'0', '1'), ('964', 'PIEDECUESTA', null, '27', b'0', '1'), ('965', 'PINCHOTE', null, '27', b'0', '1'), ('966', 'PUENTE NACIONAL', null, '27', b'0', '1'), ('967', 'PUERTO PARRA', null, '27', b'0', '1'), ('968', 'PUERTO WILCHES', null, '27', b'0', '1'), ('969', 'RIONEGRO', null, '27', b'0', '1'), ('970', 'SABANA DE TORRES', null, '27', b'0', '1'), ('971', 'SAN ANDRÉS', null, '27', b'0', '1'), ('972', 'SAN BENITO', null, '27', b'0', '1'), ('973', 'SAN GIL', null, '27', b'0', '1'), ('974', 'SAN JOAQUÍN', null, '27', b'0', '1'), ('975', 'SAN JOSÉ DE MIRANDA', null, '27', b'0', '1'), ('976', 'SAN MIGUEL', null, '27', b'0', '1'), ('977', 'SAN VICENTE DE CHUCURÍ', null, '27', b'0', '1'), ('978', 'SANTA BÁRBARA', null, '27', b'0', '1'), ('979', 'SANTA HELENA', null, '27', b'0', '1'), ('980', 'SIMACOTA', null, '27', b'0', '1'), ('981', 'SOCORRO', null, '27', b'0', '1'), ('982', 'SUAITA', null, '27', b'0', '1'), ('983', 'SUCRE', null, '27', b'0', '1'), ('984', 'SURATA', null, '27', b'0', '1'), ('985', 'TONA', null, '27', b'0', '1'), ('986', 'VALLE SAN JOSÉ', null, '27', b'0', '1'), ('987', 'VÉLEZ', null, '27', b'0', '1'), ('988', 'VETAS', null, '27', b'0', '1'), ('989', 'VILLANUEVA', null, '27', b'0', '1'), ('990', 'ZAPATOCA', null, '27', b'0', '1'), ('991', 'BUENAVISTA', null, '28', b'0', '1'), ('992', 'CAIMITO', null, '28', b'0', '1'), ('993', 'CHALÁN', null, '28', b'0', '1'), ('994', 'COLOSO', null, '28', b'0', '1'), ('995', 'COROZAL', null, '28', b'0', '1'), ('996', 'EL ROBLE', null, '28', b'0', '1'), ('997', 'GALERAS', null, '28', b'0', '1'), ('998', 'GUARANDA', null, '28', b'0', '1'), ('999', 'LA UNIÓN', null, '28', b'0', '1'), ('1000', 'LOS PALMITOS', null, '28', b'0', '1'), ('1001', 'MAJAGUAL', null, '28', b'0', '1'), ('1002', 'MORROA', null, '28', b'0', '1'), ('1003', 'OVEJAS', null, '28', b'0', '1'), ('1004', 'PALMITO', null, '28', b'0', '1'), ('1005', 'SAMPUES', null, '28', b'0', '1'), ('1006', 'SAN BENITO ABAD', null, '28', b'0', '1'), ('1007', 'SAN JUAN DE BETULIA', null, '28', b'0', '1'), ('1008', 'SAN MARCOS', null, '28', b'0', '1'), ('1009', 'SAN ONOFRE', null, '28', b'0', '1'), ('1010', 'SAN PEDRO', null, '28', b'0', '1'), ('1011', 'SINCÉ', null, '28', b'0', '1'), ('1012', 'SINCELEJO', null, '28', b'0', '1'), ('1013', 'SUCRE', null, '28', b'0', '1'), ('1014', 'TOLÚ', null, '28', b'0', '1'), ('1015', 'TOLUVIEJO', null, '28', b'0', '1'), ('1016', 'ALPUJARRA', null, '29', b'0', '1'), ('1017', 'ALVARADO', null, '29', b'0', '1'), ('1018', 'AMBALEMA', null, '29', b'0', '1'), ('1019', 'ANZOATEGUI', null, '29', b'0', '1'), ('1020', 'ARMERO (GUAYABAL)', null, '29', b'0', '1'), ('1021', 'ATACO', null, '29', b'0', '1'), ('1022', 'CAJAMARCA', null, '29', b'0', '1'), ('1023', 'CARMEN DE APICALÁ', null, '29', b'0', '1'), ('1024', 'CASABIANCA', null, '29', b'0', '1'), ('1025', 'CHAPARRAL', null, '29', b'0', '1'), ('1026', 'COELLO', null, '29', b'0', '1'), ('1027', 'COYAIMA', null, '29', b'0', '1'), ('1028', 'CUNDAY', null, '29', b'0', '1'), ('1029', 'DOLORES', null, '29', b'0', '1'), ('1030', 'ESPINAL', null, '29', b'0', '1'), ('1031', 'FALÁN', null, '29', b'0', '1'), ('1032', 'FLANDES', null, '29', b'0', '1'), ('1033', 'FRESNO', null, '29', b'0', '1'), ('1034', 'GUAMO', null, '29', b'0', '1'), ('1035', 'HERVEO', null, '29', b'0', '1'), ('1036', 'HONDA', null, '29', b'0', '1'), ('1037', 'IBAGUÉ', null, '29', b'0', '1'), ('1038', 'ICONONZO', null, '29', b'0', '1'), ('1039', 'LÉRIDA', null, '29', b'0', '1'), ('1040', 'LÍBANO', null, '29', b'0', '1'), ('1041', 'MARIQUITA', null, '29', b'0', '1'), ('1042', 'MELGAR', null, '29', b'0', '1'), ('1043', 'MURILLO', null, '29', b'0', '1'), ('1044', 'NATAGAIMA', null, '29', b'0', '1'), ('1045', 'ORTEGA', null, '29', b'0', '1'), ('1046', 'PALOCABILDO', null, '29', b'0', '1'), ('1047', 'PIEDRAS PLANADAS', null, '29', b'0', '1'), ('1048', 'PRADO', null, '29', b'0', '1'), ('1049', 'PURIFICACIÓN', null, '29', b'0', '1'), ('1050', 'RIOBLANCO', null, '29', b'0', '1'), ('1051', 'RONCESVALLES', null, '29', b'0', '1'), ('1052', 'ROVIRA', null, '29', b'0', '1'), ('1053', 'SALDAÑA', null, '29', b'0', '1'), ('1054', 'SAN ANTONIO', null, '29', b'0', '1'), ('1055', 'SAN LUIS', null, '29', b'0', '1'), ('1056', 'SANTA ISABEL', null, '29', b'0', '1'), ('1057', 'SUÁREZ', null, '29', b'0', '1'), ('1058', 'VALLE DE SAN JUAN', null, '29', b'0', '1'), ('1059', 'VENADILLO', null, '29', b'0', '1'), ('1060', 'VILLAHERMOSA', null, '29', b'0', '1'), ('1061', 'VILLARRICA', null, '29', b'0', '1'), ('1062', 'ALCALÁ', null, '30', b'0', '1'), ('1063', 'ANDALUCÍA', null, '30', b'0', '1'), ('1064', 'ANSERMA NUEVO', null, '30', b'0', '1'), ('1065', 'ARGELIA', null, '30', b'0', '1'), ('1066', 'BOLÍVAR', null, '30', b'0', '1'), ('1067', 'BUENAVENTURA', null, '30', b'0', '1'), ('1068', 'BUGA', null, '30', b'0', '1'), ('1069', 'BUGALAGRANDE', null, '30', b'0', '1'), ('1070', 'CAICEDONIA', null, '30', b'0', '1'), ('1071', 'CALI', null, '30', b'0', '1'), ('1072', 'CALIMA (DARIEN)', null, '30', b'0', '1'), ('1073', 'CANDELARIA', null, '30', b'0', '1'), ('1074', 'CARTAGO', null, '30', b'0', '1'), ('1075', 'DAGUA', null, '30', b'0', '1'), ('1076', 'EL AGUILA', null, '30', b'0', '1'), ('1077', 'EL CAIRO', null, '30', b'0', '1'), ('1078', 'EL CERRITO', null, '30', b'0', '1'), ('1079', 'EL DOVIO', null, '30', b'0', '1'), ('1080', 'FLORIDA', null, '30', b'0', '1'), ('1081', 'GINEBRA GUACARI', null, '30', b'0', '1'), ('1082', 'JAMUNDÍ', null, '30', b'0', '1'), ('1083', 'LA CUMBRE', null, '30', b'0', '1'), ('1084', 'LA UNIÓN', null, '30', b'0', '1'), ('1085', 'LA VICTORIA', null, '30', b'0', '1'), ('1086', 'OBANDO', null, '30', b'0', '1'), ('1087', 'PALMIRA', null, '30', b'0', '1'), ('1088', 'PRADERA', null, '30', b'0', '1'), ('1089', 'RESTREPO', null, '30', b'0', '1'), ('1090', 'RIO FRÍO', null, '30', b'0', '1'), ('1091', 'ROLDANILLO', null, '30', b'0', '1'), ('1092', 'SAN PEDRO', null, '30', b'0', '1'), ('1093', 'SEVILLA', null, '30', b'0', '1'), ('1094', 'TORO', null, '30', b'0', '1'), ('1095', 'TRUJILLO', null, '30', b'0', '1'), ('1096', 'TULÚA', null, '30', b'0', '1'), ('1097', 'ULLOA', null, '30', b'0', '1'), ('1098', 'VERSALLES', null, '30', b'0', '1'), ('1099', 'VIJES', null, '30', b'0', '1'), ('1100', 'YOTOCO', null, '30', b'0', '1'), ('1101', 'YUMBO', null, '30', b'0', '1'), ('1102', 'ZARZAL', null, '30', b'0', '1'), ('1103', 'CARURÚ', null, '31', b'0', '1'), ('1104', 'MITÚ', null, '31', b'0', '1'), ('1105', 'PACOA', null, '31', b'0', '1'), ('1106', 'PAPUNAUA', null, '31', b'0', '1'), ('1107', 'TARAIRA', null, '31', b'0', '1'), ('1108', 'YAVARATÉ', null, '31', b'0', '1'), ('1109', 'CUMARIBO', null, '32', b'0', '1'), ('1110', 'LA PRIMAVERA', null, '32', b'0', '1'), ('1111', 'PUERTO CARREÑO', null, '32', b'0', '1'), ('1112', 'SANTA ROSALIA', null, '32', b'0', '1'), ('1113', 'CÚCUTA', null, '22', b'0', '1'), ('1114', 'COVEÑAS', null, '28', b'0', '1'), ('1115', 'Bogotá', null, '33', b'0', '1');
COMMIT;

-- ----------------------------
--  Table structure for `basica_columna`
-- ----------------------------
DROP TABLE IF EXISTS `basica_columna`;
CREATE TABLE `basica_columna` (
  `columna_id` int(11) NOT NULL AUTO_INCREMENT,
  `tabla_id` int(11) NOT NULL,
  `columna_nombre` varchar(255) NOT NULL,
  `columna_relacion` varchar(255) NOT NULL,
  `columna_tipo` varchar(255) NOT NULL,
  `columna_remplasa` varchar(255) DEFAULT NULL,
  `columna_insert` bit(1) DEFAULT b'0',
  PRIMARY KEY (`columna_id`),
  KEY `tabla_id` (`tabla_id`),
  CONSTRAINT `basica_columna_ibfk_1` FOREIGN KEY (`tabla_id`) REFERENCES `basica_tabla` (`tabla_id`)
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Records of `basica_columna`
-- ----------------------------
BEGIN;
INSERT INTO `basica_columna` VALUES ('1', '1', '', 'tipodocumento_id', 'fijo', '1', b'0'), ('2', '1', 'Doc.colaborador / ID', 'usuario_documento', 'unico', '', b'0'), ('3', '1', 'Nombre', 'usuario_nombre', 'texto', '', b'0'), ('4', '1', 'Apellidos colaborador', 'usuario_apellido', 'texto', '', b'0'), ('5', '1', 'Nombre completo', '', 'no', '', b'0'), ('6', '1', 'Correo Electronico', 'usuario_correo', 'texto', '', b'0'), ('7', '1', '', 'usuario_direccion', 'texto', '', b'0'), ('8', '1', 'Celular', 'usuario_celular', 'texto', '', b'0'), ('9', '1', 'Fecha de nacimiento', 'usuario_fechanacimiento', 'fecha', '', b'0'), ('10', '1', '', 'usuario_habeasdata', 'fijo', '1', b'0'), ('11', '1', 'Genero', 'genero', 'busqueda', 'genero_nombre', b'0'), ('12', '1', 'Ciudad', 'ciudad', 'busqueda', 'ciudad_nombre', b'0'), ('13', '1', '', 'usuario_usuario', 'variable', 'Doc.colaborador / ID', b'0'), ('14', '1', '', 'usuario_codigounico', 'random', '10', b'0'), ('15', '1', '', 'usuario_fecha', 'fijo', 'now', b'0'), ('16', '1', '', 'rol_id', 'fijo', '7', b'0'), ('17', '1', '', 'agile_estado_id', 'fijo', '1', b'0'), ('18', '1', '', 'incentive_id', '', '', b'0'), ('19', '1', '', 'usuario_actualizado', 'fijo', '0', b'0'), ('20', '1', '', 'usuario_registra', 'fijo', '1', b'0'), ('21', '1', 'Activo / Inactivo', 'estado', 'busqueda', 'estado_nombre', b'0'), ('22', '1', 'Edad', '', 'no', '', b'0'), ('23', '1', 'Rango Edad', '', 'no', '', b'0'), ('24', '1', 'Empresa legal', 'empresalegal', 'busqueda', 'empresalegal_nombre', b'0'), ('25', '1', 'Fecha de inicio', 'usuario_ingreso', 'texto', '', b'0'), ('26', '1', 'Antigüedad', '', 'no', '', b'0'), ('27', '1', 'Rango Antigüedad', '', 'no', '', b'0'), ('28', '1', 'Nombre Posicion', 'posicion', 'busqueda', 'posicion_nombre', b'0'), ('29', '1', 'Regional', 'regional', 'busqueda', 'regional_nombre', b'0'), ('30', '1', 'Area', '', 'no', '', b'0'), ('31', '1', 'Subarea General', '', 'no', '', b'0'), ('32', '1', 'Desglose', '', 'no', '', b'0'), ('33', '1', 'Grupo de Venta', 'grupo', 'busqueda', 'grupo_nombre', b'1'), ('34', '1', 'Jefe', '', 'no', '', b'0'), ('35', '1', 'Cod Act-Inact', '', 'no', '', b'0'), ('36', '1', 'tipo Usuario', 'tipocontrato', 'busqueda', 'tipocontrato_nombre', b'0'), ('37', '2', 'CdSeller', 'usuario', 'busqueda', 'usuario_codigonomina', b'0'), ('38', '2', 'Mes', 'metaventa_mes', 'texto', null, b'0'), ('39', '2', 'Fecha Corte Información', 'metaventa_fecha', 'fecha', null, b'0'), ('40', '2', 'Recompra', 'metaventa_recompra', 'texto', null, b'0'), ('41', '2', 'Nueva', 'metaventa_nuevas', 'texto', null, b'0'), ('42', '2', 'Total general', 'metaventa_nuevas', 'texto', null, b'0'), ('43', '2', '', 'metaventa_fechacarga', 'fijo', 'now', b'0'), ('44', '2', '', 'estado_id', 'fijo', '1', b'0'), ('45', '3', 'CdSeller', 'usuario', 'busqueda', 'usuario_codigonomina', b'0'), ('46', '3', 'Mes', 'metavisita_mes', 'texto', null, b'0'), ('47', '3', 'Fecha Corte Información', 'metavisita_fecha', 'texto', null, b'0'), ('48', '3', 'Visitas Diarias', 'metavisita_diarias', 'texto', null, b'0'), ('49', '3', 'Dias Habiles L-V', 'metavisita_habiles', 'texto', null, b'0'), ('50', '3', 'Total Visitas', 'metavisita_totales', 'texto', null, b'0'), ('51', '3', '', 'metavisita_fechacarga', 'fijo', 'now', b'0'), ('52', '3', '', 'estado_id', 'fijo', '1', b'0'), ('53', '4', 'CdSeller', 'usuario', 'busqueda', 'usuario_codigonomina', b'0'), ('54', '4', 'Mes', 'venta_mes', 'texto', null, b'0'), ('55', '4', 'Fecha Corte Información', 'venta_fecha', 'texto', null, b'0'), ('56', '4', 'Recompra', 'venta_recompra', 'texto', null, b'0'), ('57', '4', 'Nueva', 'venta_nuevas', 'texto', null, b'0'), ('58', '4', '', 'venta_fechacarga', 'fijo', 'now', b'0'), ('59', '4', '', 'estado_id', 'fijo', '1', b'0'), ('60', '4', 'Total general', 'venta_nuevas', 'no', null, b'0'), ('61', '1', 'Código de nómina', 'usuario_codigonomina', 'texto', null, b'0'), ('62', '1', 'Cargo Homologado ', 'cargo', 'busqueda', 'cargo_nombre', b'0'), ('63', '1', 'Cargo', 'usuario_cargogeneral', 'texto', null, b'0'), ('64', '5', 'CdSeller', 'usuario', 'busqueda', 'usuario_codigonomina', b'0'), ('65', '5', 'Mes', 'visita_mes', 'texto', null, b'0'), ('66', '5', 'Fecha Corte Información', 'visita_fecha', 'texto', null, b'0'), ('67', '5', 'Visitas', 'visita_total', 'texto', null, b'0'), ('68', '5', '', 'visita_fechacarga', 'fijo', 'now', b'0'), ('69', '5', '', 'estado_id', 'fijo', '1', b'0'), ('70', '2', 'Nomina', 'metaventa_nomina', 'texto', null, b'0'), ('71', '4', 'Nomina', 'venta_nomina', 'texto', null, b'0'), ('72', '3', 'Nomina', 'metavisita_nomina', 'texto', null, b'0'), ('73', '5', 'Nomina', 'visita_nomina', 'texto', null, b'0'), ('74', '6', 'CdSeller', 'usuario', 'busqueda', 'usuario_codigonomina', b'0'), ('75', '6', 'Grupo de Venta', 'grupo', 'busqueda', 'grupo_nombre', b'0'), ('76', '6', 'Meta', 'metagrupo_meta', 'texto', null, b'0'), ('77', '6', 'Nomina', 'metagrupo_nomina', 'texto', null, b'0'), ('78', '6', 'Mes', 'metagrupo_mes', 'texto', null, b'0'), ('79', '1', 'Codigo Nómina Jefe', 'usuario_codigojefe', 'texto', '', b'0'), ('80', '1', 'Canal', 'usuario_canal', 'texto', null, b'0');
COMMIT;

-- ----------------------------
--  Table structure for `basica_departamento`
-- ----------------------------
DROP TABLE IF EXISTS `basica_departamento`;
CREATE TABLE `basica_departamento` (
  `departamento_id` int(11) NOT NULL AUTO_INCREMENT,
  `departamento_nombre` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `pais_id` int(11) DEFAULT NULL,
  `departamento_borrado` bit(1) DEFAULT b'0',
  `estado_id` int(11) NOT NULL,
  PRIMARY KEY (`departamento_id`),
  KEY `pk_pais` (`pais_id`),
  CONSTRAINT `basica_departamento_ibfk_1` FOREIGN KEY (`pais_id`) REFERENCES `basica_pais` (`pais_id`)
) ENGINE=InnoDB AUTO_INCREMENT=34 DEFAULT CHARSET=utf8 COLLATE=utf8_bin ROW_FORMAT=COMPACT;

-- ----------------------------
--  Records of `basica_departamento`
-- ----------------------------
BEGIN;
INSERT INTO `basica_departamento` VALUES ('1', 'AMAZONAS', '1', b'0', '1'), ('2', 'ANTIOQUIA', '1', b'0', '1'), ('3', 'ARAUCA', '1', b'0', '1'), ('4', 'ATLÁNTICO', '1', b'0', '1'), ('5', 'BOLÍVAR', '1', b'0', '1'), ('6', 'BOYACÁ', '1', b'0', '1'), ('7', 'CALDAS', '1', b'0', '1'), ('8', 'CAQUETÁ', '1', b'0', '1'), ('9', 'CASANARE', '1', b'0', '1'), ('10', 'CAUCA', '1', b'0', '1'), ('11', 'CESAR', '1', b'0', '1'), ('12', 'CHOCÓ', '1', b'0', '1'), ('13', 'CÓRDOBA', '1', b'0', '1'), ('14', 'CUNDINAMARCA', '1', b'0', '1'), ('15', 'GUAINÍA', '1', b'0', '1'), ('16', 'GUAVIARE', '1', b'0', '1'), ('17', 'HUILA', '1', b'0', '1'), ('18', 'LA GUAJIRA', '1', b'0', '1'), ('19', 'MAGDALENA', '1', b'0', '1'), ('20', 'META', '1', b'0', '1'), ('21', 'NARIÑO', '1', b'0', '1'), ('22', 'NORTE DE SANTANDER', '1', b'0', '1'), ('23', 'PUTUMAYO', '1', b'0', '1'), ('24', 'QUINDÍO', '1', b'0', '1'), ('25', 'RISARALDA', '1', b'0', '1'), ('26', 'SAN ANDRÉS Y ROVIDENCIA', '1', b'0', '1'), ('27', 'SANTANDER', '1', b'0', '1'), ('28', 'SUCRE', '1', b'0', '1'), ('29', 'TOLIMA', '1', b'0', '1'), ('30', 'VALLE DEL CAUCA', '1', b'0', '1'), ('31', 'VAUPÉS', '1', b'0', '1'), ('32', 'VICHADA', '1', b'0', '1'), ('33', 'BOGOTÁ DC', '1', b'0', '1');
COMMIT;

-- ----------------------------
--  Table structure for `basica_empresalegal`
-- ----------------------------
DROP TABLE IF EXISTS `basica_empresalegal`;
CREATE TABLE `basica_empresalegal` (
  `empresalegal_id` int(11) NOT NULL AUTO_INCREMENT,
  `empresalegal_nombre` varchar(255) NOT NULL,
  PRIMARY KEY (`empresalegal_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Records of `basica_empresalegal`
-- ----------------------------
BEGIN;
INSERT INTO `basica_empresalegal` VALUES ('1', 'Publicar S.A.S'), ('2', 'Publicar Servicios S.A.S');
COMMIT;

-- ----------------------------
--  Table structure for `basica_estado`
-- ----------------------------
DROP TABLE IF EXISTS `basica_estado`;
CREATE TABLE `basica_estado` (
  `estado_id` int(11) NOT NULL AUTO_INCREMENT,
  `estado_nombre` varchar(255) NOT NULL,
  PRIMARY KEY (`estado_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Records of `basica_estado`
-- ----------------------------
BEGIN;
INSERT INTO `basica_estado` VALUES ('1', 'Activo'), ('2', 'Inactivo'), ('3', 'Pendiente activacion'), ('4', 'Pendiente Actualizacion'), ('5', 'Actualizacion realizada');
COMMIT;

-- ----------------------------
--  Table structure for `basica_genero`
-- ----------------------------
DROP TABLE IF EXISTS `basica_genero`;
CREATE TABLE `basica_genero` (
  `genero_id` int(11) NOT NULL AUTO_INCREMENT,
  `genero_nombre` varchar(255) NOT NULL,
  `estado_id` int(11) NOT NULL,
  PRIMARY KEY (`genero_id`),
  KEY `estado_id` (`estado_id`),
  CONSTRAINT `estado_genero` FOREIGN KEY (`estado_id`) REFERENCES `basica_estado` (`estado_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Records of `basica_genero`
-- ----------------------------
BEGIN;
INSERT INTO `basica_genero` VALUES ('1', 'Masculino', '1'), ('2', 'Femenino', '1');
COMMIT;

-- ----------------------------
--  Table structure for `basica_grupo`
-- ----------------------------
DROP TABLE IF EXISTS `basica_grupo`;
CREATE TABLE `basica_grupo` (
  `grupo_id` int(11) NOT NULL AUTO_INCREMENT,
  `grupo_nombre` varchar(255) NOT NULL,
  PRIMARY KEY (`grupo_id`)
) ENGINE=InnoDB AUTO_INCREMENT=64 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Records of `basica_grupo`
-- ----------------------------
BEGIN;
INSERT INTO `basica_grupo` VALUES ('1', 'CANALES NUEVOS AGENTES'), ('2', 'CELULA VILLAVICENCIO 2018'), ('3', 'CELULA ARMENIA 2017'), ('4', 'CELULA BARRANCABERMEJA 2017'), ('5', 'CELULA BOYACA 2017'), ('6', 'CELULA BUCARAMANGA 2017'), ('7', 'CELULA BUGA 2017'), ('8', 'CELULA CARTAGO'), ('9', 'CELULA CUCUTA 2018'), ('10', 'CELULA ESPECIALIZADA DIGITAL 2017'), ('11', 'CELULA HUILA 2017'), ('12', 'CELULA MANIZALES 2018'), ('13', 'CELULA MONTERIA 2017'), ('14', 'CELULA NORTE 2017'), ('15', 'CELULA NORTE BARRANQUILLA 2017'), ('16', 'CELULA NORTE CALI'), ('17', 'CELULA NORTE CARTAGENA 2017'), ('18', 'CELULA OCCIDENTE  2017'), ('19', 'CELULA ORIENTE  2017'), ('20', 'CELULA PALMIRA 2017'), ('21', 'CELULA PASTO'), ('22', 'CELULA PEREIRA'), ('23', 'CELULA POPAYAN 2017'), ('24', 'CELULA SAN ANDRES 2017'), ('25', 'CELULA SANTA  MARTA 2017'), ('26', 'CELULA SINCELEJO 2017'), ('27', 'CELULA SU TARGET 2017'), ('28', 'CELULA SUR 2017'), ('29', 'CELULA SUR BARRANQUILLA 2017'), ('30', 'CELULA SUR CALI (JAIRO)'), ('31', 'CELULA SUR CALI (MARIA VIRGINIA)'), ('32', 'CELULA SUR CARTAGENA 2017'), ('33', 'CELULA TOLIMA 2017'), ('34', 'CELULA TULUA 2017'), ('35', 'CELULA VALLEDUPAR 2017'), ('36', 'COORDINACIÓN DE VENTAS'), ('37', 'EQUIPO ESPECIALIZADO BARRANQUILLA'), ('38', 'EQUIPO II  FREDDY CADAVID'), ('39', 'EQUIPO II VENTA NUEVA TELEVENTAS'), ('40', 'EQUIPO III  LUIS BUSTAMANTE'), ('41', 'EQUIPO III VENTA NUEVA TELEVENTAS'), ('42', 'EQUIPO RECOMPRA TELEVENTAS'), ('43', 'EQUIPO TELEVENTAS GERENCIA'), ('44', 'EQUIPO VENTA NUEVA TELEVENTAS'), ('45', 'GRUPO BARRANQUILLA'), ('46', 'GRUPO CALI JAIRO'), ('47', 'GRUPO VENTA NUEVA FREDY QUIROGA 2017'), ('48', 'GRUPO KAM SAEL BAYONA 2017'), ('49', 'GRUPO RECOMPRA JONATHAN BARROS 2017'), ('50', 'GRUPO RECOMPRA GLORIA GOMEZ 2017'), ('51', 'EQUIPO MEDELLIN ULISES'), ('52', 'GRUPO CARTAGENA'), ('53', 'GRUPO CALI MARIA VIRGINIA'), ('54', 'CELULA  VILLAVICENCIO 2018'), ('55', ''), ('56', 'VENTA NUEVO TELEVENTAS 2017'), ('57', 'EQUIPO MEDELLIN RICHAR'), ('58', 'EQUIPO MEDELLIN PAULA'), ('59', 'CELULA PEREIRA 2018'), ('60', 'EQUIPO TELEVENTAS OSCAR PRIETO'), ('61', 'GRUPO RENOVACION TELEVENTAS MEDELLIN'), ('62', 'TELEVENTAS PANAMA'), ('63', 'GRUPO VENTA NUEVA TELEVENTAS MEDELLIN');
COMMIT;

-- ----------------------------
--  Table structure for `basica_join`
-- ----------------------------
DROP TABLE IF EXISTS `basica_join`;
CREATE TABLE `basica_join` (
  `join_id` int(11) NOT NULL AUTO_INCREMENT,
  `tabla_id` int(11) NOT NULL,
  `join_tabla` varchar(255) NOT NULL,
  `join_tipo` varchar(255) NOT NULL,
  `join_conect` varchar(255) NOT NULL,
  PRIMARY KEY (`join_id`)
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

-- ----------------------------
--  Table structure for `basica_link`
-- ----------------------------
DROP TABLE IF EXISTS `basica_link`;
CREATE TABLE `basica_link` (
  `link_id` int(11) NOT NULL AUTO_INCREMENT,
  `link_nombre` varchar(255) NOT NULL,
  `link_link` varchar(255) NOT NULL,
  `menu_id` int(11) NOT NULL,
  `estado_id` int(11) NOT NULL,
  PRIMARY KEY (`link_id`),
  KEY `menu_id` (`menu_id`),
  KEY `estado_id` (`estado_id`),
  CONSTRAINT `basica_link_ibfk_1` FOREIGN KEY (`estado_id`) REFERENCES `basica_estado` (`estado_id`),
  CONSTRAINT `basica_link_ibfk_2` FOREIGN KEY (`menu_id`) REFERENCES `basica_menu` (`menu_id`)
) ENGINE=InnoDB AUTO_INCREMENT=14 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Records of `basica_link`
-- ----------------------------
BEGIN;
INSERT INTO `basica_link` VALUES ('1', 'Carga de usuarios', '/index.php/admin/Cargatablas/controlador/usuario', '1', '1'), ('2', 'Carga de Metas Ventas', '/index.php/admin/Cargatablas/controlador/metaventa', '1', '1'), ('4', 'Carga de Metas Visitas', '/index.php/admin/Cargatablas/controlador/metavisita', '1', '1'), ('5', 'Carga de ventas', '/index.php/admin/Cargatablas/controlador/venta', '1', '1'), ('6', 'Carga de Visitas', '/index.php/admin/Cargatablas/controlador/visita', '1', '1'), ('7', 'Carga Metas Grupo', '/index.php/admin/Cargatablas/controlador/metagrupo', '1', '1'), ('8', 'Reporte mensual', '/index.php/admin/Cargatablas/controlador/exportgeneral', '5', '1'), ('9', 'Reporte Grupal', '/index.php/admin/Home/reporteGrupal', '3', '1'), ('10', 'Job ventas', '/index.php/admin/job/cargarCumplimientosVentas', '4', '1'), ('11', 'Job Visitas', '/index.php/admin/job/cargarCumplimientosVisitas', '4', '1'), ('12', 'Job Grupo', '/index.php/admin/job/cargarCumplimientoGrupo', '4', '1'), ('13', 'Reporte mensual', '/index.php/admin/Cargatablas/controlador/exportgeneral', '3', '1');
COMMIT;

-- ----------------------------
--  Table structure for `basica_menu`
-- ----------------------------
DROP TABLE IF EXISTS `basica_menu`;
CREATE TABLE `basica_menu` (
  `menu_id` int(11) NOT NULL AUTO_INCREMENT,
  `categoria_menu` int(11) NOT NULL,
  `categoria_submenu` int(11) NOT NULL,
  `estado_id` int(11) NOT NULL,
  PRIMARY KEY (`menu_id`),
  KEY `categoria_menu` (`categoria_menu`),
  KEY `categoria_submenu` (`categoria_submenu`),
  KEY `estado_id` (`estado_id`),
  CONSTRAINT `basica_menu_ibfk_1` FOREIGN KEY (`categoria_menu`) REFERENCES `basica_categoria_menu` (`categoria_menu_id`),
  CONSTRAINT `basica_menu_ibfk_2` FOREIGN KEY (`categoria_submenu`) REFERENCES `basica_categoria_submenu` (`categoria_submenu_id`),
  CONSTRAINT `basica_menu_ibfk_3` FOREIGN KEY (`estado_id`) REFERENCES `basica_estado` (`estado_id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Records of `basica_menu`
-- ----------------------------
BEGIN;
INSERT INTO `basica_menu` VALUES ('1', '1', '1', '1'), ('2', '1', '3', '1'), ('3', '1', '3', '1'), ('4', '1', '4', '1'), ('5', '1', '3', '1');
COMMIT;

-- ----------------------------
--  Table structure for `basica_pais`
-- ----------------------------
DROP TABLE IF EXISTS `basica_pais`;
CREATE TABLE `basica_pais` (
  `pais_id` int(11) NOT NULL AUTO_INCREMENT,
  `pais_nombre` varchar(255) CHARACTER SET utf8 DEFAULT NULL,
  `pais_borrado` bit(1) DEFAULT b'0',
  PRIMARY KEY (`pais_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 COLLATE=utf8_bin ROW_FORMAT=COMPACT;

-- ----------------------------
--  Records of `basica_pais`
-- ----------------------------
BEGIN;
INSERT INTO `basica_pais` VALUES ('1', 'Colombia', b'0');
COMMIT;

-- ----------------------------
--  Table structure for `basica_posicion`
-- ----------------------------
DROP TABLE IF EXISTS `basica_posicion`;
CREATE TABLE `basica_posicion` (
  `posicion_id` int(11) NOT NULL AUTO_INCREMENT,
  `posicion_nombre` varchar(255) NOT NULL,
  PRIMARY KEY (`posicion_id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Records of `basica_posicion`
-- ----------------------------
BEGIN;
INSERT INTO `basica_posicion` VALUES ('1', 'Consultor Comercial Barranquilla');
COMMIT;

-- ----------------------------
--  Table structure for `basica_regional`
-- ----------------------------
DROP TABLE IF EXISTS `basica_regional`;
CREATE TABLE `basica_regional` (
  `regional_id` int(11) NOT NULL AUTO_INCREMENT,
  `regional_nombre` varchar(255) NOT NULL,
  PRIMARY KEY (`regional_id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Records of `basica_regional`
-- ----------------------------
BEGIN;
INSERT INTO `basica_regional` VALUES ('1', 'Costa'), ('2', 'Antioquia'), ('3', 'Bogotá'), ('4', 'Centro'), ('5', 'Eje Cafetero'), ('6', 'Especializado'), ('7', 'Occidente'), ('8', 'Santander'), ('9', 'Su Target'), ('10', 'Televentas');
COMMIT;

-- ----------------------------
--  Table structure for `basica_rol`
-- ----------------------------
DROP TABLE IF EXISTS `basica_rol`;
CREATE TABLE `basica_rol` (
  `rol_id` int(11) NOT NULL AUTO_INCREMENT,
  `rol_nombre` varchar(255) NOT NULL,
  `rol_admin` int(11) NOT NULL,
  `rol_index` varchar(255) NOT NULL,
  PRIMARY KEY (`rol_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Records of `basica_rol`
-- ----------------------------
BEGIN;
INSERT INTO `basica_rol` VALUES ('1', 'Superadmin', '1', 'admin/Home'), ('2', 'Admin', '1', 'admin/Home'), ('3', 'Reportero', '1', 'admin/Home'), ('4', 'Punto de Informacion', '1', 'admin/Home'), ('5', 'Asesores externos', '1', 'admin/Home'), ('6', 'Comercios', '1', 'Home'), ('7', 'Visitante', '1', 'Home'), ('8', 'Programador', '1', 'admin/Home'), ('9', 'Carga datos', '1', 'admin/Home');
COMMIT;

-- ----------------------------
--  Table structure for `basica_tabla`
-- ----------------------------
DROP TABLE IF EXISTS `basica_tabla`;
CREATE TABLE `basica_tabla` (
  `tabla_id` int(11) NOT NULL AUTO_INCREMENT,
  `tabla_nombre` varchar(255) NOT NULL,
  `tabla_js` varchar(255) NOT NULL,
  `tabla_controlador` varchar(255) NOT NULL,
  `tabla_mesaje` varchar(255) NOT NULL,
  `tipoaccion_id` int(11) NOT NULL,
  `tabla_jobfin` varchar(255) NOT NULL,
  `tabla_action` varchar(255) NOT NULL,
  `tabla_method` varchar(255) NOT NULL,
  `tabla_class` varchar(255) NOT NULL,
  PRIMARY KEY (`tabla_id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Records of `basica_tabla`
-- ----------------------------
BEGIN;
INSERT INTO `basica_tabla` VALUES ('1', 'usuario', 'datos_js', 'Usuario', 'Modulo de carga de usuarios', '1', 'admin/job/JobcargarUsuarios', '', '', ''), ('2', 'metaventa', 'datos_js', 'metaventa', 'Modulo de carga de metas ventas', '1', 'admin/job/tareaMetasVentas', '', '', ''), ('3', 'metavisita', 'datos_js', 'metavisita', 'Modulo de carga de metas visitas', '1', 'admin/job/tareaMetasVisitas', '', '', ''), ('4', 'venta', 'datos_js', 'venta', 'Modulo de carga de ventas', '1', 'admin/job/tareaVentas', '', '', ''), ('5', 'visita', 'datos_js', 'visita', 'Modulo de carga de visitas', '1', 'admin/job/tareaVisitas', '', '', ''), ('6', 'metagrupo', 'datos_js', 'metagrupo', 'Modulo de carga de metas grupo', '1', 'admin/job/tareaMetasGrupal', '', '', ''), ('7', 'exportgeneral', 'datos_js', 'exportgeneral', 'Modulo Exportacion metricas', '2', '', '', 'post', '');
COMMIT;

-- ----------------------------
--  Table structure for `basica_tipoaccion`
-- ----------------------------
DROP TABLE IF EXISTS `basica_tipoaccion`;
CREATE TABLE `basica_tipoaccion` (
  `tipoaccion_id` int(11) NOT NULL AUTO_INCREMENT,
  `tipoaccion_nombre` varchar(255) NOT NULL,
  PRIMARY KEY (`tipoaccion_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=latin1 ROW_FORMAT=DYNAMIC;

-- ----------------------------
--  Records of `basica_tipoaccion`
-- ----------------------------
BEGIN;
INSERT INTO `basica_tipoaccion` VALUES ('1', 'Carga'), ('2', 'Exportacion');
COMMIT;

-- ----------------------------
--  Table structure for `basica_tipocampo`
-- ----------------------------
DROP TABLE IF EXISTS `basica_tipocampo`;
CREATE TABLE `basica_tipocampo` (
  `tipocampo_id` int(11) NOT NULL AUTO_INCREMENT,
  `tipocampo_nombre` varchar(255) NOT NULL,
  PRIMARY KEY (`tipocampo_id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;

-- ----------------------------
--  Records of `basica_tipocampo`
-- ----------------------------
BEGIN;
INSERT INTO `basica_tipocampo` VALUES ('1', 'input'), ('2', 'submit'), ('3', 'select');
COMMIT;

-- ----------------------------
--  Table structure for `basica_tipocontrato`
-- ----------------------------
DROP TABLE IF EXISTS `basica_tipocontrato`;
CREATE TABLE `basica_tipocontrato` (
  `tipocontrato_id` int(11) NOT NULL AUTO_INCREMENT,
  `tipocontrato_nombre` varchar(255) NOT NULL,
  PRIMARY KEY (`tipocontrato_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Records of `basica_tipocontrato`
-- ----------------------------
BEGIN;
INSERT INTO `basica_tipocontrato` VALUES ('1', 'Interno'), ('2', 'Externo');
COMMIT;

-- ----------------------------
--  Table structure for `basica_tipocumplimiento`
-- ----------------------------
DROP TABLE IF EXISTS `basica_tipocumplimiento`;
CREATE TABLE `basica_tipocumplimiento` (
  `tipocumplimiento_id` int(11) NOT NULL AUTO_INCREMENT,
  `tipocumplimiento_nombre` varchar(255) NOT NULL,
  PRIMARY KEY (`tipocumplimiento_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Records of `basica_tipocumplimiento`
-- ----------------------------
BEGIN;
INSERT INTO `basica_tipocumplimiento` VALUES ('1', 'Venta Recompra'), ('2', 'Venta Nuevo'), ('3', 'Visitas'), ('4', 'Concurso');
COMMIT;

-- ----------------------------
--  Table structure for `basica_tipodocumento`
-- ----------------------------
DROP TABLE IF EXISTS `basica_tipodocumento`;
CREATE TABLE `basica_tipodocumento` (
  `tipodocumento_id` int(11) NOT NULL AUTO_INCREMENT,
  `tipodocumento_nombre` varchar(255) NOT NULL,
  `estado_id` int(11) NOT NULL,
  PRIMARY KEY (`tipodocumento_id`),
  KEY `estado_id` (`estado_id`),
  CONSTRAINT `estado_tipodocumento` FOREIGN KEY (`estado_id`) REFERENCES `basica_estado` (`estado_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Records of `basica_tipodocumento`
-- ----------------------------
BEGIN;
INSERT INTO `basica_tipodocumento` VALUES ('1', 'Cédula de ciudadanía', '1'), ('2', 'Cédula de extranjería', '1');
COMMIT;

-- ----------------------------
--  Table structure for `parametria_aplicacion`
-- ----------------------------
DROP TABLE IF EXISTS `parametria_aplicacion`;
CREATE TABLE `parametria_aplicacion` (
  `parametria_id` int(11) NOT NULL AUTO_INCREMENT,
  `parametria_nombre` varchar(255) NOT NULL,
  `parametria_valor` varchar(255) NOT NULL,
  PRIMARY KEY (`parametria_id`)
) ENGINE=InnoDB AUTO_INCREMENT=40 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Records of `parametria_aplicacion`
-- ----------------------------
BEGIN;
INSERT INTO `parametria_aplicacion` VALUES ('1', 'AGILE_DOMAIN', 'conectatepublicar'), ('2', 'AGILE_USER_EMAIL', 'info@conectatepublicar.com'), ('3', 'AGILE_REST_API_KEY', 'v31tio2bk7alga4fjfgjr4ioj8'), ('4', 'Titulo', 'Administrador Publicar'), ('5', 'NombreApp', 'Publicar Conectar'), ('6', 'formatoFecha', 'Y-m-d'), ('7', 'formatoFechaAgile', 'm/d/Y'), ('8', 'index', 'Login'), ('9', 'tokenIncentive', '1'), ('10', 'Analityc_account_email', 'plazacentral@plaza-central.iam.gserviceaccount.com'), ('11', 'Analityc_p12_key', 'File/api_analytic/Plaza central-8017b093201e.p12'), ('12', 'Analityc_ga_profile_id', '150218268'), ('13', 'uploader', '/File/uploads/'), ('14', 'claveSistema', 'Temporal2017'), ('15', 'ambiente', 'Produccion'), ('16', 'incentive', 'https://incentives.dayscript.com'), ('17', 'urlambiente', 'http://conectatepublicar.com/'), ('18', 'AGILE_JS_API_KEY', 'g9hs7vmtohbg8jkjblduhsthbi'), ('19', 'ajusteFecha', '+6 hour'), ('28', 'analitickey', 'UA-99026962-1'), ('29', 'PoweredBy', '<a style=\"width: 75%;\" href=\"http://grupo-link.com/\">Powered By Link</a>'), ('30', 'recaptcha', '6Ld10CcUAAAAAO-2uDWOrWcuGIMhEAJ6iS4y38E5'), ('31', 'ruteLogo', 'File/img/logo.png'), ('32', 'formatoFechaHora', 'Y-m-d'), ('33', 'urlServicesDrupal', 'http://conectatepublicar.com/usuarios'), ('34', 'usuarioServicesDrupal', 'admin'), ('35', 'claveServicesDrupal', 'p0p01234'), ('36', 'jobGrupo', '1'), ('37', 'jobTest', '1'), ('38', 'jobGeneral', '-1'), ('39', 'jobActivos', '1');
COMMIT;

-- ----------------------------
--  Table structure for `parametria_incentive`
-- ----------------------------
DROP TABLE IF EXISTS `parametria_incentive`;
CREATE TABLE `parametria_incentive` (
  `cargo_id` int(11) NOT NULL,
  `incentive_id_renovacion` int(11) DEFAULT NULL,
  `incentive_id_nueva` int(11) DEFAULT NULL,
  `incentive_id_ventas` int(11) DEFAULT NULL,
  `incentive_id_citas` int(11) NOT NULL,
  `incentive_id_conocimiento` int(11) NOT NULL,
  `incentive_id_grupo` int(11) NOT NULL,
  `incentive_fechainicio` date NOT NULL,
  `incentive_fechafin` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Records of `parametria_incentive`
-- ----------------------------
BEGIN;
INSERT INTO `parametria_incentive` VALUES ('1', '1', '2', null, '3', '4', '5', '2017-07-01', '2017-07-31'), ('2', '6', '7', null, '8', '9', '10', '2017-07-01', '2017-07-31'), ('8', '11', '12', null, '13', '14', '15', '2017-07-01', '2017-07-31'), ('3', '16', '17', null, '18', '19', '20', '2017-07-01', '2017-07-31'), ('4', '21', '22', null, '23', '24', '25', '2017-07-01', '2017-07-31'), ('6', '26', '27', null, '28', '29', '30', '2017-07-01', '2017-07-31'), ('7', '31', '32', null, '33', '34', '35', '2017-07-01', '2017-07-31'), ('1', null, null, '36', '37', '38', '39', '2017-08-01', '2017-12-31'), ('2', null, null, '40', '41', '42', '43', '2017-08-01', '2017-12-31'), ('3', null, null, '44', '45', '46', '47', '2017-08-01', '2017-12-31'), ('4', null, null, '48', '49', '50', '51', '2017-08-01', '2017-12-31'), ('5', null, null, '52', '53', '54', '55', '2017-08-01', '2017-12-31'), ('6', null, null, '56', '57', '58', '59', '2017-08-01', '2017-12-31'), ('7', null, null, '60', '61', '62', '63', '2017-08-01', '2017-12-31'), ('8', null, null, '64', '65', '66', '67', '2017-08-01', '2017-12-31');
COMMIT;

-- ----------------------------
--  Table structure for `produccion_cumplimiento`
-- ----------------------------
DROP TABLE IF EXISTS `produccion_cumplimiento`;
CREATE TABLE `produccion_cumplimiento` (
  `cumplimiento_id` int(11) NOT NULL AUTO_INCREMENT,
  `usuario_id` int(11) NOT NULL,
  `tipocumplimiento_id` int(11) NOT NULL,
  `cumplimiento_porcentaje` float(11,0) DEFAULT '0',
  `incentive_id` int(11) DEFAULT NULL,
  `cumplimiento_fecha` date DEFAULT NULL,
  `cumplimiento_modified` double DEFAULT '0',
  `cumplimiento_weighed` double DEFAULT '0',
  PRIMARY KEY (`cumplimiento_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12227 DEFAULT CHARSET=utf8 ROW_FORMAT=COMPACT;

-- ----------------------------
--  Records of `produccion_cumplimiento`
-- ----------------------------
BEGIN;
