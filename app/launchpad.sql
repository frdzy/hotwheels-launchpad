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

  PRIMARY KEY (`lp_node_id`)
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

  PRIMARY KEY (`lp_team_id`),
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

--
-- Functions
--

DROP PROCEDURE IF EXISTS first_login;
DROP PROCEDURE IF EXISTS enter_val;

DELIMITER |
CREATE PROCEDURE first_login (password_str VARCHAR(8))
BEGIN
  SELECT @team_id:=`lp_team_id`, @node_id:=`lp_reveal_node_id`, @str:=`lp_node_str`
  FROM `launchpad_password` JOIN `launchpad_node` ON `lp_reveal_node_id` = `lp_node_id`
  WHERE `lp_password_str` LIKE password_str;
  INSERT IGNORE INTO launchpad_progress(`lp_team_id`, `lp_node_id`)
    VALUE(@team_id, @node_id);
  SELECT @team_id AS lp_team_id, @node_id AS lp_reveal_nod_id, @str AS lp_node_str;
END |

CREATE PROCEDURE enter_val (team_id INT, fringe_str VARCHAR(8))
BEGIN
  SELECT *, IF(`lp_progress_ts` IS NOT NULL,
               `lp_node_str`,
               regex_replace('[A-Za-z0-9]', '?', `lp_node_str`)) AS lp_node_str
  FROM `launchpad_node` NATURAL LEFT JOIN `launchpad_progress` NATURAL JOIN `launchpad_password`
  WHERE lp_password_str LIKE fringe_str;
END |

DELIMITER ;

--
-- Initial data import
--

INSERT INTO `launchpad_node` (`lp_node_str`) VALUE ('test1 test2');
INSERT INTO `launchpad_node` (`lp_node_str`) VALUE ('test10 test20');
INSERT INTO `launchpad_team` (`lp_team_name`) VALUE ('team1');
INSERT INTO `launchpad_password` (`lp_password_str`, `lp_team_id`, `lp_reveal_node_id`)
  VALUE ('abc', 1, 1);
-- INSERT INTO `launchpad_progress` (`lp_team_id`, `lp_node_id`) VALUE (1, 1);
