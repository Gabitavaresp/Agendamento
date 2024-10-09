<?php
session_start();
include_once 'conexao.php';
include_once '../converte_data.php';

$pdo = conecta();

$cliente = $_SESSION['cliente'];

$sql = "SELECT c.nomecliente, c.celular, a.data, a.hora_ini, a.hora_fin, p.nomeprofissional 
	FROM agenda a
	JOIN cliente c
	ON a.cliente = c.idcliente 
	INNER JOIN profissional p 
	ON a.profissional = p.idprofissional"
?>