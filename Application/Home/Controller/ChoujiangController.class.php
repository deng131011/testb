<?php 

/** 

 *  

 * 

 * 

 */ 
namespace Home\Controller;
class ChoujiangController extends HomeController { 

     //抽奖的开始时间 

    //开源代码phpfensi.com 

    var $begin_time="2012-12-25 14:00:00"; //开始时间  0-不限制 

    //抽奖的结束时间 

    var $stop_time="0";  //结束时间  0-不限制 

    //本次抽奖的奖项信息，必须按照从大到小的顺序进行填写，id为奖次，prize为中奖信息,v为中奖概率,num为奖品数量 

    //需要注意的是，该处也必须包含不中奖的信息，概率从小到大进行排序 

    var $prize_arr = array( 

        '0' => array('id' => 1, 'prize' => '44元购买1G/年空间', 'v' => 1,'num'=>1), 

        '1' => array('id' => 2, 'prize' => '55元购买1G/年空间', 'v' => 1,'num'=>2), 

        '2' => array('id' => 3, 'prize' => '66元购买1G/年空间', 'v' => 1,'num'=>2), 

        '3' => array('id' => 4, 'prize' => '77元购买1G/年空间', 'v' => 1,'num'=>3), 

    ); 

   

    /** 

     * 生成中奖信息，ajax进行请求该方法，需要客户填写QQ号码 

     */ 

    public function make() { 
        $rrtt = mt_rand(1,2);
        echo $rrtt;exit;
         //获取奖项信息数组，来源于私有成员 

        $prize_arr=  $this->prize_arr; 

        foreach ($prize_arr as $key => $val) { 

            $arr[$val['id']] = $val['v']; 

        } 
        //p($arr);exit;
        //$rid中奖的序列号码 

        $rid = $this->get_rand($arr); //根据概率获取奖项id 
        p($rid);exit;
        $str = $prize_arr[$rid - 1]['prize']; //中奖项  
        
      
        //生成一个用户抽奖的数据，用来记录到数据库 

        $data=array( 

            'rid'=>$rid, 

            'pop'=>$str, 

            'qq_no'=>$qq_no, 

            'input_time'=>time() 

        ); 

        //将用户抽奖信息数组写入数据库 

        $Choujiang->add($data); 

        unset($Choujiang); 

         //ajax返回信息 

        $this->ajaxReturn(1, $str); 

    } 

    /** 

     * 根据概率获取中奖号码 

     */ 

    private function get_rand($proArr) { 

        $result = ''; 

        //概率数组的总概率精度  

        $proSum = array_sum($proArr); 
       
        //概率数组循环  

        foreach ($proArr as $key => $proCur) { 

            $randNum = mt_rand(1,$proSum); 

            if ($randNum <= $proCur) { 

                $result = $key; 

                break; 

            } else { 

                $proSum -= $proCur; 

            } 

        } 
       
        unset($proArr); 

        return $result; 

    } 

} 

?> 