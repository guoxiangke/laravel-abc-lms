<?php

namespace App\Http\Controllers;

use BotMan\BotMan\BotMan;
use Illuminate\Http\Request;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;

class BotmanController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function handle(Request $request)
    {
        $config = [
            // Your driver-specific configuration
            // "telegram" => [
            //    "token" => "TOKEN"
            // ]
        ];
        // Load the driver(s) you want to use
        // DriverManager::loadDriver(\BotMan\Drivers\Telegram\TelegramDriver::class);
        DriverManager::loadDriver(\BotMan\Drivers\Web\WebDriver::class);

        // Create an instance
        // $botman = BotManFactory::create($config);
        $botman = app('botman');

        // Give the bot something to listen for.
        $botman->hears('hello', function (BotMan $bot) {
            $bot->reply('Hello yourself.');
        });

        // bark_notify('验证码是0000');
        // bark_notify('验证码是0001','这是body解释');
        //
        // bark_notify('验证码是1234，已复制1234到剪切板，粘贴即可。','12345', true);
        // bark_notify('验证码是4567，已复制所有文本到剪切板。', false, true);

        $botman->fallback(function (BotMan $bot) use ($request) {
            $bot->reply('Sorry, I did not understand these commands. Here is a list of commands I understand: ...');
            bark_notify('访客'. substr($request->input('userId'), 0, 4) .'发来消息: '. $request->input('message') .'，点击回复', 'https://cn.bing.com');
            \Log::error(__CLASS__, [$request->all(), $bot->getMessage()->getPayload()]);
        });
        // Start listening
        $botman->listen();
    }
}
