<?php
use Discord\Discord;
use Discord\WebSockets\Event;
use Discord\WebSockets\Intents;
require_once('./vendor/autoload.php');
require_once('./.env');
require __DIR__ . '/vendor/autoload.php';

$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
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
    $discord->on('message', function($message, $discord){
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

        
    });
});
$discord->run();