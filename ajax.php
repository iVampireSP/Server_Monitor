<?php
require_once 'config.php';
switch ($_REQUEST['type']) {
    case 'server':
        // 获取服务器列表
        $sql = "SELECT `id`, `name`, `ip_addr`, `last_update`, `cpu`, `mem` FROM `hosts`";
        $result = $db_con->query($sql);
        while ($row = mysqli_fetch_array($result)) {
            $id = $row['id'];
            $name = $row['name'];
            $last = $row['last_update'];
            $cpu = $row['cpu'];
            $mem = $row['mem'];
            if ($row['cpu'] > 70) {
                $status = 'warning';
                $status_tip = '服务器压力较大';
                $status_tip_end = '压力较大' . "<script type=\"text/javascript\">mdui.snackbar({
            message: '$name 压力较大',
            position: 'bottom',
            buttonText: '查看',
            onButtonClick: function(){
                $('html,body').animate({scrollTop:$('#server-$id').offset().top});
              }
          });</script>";
            } elseif ($row['mem'] > 70) {
                $status = 'warning';
                $status_tip = '服务器内存占用较高';
                $status_tip_end = '内存占用较高' . "<script type=\"text/javascript\">mdui.snackbar({
            message: '$name 服务器内存占用较高',
            position: 'bottom',
            buttonText: '查看',
            onButtonClick: function(){
                $('html,body').animate({scrollTop:$('#server-$id').offset().top});
              }
          });</script>";
            } else {
                $status = 'check';
                $status_tip = "负载正常";
                $status_tip_end = '正常';
            }

            echo <<<EOF
                    <!--  Start Server-$id -->
                            <div id="server-$id-status">
                                <h2 class="mdui-text-color-blue mdui-text-center text server-name" id="server-$id">$name</h2>
                                <div id="server-$id-status-table">
                                    <div class="mdui-table-fluid">
                                        <table class="mdui-table mdui-table-hoverable">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>CPU占用</th>
                                                    <th>内存</th>
                                                    <th>最后提交</th>
                                                    <th>负载情况</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td></td>
                                                    <td id="server-$id-status-cpu">$cpu%</td>
                                                    <td id="server-$id-status-memory">$mem%</td>
                                                    <td id="server-$id-status-lastupdate">$last</td>
                                                    <td id="server-$id-status-available"><i mdui-tooltip="{content: '$status_tip', position: 'bottom'}" class="mdui-icon material-icons">$status</i>&nbsp;$status_tip_end</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                                <div id="server-$id-status-image" class="server-status-image">
                                </div>
                            </div>
                            <script type="text/javascript">
                            $(document).ready(function() {
                            var title = {
                                text: '服务器资源占用图表'
                            };
                            var yAxis = {
                                title: {
                                    text: '资源占用(%)'
                                },
                                plotLines: [{
                                    value: 0,
                                    width: 1,
                                    color: '#808080'
                                }]
                            };
                            var tooltip = {
                                valueSuffix: '%'
                            };
                            var legend = {
                                layout: 'vertical',
                                align: 'right',
                                verticalAlign: 'left',
                                borderWidth: 0
                            };
                            var series = [{
                                name: 'CPU',
                                data: [
EOF;
            // 取最后十条记录
            $sql1 = "SELECT `cpu` FROM `resource_stat` WHERE `by_host` = $id ORDER BY `submit_time` DESC LIMIT 10";
            $result1 = $db_con->query($sql1);
            while ($row1 = mysqli_fetch_array($result1)) {
                echo $row1['cpu'];
                echo ',';
            }

            echo ']
},{name: \'内存\',
    data: [';
            $sql5 = "SELECT `memory` FROM `resource_stat` WHERE `by_host` = $id ORDER BY `submit_time` DESC LIMIT 10";
            $result5 = $db_con->query($sql5);
            while ($row5 = mysqli_fetch_array($result5)) {
                echo $row5['memory'];
                echo ',';
            }

            echo ']}];';
            echo "
                        var xAxis = {
                            categories: [";

            $sql2 = "SELECT * FROM `resource_stat` WHERE `by_host` = $id ORDER BY `resource_stat`.`submit_time` DESC LIMIT 10";
            $result2 = $db_con->query($sql2);
            while ($row2 = mysqli_fetch_array($result2)) {
                echo "'";
                echo $row2['submit_time'];
                echo "',";
            }
            echo ']' . PHP_EOL . '};';
            echo '
                        var json = {};
                        json.title = title;
                        json.xAxis = xAxis;
                        json.yAxis = yAxis;
                        json.tooltip = tooltip;
                        json.legend = legend;
                        json.series = series;
                        ';
            echo " $('#server-$id-status-image').highcharts(json);";
            echo "})</script>
                        <!-- End Server-$id -->";
        }
        break;
    case 'menu':
        // 获取菜单
        $sql = "SELECT `id`, `name` FROM `hosts`";
        $result = $db_con->query($sql);
        while ($row = mysqli_fetch_array($result)) {
            $id = $row['id'];
            $name = $row['name'];
            echo <<<EOF
                        <li class="mdui-list-item mdui-ripple"><i class="mdui-list-item-icon mdui-icon material-icons"></i>
                            <div class="mdui-list-item-content"><a href="#server-$id">$name</a></div>
                        </li>
EOF;
        }
    default:
break;
}
