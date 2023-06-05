<?php
require_once('./vendor/autoload.php');
require_once('./.env');
require __DIR__ . '/vendor/autoload.php';

use Discord\Discord;
use Discord\WebSockets\Event;
use Discord\WebSockets\Intents;
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

try {
    $discord = new Discord([
        'token' => $_ENV['DISCORD_BOT_TOKEN'],
        'intents' => Intents::getDefaultIntents()
    ]);
} catch (IntentException $e) {
    echo $e->getMessage() . PHP_EOL;
    exit(1);
}

$discord->on('ready', function($discord) {
    echo 'Bot staat aan!', PHP_EOL;


    //Luistert naar berichten
    $discord->on('message', function($message, Discord $discord){
        $content = $message->content;
        if(strpos($content, '!') === false) return;

        //Help responder
        if($content === '!help') {
            $help = "!jemoeder - Niet fucken met m'n moeder, ik reageer met: JOUW MOEDER
!mop - Ik vertel een mop, als ik daar zin in heb";
            $message->reply($help);
        }

        //Jemoeder responder
        if($content === '!jemoeder') {
            $jemoeder = "JOUW MOEDER";
            $message->reply($jemoeder);
        }

        //Moppen responder
        $moppen = [
            "Twee tieten in een envelop!",
            "Ik geef zo een mop voor je hoofd",
            "Sorry, geen zin in.",
            "Val je moeder lekker lastig ofzo",
            "Ga buiten spelen alsjeblieft",
        ];
        if($content === '!mop') {
            $randomMopArrayNumber = array_rand($moppen);
            $randomMop = $moppen[$randomMopArrayNumber];
            $message->reply($randomMop);
        }

        //Steen papier schaar responder
        $sps = [
            "steen",
            "papier",
            "schaar"
        ];
        if($content === '!sps steen') {
            $randomSpsArrayNumber = array_rand($sps);
            $randomSps = $sps[$randomSpsArrayNumber];
            if($randomSps === 'steen') {
                $message->reply('Ik kies '.$randomSps.'! Gelijkspel!');
            }
            if($randomSps === 'papier') {
                $message->reply('Ik kies '.$randomSps.'! Ik win!');
            }
            if($randomSps === 'schaar') {
                $message->reply('Ik kies '.$randomSps.'! Jij wint!');
            }
        }
        if($content === '!sps papier') {
            $randomSpsArrayNumber = array_rand($sps);
            $randomSps = $sps[$randomSpsArrayNumber];
            if($randomSps === 'steen') {
                $message->reply('Ik kies '.$randomSps.'! Jij wint!');
            }
            if($randomSps === 'papier') {
                $message->reply('Ik kies '.$randomSps.'! Gelijkspel!');
            }
            if($randomSps === 'schaar') {
                $message->reply('Ik kies '.$randomSps.'! Ik win!');
            }
        }
        if($content === '!sps schaar') {
            $randomSpsArrayNumber = array_rand($sps);
            $randomSps = $sps[$randomSpsArrayNumber];
            if($randomSps === 'steen') {
                $message->reply('Ik kies '.$randomSps.'! Ik win!');
            }
            if($randomSps === 'papier') {
                $message->reply('Ik kies '.$randomSps.'! Jij wint!');
            }
            if($randomSps === 'schaar') {
                $message->reply('Ik kies '.$randomSps.'! Gelijkspel!');
            }
        }
        if($content === '!sps') {
            $message->reply('Kies dan iets, lege vaas');
        }
    });
});
$discord->run();