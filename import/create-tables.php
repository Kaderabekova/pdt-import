<?php

require_once('class/TableController.php');
require_once('class/ConnectionController.php');

function main()
{
  $connection = ConnectionController::createConnection();
  $table_controller = new TableController($connection);

  $table_controller->createAccountsTable();
  $table_controller->createCountriesTable();
  $table_controller->createHashtagsTable();
  $table_controller->createTweetsTable();
  $table_controller->createTweetMentionsTable();
  $table_controller->createTweetHashtagsTable();
}

main();
