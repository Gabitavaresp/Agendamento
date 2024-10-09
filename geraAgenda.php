<?php 
require_once 'conexao.php';

$pdo = conecta();

$stmt = $pdo->query("select * from procedimentos");
$rpro = $stmt->fetchAll(PDO::FETCH_ASSOC);

$stmp = $pdo->query("SELECT * FROM parametros WHERE idparametros = 1");
$rpar = $stmp->fetch(PDO::FETCH_ASSOC);

$hini = strtotime($rpar['hora_ini']);
$hfim = strtotime($rpar['hora_fin']);
$hiin = strtotime($rpar['int_ini']);
$hifi = strtotime($rpar['int_fim']);
$indice = 0;

?>
<html>
<head>
	<meta charset="utf-8">
	<title>Geração de Agenda</title>
	<link rel="stylesheet" type="text/css" href="../css/bootstrap.min.css">
	<script src="../js/bootstrap.js" type="text/javascript"></script>
</head>
<body> 
	<div class="div container">
		<form method="POST">
			<h3 class="h3">Selecione o procedimento: </h3>
			<div class="form-check">
				<input type="radio" class="form-check-input" name="procedimento" value="0" checked><?php echo " Todos";?><br>
				<?php foreach ($rpro as $r) { ?>
					<input type="radio" name ="procedimento" class="form-check-input" value="<?php echo $r['idprocedimentos']; ?>"><?php echo $r['descricao_procedimento'];?><br>
				<?php } ?>
			</div>
			<br>
			<h3 class="h3">Selecione o periodo:</h3>
			<div class="form-check">
				<label>Mes:</label> 
				<select name="mes" class="form-control col-6" id="mes">
					<option>Selecione</option>
					<option value="01">Janeiro</option>
					<option value="02">Fevereiro</option>
					<option value="03">Março</option>
					<option value="04">Abril</option>
					<option value="05">Maio</option>
					<option value="06">Junho</option>
					<option value="07">Julho</option>
					<option value="08">Agosto</option>
					<option value="09">Setembro</option>
					<option value="10">Outubro</option>
					<option value="11">Novembro</option>
					<option value="12">Dezembro</option>
				</select>
			</div>
			<br>
			<div class="form-check">
				<label>Dia Inicial:</label>
				<select name="diai" class="form-control col-6" id="diai"></select>
			</div>
			<br>
			<div class="form-check">
				<label>Dia Final:</label>
				<select name="diaf" class="form-control col-6" id="diaf"></select>
			</div>
			<br>
			<input type="submit" class="btn btn-primary" name="btnGera" value="Gerar">
		</form>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> 
		<script>
			$('#mes').on('change', function() {
				var dinicio = document.getElementById("diai");
				var dfim = document.getElementById("diaf");
				var data = new Date();
				var mes = this.value;
				var ano = data.getFullYear();
				var numeroDias = new Date(ano, mes, 0).getDate();
				while (dinicio.length) {
        			dinicio.remove(0);
    			}
    			while (dfim.length) {
        			dfim.remove(0);
    			}
				for (var i = 1 ; i <= numeroDias; i++) {
					$("#diai").append("<option value="+i+">"+ i +"</option>");
				}

				for (var f=1;f <= numeroDias; f++){
					$('#diaf').append('<option value='+f+'>'+ f +'</option>');
				}
			});
		</script>
	</div>
</body>
</html>
<?php
if (isset($_POST['btnGera'])){
	$proced = isset($_POST['procedimento'])? $_POST['procedimento']: null;
	$mes = isset($_POST['mes'])? $_POST['mes']: null;
	$diai = isset($_POST['diai'])? intval($_POST['diai']): null;
	$diaf = isset($_POST['diaf'])? intval($_POST['diaf']): null;

	if ($proced == 0){
		foreach($rpro as $r){
			for($d = $diai; $d <= $diaf; $d++){
				$dt_agenda = date('Y').'-'.strval($mes).'-'.strval($d);
				$dt_ag = strval($d).'-'.strval($mes).'-'.date('Y');
				if(date('w', strtotime($dt_ag) != 0 )){
					for($hi = $hini; $hi < $hfim; $hi = strtotime('+60 minutes', $hi)){
						if ($hi < $hiin || $hi >= $hifi){
							$indice++;
							$hr_comeca = date('H:i', $hi);
							$horarios[$indice] = array(
								"data" => $dt_agenda,
								"horario" => $hr_comeca,
								"procedimento" =>$r['idprocedimentos'],
								"cliente" => 0,
								"status" => "A"
							);
						}
					}
				}
			}
		}
	}else{
		for($d = $diai; $d <= $diaf; $d++){
			$dt_agenda = date('Y')."-".strval($mes).'-'.strval($d);
			$dt_ag = strval($d).'-'.strval($mes).'-'.date('Y');
			if(date('w', strtotime($dt_ag)) != 0){
				for($hi = $hini; $hi < $hfim; $hi = strtotime('+60 minutes', $hi)){
					if ($hi < $hiin || $hi >= $hifi){
						$indice++;
						$hr_comeca = date('H:i', $hi);
						$horarios[$indice] = array(
								"data" => $dt_agenda,
								"horario" => $hr_comeca,
								"procedimento" =>$proced,
								"cliente" => 0,
								"status" => "A"	);
						}
					}
				}
			}
		}
		if(!empty($horarios)){
			foreach ($horarios as $v) {
				$sql = "insert into agenda values (default, :c, :p, :d, :h, :s)";
				$stmt = $pdo->prepare($sql); 
				$stmt->bindValue(":d",$v['data']);
				$stmt->bindValue(":h",$v['horario']);
				$stmt->bindValue(":p",$v['procedimento']);
				$stmt->bindValue(":c",$v['cliente']);
				$stmt->bindValue(":s",$v['status']);

				$stmt->execute();
			}
			echo "Geração concluida com sucesso !";
		}else{
			echo "O array esta vazio, nada a gerar";
		}
	}
