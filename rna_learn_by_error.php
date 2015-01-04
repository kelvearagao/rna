<?php
	
	echo '** Aprendizado pelo erro **';
	echo '<br><br>';

	$tam = 4;
	$mult = array();

	
	// Abrindo aquivos

	$arq_caract = fopen('caracteristicas.txt', 'r+');
	
	if ($arq_caract == FALSE)
	{
		echo '* Erro ao abrir caracteristicas.txt<br>';
	}
	else
	{
		$total = fscanf($arq_caract, "n = %d");
		$tam   = fscanf($arq_caract, "tam = %d");

		echo '* Sucesso ao abrir caracteristicas.txt<br>';
		echo '** Passos de tempo    = '. $total[0].'<br>';
		echo '** Numero de sinapses = '. $tam[0].'<br><br>';
	}

	$arq_sinais = fopen("sinais.txt", "r");
	echo ($arq_caract == FALSE) ? '* Erro ao abrir sinais.txt<br>' : '* Sucesso ao abrir sinais.txt<br>';

	$arq_pesos  = fopen("pesos.txt", "r+");
	echo ($arq_caract == FALSE) ? '* Erro ao abrir pesos.txt<br>' : '* Sucesso ao abrir pesos.txt<br>';

	// Fim da abertura dos arquivos
	
	echo '<hr>';

	$sinal = fscanf($arq_sinais, "%d %d %d %d");

	echo 'Sinal de entradda: ';

	for ($i = 0; $i < $tam[0]; $i++)
	{
		echo $sinal[$i];
	}

	echo '<br>';
	echo '<table border="1">';
	echo '<th>0</th>';
	echo '<th>1</th>';
	echo '<th>2</th>';
	echo '<th>3</th>';
	echo '<th>soma</th>';
	echo '<th>saida</th>';

	for ($n = 0; $n < $total[0]; $n++) 
	{
		$peso = fscanf($arq_pesos, "%f %f %f %f");
		
		

		echo '<tr>';
		for($i = 0; $i < $tam[0]; $i++) 
		{
			$mult[$i] = $sinal[$i] * $peso[$i];

			echo '<td>'.$peso[$i].'</td>';	
		}

		$soma = array_sum($mult);
		$saida = ($soma >= 0) ? 1 : 0;

		echo '<td>'.$soma.'</td>';
		echo '<td>'.$saida.'</td>';
		echo '</tr>';
	}
	
	echo '</table>';

	if ($saida == 0) 
	{
		$taxa_apr = 0.1;
		$alvo = 1;
		$erro = $alvo - $saida;
		
		echo '<hr>';
		echo 'Taxa = '.$taxa_apr.'<br>';
		echo 'Erro = '.$erro.'<br>';
		echo '<br>';

		$novo_peso = array();
		$linha = '';

		for($j = 0; $j < $tam[0]; $j++) 
		{
			$delta = $taxa_apr * $erro * $sinal[$j];
			$novo_peso[$j] = $peso[$j] + $delta;

			echo 'Sinal = '.$sinal[$j];
			echo '; Peso = '.$peso[$j];
			echo '; Delta = '. $delta;
			echo '; Novo peso = '.$novo_peso[$j];
			echo '<br>';

			$mult[$j] = $sinal[$j] * $novo_peso[$j];

			$space = ($j < ($tam[0] - 1)) ? "\t" : "\n";
			$linha = $linha . $novo_peso[$j] . $space;
		}

		$soma = array_sum($mult);
		$saida = ($soma >= 0) ? 1 : 0;

		echo '<br>';
		echo 'Soma = '.$soma.'<br>';
		echo 'Saida = '.$saida;
		echo '<hr>';

		$result = fwrite($arq_pesos, $linha);

		$linha = 'n = ' . ($total[0] + 1);
		//echo $linha;
		$result = fseek($arq_caract, 0);
		echo ($result == 0) ? 'reposicionado<br>' : 'falha ao reposicionar<br>';

		$result = fwrite($arq_caract, $linha);
		echo ($result == FALSE) ? 'erro de escrita<br>' : 'sucesso na escrita<br>';
		
		
	}

	fclose($arq_pesos);
	fclose($arq_caract);
	fclose($arq_sinais);
	
/*
	$modo = "r+";
	$arq = fopen($nome_do_arq, $modo);
	$line = "n\t=\t2";
	fwrite($arq, $line);
	fclose($arq);

	$modo = "a+";
	$arq = fopen($nome_do_arq, $modo);
	fwrite($arq, "peso\t=\t0.2\t0.2\t0.2\t0.2");
	fclose($arq);*/