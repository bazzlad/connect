 <?php 

  global $system;
  $GLOBALS['version'] = '0.0.1';

  // build scripts
  function buildScripts($pageScripts) {
    foreach ($pageScripts as $script) {
      $sBuild = '<script src="';
      $sBuild .= $script;
      $sBuild .= '?v=';
      $sBuild .= $GLOBALS['version'];
      $sBuild .= '"></script>';
      $sBuild .= "\n";
      echo $sBuild;
    }
  }

  function buildStyles($pageScripts) {
    foreach ($pageScripts as $script) {
      $sBuild = '<link rel="stylesheet" href="';
      $sBuild .= $script;
      $sBuild .= '?v=';
      $sBuild .= $GLOBALS['version'];
      $sBuild .= '" />';
      $sBuild .= "\n";
      echo $sBuild;
    }
  }
  
?>