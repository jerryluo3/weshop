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
	$addtime = $_POST['TransTime'];
	/*
		商家自行检测商家订单状态，避免重复处理，而且请检查fee的值与订单需支付金额是否相同
	*/
	//$db->query(get_sql("insert into {pre}cash (oid,account,addtime) values ('".$oid."','".$fee."','".$addtime."')"));
	$uid = substr($oid,14,-1);
	$ptype = substr($oid,-1);
	$daytotal = get_vip_date($ptype);
	$now = time();
	$user = $db->getonerow(get_sql("select id,type,daytotal,expires,oid,paid from {mpre}member where id=".$uid));
	if($user){
		//if($user['oid'] !== $oid){
		if($user['oid'] == $oid && $user['paid'] == '1'){
			//echo 'OK';
		}else{
			if($ptype==0){
			  $wanbi=$_POST['Ext1']+$user['wanbi'];
			  $db->query(get_sql("update {mpre}member set oid = '".$out_trade_no."',wanbi = '".$wanbi."',paid = '1' where id=".$uid));
					//记录万币变化明细
					$reason='在线充值-SFT';
					$wanbicha=$_POST['Ext1'];
					if($wanbicha>0){
					  $sql="insert into wanyiwang_member_wanbi(number,uid,ctime,reason) values('".$wanbicha."','".$id."','".time()."','".$reason."')";
					  $db->query(get_sql($sql));
					}
					//
			}else{
			
			  if($user['expires'] > $now){//会员还未到期
				  $temp_userexpires = strtotime(date("Y-m-d",$user['expires'])) + 86399;
				  $temp_daytotal = $daytotal*86400;
				  $temp_expires = ($temp_userexpires + $temp_daytotal);
			  }else{//会员已到期
				  $temp_expires = (strtotime(date("Y-m-d",$now)) + $daytotal*86400 + 86399);
			  }
			  $paid = '1';
			  $db->query(get_sql("update {mpre}member set type = 1,daytotal = '".$daytotal."',expires = '".$temp_expires."',oid = '".$oid."',paid='".$paid."' where id=".$uid));
			  
				//更新剩余下载次数
				$temp_times['date']=$temp_expires;
				$temp_times['times']=$ptype==1?$yeargive:$monthgive; //判断是否年费  从配置文件中获取赠送次数
					if($ptype==3){
					$temp_times['times']=$yeargive*3;
					}else if($ptype==2){
					$temp_times['times']=$yeargive*2;
					}
				$userdowntime = $db->getonerow(get_sql("select * from wanyiwang_member_downtime where uid=".$uid));
				if($userdowntime){
					$times=json_decode($userdowntime['downtimes'],true);
					$times[]=$temp_times;
					$downtimes=json_encode($times);
					$db->getonerow(get_sql("update wanyiwang_member_downtime set uid='".$uid."',downtimes='".$downtimes."' where uid=".$uid));
				
				}else{
					$times[]=$temp_times;
					$downtimes=json_encode($times);
					$db->getonerow(get_sql("insert into  wanyiwang_member_downtime (uid,downtimes)  value ('".$uid."','".$downtimes."')"));
				}
				//更新剩余下载次数结束
			  
			}
		}
	}
	
	echo "<script>location.href='../../success.php';</script>";
	echo 'OK';
	
}else{
	echo 'Error';
}