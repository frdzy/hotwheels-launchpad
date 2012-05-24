<?php
require_once('pdo_connect.php');
require_once('post_get.php');


function check_pwd ($pwd) {
}

function type_to_data ($type) {
  $res = array('$dim' => '9');

  switch ($type) {
    case "start":
      $res['$color'] = "#0000FF";
      $res['$type'] = "square";
      break;
    case "end":
      $res['$color'] = "#FF0000";
      $res['$type'] = "square";
      break;
    default:
      $res['$color'] = "#FFCC00";
      $res['$type'] = "circle";
      break;
  }

  return $res;
}

function init_game($pwd) {
  $db = lloyd_db_connect();
  $pwd = strip_string($pwd);
  $init_exec = "CALL password_check('$pwd')";
  $res = $db->query($init_exec)->fetch();
  error_log("derpderp ".print_r($res, true));

  if ($res != null) {
    $graph_init_exec = "SELECT * FROM launchpad_init;";
    $graph_init_query = $db->query($graph_init_exec);

    $nodes_index = array();
    $nodes_json = array('lp_team_id' => $res[0]);
    foreach ($graph_init_query as $row) {
      error_log(print_r($row, true));
      $lp_node_id = $row['lp_node_id'];
      $lp_node2_id = $row['lp_node2_id'];
      $lp_node_type = $row['lp_node_type'];
      error_log("stuff $lp_node_id, $lp_node2_id, $lp_node_type");
      $node_info = null;

      if (in_array($lp_node_id, $nodes_index)) {
        // error_log("found $lp_node_id in nodes_index");
        $node_info = $nodes_json[array_search($lp_node_id, $nodes_index)];
        if ($lp_node2_id != null) {
          // error_log("adding adjs to $lp_node2_id");
          $nodes_json[array_search($lp_node_id, $nodes_index)]["adjacencies"][] =
            array(
              "nodeFrom" => $lp_node_id,
              "nodeTo" => $lp_node2_id,
              "data" => array('$color' => "#FFFFFF")
            );
        }
      }
      else {
        error_log("new $lp_node_id in nodes_index");
        $node_info = array();
        assert(!isset($node_info["adjacencies"]));
        $node_info["adjacencies"] = array();
        $node_info["data"] = type_to_data($lp_node_type);
        $node_info["id"] = $lp_node_id;
        $node_info["name"] = "*";
        $nodes_index[] = $lp_node_id;
        if ($lp_node2_id != null) {
          error_log("adding adjs to $lp_node2_id");
          $node_info["adjacencies"][] =
            array(
              "nodeFrom" => $lp_node_id,
              "nodeTo" => $lp_node2_id,
              "data" => array('$color' => "#FFFFFF")
            );
        }
        $nodes_json[] = $node_info;
      }
    }

    $res = $nodes_json;
  }

  $db = null;
  error_log("final" . print_r($res, true));
  return $res;
}

function reveal($pwd) {
  $db = lloyd_db_connect();
  $reveal_query = "select * from launchpad_fringe";
  $graph_reveal_query = $db->query($reveal_query);
  $reveal_json = array();
  foreach ($graph_reveal_query as $row) {
    $reveal_json[] = array($row['lp_node_id'], $row['lp_node_str_scrambled']);
  }

  error_log("reveal = ");
  error_log(print_r($reveal_json, true));

  return $reveal_json;
}

function enter_val ($val) {
  $db = lloyd_db_connect();
  $enter_query = "select enter_val(1, '$val');";
  $enter_result = $db->query($enter_query)->fetch();
  error_log("enterresult =$enter_result");
  if ($enter_result[0] === '0')
    return null;

  return $enter_result[0];
}

