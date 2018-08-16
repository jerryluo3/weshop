<?php
session_start();
require_once('../../inc/const.php');
require_once('core/core.php');



$shengpay=new shengpay();
$shengpay->setKey('i3kj3k6j5j2j87kl');
if($shengpay->returnSign()){
	/*支付成功*/
	$oid=$_POST['OrderNo'];
	$fee=$_POST['TransAmount'];
	/*
		商家自行检测商家订单状态，避免重复处理，而且请检查fee的值与订单需支付金额是否相同
	*/
	
	//支付成功，可进行逻辑处理！
	//商户系统的逻辑处理（例如判断金额，判断支付状态，更新订单状态等等）......
	$uid = substr($oid,14,-1);
	//$db->query(get_sql("update {mpre}member set type = 1 where id = ".$uid));
	
	$_SESSION['member_type'] = 1;
	//update_member_info($uid,$total_fee);//支付成功修改会员状态
	$ptype = substr($oid,-1);
	$daytotal = get_vip_date($ptype);
	$now = time();
	$user = $db->getonerow(get_sql("select id,type,daytotal,expires,oid from {mpre}member where id=".$uid));
	if($user){
		if($user['oid'] !== $oid){
			if($user['expires'] > $now){//会员还未到期
				$temp_userexpires = strtotime(date("Y-m-d",$user['expires'])) + 86399;
				$temp_daytotal = $daytotal*86400;
				$temp_expires = ($temp_userexpires + $temp_daytotal);
			}else{//会员已到期
				$temp_expires = (strtotime(date("Y-m-d",$now)) + $daytotal*86400 + 86399);
			}
			$_SESSION['member_expires'] = $temp_expires;
			$db->query(get_sql("update {mpre}member set type = 1,daytotal = '".$daytotal."',expires = '".$temp_expires."',oid = '".$oid."' where id=".$uid));	
		}
	}
	
	echo "<script>location.href='../../success.php';</script>";
	echo 'OK';
	
	
	
}else{
	echo 'Error';
}
?>