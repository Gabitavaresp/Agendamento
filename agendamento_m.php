<?php 
if (!isset($_SESSION)){ session_start();}

if (!isset($_SESSION['id'])) { session_destroy();header("Location: ../login1.php"); exit;}

include 'conexao.php';
include '../converte_data.php'; 

$logado = $_SESSION['id'];

$pdo = conecta();

$stagm = $pdo->query("SELECT 
	c.nomecliente, c.celular, p.nomeprofissional, a.data, a.hora_ini, a.hora_fin 
	from agenda as a 
	INNER JOIN profissional as p
	ON a.profissional = p.idprofissional
	inner join cliente as c
	ON a.cliente = c.idcliente");

$rcliente = $stagm->FETCHALL(PDO::FETCH_ASSOC);

print "<pre>";
print_r($rcliente);
print "</pre>";
?>