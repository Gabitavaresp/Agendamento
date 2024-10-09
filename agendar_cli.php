<?php 

if (!isset($_SESSION)){ session_start();}

if (!isset($_SESSION['id'])) {
    session_destroy();
    header("Location: ../login1.php"); 
    exit;
}

include 'conexao.php';
include '../converte_data.php'; 

$id = $_SESSION['id'];

$pdo = conecta();

$stmp = $pdo->query("SELECT * FROM procedimentos");
$rpro = $stmp->FETCHALL(PDO::FETCH_ASSOC);

$stma = $pdo->query("SELECT * from agenda where date(data_agendamento) >= date(now()) and cliente = 0 and status_agendamento = 'A' GROUP BY data_agendamento");
$rdia = $stma->fetchAll(PDO::FETCH_ASSOC);

?>
<html>
<head>
	<meta charset="utf-8">
	<title>Agendar horário</title>
	<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
	<script src="../js/bootstrap.js" type="text/javascript"></script>
</head>
<body>
	<div class="container">
		<form method="POST">
			<br><br>
			<h2> Agendamento de Horários - Cliente
			<div class="form">
				<label>Procedimento</label>
				<select name="procedimento" id='proced'>
					<option>Selecione</option>
					<?php foreach ($rpro as $r) { ?>
						<option value="<?php echo $r['idprocedimentos'];?>"><?php echo $r['descricao_procedimento'];?></option>
					<?php }?>
				</select>
			</div>
			<div class="form">
				<label>Data</label>
				<select name="datas" id="datas" required>
					<option>Selecione</option>}
					option
					<?php foreach($rdia as $a){ ?>
					<option value="<?php echo $a['data_agendamento'];?>"><?php echo DateMyBr($a['data_agendamento']);?></option>
					<?php } ?>
				</select>
			</div>
			<div class="form">
				<label>Horario</label>
				<select name="horario" id="horario" disabled required>
					<option>Selecione</option>
				</select>
			</div>
			<button type="submit" name="btnAgendar" class="btn btn-primary">Agendar</button>
		</form>
	</div>
<?php // include_once 'footer.php';?>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script>
$(document).ready(function() {
    $('#datas').on('change', function() {
    	console.log($('#datas').val());
	    $.ajax({
            type: 'POST',
            url: 'buscadia.php',
            data: {'dia': $('#datas').val(),
        			'procedimento': $('#proced').val()},

            beforeSend: function(xhr) {
                $('#horario').attr('disabled', 'disabled');
                if ($('#datas').val() !== 'ano') {
                   $('#horario').html('<option value="">Carregando...</option>');
                }else{
                   $('#horario').html('<option value="">Selecione</option>');
                }
            },
            success: function(data) {
                if ($('#datas').val() !== '') {
                    $('#horario').html('<option value="">Selecione</option>');
                    $('#horario').append(data);
                    $('#horario').removeAttr('disabled').focus();
                }
            }

        });
  });
});
</script>
</body>
</html>
<?php 
if (isset($_POST['btnAgendar'])){
	$horario = isset($_POST['horario']) ? $_POST['horario'] : null;
	$cliente = $_SESSION['id']
	$agendado = "M";

	$stagenda = $pdo->prepare("UPDATE agenda SET cliente = :c, status_agendamento = :s WHERE idagendamento = :h");

	$stagenda->bindValue(":c", $cliente);
	$stagenda->bindValue(":s", $agendado);
	$stagenda->bindValue(":h", $horario);

	if($stagenda->execute()){
		echo "Agendamento realizado com sucesso";
	}
}
?>