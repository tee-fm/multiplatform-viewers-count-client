<?php

namespace App\Actions;

class FetchYoutube
{
    public $viewers = 0;

    public function handle($channelId)
    {
        try {
            $key = env('YOUTUBE_API_KEY', '');

            $ch = curl_init();

            $url = "https://www.youtube.com/channel/{$channelId}/live";
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
            curl_setopt($ch, CURLOPT_TIMEOUT, 20);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            $response = curl_exec($ch);

            curl_reset($ch);

            preg_match('$<link rel="canonical" href="https://www.youtube.com/watch\?v=([^"]+)">$', $response, $matches);
            if (count($matches) < 1) return $this;
            $videoId = $matches[1];

            $url = "https://www.googleapis.com/youtube/v3/videos?part=liveStreamingDetails&id={$videoId}&key={$key}";
            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
            curl_setopt($ch, CURLOPT_TIMEOUT, 20);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            $response = curl_exec($ch);
            $json = json_decode($response);
            if (count($json->items) > 0) {
                $this->viewers = $json->items[0]->liveStreamingDetails->concurrentViewers;
            }

            curl_close($ch);
        } catch (\Exception $e) {
            dump($e->getMessage());
        }

        return $this;
    }

    public function getViewers()
    {
        return $this->viewers;
    }
}
