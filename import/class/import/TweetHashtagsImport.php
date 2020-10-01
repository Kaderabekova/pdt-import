<?php

require_once('BaseImport.php');

class TweetHashtagsImport extends BaseImport
{
  public function import(string $tweet_id, array $hashtags_ids)
  {
    foreach($hashtags_ids as $hashtag_id) {
      $this->createNewTweenHashtag($tweet_id, $hashtag_id);
    }
  }

  private function createNewTweenHashtag(string $tweet_id, string $hashtag_id)
  {
    $query = "INSERT INTO tweet_hashtags (hashtag_id, tweet_id) VALUES ($1, $2);";

    $query_params = [
      $hashtag_id,
      $tweet_id
    ];

    pg_query_params($this->connection, $query, $query_params);
  }
}



