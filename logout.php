<?php 
  session_start();

  function pathTo($destination) {
    echo "<script>window.location.href = '/rd-folder2/$destination.php'</script>";
  }


  if(isset($_POST['logout'])){
    /* Set status to invalid */
    $_SESSION['status'] = 'invalid';
    pathTo('login');
  }
  
?>