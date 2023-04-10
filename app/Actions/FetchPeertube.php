<?php

namespace App\Actions;

class FetchPeertube
{
    public $viewers = 0;

    public function handle($video)
    {
        $url = "https://peertube.su/api/v1/videos/{$video}";
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

//        preg_match('/"views":([0-9]+)/', $response, $matches);

        $this->viewers = $json->viewers;
        return $this;
    }

    public function getViewers()
    {
        return $this->viewers;
    }
}
