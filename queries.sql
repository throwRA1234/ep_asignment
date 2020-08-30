USE `bootstrap`;
CREATE USER bootstrap_admin@localhost IDENTIFIED BY 'Chicomoztoc_9';
CREATE USER bootstrap@localhost IDENTIFIED BY 'insecure_passworD9';
GRANT ALL PRIVILEGES ON `bootstrap`.* TO 'bootstrap_admin'@'localhost';
GRANT SELECT ON `bootstrap`.`user_credentials` TO 'bootstrap'@'localhost';
GRANT SELECT ON `bootstrap`.`users` TO 'bootstrap'@'localhost';
GRANT SELECT ON `bootstrap`.`vacations` TO 'bootstrap'@'localhost';

USE `bootstrap`;
DROP procedure IF EXISTS `sp_add_user`;
DELIMITER $
USE `bootstrap` $
CREATE DEFINER = 'bootstrap_admin'@'localhost' PROCEDURE `sp_add_user`(fname VARCHAR(35), lname VARCHAR(35), email_address VARCHAR(35), supervisor_id VARCHAR(36), is_supervisor TINYINT(1), is_admin TINYINT(1), username VARCHAR(50), password VARCHAR(100))
SQL SECURITY DEFINER
MODIFIES SQL DATA
BEGIN
SELECT @id:=UUID();
  INSERT INTO users (id, fname, lname, email_address, supervisor_id, is_supervisor, is_admin, date_entered, date_modified, user_name) VALUES (@id, fname, lname, email_address, supervisor_id, is_supervisor, is_admin, NOW(), NOW(), username);
  INSERT INTO user_credentials (user_id, password) VALUES (@id, password);
END$
DELIMITER ;

GRANT EXECUTE ON PROCEDURE `bootstrap`.`sp_add_user` TO 'bootstrap'@'localhost';

USE `bootstrap`;
DROP procedure IF EXISTS `sp_delete_user`;
DELIMITER $
USE `bootstrap` $
CREATE DEFINER = 'bootstrap_admin'@'localhost' PROCEDURE `sp_delete_user`(username VARCHAR(50))
SQL SECURITY DEFINER
MODIFIES SQL DATA
BEGIN
  SELECT @id:=(SELECT id FROM users WHERE user_name = username);
  DELETE FROM users WHERE user_name = username;
  DELETE FROM user_credentials WHERE user_id = @id;
END$
DELIMITER ;

GRANT EXECUTE ON PROCEDURE `bootstrap`.`sp_delete_user` TO 'bootstrap'@'localhost';

USE `bootstrap`;
DROP procedure IF EXISTS `sp_update_user`;
DELIMITER $
USE `bootstrap` $
CREATE DEFINER = 'bootstrap_admin'@'localhost' PROCEDURE `sp_update_user`(username VARCHAR(50), fname VARCHAR(35), lname VARCHAR(35), email_address VARCHAR(35), is_supervisor TINYINT(1))
SQL SECURITY DEFINER
MODIFIES SQL DATA
BEGIN
  UPDATE users SET fname = fname, lname = lname, email_address = email_address, is_supervisor = is_supervisor, date_modified = NOW() WHERE user_name = username;
END$
DELIMITER ;
GRANT EXECUTE ON PROCEDURE `bootstrap`.`sp_update_user` TO 'bootstrap'@'localhost';

USE `bootstrap`;
DROP procedure IF EXISTS `sp_create_vacation_request`;
DELIMITER $
USE `bootstrap` $
CREATE DEFINER = 'bootstrap_admin'@'localhost' PROCEDURE `sp_create_vacation_request`(uuid VARCHAR(36), requested_by_id VARCHAR(36), description TEXT, date_start TEXT, date_end TEXT)
SQL SECURITY DEFINER
MODIFIES SQL DATA
BEGIN
SELECT @from:=STR_TO_DATE(date_start, '%Y-%m-%d');
SELECT @to:=STR_TO_DATE(date_end, '%Y-%m-%d');
SELECT @numberOfDays:= 5 * (DATEDIFF(@to, @from) DIV 7) + MID('1234555512344445123333451222234511112345001234550', 7 * WEEKDAY(@from) + WEEKDAY(@to) + 1, 1);

INSERT INTO vacations (id, requested_by_id, approver_id, decision, description, date_start, date_end, number_of_days, date_entered, date_modified) 
VALUES (uuid, requested_by_id, DEFAULT, DEFAULT, description, date_start, date_end, @numberOfDays, NOW(), NOW());
END$
DELIMITER ;

GRANT EXECUTE ON PROCEDURE `bootstrap`.`sp_create_vacation_request` TO 'bootstrap'@'localhost';

USE `bootstrap`;
DROP procedure IF EXISTS `sp_update_vacation_request`;
DELIMITER $
USE `bootstrap` $
CREATE DEFINER = 'bootstrap_admin'@'localhost' PROCEDURE `sp_update_vacation_request`(uuid VARCHAR(36), decision VARCHAR(30))
SQL SECURITY DEFINER
MODIFIES SQL DATA
BEGIN
UPDATE vacations SET decision = decision, date_modified = NOW() WHERE id = uuid;
END$
DELIMITER ;

GRANT EXECUTE ON PROCEDURE `bootstrap`.`sp_update_vacation_request` TO 'bootstrap'@'localhost';