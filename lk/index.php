<?
if (!user::isAuth()) {
	header("Location: /");
}
else {

	if ( isset($_POST['summ']) ) {
		$res = user::moneyWithdraw($_POST['summ']);
		$tmp = json_decode($res);
		if ( $tmp->error ) {
			$errorText = $tmp->text;
		}
	}

	$userName     = user::getName();
	$userBalance  = user::getBalance();
}
?>
<!DOCTYPE>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Language" content="ru">
<title>Личный кабинет TEST</title>
<link href="/src/css/bilStyle.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/src/js/jquery.js"></script>
</head>
<body>

<table border="1" cellpadding="5" cellspacing="5">
	<thead>
		<tr>
			<td>Пользователь: <?=$userName?></td>
			<td>Баланс: <?=$userBalance?></td>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td valign="top">
				МЕНЮ:<br>
				<a href="/lk/index.php">Операции</a><br>
				<a href="/lk/exit.php">Выход</a><br>
			</td>
			<td width="500px;">
				<form method="post">
					Доступно средств для выполнения операции: <?=$userBalance?><br><br>
					Укажите выводимую сумму:
					<input type="text" class="authInput" name="summ" value="0" placeholder="сумма для вывода средств"><br>
					<input type="submit" class="submitBut" name="sub" value="Вывести средства"><br>
					<span class="prim">*сумма вывода может быть дробной до копеек (например: 175.25), более точное значение будет округлено до двух знаков после делителя</span>
				</form>
				<?if (isset($errorText)) {?>
				<div class="errorBlockText">
					<h1>Ошибка</h1><br>
					<span class="errorText"><?=$errorText?></span>
				</div>
				<?}?>
			</td>
		</tr>
	</tbody>
</table>

</body>
</html>