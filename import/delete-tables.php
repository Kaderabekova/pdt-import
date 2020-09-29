<?php

require_once('class/TableController.php');
require_once('class/ConnectionController.php');

function main()
{
  $connection = ConnectionController::createConnection();

  TableController::deleteTables($connection);
}

main();
