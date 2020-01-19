<?php

namespace App\Http\Controllers;

use App\Models\Social;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class FacebotController extends Controller
{
    public function receive(Request $request)
    {
        $data = $request->all();
        $data = $data['entry'][0];
        $type = 'messages';
        // Gets the body of the webhook event
        // Log::error(print_r($data,1)); // check Postback Button
        if (isset($data['postback'])) {
            //messaging_postbacks
            $psid = $data['sender']['id'];
            $context = $data['postback']['title']; //TITLE_FOR_THE_CTA
            $payload = $data['postback']['payload']; //USER_DEFINED_PAYLOAD
            $referralRef = $data['postback']['referral']['ref'];
            $referralSource = $data['postback']['referral']['source'];
            $referralType = $data['postback']['referral']['type'];

            $type = 'messaging_postbacks';
        //todo
        } elseif (isset($data['message']['reply_to'])) {
            //Reply Message
            $psid = $data['message']['sender']['id'];
            $context = $data['message']['text'];
            $replyMid = $data['message']['reply_to']['mid'];
        //todo
        } elseif (isset($data['message']['attachments'])) {
            //Message with fallback attachment from link scraping
            $psid = $data['sender']['id'];
            $context = $data['message']['text']; //URL_SENT_BY_THE_USER
            $attachmentsTitle = $data['message']['attachments']['title'];
            $attachmentsURL = $data['message']['attachments']['URL'];
        //todo
        } elseif (isset($data['message'])) {
            //Text Message
            $psid = $data['message']['sender']['id'];
            $context = $data['message']['text'];
        //todo
        } elseif (isset($data['messaging'])) {
            //Message with blob attachment
            $psid = $data['messaging']['sender']['id'];
            $context = $data['messaging']['message']['text'];
            //done!
        }
        // We want our bot to be able to handle two types of webhook events: messages and messaging_postback. The name of the event type is not included in the event body, but we can determine it by checking for certain object properties.
        // handleMessage(sender_psid, webhook_event.message);
        // handlePostback(sender_psid, webhook_event.postback);

        $lastCommand = strtolower(trim($context)); //'bind';
        switch ($lastCommand) {
            case 'bind':
                $reply = $this->bind($psid);
                break;
            case 'register':
                $reply = 'Register by click: '.route('register');
                break;
            case 'ping':
                $reply = 'Pong';
                break;
            default:
                $reply = 'Welcome to apply us, Any question PM Dale by click this link: https://m.me/xiangkeguo';
                break;
        }

        if ($this->sendTextMessage($psid, $reply)) {
            return response('EVENT_RECEIVED', 200);
        } else {
            return response()->toJson([
                'message' => 'EVENT reply Failed',
            ], 404);
        }
    }

    public function bindPsid(Request $request)
    {
        $data = $request->all();
        // 3271973086149712 《====》 MzI3MTk3MzA4NjE0OTcxMg==
        if (isset($data['psid'])) {
            $psid = $data['psid'];
            if ($psid = base64_decode($psid, true)) {
                $social = Social::firstOrCreate(
                    [
                        'social_id' => $psid,
                        'user_id'   => Auth::id(),
                        'type'      => Social::TYPE_FB_PSID,
                    ]
                );

                if ($social->wasRecentlyCreated) {
                    Session::flash('alert-success', 'Bind success！');
                } else {
                    Session::flash('alert-warning', 'Already binded!');
                }
            } else {
                Session::flash('alert-danger', 'Bind faild! Wrong psid!');
            }
        } else {
            Session::flash('alert-danger', 'Bind faild! Retry!');
        }

        return redirect('/home');
    }

    private function bind($psid)
    {
        //check if binded already?
        $social = Social::where('type', Social::TYPE_FB_PSID)->where('social_id', $psid)->first();
        if (! $social) {
            //请回复 bind 绑定您的账户
            $link = route('bind.facebook', ['psid'=> base64_encode($psid)]);
            $reply = 'Please click this link: '.$link.' to login and bind EE-LMS account to get class notifications';
        } else {
            $reply = 'You already Binded!';
        }

        return $reply;
    }

    private function sendTextMessage($recipientId, $messageText)
    {
        $messageData = [
            'recipient' => [
                'id' => $recipientId,
            ],
            'message' => [
                'text' => $messageText,
            ],
        ];
        $pageToken = \Config::get('services.facebook.page-token');

        $client = new Client([
            'headers' => ['Content-Type' => 'application/json'],
        ]);
        $url = 'https://graph.facebook.com/v5.0/me/messages?access_token='.$pageToken;
        $response = $client->post($url, ['body'=>json_encode($messageData)]);
        if ($response->getStatusCode() == 200) {
            return true;
        } else {
            Log:error(__CLASS__, [__FUNCTION__, __LINE__, 'error!']);

            return false;
        }
    }

    // $buttons =[
    //     ['type'=>'web_url','url'=>'https://www.messenger.com','title'=>"Visit Messenger"],
    // ]
    // https://developers.facebook.com/docs/messenger-platform/reference/template/button
    private function sendButtonMessage($recipientId, $title, $buttons)
    {
        $messageData = [
            'recipient' => [
                'id' => $recipientId,
            ],
            'message' => [
                'attachment' =>  [
                    'type'  => 'template',
                    'payload' => [
                        'template_type' => 'button',
                        'text'  => $title,
                        'buttons' => $buttons,
                    ],
                ],
            ],
        ];
        $pageToken = \Config::get('services.facebook.page-token');

        $client = new Client([
            'headers' => ['Content-Type' => 'application/json'],
        ]);
        $url = 'https://graph.facebook.com/v5.0/me/messages?access_token='.$pageToken;
        $response = $client->post($url, ['body' => json_encode($messageData)]);
        if ($response->getStatusCode() == 200) {
            return true;
        } else {
            Log:error(__CLASS__, [__FUNCTION__, __LINE__, 'error!']);

            return false;
        }
    }
}
