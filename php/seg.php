<?php
function daniela($cad){
    $cad=htmlentities($cad);
    $cad=strtolower($cad);
    $cad=str_replace("delete","",$cad);
    $cad=str_replace("/","",$cad);
    $cad=str_replace("*","",$cad);
    $cad=str_replace("union","",$cad);
    $cad=str_replace("where","",$cad);
    $cad=str_replace("from","",$cad);
return $cad;}
?>