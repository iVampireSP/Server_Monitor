<?php
require_once 'config.php';
?>

<!DOCTYPE html>
<html>

<head>
    <title>Monitor By iVampireSP</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/mdui@1.0.0/dist/css/mdui.min.css" integrity="sha384-2PJ2u4NYg6jCNNpv3i1hK9AoAqODy6CdiC+gYiL2DVx+ku5wzJMFNdE3RoWfBIRP" crossorigin="anonymous" />
    <link rel="icon" href="https://nwl.im/avatar" />
    <link rel="apple-touch-icon" href="https://nwl.im/avatar" />
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.4.1/jquery.min.js"></script>
    <script type="text/javascript" src="https://cdn.bootcdn.net/ajax/libs/highcharts/8.2.2/highcharts.min.js"></script>
    <style type="text/css">
        #menu .mdui-list-item,
        .mdui-collapse-item-header {
            border-radius: 0px 50px 50px 0px;
        }

        #tree .mdui-list-item,
        .mdui-collapse-item-header {
            border-radius: 0px 50px 50px 0px;
        }

        #menu .mdui-list-item-content {
            font-size: unset
        }

        #tree .mdui-list-item-content {
            font-size: unset
        }

        .server-status-image {
            margin-top: 15px;
        }

        .text {
            font-weight: unset;
            position: relative;
            top: 4px;
        }

        .server-name {
            padding: 80px 0 0 0
        }
    </style>
</head>

<body class="mdui-container mdui-drawer-body-left mdui-appbar-with-toolbar mdui-theme-accent-blue">
    <header class="mdui-appbar-fixed mdui-appbar">
        <div class="mdui-color-theme mdui-toolbar" style="background-color: white">
            <span style="border-radius: 100%" class="mdui-btn mdui-btn-icon mdui-ripple mdui-ripple-white" mdui-drawer="{target: '#main-drawer', swipe: true}"><i class="mdui-icon material-icons">menu</i></span>
            <span class="mdui-typo-headline mdui-hidden-xs">Monitor</span>
            <span class="mdui-typo-title" id="subTitle">资源监视器</span>
            <span onclick="change_style()" style="position: absolute; right: 5px; border-radius: 100%" mdui-tooltip="{content: '日夜配色', position: 'bottom'}" class="mdui-btn mdui-btn-icon mdui-ripple mdui-ripple-white"><i class="mdui-icon material-icons">&#xe3a9;</i></span>
        </div>
        <div id="topload" style="display: none; position: fixed; top: 0;" class="mdui-progress">
            <div class="mdui-progress-indeterminate"></div>
        </div>
    </header>
    <div class="mdui-drawer" id="main-drawer">
        <ul class="mdui-list">
            <div id="menu">
                <li class="mdui-list-item mdui-ripple"><i class="mdui-list-item-icon mdui-icon material-icons">event_note</i>
                    <div class="mdui-list-item-content"><a href="#">林资源监视器</a></div>
                </li>
            </div>
            <li class="mdui-subheader">服务器林</li>
            <div id="tree">
            </div>
        </ul>
    </div>
    <script type="text/javascript" src="https://cdn.jsdelivr.net/npm/mdui@1.0.0/dist/js/mdui.min.js" integrity="sha384-aB8rnkAu/GBsQ1q6dwTySnlrrbhqDwrDnpVHR2Wgm8pWLbwUnzDcIROX3VvCbaK+" crossorigin="anonymous"></script>
    <div id="mainContent">
        <h1 class="mdui-text-color-blue text">林资源监视器</h1>
        <p>资源监视器将于每周1的01:30分重置所有数据，如果有需要，请务必提前截图保存数据。</p>
        <div id="statusContent">
            <div class="mdui-progress">
                <div class="mdui-progress-indeterminate"></div>
            </div>
        </div>
    </div>
    <div style="width: 180px; height: 20px; margin: 0px auto;padding-top: 1rem;margin-bottom: 30px;margin-top: 30px;"><span style="color: gray;">由&nbsp;<a href="https://ivampiresp.com/2020/12/08/%e7%ae%80%e6%98%93%e7%9a%84%e6%9c%8d%e5%8a%a1%e5%99%a8%e7%9b%91%e6%8e%a7%e7%a8%8b%e5%ba%8f%ef%bc%9aserver-monitor.html" style="text-decoration: none;color:inherit">iVampireSP.com</a></span></div>
    <!-- 如果你没有做任何功能更新的话，能保留上方的链接吗？算是我的一个小小的心愿吧。 -->

    <script type="text/javascript">
        // 日夜配色
        function change_style() {
            $('body').toggleClass('mdui-theme-layout-dark');
        }
        /* 自动日夜模式 */
        // 获取当前时间
        let timeNow = new Date();
        // 获取当前小时
        let hours = timeNow.getHours();
        // 判断当前时间段
        if (hours >= 0 && hours <= 10) {
            $('body').removeClass('mdui-theme-layout-dark');
            $('#bottom_bar').removeClass('bottom-night');
        } else if (hours > 18 && hours <= 24) {
            $('body').addClass('mdui-theme-layout-dark');
            $('#bottom_bar').addClass('bottom-night');
        }
        htmlobj = $.ajax({
            url: "ajax.php?type=server",
            async: false,
            complete: mdui.mutation(),
        });
        $("#statusContent").html(htmlobj.responseText);

        htmlobj = $.ajax({
            url: "ajax.php?type=menu",
            async: false,
        });
        $("#tree").html(htmlobj.responseText);

        mdui.mutation();
        setInterval(function() {
            $('#topload').show();
            setTimeout(function() {
                htmlobj1 = $.ajax({
                url: "ajax.php?type=server",
                async: false,
            });
            $("#statusContent").html(htmlobj1.responseText);

            htmlobj2 = $.ajax({
                url: "ajax.php?type=menu",
                async: false,
            });
            $("#tree").html(htmlobj2.responseText);
            $('#topload').hide();
            }, 500);
        }, 15000);
    </script>

</body>

</html>
