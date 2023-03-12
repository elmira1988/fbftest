<?php
session_start();
	if (!isset($_SESSION['login']))
	{
		header("Location: index.php");    
	}
	else
	{
?>
<html>
     <head>
	 <meta http-equiv="Content-type" content="text/html; charset=utf-8">
	 <meta http-equiv="X-UA-Compatible" content="IE=Edge">
     <title>Башҡорт теленән республика Интернет-олимпиадаһы</title>
	 <base href="/fbf-test/">
	 <link href='new_style.css' rel='stylesheet' type="text/css" />
	 <script src="/fbf-test/js/jquery-3.3.1.min.js"></script>
	 <script src="/fbf-test/js/script.js"></script>
	 </head>
     <body>
	 
	 
	 <div class="main">
	 
	 <div class="header">
		Башҡорт теленән республика Интернет-олимпиадаһы
	 </div>
		<img src="images/ornam_lv.png" />
		<img src="images/ornam_pv.png" style="float:right;"/> 

		<?php 
		include '../functions.php';
		if (!has_answer()) {?>
		<div class="hello_block">
		
			<?php //print_r($_SESSION)?>
			<h1>Хөрмәтле олимпиадала ҡатнашыусы!</h1>
			<p>Һеҙгә бөтә һорауҙарға яуап бирергә кәрәк. Дүрт яуаптың береһе генә дөрөҫ.</p>
			<p>Ваҡыт бөткәс йәки “Тесты тамамларға” тигән кнопканы баҫҡас, бөтә яуаптарығыҙ һаҡланасаҡ.</p>
			<p>Уңыштар теләйбеҙ!</p>
			<h1>Уважаемый участник <br>Республиканской Интернет-олимпиады!</h1>
			<p>Вам нужно ответить на все тестовые вопросы. Из предложенных вариантов ответов только один ответ верный.</p>
			<p>После окончания времени или нажатия вами кнопки "Закрыть тестирование", все ваши ответы будут сохранены.</p>
			<p>Желаем удачи!</p>
			
			<?php
			/*
			if ($_SESSION['category']==4)
			{ 
				?>
				<br>
				<div style="width:700px; display: flex;  justify-content: space-between;">
					<div style="width:335px;">
						<label style="width:100%;line-height:30px;color: rgb(5, 80, 80); font-size: 14pt;text-align:center;">
							Государственный башкирский язык
						</label>
						<div style="width:100%;border-top:1px solid rgba(5, 80, 80,0.8);height:10px;"></div>
						<div type='submit' name='nach' data-category="5" class='push_button red' style="margin:0 auto;">
							начать тестирование
						</div>	
			
					</div>
					
					<div style="width:335px;">
						<label style="width:100%;display:block;line-height:30px;color: rgb(5, 80, 80);font-size: 14pt;text-align:center;">
							Родной башкирский язык
						</label>
						<div style="width:100%;border-top:1px solid rgba(5, 80, 80,0.8);height:10px;"></div>
						<div type='submit' name='nach' data-category="4" class='push_button red' style="margin:0 auto;">
							тесты башларға
						</div>	
					</div>
					
				</div>
				<?php
			}
			else
			{*/
			?>
			
			<div type='submit' name='nach' class='push_button red' style="left: 50%;position: absolute; margin-left: -100px;">
				тесты башларға
			</div>		
			
			<?php
			/*}*/
			?>
		</div>
		<?php }
		else
		{?>
			<div class="hello_block">
		
			<h1>Уважаемый участник,<br><?php echo $_SESSION['fam'].' '.$_SESSION['name'].' '.$_SESSION['otch'];?>
			<br>Вы уже прошли тестирование!
			</h1>
		<?php
		session_unset();
		session_destroy();
		?>
						
		</div>
		<?php
		}
		?>
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