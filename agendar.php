<?php 

if (!isset($_SESSION)){ session_start();}

if (!isset($_SESSION['id'])) {
    session_destroy();
    header("Location: ../login1.php"); 
    exit;
}

include 'conexao.php';
include '../converte_data.php'; 

$logado = $_SESSION['id'];

$pdo = conecta();

$stmp = $pdo->query("SELECT * FROM profissional");
$rpro = $stmp->FETCHALL(PDO::FETCH_ASSOC);

$stma = $pdo->query("SELECT * from agenda where date(data) >= date(now()) and cliente = 0 and status = 'A' GROUP BY data");
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
			<div class="form">
				<label>Profissional</label>
				<select name="profissional" id='prof'>
					<option>Selecione</option>
					<?php foreach ($rpro as $r) { ?>
						<option value="<?php echo $r['idprofissional'];?>"><?php echo $r['nomeprofissional'];?></option>
					<?php }?>
				</select>
			</div >
			<div class="form">
				<label>Data</label>
				<select name="datas" id="datas" required>
					<option>Selecione</option>}
					option
					<?php foreach($rdia as $a){ ?>
					<option value="<?php echo $a['data'];?>"><?php echo DateMyBr($a['data']);?></option>
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
    	console.log($('#prof').val());
        $.ajax({
            type: 'POST',
            url: 'buscadia.php',
            data: {'dia': $('#datas').val(),
        			'prof': $('#prof').val()},

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
	$id = isset($_POST['horario']) ? $_POST['horario'] : null;
	$cliente = $_SESSION['id'];
	$agendado = "M";

	$stagenda = $pdo->prepare("UPDATE agenda SET cliente = :c, status = :s WHERE idagenda = :id");

	$stagenda->bindValue(":c", $cliente);
	$stagenda->bindValue(":s", $agendado);
	$stagenda->bindValue(":id", $id);

	$stagenda->execute();
	echo "Agendamento realizado com sucesso";
}
?>