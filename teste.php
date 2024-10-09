<?php

//$hora_inicio = $_POST['hora_inicio'];  
//$hora_final = $_POST['hora_final'];
$hora_inicio = "07:00";  
$hora_final = "18:00";             
$ini = strtotime($hora_inicio);
$fim = strtotime($hora_final);
$atu = $ini;
$i = 1;
for ($atu = $ini ;  $atu <= $fim; $atu = strtotime('+60 minutes', $atu)) {
	if ($atu < strtotime('12:00') || $atu >= strtotime('13:00')){


    $hr_agendamento = date('H:i', $atu);
    $hr_final = date('H:i',strtotime('+60 minutes', $atu));
    echo "Inicio:". $hr_agendamento." - Fim: ".$hr_final."<br>";continue;
    //$sql = mysql_query("INSERT INTO agenda (id_agenda,hr_agendamento) VALUES('','$hr_agendamento')");                        
}}
echo "agenda criada";