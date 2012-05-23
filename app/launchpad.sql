USE lloyd;

DROP TABLE IF EXISTS `launchpad_node`;
CREATE TABLE `launchpad_node` (
  `lp_node_id`        INT AUTO_INCREMENT,
  `lp_strval`         VARCHAR(100) NOT NULL,
  `lp_type`           ENUM('start', 'end'),
  `lp_init_ex`        BOOLEAN NOT NULL DEFAULT FALSE,

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
  `lp_node_id`      INT,
  `lp_progress_ts`    TIMESTAMP,

  PRIMARY KEY (`lp_team_id`),
  FOREIGN KEY (`lp_team_id`)
    REFERENCES `launchpad_team` (`lp_team_id`),
  FOREIGN KEY (`lp_node_id`)
    REFERENCES `launchpad_node` (`lp_node_id`)
);

INSERT INTO `launchpad_node` (`lp_strval`) VALUE ('test1 test2');

SELECT *, IF(`lp_init_ex`,
             `lp_strval`,
             PREG_REPLACE('/[A-Za-z0-9]/', '?', `lp_strval`)) AS lp_strval
FROM `launchpad_node`;
