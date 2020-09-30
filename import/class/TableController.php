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
    // TODO: Refactor Foreign keys and parent_id
    $query = 'CREATE TABLE tweets (
      id              VARCHAR(20) PRIMARY KEY,
      content         TEXT,
      location        GEOMETRY(POINT,4326),
      retweet_count   INTEGER,
      favorite_count  INTEGER,
      happened_at     TIMESTAMP WITH TIME ZONE,
      author_id       BIGINT,
        CONSTRAINT fk_author_id
          FOREIGN KEY(author_id) 
            REFERENCES accounts(id)
              ON DELETE CASCADE,
      country_id      INTEGER,
        CONSTRAINT fk_country_id
          FOREIGN KEY(country_id) 
            REFERENCES countries(id)
              ON DELETE CASCADE,
      parent_id       VARCHAR(20)
        -- CONSTRAINT fk_parent_id
        --   FOREIGN KEY(parent_id) 
        --     REFERENCES tweets(id)
        --       ON DELETE CASCADE
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
      id              SERIAL PRIMARY KEY,
      account_id      BIGINT,
      tweet_id        VARCHAR(20),

      CONSTRAINT fk_account_id
          FOREIGN KEY(account_id) 
            REFERENCES accounts(id)
              ON DELETE CASCADE,
        CONSTRAINT fk_tweet_id
          FOREIGN KEY(tweet_id) 
            REFERENCES tweets(id)
              ON DELETE CASCADE
    )';

    pg_query_params($this->connection, $query, []);
    echo 'Table `Tweet mentions` created<br/>';
  }

  /**
   * Create tweet hashtags table
   */
  public function createTweetHashtagsTable()
  {
    $query = 'CREATE TABLE tweet_hashtags (
      id          SERIAL PRIMARY KEY,
      hashtag_id  INTEGER,
      tweet_id VARCHAR(20),
      CONSTRAINT fk_hashtag_id
          FOREIGN KEY(hashtag_id) 
            REFERENCES hashtags(id)
              ON DELETE CASCADE,
        CONSTRAINT fk_tweet_id
          FOREIGN KEY(tweet_id)
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
