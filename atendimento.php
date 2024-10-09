<?php 
if (!isset($_SESSION)){ session_start();}

if (!isset($_SESSION['id'])) { session_destroy();header("Location: ../login1.php"); exit;}

include 'conexao.php';
include '../converte_data.php'; 

$id = $_SESSION['id'];
var_dump($_SESSION);
$pdo = conecta();

$stagm = $pdo->prepare("SELECT 
	c.nomecliente, c.celular, p.nomeprofissional, a.cliente, a.data, a.hora_ini, a.hora_fin 
	from agenda as a 
	INNER JOIN profissional as p
	ON a.profissional = p.idprofissional
	join cliente as c
	ON a.cliente = c.idcliente
	WHERE a.status = 'M' and a.cliente = :id");
$stagm->bindValue(":id", $id);
$stagm->execute();

$rcliente = $stagm->FETCHALL(PDO::FETCH_ASSOC);

print "<pre>";
print_r($rcliente);
print "</pre>";
?>