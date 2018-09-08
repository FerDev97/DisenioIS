<?php
        include_once '../ParaLectura/PHPExcel/IOFactory.php';
        include_once '../ProcesoSubir/conexion.php';
        
        $nombreArchivo1=$_GET['ir'];
        $id=$_GET['llego'];
		
	$nombreArchivo =$nombreArchivo1;
	
	$objPHPExcel = PHPEXCEL_IOFactory::load($nombreArchivo);
	
	$objPHPExcel->setActiveSheetIndex(0);

	$numRows = $objPHPExcel->setActiveSheetIndex(0)->getHighestRow();
	
	echo '<table border=1><tr><td>Fecha</td><td>Hora</td><td>ms</td><td>Nivel</td><td>Temperatura</td></tr>';
	
	date_default_timezone_set('America/El_Salvador');
	 //date.timezone="Europe/Madrid";
	for($i =2; $i <= $numRows; $i++){
		
//		$nombre = $objPHPExcel->getActiveSheet()->getCell('A'.$i)->getCalculatedValue();
		$fecha = $objPHPExcel->getActiveSheet()->getCell('A'.$i)->getValue();
                $fecha=date($format="d-m-Y",PHPExcel_Shared_Date::ExcelToPHP($fecha));
                $nuevafecha = strtotime ( '+1 day' , strtotime ( $fecha ) ) ;
                $nuevafecha = date ( 'Y-m-d' , $nuevafecha );
		
                $hora = $objPHPExcel->getActiveSheet()->getCell('B'.$i)->getValue();

		

		$lahora=($hora*86400)/3600;
		$minutos=($lahora-floor($lahora))*60;
		//$segundos=($hora*86400)-((floor($lahora)*3600)+$minutos*60);
		$segundos=($minutos-floor($minutos))*60;

		$horaImprimir=floor($lahora).':'.floor($minutos).':'.floor($segundos);


		//$hora=date($format="H:i:s",($hora));

		///$InvDate= $objPHPExcel->getActiveSheet()->setCellValue('B'.$i, PHPExcel_Shared_Date::PHPToExcel( $InvDate ));
  // $otra= $objPHPExcel->getActiveSheet()
    //    ->getStyle('B'.$i)
      //  ->getNumberFormat()
        //->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_DATE_DDMMYYYY);

		$ms = $objPHPExcel->getActiveSheet()->getCell('C'.$i)->getCalculatedValue();
		$nivel = $objPHPExcel->getActiveSheet()->getCell('D'.$i)->getCalculatedValue();
                $temperatura = $objPHPExcel->getActiveSheet()->getCell('E'.$i)->getCalculatedValue();
		
		
		echo '<tr>';
		echo '<td>'.$nuevafecha.'</td>';
		echo '<td>'.$horaImprimir.'</td>';
		echo '<td>'.$ms.'</td>';
                echo '<td>'.$nivel.'</td>';
                echo '<td>'.$temperatura.'</td>';
		echo '</tr>';
                
		
		$sql = "INSERT INTO lecturapozos (idpozo,date,time,ms,level,temperature) VALUE('$id','$nuevafecha','$horaImprimir','$ms','$nivel','$temperatura')";
		$result = $mysqli->query($sql);
//$sql = "INSERT INTO prueba (ca1, ca2, ca3) VALUE('$ms','$horaImprimir','$ms')";
//		$result = $mysqli->query($sql);
		
	}
	
	echo '</table>';
	
?>