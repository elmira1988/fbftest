<?php session_start();
	if (isset($_SESSION['login']))
	{
		header("Location: protected.php");    
	}
	else
	{
?>
<html>
     <head>
	 <meta http-equiv="Content-type" content="text/html; charset=utf-8">
	 <meta http-equiv="X-UA-Compatible" content="IE=Edge">
     <title>Страница авторизации</title>
	 <base href="/fbf-test/">
	 <link href='new_style.css' rel='stylesheet' type="text/css" />
	 <script src="/fbf-test/js/jquery-3.3.1.min.js"></script>
	 <script src="/fbf-test/js/script.js"></script>
	 </head>
     <body>
	 
	 
	 <div class="main">
	 
	 <div class="header">
		Башҡорт теленән интернет-олимпиада
	 </div>
		<img src="images/ornam_lv.png" style="float:left;"/>
		<img src="images/ornam_pv.png" style="float:right;"/> 

			<div id='login-box'>
		
			<p>Авторизуйтесь</p>
			
			<div class="block_log">
				<span>
					Логин:
				</span>
				<input name='login' value=''  />
				<span>
					Пароль:
				</span>
				<input name='pas' type="password" value='' />
				<?php
				/*if($_GET['test']==true)
				{*/
				?>
				<div class='sendsubmit'></div>
				<?php
				/*}*/
				?>
			</div>
			<div class='my_error'>Неверно введены логин/пароль</div>
			
			</div>
	 <div class="footer">
		
			<img src="images/ornam_ln.png" style="float:left;margin-top:-100px;"/>
			<img src="images/ornam_pn.png" style="float:right;margin-top:-100px;"/>
			
		 © 2023 Стерлитамакский филиал УУНиТ, все права защищены
	</div>
	 </div>
	 
</body>
</html>
<?php
	}
?>