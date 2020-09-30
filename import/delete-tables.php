<?php

require_once('class/TableController.php');
require_once('class/ConnectionController.php');

function main()
{
  $connection = ConnectionController::createConnection();
  $table_controller = new TableController($connection);

  $table_controller->deleteTables();
}

main();
