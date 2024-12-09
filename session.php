<?php 
  session_start();

  function pathTo($destination) {
    echo "<script>window.location.href = '/rd-folder2/$destination.php'</script>";
  }
  
  if ($_SESSION['status'] == 'invalid' || empty($_SESSION['status'])) {
    /* Set status to invalid */
    $_SESSION['status'] = 'invalid';

    /* Unset user data 
    unset($_SESSION['username']);*/

    // Redirect to login page 
    pathTo('login');
  }
  elseif($_SESSION['status'] == 'balik_registration'){
    pathTo('registration');
  }
  elseif($_SESSION['status'] == 'admin_valid'){
    //pathTo('admin_db');
    $_SESSION['status']='valid';
  }

 
?>