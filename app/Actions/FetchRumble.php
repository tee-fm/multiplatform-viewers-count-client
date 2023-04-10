<?php

namespace App\Actions;

class FetchRumble
{
    public $viewers = 0;

    /**
     * @throws \Exception
     */
    public function handle($user)
    {
        $url = "https://rumble.com/user/${user}";
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        preg_match('|<a class=video-item--a href=/((\S*)-live.html)>|', $response, $matches);

        if ($matches) {
            $liveUrl = "https://rumble.com/{$matches[1]}";
//            dump($liveUrl);

            curl_reset($ch);
            curl_setopt($ch, CURLOPT_URL, $liveUrl);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
//            dump($response);
            preg_match('|"video":"v([^\"]*)"|', $response, $matches);
//            dump($matches);
            if ($matches) {
                $video = $matches[1];

                curl_reset($ch);
                $url = "https://rumble.com/service.php?video={$video}&name=video.watching_now&included_js_libs=main%2Cweb_services%2Cevents%2Cerror%2Crandom%2Clocal_storage%2Cui_header";
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, $url);
                curl_setopt($ch, CURLOPT_HEADER, 0);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                $response = curl_exec($ch);

                $json = json_decode($response);
                if (isset($json->data) && isset($json->data->viewer_count)) {
                    $this->viewers = $json->data->viewer_count;
                }
            }
        }

        curl_close($ch);
        return $this;
    }

    public function getViewers()
    {
        return $this->viewers;
    }
}
