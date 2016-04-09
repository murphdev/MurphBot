<?php

namespace Murph\Command;

use Murph\Command\Parent\AbstractCommand;

class Thanks extends AbstractCommand
{
    public function getText()
    {
        $text =
            "Thanks to all person who made this possible and :\n" .
            "Ip API for GeoLocation API (ip-api.com)\n" .
            "Thanks to murph.it \n" .
            "And last but not least ...\n" .
            "Thanks to Telegram!\n"
        ;

        return $text;
    }
}
