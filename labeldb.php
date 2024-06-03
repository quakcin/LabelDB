<?php

class LabelDB
{
  private $file;
  private $pool;

  /**
   * ----------------------------------------
   * PUBLIC METHODS
   * ----------------------------------------
   */

  public function __construct($file)
  {
    $this->file = $file;
    $this->loadPool();
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
      "DEL" => "delete"
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

  }

  /**
   * Converts label to string
   */
  public function stringify ($label)
  {

  }

  /**
   * ----------------------------------------
   * PRIVATE METHODS
   * ----------------------------------------
   */

  private function loadPool ()
  {
    if (!file_exists($this->file)) {
      file_put_contents($this->file, "");
    }
    $this->pool = explode("\n", file_get_contents($this->file));
    foreach ($this->pool as &$line) {
      $line = explode(" ", $line);
    }
  }

  private function filterQuery ($query)
  {
    $query = trim($query);
    while (strpos($query, "  ") !== false) {
      $query = str_replace("  ", " ", $query);
    }
    return $query;
  }

  private function deleteExact ($row)
  {

  }

  private function delete ($toks)
  {
    return "del";
  }

  private function insert ($toks)
  {
    return "ins";
  }

  private function select ($toks)
  {
    return $toks;
  }

}