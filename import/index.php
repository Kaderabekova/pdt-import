<?php

// RUN PHP SERVER LOCALLY
// php -S localhost:8080

function importFileContent($connection) {
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

// ACCOUNTS
function createAccountsTable($connection) {
    $query = 'CREATE TABLE accounts (
        id BIGINT PRIMARY KEY,
        screen_name VARCHAR(200),
        name  VARCHAR(200),
        description TEXT,
        followers_count INTEGER,
        friends_count INTEGER,
        statuses_count INTEGER
    )';

    pg_prepare($connection, 'create_table', $query);
    $result = pg_execute($connection, 'create_table', []);

    var_dump($result);
}

// COUNTRIES
function createCountriesTable($connection) {
    $query = 'CREATE TABLE countries (
        id INTEGER PRIMARY KEY,
        code VARCHAR(2),
        name  VARCHAR(200)
    )';

    pg_prepare($connection, 'create_table1', $query);
    $result = pg_execute($connection, 'create_table1', []);

    var_dump($result);
}

// HASHTAGS
function createHashtagsTable($connection) {
    $query = 'CREATE TABLE hashtags (
        id INTEGER PRIMARY KEY,
        value TEXT
    )';

    pg_prepare($connection, 'create_table2', $query);
    $result = pg_execute($connection, 'create_table2', []);

    var_dump($result);
}

// TWEETS
function createTweetsTable($connection) {
    $query = 'CREATE TABLE tweets (
        id VARCHAR(20) PRIMARY KEY,
        content TEXT,
        location GEOMETRY(POINT,4326),
        retweet_count INTEGER,
        favorite_count INTEGER,
        happened_at TIMESTAMP WITH TIME ZONE,

        author_id BIGINT
            REFERENCES accounts(id)
                ON DELETE CASCADE,

        country_id INTEGER
            REFERENCES countries(id)
                ON DELETE CASCADE,

        parent_id VARCHAR(20)
            REFERENCES tweets(id)
                ON DELETE CASCADE
    )';

    pg_prepare($connection, 'create_table3', $query);
    $result = pg_execute($connection, 'create_table3', []);

    var_dump($result);
}

// TWEET_MENTIONS
function createTweetMentionsTable($connection) {
    $query = 'CREATE TABLE tweet_mentions (
        id INTEGER PRIMARY KEY,

        account_id BIGINT
            REFERENCES accounts(id)
                ON DELETE CASCADE,

        tweet_id VARCHAR(20)
            REFERENCES tweets(id)
                ON DELETE CASCADE
    )';

    pg_prepare($connection, 'create_table4', $query);
    $result = pg_execute($connection, 'create_table4', []);

    var_dump($result);
}

// TWEET_HASHTAGS
function createTweetHashtagsTable($connection) {
    $query = 'CREATE TABLE tweet_hashtags (
        id INTEGER PRIMARY KEY,

        hashtag_id INTEGER
        REFERENCES hashtags(id)
                ON DELETE CASCADE,

        tweet_id VARCHAR(20)
        REFERENCES tweets(id)
                ON DELETE CASCADE
    )';

    pg_prepare($connection, 'create_table5', $query);
    $result = pg_execute($connection, 'create_table5', []);

    var_dump($result);
}

function createConnection() {
    return pg_connect('host=localhost port=5432 dbname=postgres user=postgres password=postgres');
}

function main() {
    $connection = createConnection();
    // importFileContent($connection);
    createAccountsTable($connection);
    createCountriesTable($connection);
    createHashtagsTable($connection);
    createTweetsTable($connection);
    createTweetMentionsTable($connection);
    createTweetHashtagsTable($connection);
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