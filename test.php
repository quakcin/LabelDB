<?php

  include './labeldb.php';

  $db = new LabelDB("./test.db");

  $ins = $db->query("INS users 1 info Marty 24");
  var_dump($ins);

  $sel = $db->query("SEL users *");
  var_dump($sel);



