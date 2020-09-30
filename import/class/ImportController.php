<?php

require_once('import/AccountImport.php');
require_once('import/CountryImport.php');
require_once('import/HashtagImport.php');
require_once('import/TweetImport.php');

class ImportController
{
  private $connection;
  private $account_import;
  private $country_import;
  private $hashtag_import;
  private $tweet_import;

  public function __construct($connection)
  {
    $this->connection = $connection;
    $this->account_import = new AccountImport($this->connection);
    $this->country_import = new CountryImport($this->connection);
    $this->hashtag_import = new HashtagImport($this->connection);
    $this->tweet_import = new TweetImport($this->connection);
  }

  public function import()
  {
    $fp = gzopen("data/test.jsonl.gz", "r");

    while ($line = fgets($fp)) {
      $country_id = NULL;
      $hashtags_ids = [];

      $tweet = json_decode($line, TRUE);

      // TODO: Remove - This prints only first row from file
      // echo '<pre>';
      // var_dump($tweet);
      // echo '</pre>';
      // die();

      // Import account
      $account_id = $this->account_import->import($tweet['user']);
      // TODO: Check if following code works correctly
      // if (isset($tweet['retweeted_status']['user'])) {
      //   $retweeted_account_id = $this->account_import->import($tweet['retweeted_status']['user']);
      // }

      // Import country
      if (isset($tweet['place'])) {
        $country_id = $this->country_import->import($tweet['place']);
      }

      // Import hashtags
      if (isset($tweet['entities']['hashtags'])) {
        foreach($tweet['entities']['hashtags'] as $hashtag) {
          $hashtags_ids[] = $this->hashtag_import->import($hashtag);
        }
      }

      // Import tweet
      $tweet_meta = [
        'author_id' => $account_id,
        'country_id' => $country_id,
        'parent_id' => isset($tweet['retweeted_status']) ? $tweet['retweeted_status']['id_str'] : null,
      ];
      $tweet_id = $this->tweet_import->import($tweet, $tweet_meta);

      // TODO: Remove
      // die();
    }
  }
}
