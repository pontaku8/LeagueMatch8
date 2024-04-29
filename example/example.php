<?php

require '../src/LeagueMatch8.php';

$leagueMatch = new LeagueMatch8([
  'data' => ['チームA','チームB','チームC','チームE','チームD'],
  'data_max_length' => 10,
  'error_message_max_length' => 5,
  'output_type' => 'tsv',
]);

if ($leagueMatch->isValid()) 
{
  print_r($leagueMatch->output());
} else 
{
  print_r($leagueMatch->getErrors());
}





