<?php
    session_start();
    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    $db = new pdo('mysql:dbname=Calendar;host=localhost', 'sulyvahn', '1234');
    $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    $upd_tasks = $db->query("SELECT `id`, `status` FROM `tasks`")->fetchAll($mode=PDO::FETCH_ASSOC);
    $check_mode = $_SESSION['status_type'];
    foreach ($upd_tasks as $i) {
        if (in_array($i['id'], array_keys($_POST))) {
            if ($i['status'] == '0') {
                $stmt = "UPDATE `tasks` SET `status` = '1' WHERE :id = `id` LIMIT 1";
                $db->prepare($stmt)->execute(array('id' => $i['id']));
            }
        }
        else {
            if ($i['status'] == '1' && ($check_mode == '3' || $check_mode == '4')) {
                $stmt = "UPDATE `tasks` SET `status` = '0' WHERE :id = `id` LIMIT 1";
                $db->prepare($stmt)->execute(array('id' => $i['id']));
            }
        }
    }
    header("Location: ../index.php"); 