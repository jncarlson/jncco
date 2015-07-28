<?php
namespace App\Services;

use GuzzleHttp\Client;

class RiotApi {

    public $client;
    public $apiURLs;
    public $region;
    public $apiKey;
    public $id;
    public $callToMake;
    public $resultsJson;

    public function __construct()
    {
        $this->region = 'na';
        $this->apiKey = env('API_KEY');
        $this->id = '26137346';
        $this->resultsJson = [];
        $this->client = new Client();
        $this->client->setDefaultOption('exceptions', false);
        $this->apiURLs = array(
            'free_champions' => "https://$this->region.api.pvp.net/api/lol/$this->region/v1.2/champion?freeToPlay=true&api_key=$this->apiKey",
            'match_history' => "https://$this->region.api.pvp.net/api/lol/$this->region/v2.2/matchhistory/" . $this->id . "?rankedQueues=RANKED_SOLO_5x5,RANKED_TEAM_5x5&beginIndex=0&endIndex=15&api_key=$this->apiKey",
            'champion_info' => "https://$this->region.api.pvp.net/api/lol/$this->region/v1.3/stats/by-summoner/" . $this->id . "/ranked?season=SEASON2015&api_key=$this->apiKey",
            'league_info'   => "https://$this->region.api.pvp.net/api/lol/$this->region/v2.5/league/by-summoner/" . $this->id . "?api_key=$this->apiKey",
        );
    }

    function setUpCalls()
    {
        foreach($this->apiURLs as $key => $callToMake)
        {
            $this->callToMake = $callToMake;
            $this->resultsJson[$key] = $this->makeRiotCalls();
        }

        foreach($this->resultsJson['match_history']['matches'] as $key => $match)
        {
            $matchID = $this->resultsJson['match_history']['matches'][$key]['matchId'];
            $matchInfo = "https://$this->region.api.pvp.net/api/lol/$this->region/v2.2/match/$matchID/?api_key=$this->apiKey";
            $this->callToMake = $matchInfo;
            $this->resultsJson['game_info'][$key] = $this->makeRiotCalls();
        }

        $this->makeRedditCalls();
        return $this->resultsJson;
    }

    function makeRiotCalls()
    {
        for ($i = 0; $i < 5; $i++)
        {
            $response = $this->client->get($this->callToMake);
            $status = $response->getStatusCode();

            if ($status == 200)
            {
                return $response->json();
            }

            usleep(100000);
        }

        return "Failed: " . $status;
    }

    function makeRedditCalls()
    {
        $response = $this->client->get('https://www.reddit.com/r/leagueoflegends/top/.json?sort=top&t=week&limit=5');
        $array= $response->json();
        foreach($array['data']['children'] as $key => $link)
        {
            $this->resultsJson['leagueLinks'][$key] = $array['data']['children'][$key]['data']['permalink'];
        }

        $response = $this->client->get('https://www.reddit.com/r/summonerschool/top/.json?sort=top&t=week&limit=5');
        $array= $response->json();
        foreach($array['data']['children'] as $key => $link)
        {
            $this->resultsJson['summonerLinks'][$key] = $array['data']['children'][$key]['data']['permalink'];
        }
    }

    function verifyUser($input)
    {
        $region = $input['region'];
        $user = $input['username'];

        $this->callToMake = "https://$region.api.pvp.net/api/lol/$region/v1.4/summoner/by-name/$user?api_key=$this->apiKey";
        $apiResult = $this->makeRiotCalls();
        if(!is_array($apiResult))
        {
            return 'Could not find summoner account.';
        }
        return 'Summoner account found.';
    }
}



