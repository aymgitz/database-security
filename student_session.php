<?php 
  session_start();

  function pathTo($destination) {
    echo "<script>window.location.href = '/rd-folder2/$destination.php'</script>";
  }

  if ($_SESSION['status'] == 'invalid' || empty($_SESSION['status'])) {
    pathTo('login');
  }
  elseif($_SESSION['status'] == 'balik_registration'){
    pathTo('registration');
  }
  elseif($_SESSION['status'] == 'admin_valid'){
    pathTo('admin_db');
  }
  elseif($_SESSION['status'] == 'teacher_valid'){
    pathTo('teacher_db');
  }
  elseif($_SESSION['status'] == 'valid'){
    pathTo('login');
  }
  
    
 
?>