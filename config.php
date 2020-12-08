<?php
// 建议数据库连接，从左到右依次为：数据库服务器地址，数据库用户名，数据库密码，数据库名称
$db_con = new mysqli('localhost', 'db_username', 'db_passwd', 'db_name');
$datetime = date('Y-m-d H:i:s');

// 清除数据库时的密钥，建议设置随机高强度
define('TOKEN', 'xxxxxxxxxxxxx');