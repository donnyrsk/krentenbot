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


    $discord->listenCommand('help', function (Interaction $interaction) {
        $help = "/jemoeder, /mop, /ping, /krentenbol, /sps";
        $interaction->respondWithMessage(MessageBuilder::new()->setContent($help));
    });

    //----------------------------------------Ping slash command----------------------------------------\\

    $command = new Command($discord, ['name' => 'ping', 'description' => 'pong']);
    $discord->application->commands->save($command);

    $discord->listenCommand('ping', function (Interaction $interaction) {
       $interaction->respondWithMessage(MessageBuilder::new()->setContent('Pong!'));
    });

    //----------------------------------------Mop slash command----------------------------------------\\

    $command = new Command($discord, ['name' => 'mop', 'description' => 'Ik vertel een mop, als ik daar zin in heb tenminste.']);
    $discord->application->commands->save($command);

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

    $discord->listenCommand('jemoeder', function (Interaction $interaction) {
        $jemoeder = "JOUW MOEDER!";
        $interaction->respondWithMessage(MessageBuilder::new()->setContent($jemoeder));
    });

    //----------------------------------------Krentenbol slash command----------------------------------------\\

    $command = new Command($discord, ['name' => 'krentenbol', 'description' => 'Wat informatie over de lekkerste versnapering op deze aardbol']);
    $discord->application->commands->save($command);

    $discord->listenCommand('krentenbol', function (Interaction $interaction) {
        $krentenbol = "Een krentenbol is een rond broodje met krenten, soms ook met rozijnen. Het deeg is ten opzichte van gewoon brooddeeg behalve met krenten en eventueel rozijnen verrijkt met ei en boter, soms ook citroenschil, sukade en suiker. Krentenbollen worden soms besmeerd met boter of margarine en al dan niet belegd met kaas, suiker of een andere zoetigheid. Krentenbollen zijn echt een verrukkelijke versnapering voor jouw mondje.";
        $interaction->respondWithMessage(MessageBuilder::new()->setContent($krentenbol));
    });

    //----------------------------------------Steen papier schaar slash command----------------------------------------\\


    $command = new Command($discord, [
        'name' => 'sps',
        'description' => 'Kom potje steen papier schaar dan mietje',
        'options' => [
            [
                'name' => 'kies',
                'description' => 'Steen, papier of schaar',
                'type' => 3, // TYPE_STRING
                'required' => true,
                'choices' => [
                    [
                        'name' => 'Steen',
                        'value' => 'steen',
                    ],
                    [
                        'name' => 'Papier',
                        'value' => 'papier',
                    ],
                    [
                        'name' => 'Schaar',
                        'value' => 'schaar',
                    ]
                ]
            ]
        ]
    ]);

    $discord->application->commands->save($command);

    $discord->listenCommand('sps', function (Interaction $interaction) {
        $choice = $interaction->data->options['kies']->value;

        // Determine the bot's choice
        $choices = ['steen', 'papier', 'schaar'];
        $botChoice = $choices[mt_rand(0, count($choices) - 1)];
        if($botChoice == 'steen') {
            $botEmote = ':rock:';
        } elseif ($botChoice == 'schaar') {
            $botEmote = ':scissors:';
        } elseif($botChoice == 'papier') {
            $botEmote = ':page_facing_up:';
        }
        if($choice == 'steen') {
            $emote = ':rock:';
        } elseif ($choice == 'schaar') {
            $emote = ':scissors:';
        } elseif($choice == 'papier') {
            $emote = ':page_facing_up:';
        }

        // Determine the winner
        $result = '';
        if ($choice === $botChoice) {
            $result = 'Bot kiest '. $botEmote . ' | Jij kiest ' . $emote . "\n\nWAT?! GELIJKSPEL?! *tieft pc in het water*";
        } elseif (
            ($choice === 'steen' && $botChoice === 'schaar') ||
            ($choice === 'papier' && $botChoice === 'rock') ||
            ($choice === 'schaar' && $botChoice === 'papier')
        ) {
            $result = 'Bot kiest '. $botEmote . ' | Jij kiest ' . $emote . "\n\nIk heb " . $botChoice . " gekozen, jij wint godverdomme *slaat bureau in tienen*";
        } else {
            $result = 'Bot kiest '. $botEmote . ' | Jij kiest ' . $emote . "\n\nIk heb " . $botChoice . " gekozen, jij verliest TERING NOOB! :middle_finger:";
        }
        $interaction->respondWithMessage(MessageBuilder::new()->setContent($result));
    });
});
$discord->run();