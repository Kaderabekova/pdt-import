<?php

require_once('BaseImport.php');

class HashtagImport extends BaseImport
{
  public function import(array $file_hashtag)
  {
    $hashtag_id = NULL;
    $hashtag_value = $file_hashtag['text'];

    $db_hashtag = $this->fetchExistingHashtag($hashtag_value);

    if ($db_hashtag) {
      // If hashtag already exists -> Do nothing
      $hashtag_id = $db_hashtag['id'];
    } else {
      // If country doesn't exists -> Create it
      $hashtag_id = $this->createNewHashtag($file_hashtag);
    }

    return $hashtag_id;
  }

  /**
   * Try to get existing hashtag from Database
   */
  private function fetchExistingHashtag(string $hashtag_value)
  {
    $query = "SELECT id FROM hashtags WHERE value = $1;";
    $query_params = [$hashtag_value];
    $result = pg_query_params($this->connection, $query, $query_params);
    return json_decode(json_encode(pg_fetch_array($result)), TRUE);
  }

  private function createNewHashtag(array $file_hashtag)
  {
    $query = "INSERT INTO hashtags (value) VALUES($1) RETURNING id;";
    $query_params = [
      $file_hashtag['text'],
    ];
    $result = pg_query_params($this->connection, $query, $query_params);
    $parsed_result = json_decode(json_encode(pg_fetch_array($result)), TRUE);
    
    // TODO: Remove - Only for debugging purposes
    echo "{$file_hashtag['text']}: Inserted new hashtag<br/>";

    return $parsed_result['id'];
  }
}
