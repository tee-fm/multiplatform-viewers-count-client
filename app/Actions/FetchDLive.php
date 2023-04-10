<?php

namespace App\Actions;

class FetchDLive
{
    public $viewers = 0;

    public function handle($handle)
    {
        $url = "https://graphigo.prd.dlive.tv/";

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 20);
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, "{\"operationName\":\"LivestreamPageRefetch\",\"variables\":{\"displayname\":\"{$handle}\",\"add\":false,\"isLoggedIn\":false},\"extensions\":{\"persistedQuery\":{\"version\":1,\"sha256Hash\":\"6340dc1c4212dfe34a3fe7f8886d4c4514af1dbd737a54ee0418827975269857\"}}}");
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $response = curl_exec($ch);
        $json = json_decode($response);

        if (!$json->data->userByDisplayName->livestream) {
            return $this;
        }

        $this->viewers = $json->data->userByDisplayName->livestream->watchingCount;

        return $this;
    }

    public function getViewers()
    {
        return $this->viewers;
    }
}
