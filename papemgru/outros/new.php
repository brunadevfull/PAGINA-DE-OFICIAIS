<?php

error_reporting(E_ALL);
ini_set('display_errors', 'On'); 

    function getOracleConnection()
    {
      if (!function_exists('oci_pconnect'))
        return false;
      $toReturn = oci_pconnect('user', 'pass', 'db');
      if ($testRes = @oci_parse($toReturn, 'SELECT Count(group_type_code) FROM pvo.group_type'))
        if (@oci_execute($testRes))
          if (@oci_fetch_array($testRes))
            return $toReturn;
      oci_close($toReturn);
      if (!function_exists('oci_connect'))
        return false;
      $toReturn = oci_connect('user', 'pass', 'db');
      if ($testRes = @oci_parse($toReturn, 'SELECT Count(group_type_code) FROM pvo.group_type'))
        if (@oci_execute($testRes))
          if (@oci_fetch_array($testRes))
            return $toReturn;
      oci_close($toReturn);
      if (!function_exists('oci_new_connect'))
        return false;
      $toReturn = oci_new_connect('user', 'pass', 'db');
      if ($testRes = @oci_parse($toReturn, 'SELECT Count(group_type_code) FROM pvo.group_type'))
        if (@oci_execute($testRes))
          if (@oci_fetch_array($testRes))
            return $toReturn;
      oci_close($toReturn);
      return false;
    }
?>