<?php

class ImportController
{
  private $connection;

  public function __construct($connection)
  {
    $this->connection = $connection;
  }

  public function import($connection)
  {
    $fp = gzopen("data/test.jsonl.gz", "r");

    while ($line = fgets($fp)) {
      $tweet = json_decode($line, TRUE);

      // TODO: Remove - This prints only first row from file
      // echo '<pre>';
      // var_dump($tweet);
      // echo '</pre>';
      // die();

      $this->handleAccount($tweet['user']);

      // TODO: Remove
      // die();
    }
  }

  private function handleAccount($file_account)
  {
    // Try to get existing account
    $db_account = $this->fetchExistingAccount($file_account['id_str']);

    if ($db_account) {
      // If account already exists -> update it
      $this->updateExistingAccount($file_account, $db_account);
    } else {
      // If account doesn't exists -> create it
      $this->createNewAccount($file_account);
    }
  }

  /**
   * Try to get existing account from Database
   */
  private function fetchExistingAccount($account_id)
  {
    $query = "SELECT * FROM accounts WHERE id = $1;";
    $query_params = [$account_id];
    $result = pg_query_params($this->connection, $query, $query_params);
    return json_decode(json_encode(pg_fetch_array($result)), TRUE);
  }

  /**
   * Update existing account if there are differences
   */
  private function updateExistingAccount(array $file_account, array $db_account)
  {
    $account_columns = ['screen_name', 'name', 'description', 'followers_count', 'friends_count', 'statuses_count'];

    foreach ($account_columns as $column) {
      if (
        (is_null($db_account[$column])) ||
        (isset($file_account[$column]) && isset($db_account[$column]) && $file_account[$column] != $db_account[$column])
      ) {
        $query = "UPDATE accounts SET {$column} = $1 WHERE id = $2";
        $query_params = [$file_account[$column], $db_account['id']];
        pg_query_params($this->connection, $query, $query_params);

        // TODO: Remove - Only for debugging purposes
        echo "{$db_account['id']}: Updating column: {$column} from: {$db_account[$column]} to: {$file_account[$column]}<br/>";
      }
    }
  }

  /**
   * Create new account if there's non in Database
   */
  private function createNewAccount(array $file_account)
  {
    $query = "INSERT INTO accounts (id, screen_name, name, description, followers_count, friends_count, statuses_count) VALUES($1, $2, $3, $4, $5, $6, $7);";
    $query_params = [
      $file_account['id_str'],
      $file_account['screen_name'],
      $file_account['name'],
      $file_account['description'],
      $file_account['followers_count'],
      $file_account['friends_count'],
      $file_account['statuses_count']
    ];
    pg_query_params($this->connection, $query, $query_params);

    // TODO: Remove - Only for debugging purposes
    echo "{$file_account['id']}: Inserted new account<br/>";
  }

  // TODO: Implement
  private function createTweet($tweet)
  {
    $query = 'INSERT INTO tweets (id, context, location, retweet_count, favorite_count, happened_at, author_id, country_id, parent_id)
      VALUES($1, $2, $3, $4, $5, $6, $7, $8, $9)
      ON CONFLICT (id)
      DO NOTHING'; // TODO: Change DO NOTHING to something
  }
}
