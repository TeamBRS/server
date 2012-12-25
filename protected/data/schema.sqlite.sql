CREATE TABLE tbl_user (
    id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
    username VARCHAR(128) NOT NULL,
    password VARCHAR(128) NOT NULL,
    email VARCHAR(128) NOT NULL
);

CREATE TABLE tbl_query_history (
	id INTEGER NOT NULL PRIMARY KEY AUTOINCREMENT,
	userid INTEGER NOT NULL FOREIGN KEY REFERENCES tbl_user(id),
	timestamp VARCHAR(64) NOT NULL,
	location VARCHAR(128) NOT NULL,
	minrating INTEGER,
	cuisinepref VARCHAR(128),
	socialpref VARCHAR(128)
);

INSERT INTO tbl_user (username, password, email) VALUES ('test1', 'pass1', 'test1@example.com');
INSERT INTO tbl_user (username, password, email) VALUES ('test2', 'pass2', 'test2@example.com');
INSERT INTO tbl_user (username, password, email) VALUES ('test3', 'pass3', 'test3@example.com');
INSERT INTO tbl_query_history (userid,  timestamp, location, minrating, cuisinepref, socialpref) VALUES (21, date('now'), "latlong", 3, "chinese,indian", "twitter");
