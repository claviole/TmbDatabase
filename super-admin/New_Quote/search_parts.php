<?php
include '../../configurations/connection.php';

if(isset($_POST["query"])) {
  $output = '';
  $query = "SELECT `Part#` FROM Part WHERE `Part#` LIKE '%".$_POST["query"]."%'";
  $result = $database->query($query);
  $output = '<ul class="list-unstyled">';
  if(mysqli_num_rows($result) > 0) {
    while($row = mysqli_fetch_array($result)) {
      $output .= '<li>'.$row["Part#"].'</li>';
    }
  } else {
    $output .= '<li>Part Not Found</li>';
  }
  $output .= '</ul>';
  echo $output;
}
?>