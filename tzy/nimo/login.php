<?php
require 'common.php';
if(isset($_POST['query'])){
	if($_POST['query'] == 'time'){
		ajaxreturn(time());
	}
	if($_POST['query'] == 'is_exist'){
		$username = $_POST['username'];
		$email = $_POST['email'];
		$data = array('valid_user'=>false,'valid_email'=>false);
		if(sql("SELECT * FROM nimo_user WHERE username = '$username'",'fetch') == false){
			$data['valid_user'] = true;
		}
		if(sql("SELECT * FROM nimo_user WHERE email = '$email'",'fetch') == false){
			$data['valid_email'] = true;
		}
		ajaxreturn($data);
	}
	if($_POST['query'] == 'auto'){
		if(isset($_COOKIE['info'])){
			$info = $_COOKIE['info'];
			$info = unserialize($info);
			$username = $info['name'];
			$password = $info['password'];
			if($data = sql("SELECT * FROM nimo_user WHERE username = '$username'",'fetch')){
				$encrypted = md5('xa2i4fwHHCR4XUlHmQinObnbDSZ1he'.$data['password'].'yCTjkWbFs79poVmJmBzkvoIekk6i0q');
				if($encrypted == $password){
					$_SESSION['uid'] = $data['id'];
					$_SESSION['login'] = 1;
					$_SESSION['data'] = array('name'=>$data['username'],'email'=>$data['email']);
					$verify = true;
				}
			}
		}
		ajaxreturn(null,'',$verify);
	}
}
if(isset($_POST['method'])){
	if($_POST['method'] == 'signin'){
		$verify = false;
		if(time()-$_POST['time'] < $config['max-time']){
			$username = $_POST['username'];
			$password = $_POST['pwd'];
			$posttime = $_POST['time'];
			if($data = sql("SELECT * FROM nimo_user WHERE username = '$username' OR email = '$username'",'fetch')){
				$encrypted = md5($data['password'].$posttime);
				if($encrypted == $password){
					$_SESSION['uid'] = $data['id'];
					$_SESSION['login'] = 1;
					$_SESSION['data'] = array('name'=>$data['username'],'email'=>$data['email']);
					$verify = true;
					//var_dump($_SESSION);
					if($_POST['save'] == 'true'){
						$info_save = array('name'=>$data['username'],'password'=>md5('xa2i4fwHHCR4XUlHmQinObnbDSZ1he'.$data['password'].'yCTjkWbFs79poVmJmBzkvoIekk6i0q'));
						$info_save = serialize($info_save);
						setcookie('info',$info_save,time()+3600*24);
					}
				}
			}
		}
		ajaxreturn(null,'',$verify);
	}
	if($_POST['method'] == 'signup'){
		$username = $_POST['username'];
		$email = $_POST['email'];
		$password = $_POST['pwd'];
		$data = false;
		$reg = '/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/';
		if(preg_match($reg, $username) && !preg_match($reg, $email)){
			ajaxreturn(null,'Please ensure your input',0);
		}
		if(sql("SELECT * FROM nimo_user WHERE username = '$username' OR email = 'email'",'fetch') == false){
			if($data = sql("INSERT INTO nimo_user (username,email,password) VALUES ('$username','$email','$password')",'insert')){
				$info = sql("SELECT * FROM nimo_user WHERE username = '$username'",'fetch');
				$_SESSION['uid'] = $info['id'];
				$_SESSION['login'] = 1;
				$_SESSION['data'] = array('name'=>$info['username'],'email'=>$info['email']);
			}
		}
		ajaxreturn($data);
	}
}