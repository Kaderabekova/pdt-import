<?php

require_once('BaseImport.php');

class CountryImport extends BaseImport
{
  public function import(array $file_country)
  {
    $country_id = NULL;
    $country_code = $file_country['country_code'];

    $db_country = $this->fetchExistingCountry($country_code);

    if ($db_country) {
      // If country already exists -> Do nothing
      $country_id = $db_country['id'];
    } else {
      // If country doesn't exists -> Create it
      $country_id = $this->createNewCountry($file_country);
    }

    return $country_id;
  }

  /**
   * Try to get existing country from Database
   */
  private function fetchExistingCountry(string $country_code)
  {
    $query = "SELECT id FROM countries WHERE code = $1;";
    $query_params = [$country_code];
    $result = pg_query_params($this->connection, $query, $query_params);
    return json_decode(json_encode(pg_fetch_array($result)), TRUE);
  }
  
  /**
   * Create new country if there's non in Database
   */
  private function createNewCountry(array $file_country)
  {
    $query = "INSERT INTO countries (code, name) VALUES($1, $2) RETURNING id;";
    $query_params = [
      $file_country['country_code'],
      $file_country['name'],
    ];
    $result = pg_query_params($this->connection, $query, $query_params);
    $parsed_result = json_decode(json_encode(pg_fetch_array($result)), TRUE);
    
    // TODO: Remove - Only for debugging purposes
    // echo "{$file_country['country_code']}: Inserted new country<br/>";

    return $parsed_result['id'];
  }
}
