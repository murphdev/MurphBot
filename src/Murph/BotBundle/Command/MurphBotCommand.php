<?php

namespace Murph\BotBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use GuzzleHttp as Guzzle;

class MurphBotCommand extends ContainerAwareCommand
{
    const TOKEN = "";
    const URL = "https://api.telegram.org/bot";
    const API_URL = self::URL . self::TOKEN . "/";

    private $client;
    private $offset;

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this
            ->setName('murph:bot:start')
            ->setDescription('Start MurphBot')
        ;

        $this->client = new Guzzle\Client(['base_uri' => self::API_URL, 'timeout' => 2.0]);
    }

    /**
     * {@inheritdoc}
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->offset = $this->getLatestOffset();
        if($this->offset) {
            $this->offset += 1;
            $output->writeln('<info>Flush message list offset : ' . $this->offset . '</info>');
            $this->getUpdate($this->offset);
        }

        $this->loop($output);
    }


    public function loop($output)
    {
        while(true) {
            $update = $this->getUpdate($this->offset);
            if(!$update) {
                continue;
            }
            $arrayUpdate = json_decode($update, true);

            if(!$arrayUpdate['result']) {
                continue;
            } else {
                $message = $arrayUpdate['result'][0]['message']['text'];
                try {
                    $cmd = $this->getContainer()->get('murph.' . str_replace('/', '', explode(' ', $message)[0]));
                    $cmd->set(explode(' ', $message), $arrayUpdate);
                    $cmd->run();
                } catch(\Exception $ex) {
                    $output->writeln($ex->getMessage());
                }
                if($this->offset) {
                    $this->offset++;
                }
            }
        }
    }

    public function getLatestOffset()
    {
        try {
            $res = $this->client->request('GET', 'getupdates');
            $updateArray = json_decode($res->getBody()->getContents(), true);
        } catch(\Exception $ex) {
            return false;
        }
        if(!$updateArray) {
            return false;
        }

        $c = count($updateArray['result']);
        if($c <= 0) {
            return false;
        } else {
            return $updateArray['result'][$c-1]['update_id'];
        }
    }

    public function getUpdate()
    {
        if(!$this->offset) {
            try {
                $res = $this->client->request('GET', 'getupdates', ['query' => ['limit' => 1]]);
                $update = $res->getBody()->getContents();
                if($update) {
                    $this->offset = $this->getLatestOffset();
                }
            } catch(\Exception $ex) {
                $update = false;
            }
        } else {
            try {
                $res = $this->client->request('GET', 'getupdates', ['query' => ['offset' => $this->offset, 'limit' => 1]]);
                $update = $res->getBody()->getContents();
            } catch(\Exception $ex) {
                $update = false;
            }
        }
        if(!$update) {
            return false;
        }

        return $update;
    }
}
