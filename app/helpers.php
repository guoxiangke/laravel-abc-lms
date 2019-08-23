<?php

if (! function_exists('bark_notify')) {
    // https://day.app/2018/06/bark-server-document/
    // https://www.v2ex.com/t/467407
    // // eg:
    // bark_notify('验证码是0000');
    // bark_notify('验证码是0001','这是body解释');
    // bark_notify('点击打开网址', 'https://cn.bing.com');
    // bark_notify('验证码是1234，已复制1234到剪切板，粘贴即可。','12345', true);
    // bark_notify('验证码是4567，已复制所有文本到剪切板。', false, true);
    function bark_notify($title, $bodyOrUrlOrCopy = false, $copy = false, $sendTo = 'admin', $host = 'https://api.day.app')
    {
        $key = \Config::get('notify.bark.'.$sendTo);
        $url = $host.'/'.$key.'/'.urlencode($title);
        $query = [];
        if (filter_var($bodyOrUrlOrCopy, FILTER_VALIDATE_URL)) {
            $query['url'] = $bodyOrUrlOrCopy;
        } else {
            //有copy就不要body了！
            if ($copy) {
                $query['copy'] = $bodyOrUrlOrCopy ?: $title; //如果没有填写body，复制title
            } else {
                $url .= '/'.urlencode($bodyOrUrlOrCopy);
            }
        }
        $query['automaticallyCopy'] = 1;
        $postdata = http_build_query($query);
        $opts = [
            'http' => [
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => $postdata,
            ],
        ];
        $context = stream_context_create($opts);

        return file_get_contents($url, false, $context);
    }
}

if (! function_exists('ftqq_notify')) {
    // http://sc.ftqq.com/?c=code
    //ftqq_notify('title', "###No MarkDown Body###", 'manager');
    //ftqq_notify('title2', "###No MarkDown Body###");
    function ftqq_notify($title, $markdown = 'No MarkDown Body', $sendTo = 'admin')
    {
        if (config('app.env') !== 'production') {
            return bark_notify($title, $markdown);
        }
        $admins = \Config::get('notify.sc');
        $key = $admins[$sendTo];
        $postdata = http_build_query([
            'text' => $title,
            'desp' => $markdown,
        ]);

        $opts = [
            'http' => [
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => $postdata,
            ],
        ];
        $context = stream_context_create($opts);

        return $result = file_get_contents('https://sc.ftqq.com/'.$key.'.send', false, $context);
    }
}
