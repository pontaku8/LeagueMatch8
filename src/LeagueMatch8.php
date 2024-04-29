<?php

class LeagueMatch8 {

  private $settings = [];
  private $errors = [];
  private $outputTypes = [];

  const DATA_TEXT_MAX_LENGTH = 50;
  const DATA_MIN_LENGTH = 1;
  const DATA_MAX_LENGTH = 20;
  const ERROR_MESSAGE_MAX_LENGTH = 20;
  const OUTPUT_TYPE_ARRAY = 'array';
  const OUTPUT_TYPE_JSON = 'json';
  const OUTPUT_TYPE_CSV = 'csv';
  const OUTPUT_TYPE_TSV = 'tsv';
  const CSV_HEADER_TEXT_MAX_LENGTH = 20;
  const CSV_HEADERS_REQUIRED_LENGTH = 3;
  const TSV_HEADER_TEXT_MAX_LENGTH = 20;
  const TSV_HEADERS_REQUIRED_LENGTH = 3;

  public function __construct($settings = []) 
  {

    $this->settings = $settings;
    $this->outputTypes = [
      self::OUTPUT_TYPE_ARRAY,
      self::OUTPUT_TYPE_JSON,
      self::OUTPUT_TYPE_CSV,
      self::OUTPUT_TYPE_TSV
    ];

    $is_array_settings = is_array($this->settings);
    if ($is_array_settings && empty($this->settings['data_max_length'])) 
    {
      $this->settings['data_max_length'] = self::DATA_MAX_LENGTH;
    }

    if ($is_array_settings && empty($this->settings['data_text_max_length'])) 
    {
      $this->settings['data_text_max_length'] = self::DATA_TEXT_MAX_LENGTH;
    }

    if ($is_array_settings && empty($this->settings['error_message_max_length'])) 
    {
      $this->settings['error_message_max_length'] = self::ERROR_MESSAGE_MAX_LENGTH;
    }

    if ($is_array_settings && empty($this->settings['output_type'])) 
    {
      $this->settings['output_type'] = self::OUTPUT_TYPE_ARRAY;
    }

    if ($is_array_settings && empty($this->settings['csv_headers'])) 
    {
      $this->settings['csv_headers'] = [];
    }

    if ($is_array_settings && empty($this->settings['csv_headr_text_max_length'])) 
    {
      $this->settings['csv_headr_text_max_length'] = self::CSV_HEADER_TEXT_MAX_LENGTH;
    }

    if ($is_array_settings && empty($this->settings['tsv_headers'])) 
    {
      $this->settings['tsv_headers'] = [];
    }

    if ($is_array_settings && empty($this->settings['tsv_headr_text_max_length'])) 
    {
      $this->settings['tsv_headr_text_max_length'] = self::TSV_HEADER_TEXT_MAX_LENGTH;
    }

  }

  public function isValid()
  {
    $this->errors = [];

    if (!is_array($this->settings)) 
    {
      $this->errors[] = '設定は配列を指定してください。';
      return false;
    }

    if (!is_array($this->settings['data'])) 
    {
      $this->errors[] = 'dataは配列を指定してください。';
      return false;
    }

    if (!is_int($this->settings['data_max_length'])) 
    {
      $this->errors[] = 'data_max_lengthは数字を指定してください。';
      return false;
    }

    if (self::DATA_MIN_LENGTH >= count($this->settings['data'])) 
    {
      $this->errors[] = 'dataの要素数は2以上を指定してください。';
      return false;
    }

    if ($this->settings['data_max_length'] < count($this->settings['data'])) 
    {
      $this->errors[] = 'dataの要素数の上限を超えました。';
      return false;
    }

    if (!is_array($this->settings['csv_headers'])) 
    {
      $this->errors[] = 'csv_headersは配列を指定してください。';
      return false;
    }

    if (!is_array($this->settings['tsv_headers'])) 
    {
      $this->errors[] = 'tsv_headersは配列を指定してください。';
      return false;
    }

    if (!is_int($this->settings['data_text_max_length'])) 
    {
      $this->errors[] = 'data_text_max_lengthは数字を指定してください。';
      return false;
    } 

    if (!is_int($this->settings['error_message_max_length'])) 
    {
      $this->errors[] = 'error_message_max_lengthは数字を指定してください。';
      return false;
    } 

    if (!in_array($this->settings['output_type'], $this->outputTypes, true)) 
    {
      $this->errors[] = '指定できないoutput_typeです。';
      return false;
    }
    
    $is_csv_header_required_length = (0 < count($this->settings['csv_headers']) && self::CSV_HEADERS_REQUIRED_LENGTH != count($this->settings['csv_headers']));
    if ($is_csv_header_required_length) 
    {
      $this->errors[] = 'csvヘッダは必ず3つ指定してください。';
      return false;
    }

    if (!is_int($this->settings['csv_headr_text_max_length'])) 
    {
      $this->errors[] = 'csv_headr_text_max_lengthは数字を指定してください。';
      return false;
    }

    $is_tsv_header_required_length = (0 < count($this->settings['tsv_headers']) && self::TSV_HEADERS_REQUIRED_LENGTH != count($this->settings['tsv_headers']));
    if ($is_tsv_header_required_length) 
    {
      $this->errors[] = 'tsvヘッダは必ず3つ指定してください。';
      return false;
    }

    if (!is_int($this->settings['tsv_headr_text_max_length'])) 
    {
      $this->errors[] = 'tsv_headr_text_max_lengthは数字を指定してください。';
      return false;
    }

    foreach ($this->settings['csv_headers'] as $header)
    {
      $error = $this->getCsvHeaderError($header);
      if (!is_null($error)) 
      {
        $this->errors[] = $error;
        break;
      }
    }

    foreach ($this->settings['tsv_headers'] as $header)
    {
      $error = $this->getTsvHeaderError($header);
      if (!is_null($error)) 
      {
        $this->errors[] = $error;
        break;
      }
    }

    foreach ($this->settings['data'] as $k => $v)
    {
      if ($this->settings['error_message_max_length'] <= count($this->errors)) break;
      $error = $this->getDataError($k, $v);
      if (!is_null($error)) 
      {
        $this->errors[] = $error;
        continue;
      }
    }

    return count($this->errors) > 0 ? false : true;
  }

  private function getDataError($key, $value)
  {
    if (is_null($value)) 
    {
      return "dataの値にnullは指定できません。({$key}番目)";
    }

    if (!is_string($value)) 
    {
      return "dataの値に文字列以外は指定できません。({$key}番目)";
    }

    if (mb_strlen($value) > $this->settings['data_text_max_length']) 
    {
      return "dataの値の最大文字数を超えてます。({$key}番目)";
    }

    $dataCount = count($this->settings['data']);
    $is_unique = (in_array($value, array_slice($this->settings['data'], 0, $key), true) 
                  || in_array($value, array_slice($this->settings['data'], $key + 1, $dataCount - $key), true));
    if ($is_unique) 
    {
      return "dataの値に重複が存在します。({$key}番目)";
    }

    return null;
  }

  private function getCsvHeaderError($value)
  {
    if (is_null($value)) 
    {
      return "csv_headersの値にnullは指定できません。";
    }

    if (!is_string($value)) 
    {
      return "csv_headersの値に文字列以外は指定できません。";
    }

    if (mb_strlen($value) > $this->settings['csv_headr_text_max_length']) 
    {
      return "csv_headersの値の最大文字数を超えてます。";
    }
    
    return null;
  }

  private function getTsvHeaderError($value)
  {
    if (is_null($value)) 
    {
      return "tsv_headersの値にnullは指定できません。";
    }

    if (!is_string($value)) 
    {
      return "tsv_headersの値に文字列以外は指定できません。";
    }

    if (mb_strlen($value) > $this->settings['tsv_headr_text_max_length']) 
    {
      return "tsv_headersの値の最大文字数を超えてます。";
    }
    
    return null;
  }


  public function getErrors()
  {
    return $this->errors;
  }

  public function output()
  {

    if (!$this->isValid()) return [];

    $outputData = $this->makeLeagueList();
    switch ($this->settings['output_type']) 
    {
      case self::OUTPUT_TYPE_ARRAY:
        return $outputData;
      case self::OUTPUT_TYPE_JSON:
        return json_encode($outputData);
      case self::OUTPUT_TYPE_CSV:
        return $this->convertCsv($outputData);
      case self::OUTPUT_TYPE_TSV:
        return $this->convertTsv($outputData);
    }
    return $outputData;
  }

  private function convertCsv($leagueList)
  {

    $csv = '';
    foreach($this->settings['csv_headers'] as $header) 
    {
      $csv .= $header . ',';
    }
    if ($csv != '') $csv = rtrim($csv, ',') . "\n";

    foreach($leagueList as $leagueIndex => $matches) 
    {
      foreach($matches as $match) 
      {
        $csv .= "{$leagueIndex},{$match[0]},{$match[1]}\n";
      }
    }

    return $csv;
  }


  private function convertTsv($leagueList)
  {

    $tsv = "";
    foreach($this->settings['tsv_headers'] as $header) 
    {
      $tsv .= $header . "\t";
    }
    if ($tsv != '') $tsv = rtrim($tsv, "\t") . "\n";

    foreach($leagueList as $leagueIndex => $matches) 
    {
      foreach($matches as $match) 
      {
        $tsv .= $leagueIndex . "\t" . $match[0] . "\t" .$match[1] . "\n";
      }
    }

    return $tsv;
  }

  private function makeLeagueList() 
  {

    $leagueList = [];
    $indexes = [];
    foreach ($this->settings['data'] as $k => $v)
    {
        $indexes[$k] = $k;
    }
    
    $is_odd = count($indexes) % 2 != 0 ? true : false;
    if ($is_odd) $indexes[] = 'dummy';
    
    $indexesCount = count($indexes);
    $circle = [];
    for ($i = 0; $i < $indexesCount; $i++)
    {
        $circle[$i] = $i;
    }
    
    $circleCount = count($circle);
    $circleRightSide = $circle;
    $circleLeftSide = [];
    for ($i = 0; $i < $circleCount / 2; $i++) 
    {
        $circleLeftSide[] = $circleRightSide[$i];
        unset($circleRightSide[$i]);
    }
    
    rsort($circleRightSide);
  
    for ($i = 0; $i < $circleCount - 1; $i++)
    {
      if (!isset($leagueList[$i])) $leagueList[$i] = [];
      
      foreach ($circleLeftSide as $k => $v) 
      {
          $leagueList[$i][] = [$circleLeftSide[$k], $circleRightSide[$k]];
      }
      array_splice($circleRightSide, 1, 0, array_shift($circleLeftSide));
      $circleLeftSide[] = array_pop($circleRightSide);
    }
    
    $leagueList = array_map(function ($matches) 
    {
      $filtered = array_filter($matches, function($dataIndexes) 
      {
        return isset($this->settings['data'][$dataIndexes[0]]) && isset($this->settings['data'][$dataIndexes[1]]);
      });

      return array_map(function ($dataIndexes) 
      {
        return [$this->settings['data'][$dataIndexes[0]], $this->settings['data'][$dataIndexes[1]]];
      }, $filtered);
    }, $leagueList);
    
    return $leagueList;
  }

}
