<?php

require_once('class/ConnectionController.php');
require_once('class/ImportController.php');

function main()
{
  $connection = ConnectionController::createConnection();
  $import_controller = new ImportController($connection);

  $import_controller->import($connection);
}

main();
