USE lloyd;

--
-- Base tables
--

DROP TABLE IF EXISTS `launchpad_node`;
CREATE TABLE `launchpad_node` (
  `lp_node_id`        INT AUTO_INCREMENT,
  `lp_node_str`       VARCHAR(100) NOT NULL,
  `lp_node_type`      ENUM('start', 'end'),
  `lp_node_init`      BOOLEAN NOT NULL DEFAULT FALSE,

  PRIMARY KEY (`lp_node_id`),
  UNIQUE (`lp_node_str`)
);

DROP TABLE IF EXISTS `launchpad_edge`;
CREATE TABLE `launchpad_edge` (
  `lp_node1_id`       INT,
  `lp_node2_id`       INT,
  
  PRIMARY KEY (`lp_node1_id`, `lp_node2_id`),
  FOREIGN KEY (`lp_node1_id`)
    REFERENCES `launchpad_node` (`lp_node_id`),
  FOREIGN KEY (`lp_node2_id`)
    REFERENCES `launchpad_node` (`lp_node_id`)
);

DROP TABLE IF EXISTS `launchpad_team`;
CREATE TABLE `launchpad_team` (
  `lp_team_id`        INT AUTO_INCREMENT,
  `lp_team_name`      VARCHAR(20) NOT NULL,
  
  PRIMARY KEY (`lp_team_id`)
);

DROP TABLE IF EXISTS `launchpad_progress`;
CREATE TABLE `launchpad_progress` (
  `lp_team_id`        INT,
  `lp_node_id`        INT,
  `lp_progress_ts`    TIMESTAMP,

  PRIMARY KEY (`lp_team_id`, `lp_node_id`),
  FOREIGN KEY (`lp_team_id`)
    REFERENCES `launchpad_team` (`lp_team_id`),
  FOREIGN KEY (`lp_node_id`)
    REFERENCES `launchpad_node` (`lp_node_id`)
);

DROP TABLE IF EXISTS `launchpad_password`;
CREATE TABLE `launchpad_password` (
  `lp_password_str`   VARCHAR(8),
  `lp_team_id`        INT NOT NULL,
  `lp_reveal_node_id` INT,

  PRIMARY KEY (`lp_password_str`),
  FOREIGN KEY (`lp_team_id`)
    REFERENCES `launchpad_team` (`lp_team_id`),
  FOREIGN KEY (`lp_reveal_node_id`)
    REFERENCES `launchpad_node` (`lp_node_id`)
);

DROP VIEW IF EXISTS `launchpad_init`;
CREATE VIEW `launchpad_init` AS
  SELECT `lp_node_id`, `lp_node2_id`, `lp_node_type`
  FROM `launchpad_node` AS a LEFT OUTER JOIN `launchpad_edge` AS b ON a.lp_node_id = b.lp_node1_id;

DROP VIEW IF EXISTS `launchpad_partial`;
CREATE VIEW `launchpad_partial` AS
  SELECT `lp_node_id`, regex_replace('[A-Za-z0-9]', '?', `lp_node_str`) AS `lp_node_str`
  FROM `launchpad_node`;

DROP VIEW IF EXISTS `launchpad_fringe`;
CREATE VIEW `launchpad_fringe` AS
  SELECT c.lp_team_id, f.lp_node_id, p.lp_node_str AS lp_node_str_scrambled
  FROM `launchpad_node` AS f JOIN `launchpad_edge` AS b ON f.lp_node_id = b.lp_node1_id
    JOIN `launchpad_progress` AS c ON c.lp_node_id = b.lp_node2_id
    JOIN `launchpad_partial` AS p ON f.lp_node_id = p.lp_node_id
  WHERE (c.lp_team_id, f.lp_node_id) NOT IN (SELECT lp_team_id, lp_node_id FROM launchpad_progress)
  UNION
  SELECT c.lp_team_id, f.lp_node_id, p.lp_node_str AS lp_node_str_scrambled
  FROM `launchpad_node` AS f JOIN `launchpad_edge` AS b ON f.lp_node_id = b.lp_node2_id
    JOIN `launchpad_progress` AS c ON c.lp_node_id = b.lp_node1_id
    JOIN `launchpad_partial` AS p ON f.lp_node_id = p.lp_node_id
  WHERE (c.lp_team_id, f.lp_node_id) NOT IN (SELECT lp_team_id, lp_node_id FROM launchpad_progress)
  ORDER BY lp_team_id;

--
-- Functions
--


DELIMITER |

DROP PROCEDURE IF EXISTS password_check|
CREATE PROCEDURE password_check (password_str VARCHAR(8))
BEGIN
  SELECT @team_id:=`lp_team_id` AS lp_team_id
  FROM `launchpad_password`
  WHERE `lp_password_str` LIKE password_str;
END |

DROP PROCEDURE IF EXISTS first_reveal|
CREATE PROCEDURE first_reveal (password_str VARCHAR(8))
BEGIN
  SELECT @team_id:=`lp_team_id` AS lp_team_id,
    @str:=`lp_node_str` AS lp_node_str
  FROM `launchpad_password` JOIN `launchpad_node` ON `lp_reveal_node_id` = `lp_node_id`
  WHERE `lp_password_str` LIKE password_str;
  INSERT IGNORE INTO launchpad_progress(`lp_team_id`, `lp_node_id`)
    VALUE(@team_id, @node_id);
END |

DROP FUNCTION IF EXISTS enter_val|
CREATE FUNCTION enter_val (team_id INT, fringe_str VARCHAR(100)) RETURNS INT
BEGIN
  DECLARE success INT;
  SET @intermediate = NULL;
  SELECT `lp_node_id` INTO @intermediate
  FROM launchpad_fringe NATURAL JOIN launchpad_node
  WHERE `lp_node_str` = fringe_str AND `lp_team_id` = team_id;
  IF @intermediate IS NOT NULL THEN
    SET success = @intermediate;
    INSERT INTO launchpad_progress(`lp_team_id`, `lp_node_id`)
      VALUE(team_id, success);
  ELSE
    SET success = 0;
  END IF;
  RETURN success;
END |

DROP PROCEDURE IF EXISTS last_correct|
CREATE PROCEDURE last_correct (team_id INT)
BEGIN
  SELECT `lp_progress_ts`
  FROM `launchpad_progress`
  WHERE `lp_team_id` = team_id
  ORDER BY lp_progress_ts DESC
  LIMIT 1;
END |

DELIMITER ;

--
-- Initial data import
--

INSERT INTO `launchpad_node` (`lp_node_str`) VALUES
  ('test1 test2'),
  ('test10 test20'),
  ('test203'),
  ('test204')
;

INSERT INTO `launchpad_edge` (`lp_node1_id`, `lp_node2_id`) VALUES
  (1, 2),
  (2, 3)
;

INSERT INTO `launchpad_team` (`lp_team_name`) VALUE ('team1');
INSERT INTO `launchpad_password` (`lp_password_str`, `lp_team_id`, `lp_reveal_node_id`)
  VALUE ('abc', 1, 1);
INSERT INTO `launchpad_progress` (`lp_team_id`, `lp_node_id`) VALUE (1, 1);
SELECT enter_val (1, 'test10 test20');
