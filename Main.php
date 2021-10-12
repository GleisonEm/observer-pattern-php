<?php

use App\Database\Connection;
use App\Database\Repository\{PdoUserRepository, PdoDeviceRepository, PdoEmailRepository, PdoReceiverRepository};
use App\Observers\NotifyNewEmail;
use App\Models\{Email, User, Device};

require_once 'vendor/autoload.php';

$pdo_connection = (new Connection)->create();

echo "Dropando tabelas \n";

$drop_tables = 'DROP TABLE users;
                DROP TABLE devices;
                DROP TABLE emails;';
$pdo_connection->exec($drop_tables);

echo "Criando tabelas \n";

$createTables = '
    CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY, 
        name TEXT,
        email TEXT,
        date_created TEXT
    );

    CREATE TABLE IF NOT EXISTS devices (
        id INTEGER PRIMARY KEY,
        platform TEXT,
        user_id INTEGER,
        date_created TEXT,
        FOREIGN KEY(user_id) REFERENCES users (id)
    );

    CREATE TABLE IF NOT EXISTS emails (
        id INTEGER PRIMARY KEY,
        title TEXT,
        content TEXT,
        creator_id INTEGER,
        receiver_id INTEGER,
        status TEXT,
        date_created TEXT,
        FOREIGN KEY(creator_id) REFERENCES users (id),
        FOREIGN KEY(receiver_id) REFERENCES users (id)
    );
';

$pdo_connection->exec($createTables);

$user_author = new User(
    null,
    'gleisine',
    'gleisin@com',
    new \DateTimeImmutable('2021-10-10'),
);

echo "Criando o usuário {$user_author->name()} author do e-mail...\n";

$repository_user = new PdoUserRepository($pdo_connection);
$user_author = $repository_user->add($user_author);

$user_receiver = new User(
    null,
    'predin',
    'predin@com',
    new \DateTimeImmutable('2021-10-10'),
);

echo "Criando o usuário {$user_receiver->name()} que vai receber o e-mail...\n";

$user_receiver = $repository_user->add($user_receiver);

echo "Criando Dipositivos do usuário: {$user_receiver->name()}...\n";

$mapped_types_devices = [
    1 => 'windows',
    2 => 'android',
    3 => 'ios'
];

$repository_device = new PdoDeviceRepository($pdo_connection);
$devices = [];

for ($i = 1; $i <= (rand(1,3)); $i++) {
    $devices[] = $repository_device->add(new Device(
        null,
        $mapped_types_devices[$i],
        $user_receiver->id(),
        new \DateTimeImmutable('2021-10-10'),
    ));
}

echo "Criando Email...\n";

$email = new Email(
    null,
    'Oferta de trabalho', 
    'Fale comigo pelo número 87988330001 para conversamos sobre uma oferta de trabalho',
    $user_author->id(),
    $user_receiver->id(),
    'pending',
    new \DateTimeImmutable('2021-10-10'),
);

$repository_email = new PdoEmailRepository($pdo_connection);
$repository_email->attach(new NotifyNewEmail(), 'email:created');
$email = $repository_email->add($email);
$email->defineStatus('notified');

$repository_email->update($email);

echo "Me contrata ai chefia\n";