<?php

class TableController
{

  // ACCOUNTS
  public static function createAccountsTable($connection)
  {
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

    echo 'Table `Accounts` created<br/>';
  }

  // COUNTRIES
  public static function createCountriesTable($connection)
  {
    $query = 'CREATE TABLE countries (
      id INTEGER PRIMARY KEY,
      code VARCHAR(2),
      name  VARCHAR(200)
    )';

    pg_prepare($connection, 'create_table1', $query);
    $result = pg_execute($connection, 'create_table1', []);

    echo 'Table `Countries` created<br/>';
  }

  // HASHTAGS
  public static function createHashtagsTable($connection)
  {
    $query = 'CREATE TABLE hashtags (
      id INTEGER PRIMARY KEY,
      value TEXT
    )';

    pg_prepare($connection, 'create_table2', $query);
    $result = pg_execute($connection, 'create_table2', []);

    echo 'Table `Hashtags` created<br/>';
  }

  // TWEETS
  public static function createTweetsTable($connection)
  {
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

    echo 'Table `Tweets` created<br/>';
  }

  // TWEET_MENTIONS
  public static function createTweetMentionsTable($connection)
  {
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

    echo 'Table `Tweet mentions` created<br/>';
  }

  // TWEET_HASHTAGS
  public static function createTweetHashtagsTable($connection)
  {
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

    echo 'Table `Tweet hashtags` created<br/>';
  }

  public static function deleteTables($connection)
  {
    $query = 'DROP TABLE accounts, countries, hashtags, tweets, tweet_hashtags, tweet_mentions';

    pg_prepare($connection, 'drop_tables', $query);
    $result = pg_execute($connection, 'drop_tables', []);

    echo 'Tables deleted';
  }
}
