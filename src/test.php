<?php
include('Creator.php');
use AcfCreator\Creator;

$myFields = (new Creator())->addText('title');

var_dump($myFields);