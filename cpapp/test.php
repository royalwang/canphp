<?php


set_time_limit(0);
$a = $_GET['a'];
if($a == 1) {
  while(true) {
    session_start();
    if(!empty($_SESSION['a'])) {
      echo $_SESSION['a'];
      file_put_contents('test.data',$_SESSION['a']);
	  exit;
    }
    session_destroy();
    sleep(3);
  }
}else {
	session_start();
  $_SESSION['a'] = $a;
  //session_write_close();
}