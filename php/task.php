<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');
    $db = new pdo('mysql:dbname=Calendar;host=localhost', 'sulyvahn', '1234');
    $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
    $task_data = $_POST;

    //get type_id
    $sql = "SELECT `id` FROM `types` WHERE `type` = '" . $task_data['type'] . "' LIMIT 1;"; 
    $type_id = $db->query($sql)->fetch(PDO::FETCH_NUM)[0];
    
    //get timestamp
    $dt_str = $task_data['date'] . ' ' . $task_data['time'];
    $dt = date_create($dt_str);

    //get duration in minutes
    $task_data["duration"] = $task_data["duration"] == '' ? 0 : strval($task_data["duration"]);
    if ($task_data["duraion_type"] = '2') {
        $task_data["duration"] *= 60;
    }

    $stmt = "INSERT INTO `tasks`(
        `name`, 
        `type_id`, 
        `place`,
        `dt`,
        `duration`,
        `comment`,
        `status`
        ) 
        VALUES (
        :name,
        :type_id,
        :place,
        :dt,
        :duration,
        :comment,
        :status
        );";

    $temp = $db->prepare($stmt);

    $temp -> execute(array(
        'name' => $task_data['name'],
        'type_id' => $type_id,
        'place' => $task_data['place'],
        'dt' => date_format($dt, 'Y-m-d H:i'),
        'duration' => $task_data['duration'],
        'comment' => $task_data['comment'],
        'status' => 0
    ));

    header("Location: ../index.php"); 