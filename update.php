<?php
require_once 'config.php';
error_reporting(0);
set_error_handler('error_report');
// custom handler code
function error_report() {
    echo 'Invalid request, the following return will not take effect: ';
}
// 筛选请求
switch ($_REQUEST['type']) {
    case 'update':
        // 获取名称
        $servername = mysqli_real_escape_string($db_con, $_REQUEST['servername']);
        // 获取IP
        $serverip = $_SERVER['HTTP_X_FORWARDED_FOR'];

        // CPU百分比
        $servercpu = mysqli_real_escape_string($db_con, $_REQUEST['cpu']);
        // 内存百分比
        $servermem = mysqli_real_escape_string($db_con, $_REQUEST['mem']);

        // 判断服务器是否存在
        $sql = "SELECT `name` FROM `hosts` WHERE `hosts`.`name` = '$servername'";
        $result = $db_con->query($sql);
        if (!mysqli_num_rows($result) > 0) {
            // 不存在，则创建
            $sql = "INSERT INTO `hosts` (`id`, `name`, `ip_addr`, `last_update`, `cpu`, `mem`) VALUES (NULL, '$servername', '$serverip', '$datetime', '$servercpu', '$servermem');";
            $db_con->query($sql);
            echo 'The server has been added to status, logging will not start until the next request.';
        } else {
            // 已存在，写入参数

            // 获取服务器ID和监控监听端口
            $sql = "SELECT `id`, `ip_addr` FROM `hosts` WHERE `hosts`.`name` = '$servername'";
            $result = $db_con->query($sql);
            while ($row = mysqli_fetch_array($result)) {
                $serverid = $row['id'];

                $serverip = $row['ip_addr'];
            }

            // 更新hosts
            $sql = "UPDATE `hosts` SET `last_update` = '$datetime' WHERE `hosts`.`name` = '$servername'";
            $db_con->query($sql);
            $sql = "UPDATE `hosts` SET `cpu` = '$servercpu' WHERE `hosts`.`name` = '$servername'";
            $db_con->query($sql);
            $sql = "UPDATE `hosts` SET `mem` = '$servermem' WHERE `hosts`.`name` = '$servername'";
            $db_con->query($sql);
            
            // 连接http端口是否开放
            
            // 记录参数
            $sql = "INSERT INTO `resource_stat` (`id`, `by_host`, `cpu`, `memory`, `submit_time`) VALUES (NULL, '$serverid', '$servercpu', '$servermem', '$datetime')";
            $db_con->query($sql);

            // 判断当前记录的条数，如果超过10条则删除前10条
            if(mysqli_num_rows($db_con->query("SELECT `id` FROM `resource_stat` WHERE `by_host` = $serverid")) >= 20) {
                // 等于10条或大于十条，则删除前10条
                $sql = "DELETE FROM `resource_stat` WHERE `by_host` = $serverid LIMIT 10";
                $db_con->query($sql);
            }

            // 返回
            echo 'This submission has been logged!';
        }
        break;
    default:
        break;
}
