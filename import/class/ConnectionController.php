<?php

class ConnectionController
{
  public static function createConnection()
  {
    return pg_connect('host=localhost port=5432 dbname=postgres user=postgres password=postgres');
  }
}
