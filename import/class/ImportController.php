<?php

// Extend request time limit
set_time_limit(10800);
ini_set('max_execution_time', 10800);

require_once('import/AccountImport.php');
require_once('import/CountryImport.php');
require_once('import/HashtagImport.php');
require_once('import/TweetImport.php');
require_once('import/TweetMentionsImport.php');
require_once('import/TweetHashtagsImport.php');

class ImportController
{
  private $connection;
  private $account_import;
  private $country_import;
  private $hashtag_import;
  private $tweet_import;
  private $tweet_mentions_import;
  private $tweet_hashtags_import;

  public function __construct($connection)
  {
    $this->connection = $connection;
    $this->account_import = new AccountImport($this->connection);
    $this->country_import = new CountryImport($this->connection);
    $this->hashtag_import = new HashtagImport($this->connection);
    $this->tweet_import = new TweetImport($this->connection);
    $this->tweet_mentions_import = new TweetMentionsImport($this->connection);
    $this->tweet_hashtags_import = new TweetHashtagsImport($this->connection);
  }

  public function import()
  {

    $files = [
      'corona-1.jsonl.gz',
      // 'corona-2.jsonl.gz',
      // 'corona-3.jsonl.gz',
      // 'corona-4.jsonl.gz',
    ];

    foreach($files as $file_name) {
      $fp = gzopen("data/{$file_name}", "r");

      $skip_to_line = NULL;
  
      $line_id = 0;
      while ($line = fgets($fp)) {
        $tweet = json_decode($line, TRUE);
        
        $line_id++;

        // Skip lines those are already imported
        if (isset($skip_to_line) && ($line_id < $skip_to_line)) {
          continue;
        }

        echo '. ';
        if ($line_id % 1000 == 0) {
          echo "$line_id";
        }
  
        // TODO: Remove - This prints only first row from file
        // echo '<pre>';
        // var_dump($tweet);
        // echo '</pre>';
        // die();
  
        // Import account
        $account_id = NULL;
        if (isset($tweet['user'])) {
          $account_id = $this->account_import->import($tweet['user']);
        }
  
        // Import country
        $country_id = NULL;
        if (isset($tweet['place'])) {
          $country_id = $this->country_import->import($tweet['place']);
        }
  
        // Import hashtags
        $hashtags_ids = [];
        if (isset($tweet['entities']['hashtags'])) {
          foreach ($tweet['entities']['hashtags'] as $hashtag) {
            $hashtags_ids[] = $this->hashtag_import->import($hashtag);
          }
        }
  
        if (isset($tweet['retweeted_status'])) {
          // Import retweet account
          $retweet_account_id = NULL;
          if (isset($tweet['retweeted_status']['user'])) {
            $retweet_account_id = $this->account_import->import($tweet['retweeted_status']['user']);
          }
  
          // Import retweet country
          $retweet_country_id = NULL;
          if (isset($tweet['retweeted_status']['place'])) {
            $retweet_country_id = $this->country_import->import($tweet['retweeted_status']['place']);
          }
  
          // Import retweet hashtags
          $retweet_hashtags_ids = [];
          if (isset($tweet['retweeted_status']['entities']['hashtags'])) {
            foreach ($tweet['retweeted_status']['entities']['hashtags'] as $hashtag) {
              $retweet_hashtags_ids[] = $this->hashtag_import->import($hashtag);
            }
          }
  
          // Import retweet tweet
          $retweet_tweet = $tweet['retweeted_status'];
          $retweet_meta = [
            'author_id' => $retweet_account_id,
            'country_id' => $retweet_country_id,
            'parent_id' => NULL
          ];
          $retweet_tweet_id = $this->tweet_import->import($retweet_tweet, $retweet_meta);
  
          // Import retweet tweet mentions
          $this->tweet_mentions_import->import($retweet_tweet_id, $retweet_account_id);
  
          // Import retweet tweet hashtags
          $this->tweet_hashtags_import->import($retweet_tweet_id, $retweet_hashtags_ids);
        }
  
        // Import tweet
        $tweet_meta = [
          'author_id' => $account_id,
          'country_id' => $country_id,
          'parent_id' => isset($tweet['retweeted_status']) ? $tweet['retweeted_status']['id_str'] : null,
        ];
        $tweet_id = $this->tweet_import->import($tweet, $tweet_meta);
  
        // Import retweet tweet mentions
        $this->tweet_mentions_import->import($tweet_id, $account_id);
  
        // Import retweet tweet hashtags
        $this->tweet_hashtags_import->import($tweet_id, $hashtags_ids);
  
        // TODO: Remove
        // die();
      }
  
      echo "<br/>{$file_name}: DONE!<br/>";
    }
  }
}
