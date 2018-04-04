<?php
/*
 * скрипт редактирования задачи
 */
include './connectdb.php';

function get_post($var)
{
    $cleanvar = htmlspecialchars(trim($_POST[$var]));
    return($cleanvar);
}

function redirect($page = "task.php")
{
    $host = htmlspecialchars(trim($_SERVER['HTTP_HOST']));
    $dir = dirname(htmlspecialchars(trim($_SERVER['PHP_SELF'])));
    $url = "http://" . $host . $dir . "/" . $page;
    header("Location: $url");
}

function sql_query($sql = 'select * from tasks')
{
    $pdo = ConnectBD();
    foreach ($pdo->query($sql) as $row)
    {
	return($row);
    }
}

function update_sql($sql)
{
    $pdo = ConnectBD();
    $pdo->exec($sql);
}

function check_id($id)
{
    $pdo = ConnectBD();
    $sql = 'SELECT `id` FROM `tasks` WHERE `id` IS NOT NULL';

    foreach ($pdo->query($sql) as $row)
    {
	$taskid[] = $row['id'];
    }
    if (in_array($id, $taskid))
    {

	return(TRUE);
    }
    else
    {
	return(FALSE);
    }
}

if (isset($_POST['del-task']))
{
    get_post(id);
    $id = $var;
    $sql = "DELETE FROM `tasks`WHERE `id` = '$id'";
    update_sql($sql);
    redirect();
}

if (isset($_POST['save-task']))
{
    $description = get_post('description');
    $id = get_post('id');
    $is_done = get_post('is_done');
    $date_added = get_post('date');
    $date = date('Y-m-d H:i:s');
    $sql = "UPDATE `tasks` SET `id` = $id,`description` = '$description',`is_done` = '$is_done',`date_added` = '$date' WHERE `id` = '$id'";
    update_sql($sql);
    redirect();
}
if (isset($_POST['save-task']) && isset($_GET['add']))
{

    $description = get_post('description');
    $id = get_post('id');
    $is_done = get_post('is_done');
    $date_added = get_post('date');
    $sql = "INSERT INTO `tasks` (`description`, `is_done`, `date_added`)VALUES ('$description', '$is_done', '$date_added')";
    update_sql($sql);
    redirect();
}
if (empty($_GET['id']) && empty($_GET['add']))
{
    redirect();
}
if (isset($_GET['id']) && empty($_GET['add']))
{
    $id = htmlspecialchars(trim(($_GET['id'])));
    if (check_id($id) == FALSE)
    {
	redirect();
    }
}

if (isset($_GET['id']) or isset($_GET['add']))
{
    ?>
    <!doctype html>
    <html>
        <head>
    	<meta charset="utf-8">
    	<meta name="viewport" content="width=device-width">
    	<title>Пупер-планер</title>
    	<style>
    	    .clearfix:after {
    		content: "";
    		display: table;
    		clear: both;
    	    }
    	    h1 {
    		text-align: center;
    		margin-top: 50px;
    	    }
    	    .btn{
    		display: block;
    		float: left;
    		text-decoration: none;
    		text-transform: uppercase;
    		color:#000;
    		background-color: #eee;
    		border: solid 1px #000;
    		margin: 10px auto;

    		max-width: 75%;
    		border-radius: 10px;
    		padding: 5px;
    		font-size: 10px;
    	    }
    	    form{
    		max-width: 530px;
    		min-width: 320px;
    		margin: 10px auto;
    	    }
    	    input, textarea, select{
    		margin: 10px;
    		padding: 5px auto;
    		min-width: 160px;
    	    }
    	    .id{
    		min-width: inherit;
    	    }
    	    .id input{
    		border: none;
    	    }
    	    .btn-1{
    		display: block;
    		float: left;
    		text-decoration: none;
    		text-transform: uppercase;
    		color:#000;
    		background-color: #eee;
    		border: solid 1px #000;
    		margin: 10px auto;
    		text-align: center;
    		width: 50px;
    		border-radius: 10px;
    		padding: 10px;
    		font-size: 10px;
    		margin-bottom: 20px;
    	    }
    	    .id, .status, .description{
    		float: left;
    	    }
    	    .description{
    		margin-bottom: 20px;
    	    }
    	    .date{
    		float: right;
    	    }
    	    .btn-save{
    		float: left;
    	    }
    	    .btn-del{
    		float: right;
    	    }


    	</style>
        </head>
        <body class="clearfix">
	    <?php
	    if (!isset($_GET['add']))
	    {
		$id = htmlspecialchars(trim(($_GET['id'])));
		$sql = 'select * from tasks where id=' . $id;
		$result = sql_query($sql);
		$id = $result['id'];
		$description = $result['description'];
		$is_done = $result['is_done'];
		$date_added = $result['date_added'];
	    }
	    ?>
    	<h1><?php
		if (!isset($_GET['add']))
		{
		    echo "Редактирование задачи №$id";
		}
		else
		{
		    echo "Создание новой задачи";
		}
		?></h1>
    	<hr>
    	<a class="btn-1" href="task.php">Назад</a>
    	<form  class="clearfix" action="" method="post">
		<?php
		if (!isset($_GET['add']))
		{
		    echo'<label class="id">ID:';
		    echo'<input  type="text" name="id" value="' . $id . '" readonly>';
		    echo'</label>';
		}
		?>
    	    <label class="date">Дата:
    		<input type="text" name="date" value="<?php
		    if (!isset($_GET['add']))
		    {
			echo $date_added;
		    }
		    else
		    {
			echo date("Y-m-d H:i:s");
		    }
		    ?>">
    	    </label>
    	    <label class="status">Статус:
    		<select name="is_done">
    		    <option value="0"<?php
			if (isset($_GET['add']))
			{
			    $is_done = 0;
			}
			if ($is_done == 0)
			{
			    echo 'selected';
			}
			?>>Не выполнено</option>
    		    <option value="1"<?php
			if ($is_done == 1)
			{
			    echo 'selected';
			}
			?>>Выполнено</option>
    		</select>
    	    </label>

    	    <label class="description">Описание:
    		<textarea cols="40" rows="7" name="description"><?php
			if (!isset($_GET['add']))
			{
			    echo $description;
			}
			?></textarea>
    	    </label>

    	    <input class="btn btn-save" type="submit" name="save-task" value="Сохранить">
		<?php
		if (!isset($_GET['add']))
		{
		    echo'<input class="btn btn-del" type="submit" name="del-task" value="Удалить">';
		}
		?>
    	</form>
	    <?php
	}
	