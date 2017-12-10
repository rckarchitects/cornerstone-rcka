<?php

$output = DeleteContact($_POST[contact_delete],$_POST[contact_mergeto]);

$techmessage = $output[0];

$actionmessage = $output[1];

