<?php
require_once('./vendor/autoload.php');
require_once('./.env');
require __DIR__ . '/vendor/autoload.php';


use Discord\Discord;
use Discord\Exceptions\IntentException;
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

    $discord->application->commands->freshen()->done(function ($commands) {
        foreach ($commands as $command) {
            echo "Deleting command: {$command->name}", PHP_EOL;
            $commands->delete($command);
        }
    });
    $discord->run();
});
