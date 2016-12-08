<?php
namespace Index\Model;
use Think\Model;
class UserModel extends Model {
	function insert($username,$email,$password){
		$data['username'] = $username;
		$data['email'] = $email;
		$data['password'] = $password;
		return $this->add($data);
	}
	function fetch($username,$email = null){
		return $this->where("username = '$username' OR email = '$email'")->find();
	}
}