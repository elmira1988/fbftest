<?php
include 'functions.php'; 

//print_r($_POST);

$fam=$_POST['fam'];
$name=$_POST['name'];
$pat=$_POST['pat'];
$category=$_POST['category'];
$class_stud=$_POST['class_stud'];
$school=$_POST['school'];
$city=$_POST['city'];
$phone=$_POST['phone'];
$ruk=$_POST['ruk'];
$log = generate_log(str_replace(" ","", $fam), str_replace(" ","",$name), str_replace(" ","",$pat));
$pas = generate_pas($fam,$name,$pat);			
				
$fam_parent=$_POST['fam_parent'];
$name_parent=$_POST['name_parent'];
$pat_parent=$_POST['pat_parent'];
$adress=$_POST['adress'];
$seriya=$_POST['seriya'];
$nomer=$_POST['nomer'];
$email=$_POST['email'];

$query="INSERT INTO `students` (`id_students`, `fam`, `name`, `otch`,";
$query.=" `cat`, `class`, `city`, `school`, `tel`, ";
$query.=" `ruk`, `log`, `pas`) VALUES ";
$query.=" (NULL, '".$fam."', '".$name."', '".$pat."', ";
$query.=" '".$category."', '".$class_stud."', '".$city."', '".$school."', '".$phone."', ";
$query.=" '".$ruk."', '".$log."', '".$pas."')";

//echo $query;

if ($id_students=set_raw($query))
{
	//получим id_students только что добавленного пользователя
	$res=get_raw("SELECT `id_students` FROM `students` WHERE `log`='".$log."' AND `pas`='".$pas."'");
	//$id_students=$res[0]['id_students'];
	
	$query="INSERT INTO `parents` (`id_parents`, `id_srudents`, ";
	$query.=" `fam`, `name`, `otch`, `adress`, ";
	$query.=" `passport_seriya`, `passport_number`, `email`) VALUES ";
	$query.=" (NULL, '".$id_students."', ";
	$query.=" '".$fam_parent."', '".$name_parent."', '".$pat_parent."', '".$adress."', ";
	$query.=" '".$seriya."', '".$nomer."', '".$email."')";
	
	if (set_raw($query))
	{
		$result['error']='false';
		$result['id_students']=$id_students;
		$result['log']=$log;
		$result['pas']=$pas;
		//Отправка письма зарегистрированному пользователю
		$header = 'Республиканская интернет-олимпиала. Регистрация.';
        /*если оплата будет производиться по квитанции, */
		$text = '<p><em><strong>Поздравляем! Регистрация успешно завершена!</strong></em></p>
		<p>Участник: <b> '.$fam.' '.$name.' '.$pat.'</b></p>
		<p>Логин: <b>'.$log.' </b></p>
		<p>Пароль: <b>'.$pas.'</b></p>
		<p>Руководитель: <b>'.$ruk.'</b></p>
		<p>Квитанция на оплату прикреплена к письму (для установления нами факты оплаты участия 
		необходимо скан копию чека, ФИО участника отправить на почтовый ящик <b>fbf_testuunit@mail.ru</b>)</p>
		<p>Организационный взнос за участие в олимпиаде составляет 200 руб. </p>
		<p>Спасибо за проявленный интерес к изучению башкирского языка. Напоминаем, что олимпиада будет проходить 13,14,15 марта 2023 г.</p> 
		<p><strong>Всего Вам доброго!</strong></p>
		<br>
		<p>Email для справок – fbf_testuunit@mail.ru</p>
		<p>Телефон для справок – 89174214705 (Саляхова Зугура Идрисовна),89273364057 (Кутлиахметова Лиана Мансуровна).</p>
		<br><br><br>------------<br>С уважением, организаторы Республиканской интернет-олимпиады!';

		$mail=send_mail($email,$header,$text,true,$_POST);    
		$result['mail']=$mail['msg'];
		echo json_encode($result);
	}
	else
	{
		$result['error']='true';
		echo json_encode($result);
	}
}
else
{
	$result['error']='true';
	echo json_encode($result);
}

?>