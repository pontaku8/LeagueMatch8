<?php

require './LeagueMatch8Test.php';
require '../src/LeagueMatch8.php';

$input = [
  'data' => ['チームA', 'チームB', 'チームC', 'チームD', 'チームE', 'チームF', 'チームG', 'チームH', 'チームI', 'チームJ', 'チームK', 'チームL', 'チームM', 'チームN', 'チームO', 'チーム7']
];

$leagueMatch = new LeagueMatch8($input);
$leagueMatch8Test = new LeagueMatch8Test();
$ok = [];

$output = $leagueMatch->output();
$result = $leagueMatch8Test->matchCountTest($input, $output);
$ok[] = ($result == 'OK') ? true : false;
echo "matchCountTest: " . $result . PHP_EOL;


$result = $leagueMatch8Test->matchAllTeamsTest($input, $output);
$ok[] = ($result == 'OK') ? true : false;
echo "matchAllTeamsTest: " . $result . PHP_EOL;

if (!in_array(false, $ok)) echo '##### All Ok!!! #####' . PHP_EOL;

