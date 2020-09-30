<?php

class TableController
{
  private $connection;

  public function __construct($connection)
  {
    $this->connection = $connection;
  }

  /**
   * Create accounts table
   */
  public function createAccountsTable()
  {
    $query = 'CREATE TABLE accounts (
      id                BIGINT PRIMARY KEY,
      screen_name       VARCHAR(200),
      name              VARCHAR(200),
      description       TEXT,
      followers_count   INTEGER,
      friends_count     INTEGER,
      statuses_count    INTEGER
    )';

    pg_query_params($this->connection, $query, []);
    echo 'Table `Accounts` created<br/>';
  }

  /**
   * Create countries table
   */
  public function createCountriesTable()
  {
    $query = 'CREATE TABLE countries (
      id    SERIAL PRIMARY KEY,
      code  VARCHAR(2),
      name  VARCHAR(200)
    )';

    pg_query_params($this->connection, $query, []);
    echo 'Table `Countries` created<br/>';
  }

  /**
   * Create hashtags table
   */
  public function createHashtagsTable()
  {
    $query = 'CREATE TABLE hashtags (
      id      SERIAL PRIMARY KEY,
      value   TEXT
    )';

    pg_query_params($this->connection, $query, []);
    echo 'Table `Hashtags` created<br/>';
  }

  /**
   * Create tweets table
   */
  public function createTweetsTable()
  {
    $query = 'CREATE TABLE tweets (
      id              VARCHAR(20) PRIMARY KEY,
      content         TEXT,
      location        GEOMETRY(POINT,4326),
      retweet_count   INTEGER,
      favorite_count  INTEGER,
      happened_at     TIMESTAMP WITH TIME ZONE,
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

    pg_query_params($this->connection, $query, []);
    echo 'Table `Tweets` created<br/>';
  }

  /**
   * Create tweet mentions table
   */
  public function createTweetMentionsTable()
  {
    $query = 'CREATE TABLE tweet_mentions (
      id  SERIAL PRIMARY KEY,
      account_id BIGINT
          REFERENCES accounts(id)
              ON DELETE CASCADE,
      tweet_id VARCHAR(20)
          REFERENCES tweets(id)
              ON DELETE CASCADE
    )';

    pg_query_params($this->connection, $query, []);
    echo 'Table `Tweet mentions` created<br/>';
  }

  /**
   * Create tween hashtags table
   */
  public function createTweetHashtagsTable()
  {
    $query = 'CREATE TABLE tweet_hashtags (
      id          SERIAL PRIMARY KEY,
      hashtag_id  INTEGER
      REFERENCES hashtags(id)
              ON DELETE CASCADE,

      tweet_id VARCHAR(20)
      REFERENCES tweets(id)
              ON DELETE CASCADE
    )';

    pg_query_params($this->connection, $query, []);
    echo 'Table `Tweet hashtags` created<br/>';
  }

  /**
   * Delete all created tables
   */
  public function deleteTables()
  {
    $query = 'DROP TABLE accounts, countries, hashtags, tweets, tweet_hashtags, tweet_mentions';

    pg_query_params($this->connection, $query, []);
    echo 'Tables deleted';
  }
}
