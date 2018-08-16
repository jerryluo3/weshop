<?php

/*购物车类
*  采用COOKIES保存信息
*  将信息写到数据表中
*
*/

class carts{
	
	
	var $cart_name = "luo_cart";  //定义购物车名称
	var $totle_price = 0;      //定义总价格
	//var $cookie_time = time() + 14400; //购物车信息保存时间：4小时
	

	
	//获取购物车信息
	function get_cart(){//获取购物车信息
		if(isset($_COOKIE[$this->cart_name])){
			return unserialize(stripslashes($_COOKIE[$this->cart_name]));
		}else{
			return array();	
		}
	}
	
	//添加购物车
	function insert($goodsarr){
		$cart_arr = $this->get_cart();//获取购物车信息
		
		if(isset($cart_arr) && is_array($cart_arr)){//购物车信息不为空
			
			$is_key = 0;
			for($i=0;$i<count($cart_arr);$i++){
				if(isset($cart_arr[$i]) && $cart_arr[$i]['id'] == $goodsarr['id']){
					$temp_i = $i;
					$is_key = 1;
				}
			}
			if($is_key == 1){
				$cart_arr[$temp_i]['num'] += $goodsarr['num'];				
			}else{
				$new_arr['id'] = $goodsarr['id'];
				$new_arr['name'] = $goodsarr['name'];
				$new_arr['img'] = $goodsarr['img'];
				$new_arr['storeid'] = $goodsarr['storeid'];
				$new_arr['pid'] = $goodsarr['pid'];
				$new_arr['price'] = $goodsarr['price'];
				$new_arr['num'] = $goodsarr['num'];
				array_push($cart_arr,$new_arr);
			}
			//die();
		}else{			
			$cart_arr[0]['id'] = $goodsarr['id'];
			$cart_arr[0]['name'] = $goodsarr['name'];
			$cart_arr[0]['img'] = $goodsarr['img'];
			$cart_arr[0]['storeid'] = $goodsarr['storeid'];
			$cart_arr[0]['pid'] = $goodsarr['pid'];
			$cart_arr[0]['price'] = $goodsarr['price'];
			$cart_arr[0]['num'] = $goodsarr['num'];
		}
		//$domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
		setcookie($this->cart_name,serialize($cart_arr),time() + 14400,"/");
	}
	
	//删除购物车中指定的内容
	function del_cart($goods_array_id){
		$cart_arr = $this->get_cart();
		
		unset($cart_arr[$goods_array_id]);
		//$domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
    	setcookie($this->cart_name,serialize($cart_arr),time() + 14400,"/");	
	}
	
	//修改购物车中的信息,修改数量
	function update($goods_array_id,$goods_num=""){
		//$domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
		$cart_arr = $this->get_cart();
		$cart_arr[$goods_array_id]['num'] += $goods_num;
		setcookie($this->cart_name,serialize($cart_arr),time() + 14400,"/");
	}
	
	
	//购物车中总价格
	function total_cart($str=""){
		if($str=="") $str = "price";
		$cart_arr = $this->get_cart();
		if(is_array($cart_arr)){
			foreach($cart_arr as $goods_info){
				if($str == 'price'){
					$this->total_price += $goods_info[$str]*$goods_info['num'];
				}else{
					$this->total_price += $goods_info[$str];	
				}
				
			}
		}
		$this->total_price = empty($this->total_price) ? 0 : $this->total_price;
		return $this->total_price;
	}
	
	
	//统计购物车中有多少商品,品种
	function nums_cart(){
		$cart_arr = $this->get_cart();
		$nums = 0;
		if(!empty($cart_arr)){
			foreach($cart_arr as $goods){
				
				$nums += $goods['num'];	
			}
		}
		$nums = empty($nums) ? 0 : $nums;
		return $nums;
	}
	
	
	//清空购物车
	function clear_cart(){
		$cart_arr = $this->get_cart();
		
		if(isset($_COOKIE[$this->cart_name])){
			unset($_COOKIE[$this->cart_name]);
			
		}		
		if(isset($cart_arr)){
			setcookie($this->cart_name,serialize($cart_arr),time() - 14400,"/");
		}
		//$domain = ($_SERVER['HTTP_HOST'] != 'localhost') ? $_SERVER['HTTP_HOST'] : false;
		
		
		
	}

	
	
}



?>