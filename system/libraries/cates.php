<?php
/*==========================================================================*/
		/*      文件名:Category.php                                          */
		/*      功能：实现无限分类的增删改查，用于codeigniter框架，
                也可以修改后用于其它用途。                                     */
        /*      作者：张礼军
        /*      英文名：JayJun             QQ：413920268                     */
		/*      创建时间：2012-08-29                                         */
		/*      最后修改时间：2012-08-31                                      */
		/*      copyright (c)2012 jayjun0805@sina.com                       */
		/*==========================================================================*/
if (!defined('BASEPATH')) exit('No direct script access allowed');
    class CI_Cates {
        private $CI;         //CI对象
        private $tableName;  //要操作的表名
        //表的七个字段
        private $cat_id;        //分类ID
        private $cat_fid;   //父分类ID
        private $cat_name;   //分类名称
        private $cat_rank;       //分类排序，在同一父级下有多级时，用于排序
        private $content;    //分类介绍
        private $cat_level;      //分类等级,即当前目录的级别
        private $cat_display;    //分类显示状态
        //所取分类的深度
        private $depth = 0;
        private $startLevel = 0;
            /**
             * 构造函数
             * @param $arr 参数包括表名，及分类表的七个字段名，如果没有定义，则采用默认，
         * 默认值
            * 表名：category       
            * 分类ID：cid       
            * 父ID：cat_fid       
            * 分类名称：cat_name
            * 分类排序：sort
            * 分类介绍：content
            * 分类等级：level
            * 分类显示状态：display
            */
        public function __construct($arr = array())
        { 
            //通过引用的方式赋给变量来初始化原始的CodeIgniter对象
            $this->CI = &get_instance();
            //初始化表参数
            $this->tableName = (isset($arr['tableName'])) ? $arr['tableName'] : 'category';
            $this->cat_id = (isset($arr['cat_id'])) ? $arr['cat_id'] : 'cat_id';
            $this->cat_fid = (isset($arr['cat_fid'])) ? $arr['cat_fid'] : 'cat_fid';
            $this->cat_name = (isset($arr['cat_name'])) ? $arr['cat_name'] : 'cat_name';
            $this->cat_rank = (isset($arr['cat_rank'])) ? $arr['cat_rank'] : 'cat_rank';
            //$this->content = (isset($arr['content'])) ? $arr['content'] : 'content';
            $this->cat_level = (isset($arr['cat_level'])) ? $arr['cat_level'] : 'cat_level';
            $this->cat_display = (isset($arr['cat_display'])) ? $arr['cat_display'] : 'cat_display';
        }
            /**
             * 从数据库取所有分类数据，返回数组
        */
        public function fetchData($display)
        {
            if ($display)
            {
                $query = $this->CI->db->get_where($this->tableName,array($this->cat_display => 0));
                
            }
                        else
                        {
                            $query = $this->CI->db->get($this->tableName);
                        }
            return $query->result_array();
        }
        /**
         *取某一条分类数据
         *@param $cid 分类ID
        */
        public function fetchOne($cid)
        {
            $this->CI->db->where($this->cat_id,$cid);
            $query = $this->CI->db->get($this->tableName);
            return $query->row_array(1);
        }
        /**
         *取出所有分类信息，返回数组，包括分类名称，一般用在select标签中显示
         * @param $cat_fid 父类ID
             * @param $withself 查下级分类的时候，是否包含自己，默认false不包含。
         * @param $depth    所取分类的深度,值为0表示不限深度,会取所有的子分类。
         * @param $display  分类显示状态，
        */
        public function getAllCategory($cat_fid = 0,$withself = false,$depth = 0,$display = false)
        {
                    $result = array();
            $resArr = $this->fetchData($display); //获取所有分类信息
           // p($resArr);
            if($cat_fid == 0 && $withself)
            {
                $root = array(
                    $this->cat_id => 0,
                    $this->cat_fid => -1,
                    $this->cat_name => '根目录',
                    $this->cat_level => 0,
                    $this->cat_rank => 0
                );
                array_unshift($resArr, $root); 
            }
            //p($resArr);
			
                    if (empty($resArr))
                    {
                            return array();
                    }
            //取得根目录
                    foreach($resArr as $item)
                    {
                            if ($item['cat_fid'] == $cat_fid)   
                            {
									
                                    $level = $item[$this->cat_level];
                            }
                            if ($withself)
                            {
                                    if ($item[$this->cat_id] == $cat_fid)   
                                    {

                                            $result[] = $item;
											$level = $item[$this->cat_level];
											break;
                                    }
                            }
                    }
                    if (!isset($level))
                    {
                            return array();
                    }
                    $this->depth = $depth;
                    $this->startLevel = $level;
           			$nextLevel = $withself ? ($level + 1) : $level;
                    return array_merge($result,$this->getChildren($resArr,$cat_fid,$nextLevel));
            }
            /**
             * 取出某一分类下的所有ID，返回数组，cat_fid = 0为根目录
             * @param $cat_fid   父类ID
             * @param $widthself  取子分类时，是否包含自己,默认不包含
             * @param $depth      要读取的层级深度，默认查出所有子分类
            */
        public function getAllCategoryId($cat_fid = 0,$widthself = false,$depth = 0,$display = false)
        {
            $idArr = array();
                    if ($widthself)
                    {
                            array_push($idArr,$cat_fid);
                    }
                        $cate = $this->getAllCategory($cat_fid,$widthself,$depth,$display);
                    foreach($cate as $item)
                    {
                            $idArr[] = $item[$this->cat_id];
                    }
                    return $idArr;
        }
        /**
             * 用于在下拉列表框中使用
             * @param $fatheriId 父类ID
             * @param $widthself 若取子分类的时候是否获取本身
         * @param $depth     分类深度
         * @param $display   分类显示状态
         * @param $selectId  用于编辑分类时自动设置默认状态为selected
        */
        public function getOptionStr($cat_fid = 0,$withself = false,$depth = 0,$display = false,$selectId = 0)
        {
            $str = '';
            $cate = $this->getAllcategory($cat_fid,$withself,$depth,$display);
            if (!empty($cate))
            {
                $line = '┣';
                foreach($cate as $item)
                {
                    $selected = '';
                    if ($selectId != 0 && $item[$this->cat_id] == $selectId)
                    {
                        $selected = 'selected';
                    }
                    $str .= '<option '.$selected.' value="'.$item[$this->cat_id].'">'.$line.str_repeat('━',($item[$this->cat_level] - $this->startLevel)*2).$item[$this->cat_name].'</option>';
                }
            }
            return $str;
        }
        /**
         * 用于列表显示，按ul li标签组织
         * @param $cat_fid   父分类ID
         * @param $widthself  若取子分类的时候是否获取本身
         * @param $widthHref  是否提供超链接，即编辑和删除链接
         * @param $depth      分类深度
         */
        public function getListStr($cat_fid = 0,$widthself = false,$withHref = true,$depth = 0,$display = false)
        {
            //开头
            $str = '';
            $startLevel = -1;
            $preLevel = 0;
            $cate = $this->getAllCategory($cat_fid,$widthself,$depth,$display);
			//print_r($cate);     
            if (!empty($cate))
            {
                foreach($cate as $item)
                {
                    if ($startLevel < 0)
                    {
                        $startLevel = $item[$this->cat_level];
                    }
                    if ($item[$this->cat_level] < $preLevel) {
                        $str .='</li>'.str_repeat('</ul></li>',$preLevel - $item[$this->cat_level]);
                    }
                    elseif ($item[$this->cat_level] > $preLevel) {
                        $str .='<ul>';
                    }       
                    else
                    {
                        $str .='</li>';
                    }  
					
                    if ($withHref && $item[$this->cat_id]!= 0)
                    {
                        $str .= '<tr>
								  <td>'.str_repeat(' ',($item[$this->cat_level]-$this->startLevel)*4).($this->isChildren($item[$this->cat_id]) ? "+" : "-").'</td>
								  <td align="left">'.$item[$this->cat_name].'</td>
								  <td>'.$item[$this->cat_fid].'</td>
								  <td></td>
								  <td></td>
								  <td>'.$item[$this->cat_rank].'</td>
								  <td><a href="'.site_url('cate/edit/'.$item[$this->cat_id]).'" class="mr50 ml200">edit</a>  <a onclick=\'return confirm("Are your sure to delete?");\' href="'.site_url('cate/delete/'.$item[$this->cat_id]).'">del</a></td>
								</tr>';
                    }
                    else
                    {
                        $str .= '<li>'.$item[$this->cat_name];
                    }
                                        
                    $preLevel = $item[$this->cat_level];
                }
            }
            //收尾
            $str .=str_repeat('</li></ul>',$preLevel - $startLevel + 1);
            return $str;
        }
            /**
             * 增加分类
             * @param $cat_fid 父类ID
         * @param $cat_name 分类名称
         * @param $content  分类介绍
         * @param $sort     分类排序， 只对同一级下的分类有用
         * @param $display  分类显示状态
            */
        public function addCategory($cat_fid,$cat_name,$content,$sort,$display)
        {
            //先获取父类的类别信息
            $parentInfo = $this->fetchOne($cat_fid);
            //p($parentInfo);
            //获取分类的分类级别
            if (isset($parentInfo[$this->cat_level]))
            {
                $level = $parentInfo[$this->cat_level];     
            }
            else
            {
                $level = 0;
            }
            $data = array(
                $this->cat_fid => $cat_fid,
                $this->cat_name => $cat_name,
                $this->content => $content,
                $this->cat_rank => $sort,
                $this->cat_level => $level + 1,
                $this->cat_display => $display
            );       
            $this->CI->db->insert($this->tableName,$data);
            return $this->CI->db->affected_rows();
        }
        /**
             * 删除分类
             * @param $cid 要删除的分类ID
             * @param $widthChild 是否删除下面的子分类，默认会删除
        */
        public function delCategory($cid,$widthChild = true)
        {
            if ($widthChild)
            {
                $idArr = $this->getAllCategoryId($cid,true);
                $this->CI->db->where_in($this->cat_id,$idArr);
            }
            else
            {
                $this->CI->db->where($this->cat_id,$cid);
            }
            $this->CI->db->delete($this->tableName);
            return $this->CI->db->affected_rows();
        }
            
        /**
         * 更新分类
         * @param $cid        要编辑的分类ID
         * @param $cat_fid    父类ID
         * @param $cat_name 分类的名称
         * @param $sort     分类排序
         * @param $display  分类显示状态
         */
        function editCategory($cid,$cat_fid,$cat_name,$content,$sort,$display)
        {
            //先获取父分类的信息
            $parentInfo = $this->fetchOne($cat_fid);
            //获取当前等级
            if(isset($parentInfo[$this->cat_level]))
            {
                $level = $parentInfo[$this->cat_level];
            }
            else
            {
                $level = 0;
            }
            $currentInfo = $this->fetchOne($cid);
            //p($currentInfo);
            $newLevel = $level + 1;
            $levelDiff = $newLevel - $currentInfo[$this->cat_level];
            //修改子分类的level
            if(0 != $levelDiff)
            {
                $childIdArr = $this->getAllCategoryId($cid);
                foreach($childIdArr as $item)
                {
                    $this->CI->db->set($this->cat_level, $this->cat_level.'+'.$levelDiff, FALSE);
                    $this->CI->db->where($this->cat_id, $item);
                    $this->CI->db->update($this->tableName);
                }
            }
            //修改自己的信息
            $data = array(
                $this->cat_fid => $cat_fid,
                $this->cat_name => $cat_name,
                $this->cat_level => $newLevel,
                $this->cat_rank => $sort,
                $this->cat_display => $display,
            );               
            $this->CI->db->where($this->cat_id, $cid);
            $this->CI->db->update($this->tableName, $data);
            return $this->CI->db->affected_rows();
        } 
        /**
             * 按顺序返回分类数组,用递归实现
             * @param unknown_type $cateArr
             * @param unknown_type $cat_fid
             * @param unknown_type $level
            */
        private function getChildren($cateArr,$cat_fid=0,$level = 1)
        {
            if($this->depth != 0 && ($level >=($this->depth + $this->startLevel)))
            {
                return array();
            }
            $resultArr = array();
            $childArr = array();
                
            //遍历当前父ID下的所有子分类
            foreach($cateArr as $item)
            {
                if($item[$this->cat_fid] == $cat_fid && ($item[$this->cat_level] == $level))
                {
                    //将子分类加入数组
                    $childArr[] = $item;
                }
            }
            if(count($childArr) == 0)
            {
                //不存在下一级，无需继续
                return array();
            }
            //存在下一级，按sort排序先
            //usort($childArr,array('Category','compareBysort'));   
            foreach($childArr as $item)
            {
                $resultArr[] = $item;
                $temp = $this->getChildren($cateArr,$item[$this->cat_id],($item[$this->cat_level] + 1));   
                if(!empty($temp))
                {
                    $resultArr = array_merge($resultArr, $temp);
                }                               
            }               
            return $resultArr;
        }
            
        //比较函数,提供usort函数用
        private function compareBysort($a, $b)
        {
            if ($a == $b)
            {
                return 0;
            }
            return ($a[$this->cat_rank] > $b[$this->cat_rank]) ? +1 : -1;       
        }
            
            //判断是否有子类别
        function isChildren($id)
        {
                    //从数据库中取出只有cat_fid字段的数据，返回数组
                    $this->CI->db->select($this->cat_fid);
                    $query = $this->CI->db->get($this->tableName);
                    $resArr = $query->result_array();
            foreach ($resArr as $v)
            {
                            $arr[] = $v[$this->cat_fid];
                    }
                    return (in_array($id,array_unique($arr))) ? true : false;
            }
            
            //判断状态是否启用
        function isDisplay($id)
        {
                    $query = $this->fetchOne($id);
                    return ($query[$this->cat_display] == 1) ? true : false;
            }
    }