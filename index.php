<?
if ( isset($_POST['login']) && isset($_POST['pass']) ) {
	$res = user::logIn($_POST['login'],$_POST['pass']);
	$tmp = json_decode($res);
	if ( $tmp->error ) {
		$flagError = $tmp->error;
		$errorText = $tmp->text;	
	}
	else {
		header("Location: lk/index.php");
	}
}
?>

<!DOCTYPE>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<meta http-equiv="Content-Language" content="ru">
<title>Добро пожаловать в билинг TEST</title>
<link href="/src/css/bilStyle.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="/src/js/jquery.js"></script>
</head>
<body>
	<div class="authDiv">
		<h1>Вход в систему</h1>
		<form method="post">
			<input type="text" class="authInput" name="login" placeholder="Логин" autocomplete="off" required>
				<br><br>
			<input type="password" class="authInput" name="pass" placeholder="Пароль" autocomplete="off" required>
				<br><br>
			<input type="submit" class="authEnterBut" name="auth" value="Войти">
		</form>
	</div>

	<?if (isset($flagError) && $flagError ) {?>
		<div class="errorOnLoginDiv">
			<h1>Ошибка</h1>
			<span class="errorText"><?=$errorText?></span>
		</div>
	<?}?>

</body>
</html>
