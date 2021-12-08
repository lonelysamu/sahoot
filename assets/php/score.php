<?php
require_once "db_con.php";
require_once "vs_php.php";

$time = [];
$time[] = ["step" => "init", "time" => microtime(true), "del" => 0];

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['a'])) {
    exit();
}


$mysql = new MySQL($dbc);

$time[] = ["step" => "mysql_init", "time" => microtime(true), "del" => $time[count($time) - 1]['time'] - microtime(true)];

switch ($_POST['a']) {
    case "REG":
        $find = $mysql->GetOneItem(
            "SELECT s_id FROM score WHERE s_phone = ?  AND s_set = ? AND s_date",
            "ss",
            [$_POST['phone'], $_POST['set']]
        );

        $time[] = ["step" => "mysql_find", "time" => microtime(true), "del" => $time[count($time) - 1]['time'] - microtime(true)];

        empty($find) ?
            exitStatus("OK", ['status' => true, "time" => $time]) :
            exitStatus("OK", ['status' => false, "reason" => "USER_EXISTS", "time" => $time]);
        break;
    case "SCORE":
        // $find = $mysql->GetOneItem(
        //     "SELECT s_id FROM score WHERE s_phone = ?  AND s_set = ?",
        //     "ss",
        //     [$_POST['phone'], $_POST['set']]
        // );

        // $time[] = ["step" => "mysql_find", "time" => microtime(true), "del" => $time[count($time) - 1]['time'] - microtime(true)];

        // if (!empty($find)) {
        //     exitStatus("OK", ['status' => false, "USER_EXISTS"]);
        // }

        // check score
        $score = 0;
        $debug = [];
        $max = 10;
        $answers = [
            "set1" => [1, 2, 0, 1, 0, 2, 1, 3, 3, 0],
            "set2" => [2, 2, 1, 2, 1, 3, 2, 0, 1, 1]
        ];
        $_POST['ans'] = explode(",",$_POST['ans']);

        for ($i = 0; $i < $max; $i++) {
            $score += ($_POST['ans'][$i] == $answers[$_POST['set']][$i]) ? 1 : 0;
            $debug[] = ["user" => $_POST['ans'][$i], "system" => $answers[$_POST['set']][$i]];
            $time[] = ["step" => "checkScore-" . $i, "time" => microtime(true), "del" => $time[count($time) - 1]['time'] - microtime(true)];
        }

        $addScore = $mysql->Exec_Prepared(
            "INSERT INTO score (s_phone,s_name,s_score,s_set) VALUES (?,?,?,?) ",
            "ssis",
            [$_POST['phone'], $_POST['name'], $score,$_POST['set']]
        );

        $time[] = ["step" => "mysql_add", "time" => microtime(true), "del" => $time[count($time) - 1]['time'] - microtime(true)];

        is_numeric($addScore) ?
            exitStatus("OK", ['status' => true, "time" => $time]) :
            exitStatus("OK", ['status' => false, "reason" => $addScore]);
        break;
}
