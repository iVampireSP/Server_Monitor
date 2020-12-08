<?php
require_once 'config.php';

if($_REQUEST['token'] == TOKEN) {
    if($_REQUEST['type'] == 'clear') {
        $sql = "TRUNCATE `status_lo_li_art`.`hosts`";
        $db_con->query($sql);
        $sql = "TRUNCATE `status_lo_li_art`.`resource_stat`";
        $db_con->query($sql);
    }
}
