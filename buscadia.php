<?php
include 'conexao.php';
include '../converte_data.php'; 

$pdo = conecta();

$d = $_POST['dia'];
$p = $_POST['procedimento'];

$sql = "SELECT * FROM agenda WHERE data_agendamento=:d and procedimento = :p AND cliente = 0 and status_agendamento = 'A'";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(":d", $d);
$stmt->bindValue(":p", $p);
$stmt->execute(); 
$resultado = $stmt->fetchAll(PDO::FETCH_ASSOC);

foreach ($resultado as $v) {
	echo "<option value=".$v['idagendamento']. ">".$v['hora_agendamento']."</option>";
}
