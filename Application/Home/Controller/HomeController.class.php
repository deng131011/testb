<?php
// +----------------------------------------------------------------------
// | OneThink [ WE CAN DO IT JUST THINK IT ]
// +----------------------------------------------------------------------
// | Copyright (c) 2013 http://www.onethink.cn All rights reserved.
// +----------------------------------------------------------------------
// | Author: 麦当苗儿 <zuojiazi@vip.qq.com> <http://www.zjzit.cn>
// +----------------------------------------------------------------------

namespace Home\Controller;

use Think\Controller;

/**
 * 前台公共控制器
 * 为防止多分组Controller名称冲突，公共Controller名称统一使用分组名称
 */
class HomeController extends Controller
{

    /* 空操作，用于输出404页面 */
    public function _empty()
    {
        $this->redirect('Index/index');
    }

    protected function _initialize()
    {
        /* 读取站点配置 */
        $config = api('Config/lists');
        C($config); //添加配置

        if (!C('WEB_SITE_CLOSE')) {
            $this->error('站点已经关闭，请稍后访问~');
        }

        $this->head();
        $this->footer();
        $this->getopenid();

    }

    public function getopenid()
    {

        //$open_id = session('bsg_open_id');
        $users_id = session('bsg_user_id');
        //session('bsg_user_id',null);
        if (empty($users_id) || $users_id == '') {

            $this->returnopenid();
            //session('bsg_open_id',$openid);

        } else {
            $jgs = M('usermember')->find($users_id);
            if (empty($jgs[open_id]) || $jgs[open_id] == '') {
                session('bsg_user_id', null);

                $this->returnopenid();

            }
        }

    }

    public function returnopenid()
    {

        $return_url = 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        if (!isset($_GET['code'])) {

            //触发微信返回code码
            $url = createOauthUrlForCode($return_url);
            header('Location:' . $url);

        } else {
            header("Content-type: text/html; charset=utf-8");
            $code = $_GET['code'];
            //获取openid
            $appid = C("WEIXIN_APPID");
            $url   = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $appid . '&secret=' . C("WEIXIN_APPSECRET") . '&code=' . $code . '&grant_type=authorization_code';

            $str    = file_get_contents($url);
            $arr    = json_decode($str, true);
            $openid = $arr['openid'];
			
			session('user_open_id',$openid);
			
            //获取全局acc_token
            $urla         = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . C("WEIXIN_APPID") . '&secret=' . C("WEIXIN_APPSECRET");
            $stra         = file_get_contents($urla);
            $arra         = json_decode($stra, true);
            $access_token = $arra['access_token'];

            //获取openid对应的用户信息
            $accurl = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $access_token . '&openid=' . $openid;

            $infostr = file_get_contents($accurl);
            $infoarr = json_decode($infostr, true);
			
			session('user_open_info',$infoarr);
            //p($infoarr);exit;

            $krtj['open_id'] = array('eq', $openid);
            $users           = M('usermember')->where($krtj)->find();

            if (empty($users['id'])) {
                $arryu['open_id']    = $openid;
                $arryu['nickname']   = $infoarr['nickname'];
                $arryu['headimgurl'] = $infoarr['headimgurl'];
                $arryu['addtime']    = time();

                $user_ids = M('usermember')->add($arryu);
                session('weixin_avatar', $infoarr['headimgurl']);
                if ($user_ids) {
                    session('bsg_user_id', $user_ids);
                }
            } else {
                session('bsg_user_id', $users['id']);
            }

            session('weixinUser', $arryu);
            return $arryu;
        }
    }

    //脚步
    public function footer()
    {

        //中间长条广告
        $lgs['id']     = array('eq', 3);
        $lgs['status'] = array('eq', 1);
        $admid         = M('ad')->where($lgs)->find();
        $this->assign('admid', $admid);

    }

    //头部
    public function head()
    {

        //查询导航
        $nav['pid']    = array('eq', 0);
        $nav['isnav']  = array('eq', 1);
        $nav['status'] = array('eq', 1);
        $navList       = M('category')->where($nav)->field('url,title,id,pid')->order('sort asc')->limit(3)->select();
        foreach ($navList as $ks => $vs) {
            $nav['pid']               = $vs[id];
            $nav['isnav']             = array('eq', 1);
            $nav['status']            = array('eq', 1);
            $navList[$ks][twoNavlist] = M('category')->where($nav)->field('url,title,id,pid')->order('sort asc')->select();

        }

        $this->assign('navList', $navList);

    }

    public function send_post($url, $post_data)
    {

        $postdata = http_build_query($post_data);
        $options  = array(
            'http' => array(
                'method'  => 'POST', //or GET
                'header'  => 'Content-type:application/x-www-form-urlencoded',
                'content' => $postdata,
                'timeout' => 15 * 60, // 超时时间（单位:s）
            ),
        );
        $context = stream_context_create($options);
        $result  = file_get_contents($url, false, $context);
        return $result;
    }
    public function checklogin()
    {

        /*$uid = $_GET['uid'];

        file_put_contents("text.txt","Hello World. Testing!");
        session('[start]');
        session('dr-uid',$uid);
        echo $uid;*/

        $memList = str_replace("_", "", $_GET['key']);

        $memInfo = base64_decode($memList);

        $uid = $memInfo;

        $key       = md5('sourceCode=0005wllzztzdr@v_142779052200003');
        $post_data = array(
            'sourceCode' => 5,
            'key'        => $key,
            'uid'        => $uid,
        );

        $result = $this->send_post('http://c.tzdr.com:999/tzdr-web/getUserInfo', $post_data);

        $memResult = json_decode($result, true);

        if ($memResult['success'] == true) {

            //查询当前会员是否在这边注册
            $memList = $memResult['data'];
            //var_dump($memList);
            $user['uid'] = array('eq', $memList['uid']);

            $userList = M('usermember')->where($user)->find();
            if (!$userList) {

                //添加会员

                $data['uid']       = $memList['uid'];
                $data['mobile']    = $memList['mobile'];
                $data['username']  = $memList['uname'];
                $data['email']     = $memList['email'];
                $data['position']  = getCateId(31, $memList['position']);
                $data['industry']  = getCateId(16, $memList['industry']);
                $data['education'] = getCateId(9, $memList['education']);
                $data['marriage']  = getCateId(27, $memList['marriage']);
                if (!empty($memList['province'])) {

                    $data['nowsprovince'] = $memList['province'];

                } else {

                    $data['nowsprovince'] = 0;

                }

                if (!empty($memList['city'])) {

                    $data['nowscity'] = $memList['city'];

                } else {

                    $data['nowscity'] = 0;

                }
                $data['nowsaddress']  = $memList['address'];
                $data['birthday']     = '';
                $data['leastlogin']   = time();
                $data['addtime']      = $memList['ctime'];
                $data['icon']         = 0;
                $data['point']        = 0;
                $data['birthaddress'] = 0;

                if (M('usermember')->add($data)) {

                    session('dr-uid', $memList['uid']);
                    $data['mobile']  = $$memList['mobile'];
                    $data['uid']     = $memList['uid'];
                    $data['success'] = true;
                    $data['message'] = '登陆成功！';
                }

            } else {

                session('dr-uid', $memList['uid']);
                $data['mobile']  = $$memList['mobile'];
                $data['uid']     = $memList['uid'];
                $data['success'] = true;
                $data['message'] = '登陆成功！';

            }

        } else {

            $data['success'] = false;
            $data['message'] = '用户不存在，非法操作！';

        }

        //var_dump($data);

        echo json_encode($data);

    }
    public function data_post($url, $post_data)
    {

        $postdata = http_build_query($post_data);
        $options  = array(
            'http' => array(
                'method'  => 'GET', //or GET
                'header'  => 'Content-type:application/x-www-form-urlencoded',
                'content' => $postdata,
                'timeout' => 15 * 60, // 超时时间（单位:s）
            ),
        );
        $context = stream_context_create($options);
        $result  = file_get_contents($url, false, $context);
        return $result;
    }

}
