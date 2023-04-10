<?php

namespace App\Actions;

class FetchOdysee
{
    public $viewers = 0;

    /**
     * @throws \Exception
     */
    public function handle($claim_id)
    {
        $url = "wss://sockety.lbry.com/ws/commentron?id={$claim_id}&category={$claim_id}";
        $path = realpath(__DIR__ . '/../../node/odysee.js');
        $command = 'node ' . $path . ' "' . $url . '"';
        exec($command, $output, $response);
        $this->viewers = (int)$output[1];

        return $this;
    }

    public function getViewers()
    {
        return $this->viewers;
    }
}
