<?php

namespace App\Actions;

class FetchTrovo
{
    public $viewers = 0;

    /**
     * @throws \Exception
     */
    public function handle($channel)
    {
        $url = "https://open-api.trovo.live/openplatform/channels/id";
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, '{"channel_id": ' . $channel . '}');
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Client-ID: 551a298d0d79feff9582608da90b98a9",
        ]);
        $response = curl_exec($ch);
        curl_close($ch);
        $json = json_decode($response);
        $this->viewers = $json->current_viewers;
    }
}
