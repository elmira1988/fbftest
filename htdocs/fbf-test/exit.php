<?php 
	session_start();
	include '../functions.php';
    /*
         unset($_SESSION['pas']);
         unset($_SESSION['login']);
		 unset($_SESSION['fam']);
		 unset($_SESSION['name']);
		 unset($_SESSION['otch']);
		 unset($_SESSION['id_students']);
		 unset($_SESSION['start_time']);
		 unset($_SESSION['end_test']);
		 unset($_SESSION['category']);
		 unset($_SESSION['sort_questions']);//смешанные вопросы
		 unset($_SESSION['number_of_questions']);//номер текущего вопроса
	*/
    
	$query="SELECT `questions`.`id_questions`,`questions`.`number_true`, `answers`.`answer` ";
	$query.=" FROM `questions` INNER JOIN `answers` ON `questions`.id_questions = `answers`.id_questions ";
	$query.=" WHERE `answers`.`id_students`=".$_SESSION['id_students']." AND `answers`.`answer`=`questions`.`number_true`";
	$count_true_answers_student=count(get_raw($query));//кол-во верных ответов
	

	$query="SELECT * FROM `questions` WHERE `category`=".$_SESSION['category'];
	$count_questions=count(get_raw($query));
	$proc=round(($count_true_answers_student/$count_questions)*100, 2);
						
	save_time_close_of_student();
	session_unset();
    session_destroy();
	
	//header("Location: protected.php");
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
		Башҡорт теленән интернет-олимпиада
	 </div>
		<img src="images/ornam_lv.png" style="float:left;"/>
		<img src="images/ornam_pv.png" style="float:right;"/> 
			
			<div class="hello_block" style="top:150px;position:absolute;">
		
			<h1>Уважаемый участник,
			Вы ответили верно на <?php echo $proc.'%';?>!
			</h1>

						
		</div>
	 <div class="footer">
		
			<img src="images/ornam_ln.png" style="float:left;margin-top:-100px;"/>
			<img src="images/ornam_pn.png" style="float:right;margin-top:-100px;"/>
			
		 © 2023 Стерлитамакский филиал УУНиТ, все права защищены
	</div>
	 </div>
	 
</body>
</html>
