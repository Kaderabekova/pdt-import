<?php

require_once('BaseImport.php');

class TweetImport extends BaseImport
{
  public function import(array $file_tweet, array $tweet_meta)
  {
    $tweet_id = $file_tweet['id_str'];

    $db_tweet = $this->fetchExistingTweet($tweet_id);

    if ($db_tweet) {
      // If tweet already exists -> Do nothing
    } else {
      // If tweet doesn't exists -> Create it
      $this->createNewTweet($file_tweet, $tweet_meta);
    }

    return $tweet_id;
  }

  private function fetchExistingTweet(string $tweet_id)
  {
    $query = "SELECT id FROM tweets WHERE id = $1;";
    $query_params = [$tweet_id];
    $result = pg_query_params($this->connection, $query, $query_params);
    return json_decode(json_encode(pg_fetch_array($result)), TRUE);
  }

  /**
   * Create new tweet if there's non in Database
   */
  private function createNewTweet(array $file_tweet, array $tweet_meta)
  {
    // TODO: Fix coordinates creation
    $coordinates = isset($file_tweet['coordinates']) ? "ST_GeomFromText('POINT({$file_tweet['coordinates'][1]} {$file_tweet['coordinates'][0]})', 4326)" : null;
    $query = "INSERT INTO tweets (id, content, location, retweet_count, favorite_count, happened_at, author_id, country_id, parent_id) VALUES($1, $2, $3, $4, $5, $6, $7, $8, $9);";
    $query_params = [
      $file_tweet['id_str'],
      $file_tweet['full_text'],
      $coordinates,
      $file_tweet['retweet_count'],
      $file_tweet['favorite_count'],
      $file_tweet['happened_at'],
      $tweet_meta['author_id'],
      $tweet_meta['country_id'],
      $tweet_meta['parent_id']
    ];
    pg_query_params($this->connection, $query, $query_params);

    // TODO: Remove - Only for debugging purposes
    echo "{$file_tweet['id']}: Inserted new tweet<br/>";
  }
}
