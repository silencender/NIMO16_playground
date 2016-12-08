<?php
namespace Index\Controller;
use Think\Controller;
class IndexController extends Controller {
	public function index(){
		$model = D('user');
		if(isset($_SESSION['login']) && $_SESSION['login'] == 1){
			redirect("welcome");
		}
		if(isset($_POST['query'])){
			if($_POST['query'] == 'time'){
				$this->ajaxReturn(time());
			}
			if($_POST['query'] == 'is_exist'){
				$username = $_POST['username'];
				$email = $_POST['email'];
				$data = array('valid_user'=>false,'valid_email'=>false);
				if($model->fetch($username) == false){
					$data['valid_user'] = true;
				}
				if($model->fetch(null,$email) == false){
					$data['valid_email'] = true;
				}
				$this->ajaxReturn($data);
			}
			if($_POST['query'] == 'auto'){
				if(isset($_COOKIE['info'])){
					$info = $_COOKIE['info'];
					$info = unserialize($info);
					$username = $info['name'];
					$password = $info['password'];
					if($data = $model->fetch($username)){
						$encrypted = md5(C('SALT_1').$data['password'].C('SALT_2'));
						if($encrypted == $password){
							$_SESSION['uid'] = $data['id'];
							$_SESSION['login'] = 1;
							$_SESSION['data'] = array('name'=>$data['username'],'email'=>$data['email']);
							$verify = true;
						}
					}
				}
				$this->ajaxReturn($verify);
			}
		}
		if(isset($_POST['method'])){
			if($_POST['method'] == 'signin'){
				$verify = false;
				if(time()-$_POST['time'] < C('MAX-TIME')){
					$username = $_POST['username'];
					$password = $_POST['pwd'];
					$posttime = $_POST['time'];
					$data = $model->fetch($username,$username);
					if($data){
						$encrypted = md5($data['password'].$posttime);
						if($encrypted == $password){
							$_SESSION['uid'] = $data['id'];
							$_SESSION['login'] = 1;
							$_SESSION['data'] = array('name'=>$data['username'],'email'=>$data['email']);
							$verify = true;
							//var_dump($_SESSION);
							if($_POST['save'] == 'true'){
								$info_save = array('name'=>$data['username'],'password'=>md5(C('SALT_1').$data['password'].C('SALT_2')));
								$info_save = serialize($info_save);
								setcookie('info',$info_save,time()+3600*24);
							}
						}
					}
				}
				$this->ajaxReturn($verify);
			}
			if($_POST['method'] == 'signup'){
				$username = $_POST['username'];
				$email = $_POST['email'];
				$password = $_POST['pwd'];
				$data = false;
				$reg = '/^([a-zA-Z0-9_\-\.]+)@([a-zA-Z0-9_\-\.]+)\.([a-zA-Z]{2,5})$/';
				if(preg_match($reg, $username) && !preg_match($reg, $email)){
					$this->ajaxReturn(false);
				}
				if($model->fetch($username,$username) == false){
					if($data = $model->insert($username,$email,$password)){
						$info = $model->fetch($username);
						$_SESSION['uid'] = $info['id'];
						$_SESSION['login'] = 1;
						$_SESSION['data'] = array('name'=>$info['username'],'email'=>$info['email']);
					}
				}
				$this->ajaxReturn($data);
			}
		}
		//var_dump($_SESSION);
		$this->display();
	}
}
?>