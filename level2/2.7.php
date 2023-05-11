<?php
require_once ('style.html');
require_once ('connection.php');
$sql ="SELECT DISTINCT actor.first_name , actor.last_name 
FROM actor
INNER JOIN film_actor ON actor.actor_id = film_actor.actor_id
INNER JOIN film ON film.film_id = film_actor.film_id
WHERE film.rating = 'R'
AND actor.actor_id NOT IN (
    SELECT actor.actor_id
    FROM actor
    INNER JOIN film_actor ON actor.actor_id = film_actor.actor_id
    INNER JOIN film ON film.film_id = film_actor.film_id
    WHERE film.rating = 'G')";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
  echo "<button onclick=\"window.location.href='http://localhost/mysql/form.html'\"> Back </button>";
  echo "<br> </br>";
  echo "<br> 2.7: Viết một truy vấn SQL để trả về tên của tất cả các diễn viên đã xuất hiện trong ít nhất một bộ phim có xếp hạng 'R', nhưng chưa bao giờ xuất hiện trong một bộ phim có xếp hạng 'G': </b>";
  echo "<table><tr><th>first_name</th><th>last_name</th></tr>";
    
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr><th>".$row["first_name"]."</th>". "<th>".$row["last_name"]."</th>"."</tr>";
  }
  } else {
    echo "0 results";
  }
  
  $conn->close();
?>