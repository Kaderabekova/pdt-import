<?php

require_once('BaseImport.php');

class TweetMentionsImport extends BaseImport
{
  public function import(string $tweet_id, int $account_id)
  {
    $this->createNewTweenMention($tweet_id, $account_id);
  }

  private function createNewTweenMention(string $tweet_id, string $account_id)
  {
    $query = "INSERT INTO tweet_mentions (account_id, tweet_id) VALUES ($1, $2);";

    $query_params = [
      $account_id,
      $tweet_id
    ];

    pg_query_params($this->connection, $query, $query_params);
  }
}



