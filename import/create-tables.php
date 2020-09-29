<?php

require_once('class/TableController.php');
require_once('class/ConnectionController.php');

function main()
{
  $connection = ConnectionController::createConnection();

  TableController::createAccountsTable($connection);
  TableController::createCountriesTable($connection);
  TableController::createHashtagsTable($connection);
  TableController::createTweetsTable($connection);
  TableController::createTweetMentionsTable($connection);
  TableController::createTweetHashtagsTable($connection);
}

main();
