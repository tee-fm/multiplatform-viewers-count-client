<?php

namespace App\Actions;

class FetchEntropy
{
    public $viewers = 0;

    public function handle($handle)
    {
        $url = "https://entropystream.live/api/shows?handle=${handle}";
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        $json = json_decode($response);

        if (!isset($json->show)) {
            return $this;
        }

        $showId = $json->show->id;
        if (!$showId)
            throw new \Exception('Invalid show id');

        curl_reset($ch);

        $url = "https://entropystream.live/api/shows/{$showId}/watch";
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);

        curl_close($ch);

        $json = json_decode($response);
        $this->viewers = $json->count;

        return $this;
    }

    public function getViewers()
    {
        return $this->viewers;
    }
}
