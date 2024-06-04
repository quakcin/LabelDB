<?php

class LabelDB
{
  private $file;

  /**
   * ----------------------------------------
   * PUBLIC METHODS
   * ----------------------------------------
   */

  public function __construct($file)
  {
    $this->file = $file;
  }

  /**
   * Performs LQL query
   */
  public function query ($lql)
  {
    $toks = explode(" ", $this->filterQuery($lql));
    $cmds = [
      "SEL" => "select",
      "INS" => "insert",
      "DEL" => "delete",
      "MOD" => "modify"
    ];

    if (!isset($cmds[$toks[0]])) {
      return false;
    }

    $cmd = array_shift($toks);
    return $this->{$cmds[$cmd]}($toks);
  }

  /**
   * Converts string to label
   */
  public function labelify ($str)
  {
    return base64_encode(urlencode($str));

  }

  /**
   * Converts label to string
   */
  public function stringify ($label)
  {
    return urldecode(base64_decode($label));
  }

  /**
   * ----------------------------------------
   * PRIVATE METHODS
   * ----------------------------------------
   */

  private function loadData ()
  {
    if (!file_exists($this->file)) {
      file_put_contents($this->file, "");
    }
    return file_get_contents($this->file);
  }

  private function loadPool ()
  {
    $pool = explode("\n", $this->loadData());
    foreach ($pool as &$line) {
      $line = explode(" ", $line);
    }

    return $pool;
  }

  private function storeData ($data)
  {
    file_put_contents($this->file, $data);
  }

  private function filterQuery ($query)
  {
    $query = trim($query);
    while (strpos($query, "  ") !== false) {
      $query = str_replace("  ", " ", $query);
    }
    return $query;
  }

  private function deleteExact ($toks, &$data)
  {
    $data = str_replace(implode(" ", $toks) . "\n", "", $data);
    if (count($toks) > 1) {
      array_pop($toks);
      $this->deleteExact($toks, $data);
    }
  }

  private function modify ($toks)
  {
    return "mod";
  }

  private function delete ($toks)
  {
    $pool = $this->loadPool();
    $newData = [];

    foreach ($pool as $line) {
      $found = $this->match($toks, $line);
      if (empty($found)) {
        $newData[] = implode(" ", $line);
      }
    }

    $this->storeData(implode("\n", $newData));
  }

  private function insert ($toks)
  {
    $data = $this->loadData();
    $this->deleteExact($toks, $data);
    $data .= implode(" ", $toks) . "\n";
    $this->storeData($data);
    return true;
  }

  private function select ($toks)
  {
    $pool = $this->loadPool();
    $matches = [];
    foreach ($pool as $line) {
      $found = $this->match($toks, $line);
      if (!empty($found)) {
        $matches[] = implode(" ", $found);
      }
    }

    $matches = array_unique($matches);
    foreach ($matches as &$match) {
      $match = explode(" ", $match);
    }

    return $matches;
  }

  private function match ($toks, $line)
  {
    $matches = [];
    if (count($line) < count($toks)) {
      return [];
    }

    for ($i = 0; $i < count($toks); $i++) {
      if ($toks[$i] == "*") {
        $matches[] = $line[$i];
        continue;
      }

      if ($toks[$i] != $line[$i]) {
        return [];
      }
    }

    return $matches;
  }

}