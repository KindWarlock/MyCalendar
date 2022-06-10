<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Мой календарь</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php 
        session_start(); 
        $_SESSION['status_type'] = $_POST['task_filter'];
        if ($_POST['task_filter'] == 4) {
            $_SESSION['dt'] = date_format(date_create($_POST['date']),'Y-m-d H:i');
        } else {
            $_SESSION['dt'] = NULL;
        }
    ?>
    <h1 class="title">Мой календарь</h1>
    <div class="wrapper">
        <div class="task">
            <h2 class="subtitle">Задача</h2>
            <form action="php/task.php" method="post" class="task__form">
                <label for="name" class="task__label">Тема:</label>
                <input type="text" name="name" class="task__input-text" id="name">
                <label for="type" class="task__label">Тип:</label>
                <select name="type" class="task__select select" id="type">
                    <option value="Дело" class="task__option option" default>Дело</option>
                    <option value="Встреча" class="task__option option">Встреча</option>
                    <option value="Звонок" class="task__option option">Звонок</option>
                    <option value="Совещание" class="task__option option">Совещание</option>
                </select>
                <label for="place" class="task__label">Место:</label>
                <input type="text" name="place" class="task__input-text" id="place">
                <label for="date" class="task__label">Дата и время:</label>
                <div class="task__dt-container dt">
                    <input type="date" name="date" id="date" class="task__input-date input-date">
                    <input type="time" name="time" class="task__input-time" value="08:00">
                </div>
                <label for="duration" class="task__label">Длительность:</label>
                <div class="task__duration-container duration">
                    <input type="number" name="duration" id="duration" class="duration__number">
                    <select name="duraion_type" class="task__select duration__select">
                        <option value="1" class="task__option duration__option">мин.</option>
                        <option value="2" class="task__option duration__option">час.</option>
                    </select>
                </div>
                <label for="comment" class="task__label task__label_textarea">Комментарий:</label>
                <textarea name="comment" id="comment" class="task__textarea" cols="30" rows="4"></textarea>
                <div></div>
                <input type="submit" value="Добавить" class="submit task__submit">
            </form>    
        </div>
    </div>
    <div class="wrapper">
        <div class="task-list">
            <h2 class="subtitle">Список задач</h2>
            <form class="task-list__filters" method="post">
                <select name="task_filter" class="task-list__select select">
                    <option value="1" <?php if ($_SESSION["status_type"] == '1') echo "selected" ?>>Текущие задачи</option>
                    <option value="2" <?php if ($_SESSION["status_type"] == '2') echo "selected" ?>>Просроченные задачи</option>
                    <option value="3" <?php if ($_SESSION["status_type"] == '3') echo "selected" ?>>Выполненные задачи</option>
                    <option value="4" <?php if ($_SESSION["status_type"] == '4') echo "selected" ?>>Выбрать дату</option>
                </select>
                <input type="date" name="date" id="date" class="task-list__select input-date">
                <input type="submit" value="Поиск" class="submit task-list__sumbit">
            </form>
            <table class="task-list__table table">
                <tr class="table__tr">
                    <th class="table__th">Статус</th>
                    <th class="table__th">Тип</th>
                    <th class="table__th">Задача</th>
                    <th class="table__th">Место</th>
                    <th class="table__th">Дата и время</th>
                </tr>
                <?php
                    error_reporting(E_ALL);
                    ini_set('display_errors', '1');
                    $db = new pdo('mysql:dbname=Calendar;host=localhost', 'sulyvahn', '1234');
                    $db->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
                    switch ($_SESSION['status_type']) {
                        case '2':
                            $sql = "SELECT * 
                            FROM `tasks` 
                            JOIN `types` USING (type_id)
                            WHERE `status` = '0' AND DATE(`dt`) < DATE(NOW());";
                            break;
                        case '3':
                            $sql = "SELECT * 
                            FROM `tasks` 
                            JOIN `types` USING (type_id)
                            WHERE `status` = '1';";
                            break;
                        case '4':
                            $sql = "SELECT * 
                            FROM `tasks` 
                            JOIN `types` USING (type_id)
                            WHERE DATE(`dt`) = '" . $_SESSION['dt'] . "';";
                            break;
                        default:
                            $sql = "SELECT * 
                            FROM `tasks` 
                            JOIN `types` USING (type_id)
                            WHERE `status` = '0' AND `dt` < NOW();";
                    }
                    $tasks = $db->query($sql)->fetchAll($mode=PDO::FETCH_ASSOC);
                    $tasks_data = array();
                    foreach ($tasks as $task) {
                        $if_checked = $task['status'] == 1 ? " checked " : "";

                        array_push($tasks_data, $task);

                        echo 
                        "<form method='post' action='php/change_status.php'><tr onclick='show_card(" . $task['id'] . ")'>
                        <td class='table_td'> 
                            <input type='checkbox' name='" . $task['id'] . "'" .
                            $if_checked . 
                            "onChange='submit()'>
                        </td>" .
                        "<td class='table_td'> " . $task['type'] . "</td>" .
                        "<td class='table_td'> " . $task['name'] . "</td>" .
                        "<td class='table_td'> " . $task['place'] . "</td>" .
                        "<td class='table_td'> " . $task['dt'] . "</td>
                        </tr></form>";
                    }
                ?>
                <script>
                    var tasks_data = <?php echo json_encode($tasks_data);?>;
                </script>
            </table>
        </div>
    </div>
    
    <script src="js/script.js">
        
    </script>
</body>
</html>