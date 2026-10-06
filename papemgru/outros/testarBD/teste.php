<?php

$dataano = date("Y");
$datames = date("m") + 1;
$dataVencimento = "30".$datames.$dataano;
echo $dataVencimento;

// Connects to the XE service (i.e. database) on the "localhost" machine
$db = "(DESCRIPTION=(ADDRESS_LIST = (ADDRESS = (PROTOCOL = TCP)(HOST = 10.9.17.223)(PORT = 1521)))(CONNECT_DATA=(SID=SPAGNETH)))";
$conn = oci_connect('SPAGNET', 'SPAGNET', $db);
//$conn = oci_connect(‘SPAGNET’, ‘SPAGNET’, ‘10.9.17.223/SPAGNET-HOMOLOG’);
if (!$conn) {
    $e = oci_error();
    trigger_error(htmlentities($e['message'], ENT_QUOTES), E_USER_ERROR);
}

$stid = oci_parse($conn, 'SELECT * FROM employees');
oci_execute($stid);


$conn = oci_connect('user', 'password', 'hostname/L');

if ($conn) {
    echo "Connected to Oracle.";
    oci_close($conn);
}

if (!$conn) {
    echo "Not connected to Oracle.";
}

?>
