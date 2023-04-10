<?php

namespace App\Actions;

class FetchProhibited
{
    public $viewers = 0;

    public function handle($url)
    {
		// dump('links ' . $url . ' -dump');
        exec('links ' . $url . ' -dump', $output, $return);
		// dd($output);

		// return 0;

        $response = implode(" ", $output);
        $json = json_decode($response);
        $this->viewers = $json->results[0]->currentViews;
    }
}
