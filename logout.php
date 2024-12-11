<?php 
  session_start();

  function pathTo($destination) {
    echo "<script>window.location.href = '/rd-folder2/$destination.php'</script>";
  }

  //validation
  if ($_SESSION['status'] == 'invalid' || empty($_SESSION['status'])) {
    pathTo('login');
  }
  elseif($_SESSION['status'] == 'balik_registration'){
    pathTo('registration');
  }
  elseif($_SESSION['status'] == 'admin_valid'){
    pathTo('admin_db');
  }
  elseif($_SESSION['status'] == 'student_valid'){
    pathTo('student_db');
  }
  elseif($_SESSION['status'] == 'teacher_valid'){
    pathTo('teacher_db');
  }
  elseif($_SESSION['status'] == 'valid'){
    pathTo('login');
  }

  if(isset($_POST['logout'])){
    /* Set status to invalid */
    $_SESSION['status'] = 'invalid';
    pathTo('login');
  }
  
?>