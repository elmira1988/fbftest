<?php
require 'vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer; 
use Mpdf\Mpdf;

require 'vendor/autoload.php';

include 'Open_db.php';

function generate_log($fam, $imya, $otch) //формируем логин по ФИО
	{
		mb_internal_encoding("UTF-8");

		$log = $fam.mb_substr($imya, 0, 1, "UTF-8").mb_substr($otch, 0, 1, "UTF-8");
		$log_start = $log;
		
		if (test_login($log) != 0) //Меняем логин, если такой уже в базе есть
		{
			$i = 1;
			
			while (test_login($log_start) != 0)
			{
				$log_start = $log.$i;
				$i++;
			}
			
			$log = $log_start;
		}
		
		return $log;
	}
	
	
function generate_pas($fam, $imya, $otch) //формируем пароль по ФИО
	{
		$pas = substr(md5($fam.$imya.$otch), 0, 7);
		
		$one = mt_rand(1, 6);
		$two = mt_rand(1, 6);
		$three = mt_rand(1, 6);
		$pas[$one] = strtoupper($pas[$one]);
		$pas[$two] = strtoupper($pas[$two]);
		$pas[$three] = strtoupper($pas[$three]);
		$pas[7] = mt_rand(1, 9);
		
		return $pas;
	}

function test_login($login) //проверка на наличие такого логина в бд
	{
		$res = get_raw("SELECT * FROM `students` WHERE `log` ='".$login."'");
		
		if (count($res)!= 0) 
		{
			return 1;
		}
		else
		{
			return 0;
		}
	}
	
	
	function time_to_mls($time)//перевести время 22 02 22 в милисекунды
{
	$date_elements  = explode(" ",$time);
	$h=$date_elements[0];
	$m=$date_elements[1];
	$s=$date_elements[2];
	$this_time=$h*3600+$m*60+$s;//текущее время
	return $this_time;
	
}


function time_to_clock($time)//перевести время из милисекунд в 22 02 22
{
	$hh=(integer)($time/3600);
	$mm=(integer)(($time-$hh*3600)/60);
	$ss=$time-$hh*3600-$mm*60;
						
	return $hh." ".$mm." ".$ss;
	
}

function save_time_open_of_student()
{
	$query=" INSERT INTO `authorization` ";
	$query.=" (`id_authorization`, `id_students`, `time_open`, `time_close`) VALUES ";
	$query.=" (NULL, '".$_SESSION['id_students']."', '".current_time()."', '');";
	set_raw($query);
}

function current_time()
{
	mysql_query("SET time_zone='+05:00'");
	$res=get_raw("SELECT now()");
	return $res[0]['now()'];
}


function save_time_close_of_student()
{
	$query="UPDATE `authorization` SET `time_close` = '".current_time()."' WHERE ";
	$query.=" `id_students` = ".$_SESSION['id_students'];
	set_raw($query);
}

function has_answer()//проверяем отвечал ли пользователь на вопросы
{
	$query="SELECT * FROM `authorization` WHERE `id_students` =".$_SESSION['id_students'];
	$res=get_raw($query);
	
	if (count($res)!==0)
	{
		return true;//отвечал уже
	}
	else
	{
		return false;//не отвечал
	}
}


//отправка письма
 function send_mail($email,$header,$text,$file=false,$data)  
 {
  // Настройки
  $mail = new PHPMailer;

  $mail->isSMTP(); 
  $mail->SMTPDebug = 0;
  //$mail->Host = 'smtp.yandex.ru'; 
  $mail->Host = 'ssl://smtp.mail.ru'; 
  $mail->SMTPAuth = true; 
  //$mail->Username = 'fbftest.strbsu';
  $mail->Username = 'fbftest.strbsu@mail.ru';
  //$mail->Password = 'fbftest2019'; // Ваш пароль 
  $mail->Password = 'S0s9M3QfZUpHDJmSYmPY'; // Ваш пароль
  
  $mail->CharSet = 'UTF-8';
  $mail->SMTPSecure = 'SSL';  
  $mail->Port = 465;   
   
  $mail->SMTPOptions = [
	  'ssl' => [
		'verify_peer' => false,
		'verify_peer_name' => false,
		'allow_self_signed' => true
	  ]
	]; 
	
  $mail->SMTPKeepAlive = true;   
  $mail->Mailer = "smtp"; // don't change the quotes!

  //$mail->setFrom('fbftest.strbsu@yandex.ru'); // Ваш Email
  $mail->setFrom('fbftest.strbsu@mail.ru'); // Ваш Email
  $mail->addAddress($email);//Email получателя 
  $mail->isHTML(true); 
  $mail->Subject = $header; // Заголовок письма
  $mail->Body = $text;//Текст письма
  if($file)
    {
    	$emailAttachment=make_pdf_rec($data);
    	$mail->AddStringAttachment($emailAttachment, 'Квитанция.pdf', 'base64', 'application/pdf');
      //$mail->AddAttachment($file); // pdf file path 
    }
 
  if (!$mail->send())  
  {
    return array("result" => "error","msg" => $mail->ErrorInfo);
  } 
  else 
  { 
    return array("result" => "ok","msg" => 'Письмо успешно отправлено');
  } 
 }

function make_pdf_rec($data = false, $save = true){

    $_POST=$data;
    /*
    $_POST['fam']='Саитгараев';
    $_POST['name']='Ильназ';
    $_POST['pat']='Наилевич';
    $_POST['fam_parent']='Татлыбаева';
    $_POST['name_parent']='Гулькай';
    $_POST['pat_parent']='Нурисламовна';
    $_POST['adress']='г.Мелеуз,ул.Акмуллы,16/А';
*/
    $html = '
		<style>
		body{font-size:10pt;font-family: Times New Roman;}
		h1{color:black;font-size:10pt;margin:0px;text-align:center;font-weight:normal;line-height:20px;}
		p{text-indent: 0px;margin-top:5px;margin-bottom:5px;font-size:12pt;}
		.footer p{text-indent: 0px;}
		</style>';

    $html.='<table style="width:800px;margin:0 auto; border:1px solid black">';
    $html.='<tr>';
    $html.='<td style="vertical-align: top;padding-top:10px;text-align: center;" colspan="3">';
    $html.='<p style="padding-left: 10px;"><b>УФК по Республике Башкортостан </b></p>';
    $html.='<p><b>(Стерлитамакский филиал УУНиТ, СФ УУНиТ, л/с  20016НЖУЦ50)</b></p>';
    $html.='<hr style="margin-bottom: 10px;margin-top:5px;">';
    $html.='<table style="width: 100%">';
    $html.='<tr>';

    $html.='<td style="border-bottom: 1px solid black"><span style="margin: 0px;"><b>0274975591</b></span></td>';
    $html.='<td style="border-bottom: 1px solid black"><span style="margin: 0px;"><b>026843001</b></span></td>';
    $html.='<td style="border-bottom: 1px solid black"><span style="margin: 0px;"><b>03214643000000010100</b></span></td>';

    $html.='</tr>';
    $html.='<tr>';

    $html.='<td><span style="margin: 0px;font-size: 9pt;">(ИНН получателя платежа)</span></td>';
    $html.='<td><span style="margin: 0px;font-size: 9pt;">(КПП получателя платежа)</span></td>';
    $html.='<td><span style="margin: 0px;font-size: 9pt;">(номер р/счета получателя платежа)</span></td>';

    $html.='</tr>';

    $html.='<tr>';

    $html.='<td style="border-bottom: 1px solid black;" colspan="2"><p>&nbsp;</p><span style="margin: 0px;padding-top: 20px"><b>Отделение-НБ Республика Башкортостан</b></span></td>';
    $html.='<td style="border-bottom: 1px solid black;"><span style="margin: 0px;"><p>&nbsp;</p><b>018073401</b></span></td>';

    $html.='</tr>';
    $html.='<tr>';

    $html.='<td  colspan="2"><span style="margin: 0px;font-size: 9pt;">(наименование банка получателя)</span></td>';
    $html.='<td><span style="margin: 0px;font-size: 9pt;">(БИК)</span></td>';

    $html.='</tr>';

    $html.='<tr>';
    $html.='<td colspan="3"><b>КБК: 00000000000000000130  ОКТМО: 80745000</b></td>';
    $html.='</tr>';

    $html.='<tr>';
    $html.='<td colspan="3">Назначение: <b>Плата за курсы</b></td>';
    $html.='</tr>';

    $html.='</table>';
    $html.='<hr style="border: 2px solid black;margin-top: 5px;">';
    $html.='</td>';

    $html.='<td><img src="assets/img/qr_code.png"></td>';
    $html.='</tr>';

    $html.='<tr>';
    $html.='<td style="padding:0px;padding-left: 10px;" colspan="3">Ф.И.О. плательщика: &nbsp;&nbsp;<b>'.$_POST['fam_parent'].' '.$_POST['name_parent'].' '.$_POST['pat_parent'].'</b></td>';
    $html.='<td style="text-align: center;vertical-align: top" rowspan="2">Доступна оплата по QR-коду<br> через мобильное приложение и банкоматы банка</td>';
    $html.='</tr>';

    $html.='<tr>';
    $html.='<td style="padding: 10px;" colspan="4" >Назначение: <b>№ О23/07 Оргвзнос участника Республиканской ';
    $html.='<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;интернет-олимпиады по башкирскому языку</b></td>';
    $html.='</tr>';

    $html.='<tr>';
    $html.='<td style="padding: 10px;" colspan="4">Ф.И.О. обучающегося:&nbsp;&nbsp;<b>'.$_POST['fam'].' '.$_POST['name'].' '.$_POST['pat'].'</b></td>';
    $html.='</tr>';

    $html.='<tr>';
    $html.='<td colspan="4" style="padding-left: 10px;">';
    $html.='<hr style="border: 2px solid black;margin-top: 5px;">';$html.='</td>';
    $html.='</tr>';

    $html.='<tr>';
    $html.='<td colspan="4" style="padding-left: 10px;padding-bottom: 20px">';
    $html.='<span style="padding-left: 10px"> Сумма платежа: 200 руб. 00__коп.                                 	«______»_____________________ 2023 г.</span>';
    $html.='</td>';
    $html.='</tr>';

    $html.='</table>';


   // echo $html;


    $mpdf = new Mpdf(['mode' => 'ru-RU', 'format' => [230, 236]]);
    $mpdf->WriteHTML($html);

    if($save)
    {
        return $mpdf->Output("Квитанция.pdf",'S');
    }
    return $mpdf->Output("Квитанция_".$_POST['fam'].".pdf",'I');


}


function make_pdf_rec_old($data, $save = true){

		/*$_POST=$data;
		*/
		$_POST['fam']='Саитгараев';
		$_POST['name']='Ильназ';
		$_POST['pat']='Наилевич';
		$_POST['fam_parent']='Татлыбаева';
		$_POST['name_parent']='Гулькай';
		$_POST['pat_parent']='Нурисламовна';
		$_POST['adress']='г.Мелеуз,ул.Акмуллы,16/А';

 		$html = '
		<style>
		body{font-size:10pt;font-family: Times New Roman;}
		h1{color:black;font-size:10pt;margin:0px;text-align:center;font-weight:normal;line-height:20px;}
		p{text-indent: 0px;margin-top:5px;margin-bottom:5px;font-size:8pt;}
		.footer p{text-indent: 0px;}
		</style>
		<h1 style="text-align:right;font-size:8pt;">Приложение №2</h1>';
		
		$html.='<div style="width:28%;float:left;border-right:1px solid black;border-bottom:1px solid black;">';
		$html.='<p style="text-align:center;">Извещение</p><br><br><br><br><br><br><br><br><br><br><br><br><br>';
		$html.='<p style="text-align:center;">Кассир</p><br><br><br>';
		$html.='</div>';
		$html.='<div style="width:70%;float:left;border-bottom:1px solid black;padding-left:10px;">';
		$html.='<p>Наименование получателя платежа: <span style="text-decoration:underline;font-weight:bold">';
		$html.='УФК по Республике Башкортостан ';
		$html.='(Стерлитамакский филиал БашГУ, СФБашГУ, л/с 20016Х52360 .</p>';
		$html.='<table style="width:100%;">';
		$html.='<tr>';
		$html.='<td style="width:60%">';
		$html.='<div style="font-weight:bold;display: block;text-align: center;">';
		$html.='<span style="text-decoration:underline;display:inline-block;">ИНН  0274011237</span>';
		$html.='&nbsp;&nbsp;&nbsp;';
		$html.='<span style="text-decoration:underline;display:inline-block;">КПП  026802001</span>';
		$html.='<div style="text-align:center;font-size:8pt;margin: 0 auto;display:block;font-weight:normal;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(ИНН/КПП получателя платежа)</div>';
		$html.='</div></td>';
		$html.='<td style="width:40%;">';
		$html.='<div style="text-align:center;width:100%;font-weight:bold;text-decoration:underline;">03214643000000010100.</div>';
		$html.='<div style="text-align:center;font-size:8pt;width:100%;">(номер счета получателя платежа)</div>';
		$html.='</td>';
		$html.='</tr>';
		$html.='</table>';
		
		$html.='<table style="width:100%;display:inline:block">';
		$html.='<tr>';
		$html.='<td style="width:70%">';
		$html.='<span style="text-decoration:underline;">в Отделение-НБ Республика Башкортостан Банка России//УФК по Республике Башкортостан г.Уфа  </span>';
		$html.='<br><span style="font-size:8pt;">&nbsp;&nbsp;&nbsp;(наименование банка получателя платежа)</span>';
		$html.='</td>';
		$html.='<td style="width:30%;">';
		$html.='<span>БИК&nbsp;&nbsp;</span><span style="font-weight:bold;text-decoration:underline;">018073401</span><br>';
		$html.='</td>';
		$html.='</tr>';
		$html.='</table>';
		
		$html.='<div style="text-align:center;width:100%;font-weight:bold;">40102810045370000067</div>';
		$html.='<div style="font-size:8pt;text-align:center;">&nbsp;&nbsp;&nbsp;(номер счета (единый казначейский, корр. счет))</div>';
		
		$html.='<table style="width:100%;">';
		$html.='<tr>';
		$html.='<td style="width:25%">';
		$html.='л/с <span style="font-weight:bold;">20016Х52360</span>';
		$html.='</td>';
		$html.='<td style="width:30%;">';
		$html.='ОКТМО <span style="font-weight:bold;">80745000</span>';
		$html.='</td>';
		$html.='<td style="width:45%;">';
		$html.='КБК <span style="font-weight:bold;">000 000 00000 00 0000 130</span>';
		$html.='</td>';
		$html.='</tr>';
		$html.='</table>';
		
		$html.='<div style="text-align:center;width:100%;margin-bottom:0px;margin-top:5px;">'.$_POST['fam'].' '.$_POST['name'].' '.$_POST['pat'].'</div>';
		//$html.='<div style="text-align:center;width:100%;margin-bottom:0px;">'.$_GET['fam'].' '.$_GET['name'].' '.$_GET['pat'].'</div>';
		$html.='<div style="text-align:center;font-size:8pt;width:100%">(фио ученика/студента)</div>';
		
		$html.='<p style="text-decoration:underline;">Договор  (O22/13)<span style="text-decoration:none;text-indent:60px;"> ОРГВЗНОС УЧАСТНИКА МЕРОПРИЯТИЯ</span></p>';
		//$html.='<div style="text-align:center;font-size:8pt;width:100%">(курс, форма обучения, факультет)</div>'; 
		
		//$html.='<p>Плательщик (ФИО) ______________________________________________________________________________________</p>';
		$html.='<p>Плательщик (ФИО) '.$_POST['fam_parent'].' '.$_POST['name_parent'].' '.$_POST['pat_parent'].'</p>';
		$html.='<p>Адрес плательщика '.$_POST['adress'].'</p>';
		//$html.='<p>ИНН плательщика _______________________________________________________________________________________</p>';
		$html.='<p>Дата ___________________________ Сумма платежа: 200  руб. 00 коп.</p>';
		$html.='<p style="margin-bottom:19px;">Плательщик (подпись) ________________________________________</p>';
		$html.='</div>';
		
		$html.='<div style="width:28%;float:left;border-right:1px solid black;">';
		$html.='<p style="text-align:center;"><br><br><br><br><br><br><br><br><br><br>Квитанция</p><br><br><br><br><br><br>';
		$html.='<p style="text-align:center;">Кассир</p><br><br>';
		$html.='</div>';
		$html.='<div style="width:70%;float:left;padding-left:10px;">';
		$html.='<p>Наименование получателя платежа: <span style="text-decoration:underline;font-weight:bold">';
		$html.='Управление Федерального  казначейства по Республике Башкортостан ';
		$html.='(Стерлитамакский филиал БашГУ,  СФ БашГУ,   л/с 20016Х52360).</p>';
		$html.='<table style="width:100%;">';
		$html.='<tr>';
		$html.='<td style="width:60%">';
		$html.='<div style="font-weight:bold;display: block;text-align: center;">';
		$html.='<span style="text-decoration:underline;display:inline-block;">ИНН  0274011237</span>';
		$html.='&nbsp;&nbsp;&nbsp;';
		$html.='<span style="text-decoration:underline;display:inline-block;">КПП  026802001</span>';
		$html.='<div style="text-align:center;font-size:8pt;margin: 0 auto;display:block;font-weight:normal;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;(ИНН/КПП получателя платежа)</div>';
		$html.='</div></td>';
		$html.='<td style="width:40%;">';
		$html.='<div style="text-align:center;width:100%;font-weight:bold;text-decoration:underline;">03214643000000010100.</div>';
		$html.='<div style="text-align:center;font-size:8pt;width:100%;">(номер счета получателя платежа)</div>';
		$html.='</td>';
		$html.='</tr>';
		$html.='</table>';
		
		$html.='<table style="width:100%;display:inline:block">';
		$html.='<tr>';
		$html.='<td style="width:70%">';
		$html.='<span style="text-decoration:underline;">в Отделение-НБ Республика Башкортостан Банка России//УФК по Республике Башкортостан г.Уфа</span>';
		$html.='<br><span style="font-size:8pt;">&nbsp;&nbsp;&nbsp;(наименование банка получателя платежа)</span>';
		$html.='</td>';
		$html.='<td style="width:30%;">';
		$html.='<span>БИК&nbsp;&nbsp;</span><span style="font-weight:bold;text-decoration:underline;">018073401</span><br>';
		$html.='</td>';
		$html.='</tr>';
		$html.='</table>';
		
		$html.='<div style="text-align:center;width:100%;font-weight:bold;">40102810045370000067</div>';
		$html.='<div style="font-size:8pt;text-align:center;">&nbsp;&nbsp;&nbsp;(номер счета (единый казначейский, корр. счет)</div>';
		
		$html.='<table style="width:100%;">';
		$html.='<tr>';
		$html.='<td style="width:25%">';
		$html.='л/с <span style="font-weight:bold;">20016Х52360</span>';
		$html.='</td>';
		$html.='<td style="width:30%;">';
		$html.='ОКТМО <span style="font-weight:bold;">80745000</span>';
		$html.='</td>';
		$html.='<td style="width:45%;">';
		$html.='КБК <span style="font-weight:bold;">000 000 00000 00 0000 130</span>';
		$html.='</td>';
		$html.='</tr>';
		$html.='</table>';
		
		$html.='<div style="text-align:center;width:100%;margin-bottom:10px;">'.$_POST['fam'].' '.$_POST['name'].' '.$_POST['pat'].'</div>';
		$html.='<div style="text-align:center;font-size:8pt;width:100%">(фио ученика/студента)</div>';
		
		$html.='<p style="text-decoration:underline;">Договор  (O22/13)<span style="text-decoration:none;text-indent:60px;"> ОРГВЗНОС УЧАСТНИКА МЕРОПРИЯТИЯ</span></p>';
		//$html.='<div style="text-align:center;font-size:8pt;width:100%">(курс, форма обучения, факультет)</div>';
		
		$html.='<p>Плательщик (ФИО) '.$_POST['fam_parent'].' '.$_POST['name_parent'].' '.$_POST['pat_parent'].'</p>';
		$html.='<p>Адрес плательщика '.$_POST['adress'].'</p>';
		//$html.='<p>ИНН плательщика _______________________________________________________________________________________</p>';
		$html.='<p>Дата ___________________________ Сумма платежа: 200  руб. 00 коп.</p>';
		$html.='<p style="margin-bottom:15px;">Плательщик (подпись) ________________________________________</p>';
		$html.='</div>';
		/*
		echo $html; 
		*/
		
		//include "vendor/mpdf/mpdf.php"; 
		//define('_MPDF_TTFONTDATAPATH',Yii::getAlias('@runtime/mpdf'));

		//$mpdf = new \Mpdf\Mpdf('ru-RU','A4','','',5,5,5,10,1,1);
		
		
		$mpdf = new Mpdf(['mode' => 'ru-RU', 'format' => [230, 236]]); 
		$mpdf->WriteHTML($html); 
		
		if($save)
		{
			return $mpdf->Output("Квитанция.pdf",'S');
		}
		return $mpdf->Output("Квитанция_".$_POST['fam'].".pdf",'I');
		
		/* это старое
		$mpdf = new mPDF('ru-RU','A4','','',5,5,5,10,1,1);//(left,right,top,bottom)
		$mpdf->setHTMLFooter('<span float="right" style="font-size:10pt;font-family:Arial;"> Страница {PAGENO} из {nbpg}</span>') ;

		$mpdf->WriteHTML($html);
		return $mpdf->Output('Квитанция.pdf',S);  */
	}

function get_setting(){
    return get_raw("SELECT * FROM `settings` LIMIT 1")[0];
}


  function file_get_contents_curl($url,$post_data)
  {
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_HEADER, 0);
    //Устанавливаем параметр, чтобы curl возвращал данные, вместо того, чтобы выводить их в браузер.
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);  
    // указываем, что у нас POST запрос
    curl_setopt($ch, CURLOPT_POST, 1);
    // добавляем переменные
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
    curl_setopt($ch, CURLOPT_URL, $url);

    $data = curl_exec($ch);
    curl_close($ch);

    return $data;
  }




?>