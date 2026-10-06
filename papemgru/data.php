
<?php
$date = date_create();
date_add($date, date_interval_create_from_date_string('30 days'));
$Vencimento = date_format($date, 'Y-m-d');
echo $Vencimento;
$partesVencimento = explode("-",$Vencimento);
$dataVencimento=$partesVencimento[2].$partesVencimento[1].$partesVencimento[0];
echo "<br>".$dataVencimento;
?>
