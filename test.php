<?php

  include './labeldb.php';

  function printj ($var)
  {
    echo json_encode($var) . "\n";
  }

  $db = new LabelDB("./test.db");

  $ins = $db->query("INS users 1 info Marty 24");
  $ins = $db->query("INS users 2 info Test 20");
  $ins = $db->query("INS users 3 info Test2 18");

  printj($db->query("SEL users * info *"));
  $db->query("DEL users * info * 20");
  printj($db->query("SEL users * info *"));

