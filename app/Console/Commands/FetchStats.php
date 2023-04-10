<?php

namespace App\Console\Commands;

use App\Actions\FetchDLive;
use App\Actions\FetchEntropy;
use App\Actions\FetchMgtow;
use App\Actions\FetchOdysee;
use App\Actions\FetchPeertube;
use App\Actions\FetchRumble;
use App\Actions\FetchYoutube;
use Illuminate\Console\Command;

class FetchStats extends Command
{
    private string $serverDomain;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'stats:fetch';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function __construct()
    {
        set_time_limit(30);
        $this->serverDomain = env('STATS_SERVER', '');

        parent::__construct();

        echo "\n\"" . date(DATE_RFC2822) . '",';
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $response = $this->getNextAvailablePlatform();
        if (!$response or !isset($response['platform'])) return 0;
        echo "\"{$response['platform']['service']}\",";

        $viewers = $this->getViewers($response);

        echo('"fetched viewers",' . $viewers);
        if ($viewers === 0) return 0;

        $data = [
            'service' => $response['platform']['service'],
            'viewers' => $viewers,
        ];

        $url = "{$this->serverDomain}/api/viewers";
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        $response = curl_exec($ch);
        curl_close($ch);
        dump($response);

        return 0;
    }

    private function getNextAvailablePlatform()
    {
        $url = "{$this->serverDomain}/api/platforms/available";

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        $responseJson = json_decode($response, true);
        curl_close($ch);

        return $responseJson;
    }

    /** @return int */
    private function getViewers($response)
    {
        if ($response['platform']['service'] === 'dlive') {
            $data = json_decode($response['platform']['extra_data'], true);

            return (new FetchDLive())
                ->handle($data['handle'])
                ->getViewers();
        }
        if ($response['platform']['service'] === 'entropy') {
            $data = json_decode($response['platform']['extra_data'], true);

            return (new FetchEntropy())
                ->handle($data['handle'])
                ->getViewers();
        }
        if ($response['platform']['service'] === 'mgtow.tv') {
            $data = json_decode($response['platform']['extra_data'], true);

            return (new FetchMgtow())
                ->handle($data['url'])
                ->getViewers();
        }
        if ($response['platform']['service'] === 'odysee') {
            $data = json_decode($response['platform']['extra_data'], true);

            return (new FetchOdysee())
                ->handle($data['claim_id'])
                ->getViewers();
        }
        if ($response['platform']['service'] === 'rumble') {
            $data = json_decode($response['platform']['extra_data'], true);

            return (new FetchRumble())
                ->handle($data['user'])
                ->getViewers();
        }
        if ($response['platform']['service'] === 'peertube') {
            $data = json_decode($response['platform']['extra_data'], true);

            return (new FetchPeertube())
                ->handle($data['video'])
                ->getViewers();
        }
        if ($response['platform']['service'] === 'youtube') {
            $data = json_decode($response['platform']['extra_data'], true);

            return (new FetchYoutube())
                ->handle($data['channel'])
                ->getViewers();
        }

        return 0;
    }
}
