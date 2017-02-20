<?php

   class AppAction extends Action{
        // 注册
   	    public function register(){
   	    	$phone = $_GET['phone_data'];
   	    	//先判断手机号码是不是已经注册了
   	        $dd =M('user')->where("`phone`='$phone'")->find();
   	        if ($dd) {
   	           echo -1;
   	           exit ;//和return一样，停止的意思下面不在执行

   	        }
   	    	$arr['phone']= $phone;
   	    	$arr['password'] = md5($_GET['password_data']);
   	     $re =M('user')->add($arr);
   	      echo  $re;
   	    }
         // 获取用户信息
         public  function get_user_info(){
            $phone =$_GET['phone'];
            $arr =M('user')->where("`phone`='$phone'")->find();
            echo json_encode($arr);
         }
        // 登陆
         public function login(){
            $phone =$_GET['phone_data'];
            $password =md5($_GET['password_data']);
            // 先查找用户
            $arr =M('user')->where("`phone`='$phone'")->find();
            // 如果不存在
            if (!$arr) {
               echo -1 ;
               exit ;
            }
              if ($arr['password'] == $password) {
                 echo 1 ;
              }else {
                echo  0 ;
              }
           
         }

         //更改用户信息
         public function mod_user(){
             $phone =$_GET['phone'];             
             $re = M('user')->where("`phone`='$phone'")->save($_GET);
             echo $re ;
         }
        
        // 修改密码
         public function mod_password(){
             $phone =$_GET['phone'];
             $_GET['password'] =md5($_GET['password']);
              $re = M('user')->where("`phone`='$phone'")->save($_GET);
             echo $re ;

         }
         // 添加或者修改地址
         public function add_address(){
            
             $id=$_GET['aid'];
             if ($id) {
               $phone=$_GET['login_phone'];
               $str =M('address')->where("`id`='$id'")->save($_GET);
             } else {
                 $_GET['time'] =time();
                 $str =M('address')->add($_GET);
             }
             
            
             if ($str) {
               echo 1 ;
             } else {
               echo  0  ;
             }
             
         }
         // 获得我的收获地址
          public function get_address(){
             $phone =$_GET['phone'];
             $arr = M('address')->where("`login_phone`='$phone'")->order("id desc")->select();
             echo json_encode($arr);
             
         }

         // 删除地址
         public function del_address(){
           $id =$_GET['id'];
           echo M('address')->where("`id`='$id'")->delete();
         }
         // 提交意见
         public function feedback(){
            $_GET['time'] =time();
            echo M('feedback')->add($_GET);
         }
         // 获得最新的版本
         public function version(){
             $arr =M('version')->order("id desc")->find();
             echo json_encode($arr);
         }
         // 添加话题
          public function add_topic(){
            $_GET['time'] =time();
            echo M('topic')->add($_GET);
         }
         // 获取问答
          public function get_topic(){
            
            $arr = M('topic')->order("id desc")->select();
            foreach ($arr as $key => $value) {
              $phone =$value['phone'];
              $user = M('user')->where("`phone`='$phone'")->find();
              $arr[$key]['user'] =$user ;
              $arr[$key]['time'] =date('Y-m-d H:i:s',$value[time]) ;
            }
            echo json_encode($arr);
         }
        // 首页获取商品
         public function get_index_item(){
             $arr =M('items')->order("id desc")->select();
             echo json_encode($arr);

         }
         // 获得商品详情页
          public function get_item(){
             $id =$_GET['id'];
             $arr =M('items')->where("`id`='$id'")->find();
             $com =M('com')->where("`item_id`='$id'")->order("id desc")->limit(10)->select();
             foreach ($com as $key => $value) {
                $com[$key]['time'] =date('Y-m-d H:i:s',$value['time']);
                $phone =$value['phone'];
                $user = M('user')->where("`phone`='$phone'")->find();
               $com[$key]['user'] =$user ;
             }
             $newarr =array($arr,$com );;
             echo json_encode($newarr);

         }
         // 提交商品评价
         public function add_comd(){
            $_GET['time'] =time();
            echo M('com')->add($_GET);

         }
         // 获得附近商家
         public function get_shop(){
              $arr =M('items')->select();
              // 手动定位一个我们的位置
              $lat =39.5214 ;
              $lng =116.505 ;
              foreach ($arr as $key => $value) {
                  $lat2 = $value['lat'];
                  $lng2 = $value['lng'];
                  $number = $this->get_distance($lat,$lng,$lat2,$lng2);
                  $arr[$key]['number'] = $number;
                  $arr[$key]['lo'] = $this->changeLo($number);
              }
              $new_arr = $this->my_sort($arr,'number');
              echo json_encode($new_arr);
         } 
               //转换距离
               function changeLo($number){
                 if($number < 1000){
                   return $number . 'm';
                 }else{
                   return ceil($number/1000) . 'km';
                 }
               }


               //二维数组排序
            function my_sort($arrays,$sort_key,$sort_order=SORT_ASC,$sort_type=SORT_NUMERIC){
                  if(is_array($arrays)){
                      foreach ($arrays as $array){
                          if(is_array($array)){
                              $key_arrays[] = $array[$sort_key];
                          }else{
                              return false;
                          }
                      }
                  }else{
                      return false;
                  }
                  array_multisort($key_arrays,$sort_order,$sort_type,$arrays);
                  return $arrays;
              }

        
              /**
              * @desc 根据两点间的经纬度计算距离
              * @param float $lat 纬度值
              * @param float $lng 经度值
              */
              function get_distance($lat1,$lng1,$lat2,$lng2){
                $earthRadius = 6367000; //approximate radius of earth in meters

                /*
                Convert these degrees to radians
                to work with the formula
                */

                $lat1 = ($lat1 * pi() ) / 180;
                $lng1 = ($lng1 * pi() ) / 180;

                $lat2 = ($lat2 * pi() ) / 180;
                $lng2 = ($lng2 * pi() ) / 180;

                /*
                Using the
                Haversine formula

                http://en.wikipedia.org/wiki/Haversine_formula

                calculate the distance
                */

                $calcLongitude = $lng2 - $lng1;
                $calcLatitude = $lat2 - $lat1;
                $stepOne = pow(sin($calcLatitude / 2), 2) + cos($lat1) * cos($lat2) * pow(sin($calcLongitude / 2), 2);
                $stepTwo = 2 * asin(min(1, sqrt($stepOne)));
                $calculatedDistance = $earthRadius * $stepTwo;

                return round($calculatedDistance);
              }


   }
 ?>