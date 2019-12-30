<?php
/**
 * 微信模板推送 WxTemple.class..php
 * ============================================================================
 * 版权所有 (C) 2015-2016 壹尚科技有限公司，并保留所有权利。
 * 网站地址:   http://www.ethank.com.cn
 * ----------------------------------------------------------------------------
 * 许可声明：这是一个开源程序，未经许可不得将本软件的整体或任何部分用于商业用途及再发布。
 * ============================================================================
 * Author: 勾国印 (gouguoyin@ethank.com.cn) 
 * Date: 2016年5月18日 下午11:23:40  
*/
namespace Think;
class sendMessage {
    // 推送模版消息
    function getAuthParam() {
        $apiKey = C('WEIXIN_ENCODINGAESKEY');
        $apiSecret = C('WEIXIN_APPSECRET');
        $timestamp = time();
        return array(
            'apiKey' => $apiKey,
            'timestamp' => $timestamp,
            'sig' => md5($apiKey . $apiSecret . $timestamp),
        );
    }
    function sendTempleMessage($data) {
        $param['type'] = 'template';
        $param['templateId'] = '3PPGzprkI3EEPcSR1ELci8WFwnQ19zfV0caE26y4AmQ';
        $param['topcolor'] = '#04a7f9';
        //模版消息跳转地址
      //  $param['url'] = $data['url'];
        $param['toUsers'] = $data['openid'];
        $param['data'] = $this->getCardTemplateData($data['title'],$data['username'],$data['applytime'],$data['remark']);
        $send = $this->sendTempleMsg($param);
    }

    //模版消息
    function getCardTemplateData($title,$username,$times,$remark) {
        $templateData = array(
                'first' => array(
                        'value' => $title,
                        'color' => '#0e356e'
                ),
                'username' => array(
                        'value' => $username,
                        'color' => '#2832e1'
                ),
                'applyTime' => array(
                        'value' => $times,
                        'color' => '#0e356e'
                ),
                'Remark' => array(
                        'value' => $remark,
                        'color' => '#0e356e'
                )
        );
        return json_encode($templateData);
    }

    function sendDeliverTempleMessage($data) {
        $param['type'] = 'template';
        $param['templateId'] = 'N-SQ82B-F19ABF-hH84nt-2hacfucnKe7iynnQLyalY';
        $param['topcolor'] = '#04a7f9';
        //模版消息跳转地址
        $param['url'] = $data['url'];
        $param['toUsers'] = $data['openid'];
        $param['data'] = $this->getDeliverCardTemplateData($data['title'],$data['order_id'], $data['remark']);
        $send = $this->sendTempleMsg($param);
    }

    //模版消息
    function getDeliverCardTemplateData($title,$order_id,$remark) {
        $templateData = array(
                'first' => array(
                        'value' => $title,
                        'color' => '#0e356e'
                ),
                'OrderSn' => array(
                        'value' => $order_id,
                        'color' => '#2832e1'
                ),
                'OrderStatus' => array(
                        'value' => '已发货',
                        'color' => '#0e356e'
                ),
                'remark' => array(
                        'value' => $remark,
                        'color' => '#0e356e'
                )
        );
        return json_encode($templateData);
    }

    //set temple by one
    function sendTempleMsg($param) {
        $sendParam = array(
            'a' => 'Send',
            'm' => 'sendTemplate'
        );
        $sendParam = array_merge($this->getAuthParam(),$sendParam);
        $sendParam = array_merge($param, $sendParam);
        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, 'api.socialjia.com/index.php');
        curl_setopt($curl, CURLOPT_TIMEOUT, 30);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($curl, CURLOPT_POST, 1);
        $body = http_build_query($sendParam);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($curl);
        $httpCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        $httpInfo = curl_getinfo($curl);
        curl_close($curl);
        return $response;
    }

}
