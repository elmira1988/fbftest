<?php

include '../functions.php';

$students=get_raw("SELECT * FROM `students` ORDER by `fam`,`name`");
echo '<b>РЕЗУЛЬТАТЫ</b> <br> (для обновления данных нажмите ctrl+r)<br><br>';
echo '<table class="my_table">';
echo '<tr>
		  <td><b>№</b></td>
		  <td style="min-width:60px;"><b>Оплата</b></td>
		  <td><b>ФИО</b></td>
		  <td><b>Квитанция</b></td>
		  <td><b>Email/Телефон</b></td>
		  <td><b>Город/Район</b></td>
		  <td><b>Школа</b></td>
		  <td><b>Руководитель</b></td>
		  <td><b>Класс</b></td>
		  <td><b>Балл</b> <br>из 100</td>
	  </tr>';
$not_payment=0;	
$tested = 0;		
$count_online=0;	
$not_pay_email="";	//без оплаты
$not_testing="";	//с оплатой и не протестировались
for ($i=0;$i<count($students);$i++)
	{
		
		$query="SELECT `questions`.`id_questions`,`questions`.`number_true`, `answers`.`answer` ";
		$query.=" FROM `questions` INNER JOIN `answers` ON `questions`.id_questions = `answers`.id_questions ";
		$query.=" WHERE `answers`.`id_students`=".$students[$i]['id_students']." AND `answers`.`answer`=`questions`.`number_true`";
		
		$count_true_answers_student=count(get_raw($query));//кол-во верных ответов
		
		$class=$students[$i]['class'];

		if ($class<=4) {$category=1;}
		if (($class>=5) & ($class<=8))	 {$category=2;}
		if (($class>=9) & ($class<=11)) {$category=3;}

		if ($students[$i]['cat']==2) {
			$category=4;
			$this_answer=get_raw("SELECT * FROM `answers` WHERE `id_students`='".$students[$i]['id_students']."'");
			
			if (count($this_answer)!==0)
				{
				$new_cat=get_raw("SELECT * FROM `questions` WHERE `id_questions` IN (SELECT `id_questions` FROM `answers` WHERE `id_students`='".$students[$i]['id_students']."')");
				$category=$new_cat[0]['category'];//разделение по категориям Родной и Государственный башкирский язык
				}
			}

		//Общее количество вопросов в категории
		$query="SELECT * FROM `questions` WHERE `category`=".$category;
		$count_questions=count(get_raw($query));
		
		$ball=round(($count_true_answers_student/$count_questions)*100, 2);
		
		$new_ball=str_replace(".", ",", (string)$ball);
		
		//ОПЛАТА
		$payment=count(get_raw("SELECT * FROM `payment` WHERE `id_students`=".$students[$i]['id_students']));

		$payment_online=count(get_raw("SELECT * FROM `payment_online` WHERE `id_student`=".$students[$i]['id_students']." AND `result`=1"));
		$count_online=$count_online+$payment_online;
		
		$parents=get_raw("SELECT * FROM `parents` WHERE `id_srudents`=".$students[$i]['id_students']);
		
		if ($new_ball!=0) {$tested++;}
		echo '<tr';
			if (($new_ball==0) && (($payment+$payment_online)==0)) 
			{
				echo " style='color:#9e9e9ea8;'";
				$not_payment++; 
				$not_pay_email=$not_pay_email.",".$parents[0]['email'];
				}
				
			if (($new_ball!=0) && (($payment+$payment_online)==0)) 
			{
				echo " style='color:red;'";
				$not_payment++; 
				$not_pay_email=$not_pay_email.",".$parents[0]['email'];
				} 
			if (($new_ball==0) && (($payment+$payment_online)!=0)) 
			{
				echo " style='color:green;'";
				$not_testing=$not_testing.",".$parents[0]['email'];
			}
		echo '>';
		echo '<td>'.($i+1).'</td>';
		echo '<td>'.(($payment_online) ? 'онлайн': '<label class="set_payment" title="подвердить/отменить факт оплаты" data-ball="'.$ball.'" data-name="'.$students[$i]['fam'].'  '.$students[$i]['name'].' '.$students[$i]['otch'].'" data-id_students="'.$students[$i]['id_students'].'"><input type="checkbox" '.(($payment) ? 'checked':'').' ><span>чек</span></label>').'</td>';  
		
		echo '<td title="id  в базе '.$students[$i]['id_students'].'">'.$students[$i]['fam'].'  '.$students[$i]['name'].' '.$students[$i]['otch'].'</td>';
		echo '<td><button onclick="get_rec('.$students[$i]['id_students'].')" style="cursor:pointer">квитанция</button></td>';
		
		
		echo '<td>'.$parents[0]['email'].'&nbsp;&nbsp;&nbsp;&nbsp;'.$students[$i]['tel'].'</td>';
		echo '<td>'.$students[$i]['city'].'</td>';
		echo '<td>'.$students[$i]['school'].'</td>';
		echo '<td>'.$students[$i]['ruk'].'</td>';
		
		if ($students[$i]['cat']!=='2')	
			{ echo '<td>'.$students[$i]['class'].'</td>';}
			else
			{ echo '<td>студент</td>';}
		
		echo '<td>'.$new_ball.'</td>';
		
		echo '</tr>';
	}
	echo '</table>';
	echo '<input type="hidden" value="'.$not_payment.'">';
	echo '<p>Оплата онлайн: '.$count_online.' с чеками: '.($i-$not_payment-$count_online);
	echo '<p>Без оплаты: '.$not_payment.'  Протестировались: '.$tested.'</p>';
	echo '<p style="display:none">'.$not_testing.'</p>';
	echo '
	<style>
	body
	{
		padding:30px;
	}
	
	.my_table
	{
		border-collapse: collapse;
	}
	
	.my_table td
	{
		border:1px solid black;
		padding:5px;
	}

	.set_payment
	{
		cursor:pointer;
	}

	.set_payment span
	{
		position:absolute;
		display:inline-block;
	}

	</style>
	<script type="text/javascript" src="../assets/js/jquery.1.11.3.min.js"></script>  	
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
	<script type="text/javascript" src="../assets/js/script_result.js"></script>';
	?>
	 
	 