<?php

class LeagueMatch8Test {

  public function matchCountTest($input, $output)
  {

    $n = count($input['data']);
    $okCount = $n * ($n - 1) / 2;
    $matchCount = 0;
    foreach($output as $match) 
    {
      $matchCount += count($match);
    }

    return $okCount === $matchCount ? 'OK' : 'NG';
  }

  public function matchAllTeamsTest($input, $output)
  {
    foreach($input['data'] as $team) 
    {
      $opponents = array_diff($input['data'], [$team]);
      $result = $this->matchAllTeams($team, $opponents, $output);
      if ($result === false) return 'NG';
    }
    return 'OK';
  }

  private function matchAllTeams($team, $opponents, $leagueList)
  {
    foreach($leagueList as $leagueIndex => $matches) 
    {
      foreach($matches as $match) 
      {
        $key = array_search($match[0], $opponents);
        if ($key !== false && $team === $match[1]) unset($opponents[$key]);
        $key = array_search($match[1], $opponents);
        if ($key !== false && $team === $match[0]) unset($opponents[$key]);
      }
    }
    return count($opponents) === 0 ? true : false;
  }

}