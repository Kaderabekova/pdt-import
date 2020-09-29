<?php

require_once('class/TableController.php');
require_once('class/ConnectionController.php');

// RUN PHP SERVER LOCALLY
// php -S localhost:8080

function importFileContent($connection)
{
    $fp = gzopen("data/test.jsonl.gz", "r");

    $values = [];

    while ($line = fgets($fp)) {
        $tweet = json_decode($line);

        $values[] = "('12345', '{$tweet->id_str}', 1, NULL, 'horror', NULL)";
    }

    // query pre films
    // $query = "INSERT INTO films (code, title, did, date_prod, kind, len) VALUES " . implode(',', $values);

    $query = "INSERT INTO films (code, title, did, date_prod, kind, len) VALUES " . implode(',', $values);

    // TODO
    pg_prepare($connection, 'insert_films', $query);
    pg_execute($connection, 'insert_films', []);

    // INSERT INTO films (code, title, did, date_prod, kind) VALUES
    // ('B6717', 'Tampopo', 110, '1985-02-10', 'Comedy'),
    // ('B6717', 'Tampopo', 110, '1985-02-10', 'Comedy'),
    // ('B6717', 'Tampopo', 110, '1985-02-10', 'Comedy'),
}

function main()
{
    // importFileContent($connection);

    echo '
        <ul>
            <li><a href="/create-tables.php">Create tables</a></li>
            <li><a href="/delete-tables.php">Delete tables</a></li>
            <li>------------------------------</li>
            <li><a href="/import.php">Import</a></li>
        </ul>
    ';
}

main();

// DELETE FROM films WHERE code = '12345';

// DROP TABLE films;

// CREATE TABLE geometries (
//   name VARCHAR(20),
//   point GEOMETRY
// );


// Parent_id typ references tweets(id)
// S tym ze najskor vlozis tweet z retweeted_status a potom tweet v ktorom si
// Lebo opacne by ti to povedalo ze id na ktore sa odkazujes este v db nemas


// pri niektorých stĺpcoch sa pozerám, či v JSONE existujú 😃 a treba mať get aj create, to znamená, 
// že buď máš už existujúci záznam alebo musíš vytvoriť nový 😃 a napr. c user_mentions v jsone sa môžu 
// vyskytovať nové accounts, ktoré ešte nemáš v DB.. lenže tým chýba polovica atribútov 😃 preto kontrolujem a
//  prípadne updatnem, ak program neskôr prečíta nové dáta pre toho usera 
