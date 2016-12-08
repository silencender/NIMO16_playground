<?php
namespace Welcome\Controller;
use Think\Controller;
class IndexController extends Controller{
	public function index(){
		$data = false;
		if(isset($_SESSION['login']) && $_SESSION['login'] == 1){
			$data =array('name'=>$_SESSION['data']['name'],'email'=>$_SESSION['data']['email']);
		}
		if(isset($_GET['logout'])){
			session_unset();
			session_destroy();
			setcookie('info','',time()-3600);
			$this->ajaxReturn(true);
		}
		$this->assign($data);
		$this->display();
	}
}
?>