<?php

  include './labeldb.php';

  $db = new LabelDB("./test.db");

  $ins = $db->query("INS users 1 info Marty 24");
  var_dump($ins);

  $ins = $db->query("INS users 1 info Test 18");
  var_dump($ins);

  $sel = $db->query("SEL users * info * 24");
  var_dump($sel);

  $sel = $db->query("SEL users * info *");
  var_dump($sel);


  $lbl = $db->labelify("<h2>Name and surname</h2>\n<small>special chars 😀</small>");
  var_dump($lbl);
  var_dump($db->stringify($lbl));

  $ins = $db->query("INS things " . $lbl);
  var_dump($ins);
  $sel = $db->query("SEL things *");
  var_dump($sel);
