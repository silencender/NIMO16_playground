<?php
$config = getconfig();
session_start();
function getconfig(){
    return include ('config.php');
}
function ajaxReturn($data,$info='',$status=1,$type='')
{
    $config = getconfig();
    // 保证AJAX返回后也能保存日志
    $result  =  array();
    $result['status']  =  $status;
    $result['info'] =  $info;
    $result['data'] = $data;
    if(empty($type)) $type  =   $config['default_type'];
    if(strtoupper($type)=='JSON') {
        // 返回JSON数据格式到客户端 包含状态信息
        header("Content-Type:text/html; charset=utf-8");
        exit(json_encode($result));
    }elseif(strtoupper($type)=='XML'){
        // 返回xml格式数据
        header("Content-Type:text/xml; charset=utf-8");
        exit(xml_encode($result));
    }elseif(strtoupper($type)=='EVAL'){
        // 返回可执行的js脚本
        header("Content-Type:text/html; charset=utf-8");
        exit($data);
    }else{
        // TODO 增加其它格式
    }
}
function sql($sql,$method){
    $config = getconfig();
    $data = false;
    $con = mysqli_connect($config['dbhost'],$config['dbusername'],$config['dbpwd'],$config['dbname']);
    if ($con->connect_errno) {
        die("Connect failed: %s\n".$con->connect_error);
    }
    $result = $con -> query($sql);
    if($method == 'fetch'){
        if($result != false){
            $data = $result -> fetch_assoc();
        }else{
            $data = false;
        }
    }
    elseif ($method == 'insert') {
       $data = $result;
    }
    mysqli_close($con);
    return $data;
}
?>