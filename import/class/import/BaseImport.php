<?php

class BaseImport
{
  protected $connection;

  public function __construct($connection)
  {
    $this->connection = $connection;
  }
}
