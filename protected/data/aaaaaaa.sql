BEGIN TRANSACTION;
CREATE TABLE cuisine (id INTEGER PRIMARY KEY, type VARCHAR(128), description TEXT, parent INTEGER);
INSERT INTO cuisine VALUES(1,'All','Every single possible cuisine',NULL);
CREATE TABLE sqlite_sequence(name,seq);
INSERT INTO sqlite_sequence VALUES('tbl_user',52);
INSERT INTO sqlite_sequence VALUES('tbl_query_history',708);
CREATE TABLE tbl_query_history (
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	userid VARCHAR(128) NOT NULL,
	timestamp VARCHAR(64) NOT NULL,
	location VARCHAR(128) NOT NULL,
	minrating INTEGER,
	cuisinepref VARCHAR(128),
	socialpref VARCHAR(128)
);
INSERT INTO tbl_query_history VALUES(679,'Bernie','2013-01-04 18:05:51','52.3813257@ -1.5617241',1,0,0);
INSERT INTO tbl_query_history VALUES(680,'Bernie','2013-01-04 18:08:39','52.3813257@ -1.5617241',1,0,0);
INSERT INTO tbl_query_history VALUES(681,'Bernie','2013-01-04 18:20:12','52.3813257@ -1.5617241',1,1,0);
INSERT INTO tbl_query_history VALUES(682,'Bernie','2013-01-04 18:40:24','52.3813295@ -1.5617135',1,0,0);
INSERT INTO tbl_query_history VALUES(683,'Bernie','2013-01-04 18:42:53','52.3813295@ -1.5617135',1,0,0);
INSERT INTO tbl_query_history VALUES(684,'Bernie','2013-01-04 19:06:47','52.3813295@ -1.5617135',1,0,0);
INSERT INTO tbl_query_history VALUES(685,'Bernie','2013-01-04 19:07:04','52.3813295@ -1.5617135',1,0,0);
INSERT INTO tbl_query_history VALUES(686,'Bernie','2013-01-04 19:09:32','52.3813295@ -1.5617135',1,0,0);
INSERT INTO tbl_query_history VALUES(687,'Bernie','2013-01-04 19:09:49','52.3813295@ -1.5617135',1,0,0);
INSERT INTO tbl_query_history VALUES(688,'Bernie','2013-01-04 19:10:11','52.3813295@ -1.5617135',1,0,0);
INSERT INTO tbl_query_history VALUES(689,'Bernie','2013-01-04 19:10:58','52.3813295@ -1.5617135',1,0,0);
INSERT INTO tbl_query_history VALUES(690,'Bernie','2013-01-04 19:11:14','52.3813295@ -1.5617135',1,0,0);
INSERT INTO tbl_query_history VALUES(691,'Bernie','2013-01-04 19:12:51','52.3813295@ -1.5617135',1,0,0);
INSERT INTO tbl_query_history VALUES(692,'Bernie','2013-01-04 19:13:35','52.3813295@ -1.5617135',1,0,0);
INSERT INTO tbl_query_history VALUES(693,'Bernie','2013-01-04 19:14:30','52.3813295@ -1.5617135',1,0,0);
INSERT INTO tbl_query_history VALUES(694,'Bernie','2013-01-04 19:14:53','52.3813295@ -1.5617135',1,0,0);
INSERT INTO tbl_query_history VALUES(695,'Bernie','2013-01-04 19:19:41','52.3813295@ -1.5617135',1,0,0);
INSERT INTO tbl_query_history VALUES(696,'Bernie','2013-01-04 19:29:48','52.3813295@ -1.5617135',1,0,0);
INSERT INTO tbl_query_history VALUES(697,'Bernie','2013-01-04 19:36:38','52.3833786@ -1.5602091999999999',1,0,2);
INSERT INTO tbl_query_history VALUES(698,'Bernie','2013-01-04 20:30:13','52.3833811@ -1.5602118',1,1,0);
INSERT INTO tbl_query_history VALUES(699,'Bernie','2013-01-04 20:33:10','52.3833811@ -1.5602118',1,1,0);
INSERT INTO tbl_query_history VALUES(700,'Bernie','2013-01-04 20:34:11','52.3833811@ -1.5602118',1,1,0);
INSERT INTO tbl_query_history VALUES(701,'Bernie','2013-01-04 20:35:21','52.3833811@ -1.5602118',1,1,0);
INSERT INTO tbl_query_history VALUES(702,'Bernie','2013-01-04 20:36:08','52.3833811@ -1.5602118',1,1,0);
INSERT INTO tbl_query_history VALUES(703,'Bernie','2013-01-04 23:00:56','52.3836544@ -1.5558001',2,0,0);
INSERT INTO tbl_query_history VALUES(704,'Robin','2013-01-05 13:47:13','52.291971499999995@ -1.5321799',1,0,0);
INSERT INTO tbl_query_history VALUES(705,'Robin','2013-01-05 14:01:01','52.291971499999995@ -1.5321799',1,0,0);
INSERT INTO tbl_query_history VALUES(706,'Robin','2013-01-05 14:08:59','52.291971499999995@ -1.5321799',1,0,0);
INSERT INTO tbl_query_history VALUES(707,'Robin','2013-01-05 14:09:20','52.291971499999995@ -1.5321799',1,0,0);
INSERT INTO tbl_query_history VALUES(708,'Robin','2013-01-05 14:10:09','52.291971499999995@ -1.5321799',1,0,0);
CREATE TABLE tbl_user (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    username VARCHAR(128) NOT NULL,
    password VARCHAR(128) NOT NULL,
    email VARCHAR(128) NOT NULL
);
INSERT INTO tbl_user VALUES(1,'Stefania','pass1','test1@example.com');
INSERT INTO tbl_user VALUES(22,'Hamar','Dobbeltrom','dfghjkl');
INSERT INTO tbl_user VALUES(23,'Stef91','win123','stef.pap@hotmail.com');
INSERT INTO tbl_user VALUES(24,'KaliIsCool','kali5','kali@cool.com');
INSERT INTO tbl_user VALUES(25,'Robin','asasas','robin.janssens@gmail.com');
INSERT INTO tbl_user VALUES(30,'test1','pass1','test1@example.com');
INSERT INTO tbl_user VALUES(31,'test2','pass2','test2@example.com');
INSERT INTO tbl_user VALUES(32,'test3','pass3','test3@example.com');
INSERT INTO tbl_user VALUES(33,'test4','pass4','test4@example.com');
INSERT INTO tbl_user VALUES(34,'test5','pass5','test5@example.com');
INSERT INTO tbl_user VALUES(35,'test6','pass6','test6@example.com');
INSERT INTO tbl_user VALUES(36,'test7','pass7','test7@example.com');
INSERT INTO tbl_user VALUES(37,'test8','pass8','test8@example.com');
INSERT INTO tbl_user VALUES(38,'test9','pass9','test9@example.com');
INSERT INTO tbl_user VALUES(39,'test10','pass10','test10@example.com');
INSERT INTO tbl_user VALUES(40,'test11','pass11','test11@example.com');
INSERT INTO tbl_user VALUES(41,'test12','pass12','test12@example.com');
INSERT INTO tbl_user VALUES(42,'test13','pass13','test13@example.com');
INSERT INTO tbl_user VALUES(43,'test14','pass14','test14@example.com');
INSERT INTO tbl_user VALUES(44,'test15','pass15','test15@example.com');
INSERT INTO tbl_user VALUES(45,'test16','pass16','test16@example.com');
INSERT INTO tbl_user VALUES(46,'test17','pass17','test17@example.com');
INSERT INTO tbl_user VALUES(47,'test18','pass18','test18@example.com');
INSERT INTO tbl_user VALUES(48,'test19','pass19','test19@example.com');
INSERT INTO tbl_user VALUES(49,'test20','pass20','test20@example.com');
INSERT INTO tbl_user VALUES(50,'test21','pass21','test21@example.com');
INSERT INTO tbl_user VALUES(51,'Stef90','win123','bla@bla.com');
INSERT INTO tbl_user VALUES(52,'Bernie','win123','b.sexton@warwick.ac.uk');
COMMIT;
