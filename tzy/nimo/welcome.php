<?php
require 'common.php';
if(isset($_POST['query'])){
	if($_POST['query'] == 'name'){
		if(isset($_SESSION['login']) && $_SESSION['login'] == 1){
			$data =array('name'=>$_SESSION['data']['name'],'email'=>$_SESSION['data']['email']);
			ajaxreturn($data);
		}
		ajaxreturn(null,'',0);
	}
}
if(isset($_GET['logout'])){
	session_unset();
	session_destroy();
	$_SESSION['login'] = 0;
	setcookie('info','',time()-3600);
	ajaxreturn(true);
}
?>