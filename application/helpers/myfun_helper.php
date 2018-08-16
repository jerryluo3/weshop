<?php

if ( ! function_exists('utf_substr'))
{
	/**
	 * Random Element - Takes an array as input and returns a random element
	 *
	 * @param	array
	 * @return	mixed	depends on what the array contains
	 */
	function utf_substr($str,$len){
		if(strlen($str) > $len){
			for($i=0;$i<$len;$i++){
				$temp_str=substr($str,0,1);
				if(ord($temp_str) > 127){
					$i++;
					if($i<$len){
						$new_str[]=substr($str,0,3);
						$str=substr($str,3);
					}
				}else{
					$new_str[]=substr($str,0,1);
					$str=substr($str,1);
				}
			}
			$t_str = join($new_str);
			return $t_str.'...';
		}else{
			return $str;
		}
		
        
    }
}

if ( ! function_exists('format_content'))
{
	/**
	 * Random Element - Takes an array as input and returns a random element
	 *
	 * @param	array
	 * @return	mixed	depends on what the array contains
	 */
	function format_content($str){
		$str = htmlspecialchars(strip_tags(stripslashes($str)));
		$str = str_replace('&amp;','',$str);
		$str = str_replace('nbsp;','',$str);
		$str = str_replace(' ','',$str);
        return $str;
    }
}

if ( ! function_exists('getthumb'))
{
	/**
	 * Random Element - Takes an array as input and returns a random element
	 *
	 * @param	array
	 * @return	mixed	depends on what the array contains
	 */
	function getthumb($pic,$width=250,$height=180){
		$thumb = substr($pic,0,strlen($pic)-4).'_thumb'.substr($pic,-4);
		return $thumb;
    }
}







?>