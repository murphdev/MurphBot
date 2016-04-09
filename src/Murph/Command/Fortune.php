<?php

namespace Murph\Command;

use Murph\Command\Parent\AbstractCommand;
use GuzzleHttp as Guzzle;

class Fortune extends AbstractCommand
{
    const API = "";

    private $arrayFortune;

    public function run()
    {
        $client = new Guzzle\Client(['base_uri' => self::API, 'timeout' => 2.0]);
        $res = $client->request('GET', 'proverb.json');
        $fortune = $res->getBody()->getContents();
        $this->arrayFortune = json_decode($fortune, true);

        if($this->message->send($this->getText(), $this->chatId)) {
            return true;
        }

        return false;
    }

    public function getText()
    {
        return $this->arrayFortune[0][0]['proverb'];
    }
}
