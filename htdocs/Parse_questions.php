<?php
include 'functions.php';
//$filepath='/home/strbsuru/html/fbftest.strbsu.ru/htdocs/achive_questions_2023/voprosi_1_2023.xlsx'; //$_SERVER['DOCUMENT_ROOT'].
$filepath='achive_questions_2023/voprosi_3_2023.xlsx'; //$_SERVER['DOCUMENT_ROOT'].

function readExelFile($filepath)
				{
				require_once 'vendor/phpoffice/phpexcel/Classes/PHPExcel.php'; //подключаем наш фреймворк
				$ar=array(); // инициализируем массив
				
				$inputFileType = PHPExcel_IOFactory::identify($filepath);  // узнаем тип файла, excel может хранить файлы в разных форматах, xls, xlsx и другие
				$objReader = PHPExcel_IOFactory::createReader($inputFileType); // создаем объект для чтения файла
				$objPHPExcel = $objReader->load($filepath); // загружаем данные файла в объект
				$ar = $objPHPExcel->getActiveSheet()->toArray(); // выгружаем данные из объекта в массив
				//unlink($filepath);//удаляем файл после выгрузки
				return $ar;// $ar; //возвращаем массив
				}
	$arr=readExelFile($filepath); 
	//print_r($arr); 
	
	//print_r($arr);

	$i=0;$k=1;
	while ($i<count($arr))
	{
		$question=$arr[$i][0];
		$number_true=0;
		
		$one=$arr[$i+1][0];
		
		if ($arr[$i+1][1]==1)
		{
			$number_true=1;
		}
		
		$two=$arr[$i+2][0];
		
		if ($arr[$i+2][1]==1)
		{
			$number_true=2;
		}
		
		$three=$arr[$i+3][0];
		
		if ($arr[$i+3][1]==1)
		{
			$number_true=3;
		}
		
		$four=$arr[$i+4][0];
		
		if ($arr[$i+4][1]==1)
		{
			$number_true=4;
		}
		
		$i=$i+5;
		
		echo $question.'<br>'; 
		echo '1 '.$one.'<br>';
		echo '2 '.$two.'<br>';
		echo '3 '.$three.'<br>';
		echo '4 '.$four.'<br>';
		echo $number_true.'<br>'.'<br>';
		
		$query="INSERT INTO `questions` (`id_questions`, ";
		$query.=" `number`, `number_true`, `category`, ";
		$query.=" `question`, `one`, `two`, `three`, `four`) ";
		$query.=" VALUES (NULL, ";
		$query.=" '".($k)."', '".$number_true."', '3', ";
		$query.=" '".$question."', '".$one."', '".$two."', '".$three."', '".$four."');";
		//set_raw($query);
		echo '<br>'.$query.'<br>';
		$k++;
	}

?>