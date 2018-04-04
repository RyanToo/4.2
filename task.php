<?php

		include './connect_db.php';
		function check_sort($sort)
		{
			if (isset($_GET['sort']))
			{
			if ($_GET['sort'] == $sort)
			{
				echo "sort-active";
			}
			}
		}
		function sql_query($sql)
		{
			$pdo = Connect_BD();
			foreach ($pdo->query($sql) as $row)
			{
			echo "<tr>" . PHP_EOL;
			echo "<td>" . $row['description'] . "</td>" . PHP_EOL;
			if ($row['is_done'] == 0)
			{
				echo "<td style='color:blue;'>Не выполнено</td>" . PHP_EOL;
			}
			if ($row['is_done'] == 1)
			{
				echo "<td style='color:black;'>Выполнено</td>" . PHP_EOL;
			}
			echo "<td>" . $row['date_added'] . "</td>" . PHP_EOL;
			$_SESSION['id'] = $row['id'];
			$id = $row['id'];
			echo "<td><a class='botn' href='edit.php?id=$id'>Редактировать</a></td>" . PHP_EOL;
			echo "</tr>" . PHP_EOL;
			}
		}
		?>
		<html>
			<head>
				<meta charset="utf-8">
			<meta name="view" content="width=device-width">
				<title>Дела</title>
			
				</style>
			</head>
			<body>
				<h1>Список задач</h1>
			<hr>
				<table>
					<tbody>
				<tr>
					<th><a class="sort <?php
					check_sort("up-task");
					?>" href="task.php?sort=up-task">Верх</a><br><a class="sort <?php
					   check_sort("down-task");
					   ?>" href="task.php?sort=down-task">Вниз</a>Описание действий</th>
					<th><a class="sort <?php
					check_sort("up-status");
					?>" href="task.php?sort=up-status">Верх</a><br><a class="sort <?php
					   check_sort("down-status");
					   ?>" href="task.php?sort=down-status">Вниз</a>Статус</th>
					<th><a class="sort <?php
					check_sort("up-date");
					?>" href="task.php?sort=up-date">Верх</a><br><a class="sort <?php
					   check_sort("down-date");
					   ?>" href="task.php?sort=down-date">Вниз</a>дата</th>
					<th></th>

				</tr>
				<?php
				if (isset($_GET['sort']))
				{
					$sort = $_GET['sort'];
					switch ($sort)
					{
					case "up-task":
						$sql = "SELECT * FROM `tasks` ORDER BY `description` LIMIT 50";
						break;
					case "down-task":
						$sql = "SELECT * FROM `tasks` ORDER BY `description` DESC LIMIT 50";
						break;
					case "up-status":
						$sql = "SELECT * FROM `tasks` ORDER BY `is_done` LIMIT 50";
						break;
					case "down-status":
						$sql = "SELECT * FROM `tasks` ORDER BY `is_done` DESC LIMIT 50";
						break;
					case "up-date":
						$sql = "SELECT * FROM `tasks` ORDER BY `date_added`  LIMIT 50";
						break;
					case "down-date":
						$sql = "SELECT * FROM `tasks` ORDER BY `date_added` DESC LIMIT 50";
						break;
					}
				}
				else
				{
					$sql = "select * from tasks";
				}
				sql_query($sql);
				?>
				</tbody>
			</table>
			<a class="botn-1" href="edit.php?add=1">Добавить</a>
			<hr>
			</body>
		</html>