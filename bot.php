<?php
require_once('./vendor/autoload.php');
require_once('./.env');
require __DIR__ . '/vendor/autoload.php';

use Discord\Builders\MessageBuilder;
use Discord\Discord;
use Discord\Exceptions\IntentException;
use Discord\Parts\Interactions\Command\Command;
use Discord\Parts\Interactions\Interaction;
use Discord\Builders\CommandBuilder;
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

$discord->on('ready', function(Discord $discord) {
    echo 'Bot staat aan!', PHP_EOL;

    $command = new Command($discord, ['name' => 'ping', 'description' => 'pong']);
    $discord->application->commands->save($command);

    $discord->application->commands->save(
        $discord->application->commands->create(CommandBuilder::new()
            ->setName('ping')
            ->setDescription('pong')
            ->toArray()
        )
    );
    $discord->listenCommand('ping', function (Interaction $interaction) {
       $interaction->respondWithMessage(MessageBuilder::new()->setContent('Pong!'));
    });
    //Luistert naar berichten
    $discord->on('message', function($message, Discord $discord){
        $content = $message->content;
        if(strpos($content, '!') === false) return;

        //Help responder
        if($content === '!help') {
            $help = "!jemoeder, !mop, !sps (steen, papier of schaar)";
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
            "Ik ken helemaal geen mop, stop met vragen",
            "Wil je een krentenbol?",
            "Deze week zijn de krentenbollen in de bonus bij de appie, wist je dat? Nee grapje, ik wil gewoon dat je opflikkerd",
            "Sterf ff lekker af joh bloedzuiger"
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

        //krentenbol responder
        if($content === '!krentenbol') {
            $message->reply('Een krentenbol is een rond broodje met krenten, soms ook met rozijnen. Het deeg is ten opzichte van gewoon brooddeeg behalve met krenten en eventueel rozijnen verrijkt met ei en boter, soms ook citroenschil, sukade en suiker. Krentenbollen worden soms besmeerd met boter of margarine en al dan niet belegd met kaas, suiker of een andere zoetigheid. Krentenbollen zijn echt een verrukkelijke versnapering voor jouw mondje.');
        }
    });
});
$discord->run();