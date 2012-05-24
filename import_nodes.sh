#!/bin/bash
mysqlimport -u fred -p --local lloyd launchpad_node.csv --ignore-lines=0 --fields-terminated-by=',' --lines-terminated-by='\n' --columns='lp_node_str'

mysqlimport -u fred -p --local lloyd launchpad_edge.csv --ignore-lines=0 --fields-terminated-by=',' --lines-terminated-by='\n' --columns='lp_node1_id, lp_node2_id'
