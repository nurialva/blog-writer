<?php

$data =  file_get_contents('https://randomuser.me/api/?inc=name,login,dob');
$res = explode ( "username" , $data );
var_dump ( $data );
//$ds = explode ( '"' , $res[1] );
//echo $ds[2];
//echo password_hash ( $ds[2] , PASSWORD_DEFAULT );
