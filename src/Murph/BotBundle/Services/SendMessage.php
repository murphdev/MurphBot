<?php

namespace Murph\BotBundle\Services;

use Murph\BotBundle\Command\MurphBotCommand;
use GuzzleHttp as Guzzle;

class SendMessage
{
    public function __construct()
    {
        $this->client = new Guzzle\Client(['base_uri' => MurphBotCommand::API_URL, 'timeout' => 2.0]);
    }

    public function send($text = null, $chatId = null, $keyboard = null)
    {
        if(!$chatId || !$text) {
            return false;
        }

        try {
            $this->client->request('POST', 'sendmessage', ['query' => ['chat_id' => $chatId, 'text' => $text, 'reply_markup' => $keyboard]]);
            return true;
        } catch(\Exception $ex) {
            return false;
        }
    }
}