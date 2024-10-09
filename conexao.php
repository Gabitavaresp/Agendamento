<?php

function conecta() {
	try {
		$pdo = NEW PDO("mysql:host=localhost; dbname=salaofinal", "root", "");
		$pdo->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, "SET NAMES 'utf8'");
		$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		return $pdo;
	} catch (PDOException $e) {
		echo "Erro: " . $e->getMessage();
		return false;
	}
}

?>