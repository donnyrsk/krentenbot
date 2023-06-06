<?php
require_once('./vendor/autoload.php');
require_once('./.env');
require __DIR__ . '/vendor/autoload.php';


use Discord\Discord;
use Discord\Exceptions\IntentException;
use Discord\Parts\Interactions\Command\Command;
use Discord\Parts\Interactions\Interaction;
use Discord\Parts\Interactions\Command\Option;
use Discord\Builders\CommandBuilder;
use Discord\Builders\MessageBuilder;
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

    //----------------------------------------Help slash command----------------------------------------\\

    $command = new Command($discord, ['name' => 'help', 'description' => 'Alle commands die beschikbaar zijn!']);
    $discord->application->commands->save($command);

    $discord->application->commands->save(
        $discord->application->commands->create(CommandBuilder::new()
            ->setName('help')
            ->setDescription('Alle commands die beschikbaar zijn!')
            ->toArray()
        )
    );

    $discord->listenCommand('help', function (Interaction $interaction) {
        $help = "/jemoeder, /mop, /ping, !sps";
        $interaction->respondWithMessage(MessageBuilder::new()->setContent($help));
    });

    //----------------------------------------Ping slash command----------------------------------------\\

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

    //----------------------------------------Mop slash command----------------------------------------\\

    $command = new Command($discord, ['name' => 'mop', 'description' => 'Ik vertel een mop, als ik daar zin in heb tenminste.']);
    $discord->application->commands->save($command);

    $discord->application->commands->save(
        $discord->application->commands->create(CommandBuilder::new()
            ->setName('mop')
            ->setDescription('Ik vertel een mop, als ik daar zin in heb tenminste.')
            ->toArray()
        )
    );

    $discord->listenCommand('mop', function (Interaction $interaction) {
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
        $randomMopArrayNumber = array_rand($moppen);
        $randomMop = $moppen[$randomMopArrayNumber];
        $interaction->respondWithMessage(MessageBuilder::new()->setContent($randomMop));
    });

    //----------------------------------------Je moeder slash command----------------------------------------\\

    $command = new Command($discord, ['name' => 'jemoeder', 'description' => 'Niet fucken met mijn moeder vriend']);
    $discord->application->commands->save($command);

    $discord->application->commands->save(
        $discord->application->commands->create(CommandBuilder::new()
            ->setName('jemoeder')
            ->setDescription('Niet fucken met mijn moeder vriend')
            ->toArray()
        )
    );

    $discord->listenCommand('jemoeder', function (Interaction $interaction) {
        $jemoeder = "JOUW MOEDER!";
        $interaction->respondWithMessage(MessageBuilder::new()->setContent($jemoeder));
    });

    //----------------------------------------Krentenbol slash command----------------------------------------\\

    $command = new Command($discord, ['name' => 'krentenbol', 'description' => 'Wat informatie over de lekkerste versnapering op deze aardbol']);
    $discord->application->commands->save($command);

    $discord->application->commands->save(
        $discord->application->commands->create(CommandBuilder::new()
            ->setName('krentenbol')
            ->setDescription('Wat informatie over de lekkerste versnapering op deze aardbol')
            ->toArray()
        )
    );

    $discord->listenCommand('krentenbol', function (Interaction $interaction) {
        $krentenbol = "Een krentenbol is een rond broodje met krenten, soms ook met rozijnen. Het deeg is ten opzichte van gewoon brooddeeg behalve met krenten en eventueel rozijnen verrijkt met ei en boter, soms ook citroenschil, sukade en suiker. Krentenbollen worden soms besmeerd met boter of margarine en al dan niet belegd met kaas, suiker of een andere zoetigheid. Krentenbollen zijn echt een verrukkelijke versnapering voor jouw mondje.";
        $interaction->respondWithMessage(MessageBuilder::new()->setContent($krentenbol));
    });

    //----------------------------------------Steen papier schaar slash command----------------------------------------\\

    $discord->on('INTERACTION_CREATE', function (Interaction $interaction) {
        $command = $interaction->data->name;

        if ($command === 'sps') {
            // Check if options are present
            if (isset($interaction->data->options)) {
                $option1 = null;
                $option2 = null;
                $option3 = null;

                // Loop through options and retrieve their values
                foreach ($interaction->data->options as $option) {
                    if ($option->name === 'option1') {
                        $option1 = $option->value;
                    } elseif ($option->name === 'option2') {
                        $option2 = $option->value;
                    } elseif ($option->name === 'option3') {
                        $option3 = $option->value;
                    }
                }

                // Process the options and generate a response
                $response = "Option 1: $option1\n";
                $response .= "Option 2: $option2\n";
                $response .= "Option 3: " . ($option3 ? 'True' : 'False');

                $message = new MessageBuilder();
                $message->setContent($response);

                $interaction->respondWithMessage($message);
            } else {
                // No options provided
                $spsErrorMessage = "Kies dan iets, lege vaas";

                $message = new MessageBuilder();
                $message->setContent($spsErrorMessage);

                $interaction->respondWithMessage($message);
            }
        }
    });

    $discord->on('READY', function () use ($discord) {
        $discord->registerCommand('sps', function (CommandBuilder $builder) {
            $builder->setDescription('Play a game of rock-paper-scissors with me! Not rigged at all!')
                ->addOption(
                    new Option('option1', 'Option 1', 3, true) // 3 represents String type
                )
                ->addOption(
                    new Option('option2', 'Option 2', 3, true) // 3 represents String type
                )
                ->addOption(
                    new Option('option3', 'Option 3', 5, false) // 5 represents Boolean type
                );
        });
    });

    //----------------------------------------! responder rommel----------------------------------------\\
    $discord->on('message', function($message, Discord $discord){
        $content = $message->content;
        if(strpos($content, '!') === false) return;

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