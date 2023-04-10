<?php

namespace App\Actions;

class FetchMgtow
{
    public $viewers = 0;

    public function handle($url)
    {
        $url = "{$url}api/status";
        $ch = curl_init();

        // set URL and other appropriate options
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        // grab URL and pass it to the browser
        $response = curl_exec($ch);

        // close cURL resource, and free up system resources
        curl_close($ch);

        $json = json_decode($response);

        $this->viewers = $json->viewerCount;
        return $this;
    }

    public function getViewers()
    {
        return $this->viewers;
    }
}
