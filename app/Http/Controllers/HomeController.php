<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Overtrue\EasySms\EasySms;
use Mews\Captcha\Captcha;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        return view('home');
// WECHAT_PAYMENT_SANDBOX=false
// WECHAT_PAYMENT_APPID=wx07d8c7489700d878
// WECHAT_PAYMENT_MCH_ID=1246435301

// WECHAT_PAYMENT_KEY=558f5f3b10b1fa0b44dd278bdb872171
// WECHAT_PAYMENT_SECRET=2cb26ee8323d99dc1f466c4fa507aecf
// WECHAT_PAYMENT_NOTIFY_URL=https://wechat.yongbuzhixi.com/wxpay/notify
        //gateways: WechatPay_App, WechatPay_Native, WechatPay_Js, WechatPay_Pos, WechatPay_Mweb
        $gateway    = Omnipay::create('WechatPay_App');
        $gateway->setAppId('wx07d8c7489700d878');
        $gateway->setMchId('1246435301');
        $gateway->setApiKey('558f5f3b10b1fa0b44dd278bdb872171');

        $order = [
            'body'              => 'The test order',
            'out_trade_no'      => date('YmdHis').mt_rand(1000, 9999),
            'total_fee'         => 1, //=0.01
            'spbill_create_ip'  => '1.1.1.1',
            'fee_type'          => 'CNY'
        ];

        /**
         * @var Omnipay\WechatPay\Message\CreateOrderRequest $request
         * @var Omnipay\WechatPay\Message\CreateOrderResponse $response
         */
        $request  = $gateway->purchase($order);
        $response = $request->send();

        //available methods
        dd($response->isSuccessful(),$response->getData());
        $response->getData(); //For debug
        $response->getAppOrderData(); //For WechatPay_App
        $response->getJsOrderData(); //For WechatPay_Js
        $response->getCodeUrl(); //For Native Trade Type

        $easySms = new EasySms(config('easy-sms'));

        $res = $easySms->send(13188888888, [
            // 'content'  => '您的验证码为: 6379',
            'template' => '295121',
            'data' => [
                'code' => 6379
            ],
        ]); 
        dd($res);
    }
}
