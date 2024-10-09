<?php
session_start();
session_destroy();
if(!isset($_SESSION)){
	session_start();
}

$_SESSION['venda'] = array(
		'cliente'=> 1 ,					// cliente
		'data' => '12/11/2020',				// data
		'observacao' => "texto qualquer" ,	// observação
		'vl_venda' => 0);					// vl_venda
// $_SESSION['venda']['vl_venda'] += $_SESSION['carrinho']['tot_produto'];
//
for($i=0; $i<3;$i++){
$_SESSION['carrinho']['venda'][$i] = array(
		'produto' => $i, 					// produto
		'quantidade' => 1,					// quantidade
		'preco' => 19.98,		// $v['preco']			// preço
		'tot_produto' =>  19.98); 	// quantidade * $v['preco']
}

print "<pre>";
print_r($_SESSION);
print "<pre>";

include_once '../conexao.php';

$pdo = conn();

$cliente = $_SESSION['venda']['cliente'];
$sqlc = "select * from cliente where idcliente = $cliente";
$stmt = $pdo->query($sqlc);
$stmt->execute();
$res = $stmt->fetch(PDO::FETCH_ASSOC);


$produto = $_SESSION['carrinho']['venda'][0]['produto'];
$sqlp = "select * from produto where idproduto = $produto";
$stmp = $pdo->query($sqlc);
$stmp->execute();
$rs = $stmp->fetch(PDO::FETCH_ASSOC);


echo "Cliente: ". $_SESSION['venda']['cliente']. " - ". $res['nomecliente']."<br>";
echo "Rua: ". $res['rua'];
echo "<hr>";
echo "Data: ". $_SESSION['venda']['data']."\t Observação:". $_SESSION['venda']['observacao']; 