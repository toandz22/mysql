<?php
require_once ('style.html');
require_once ('connection.php');
$sql ="SELECT film.title, COUNT(rental.rental_id) AS rental_count
FROM film
JOIN inventory ON film.film_id = inventory.film_id
JOIN rental ON inventory.inventory_id = rental.inventory_id
GROUP BY film.film_id
ORDER BY rental_count DESC
LIMIT 5; ";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    echo "<button onclick=\"window.location.href='http://localhost/mysql/form.html'\"> Back </button>";
    echo"<b>1.3: Viết truy vấn SQL để trả về 5 bộ phim được thuê nhiều nhất trong cơ sở dữ liệu, cùng với số lần chúng được thuê:  </b>";
    echo "<table><tr><th>title</th><th>rental_count</th></tr>";
    
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr><th>".$row["title"]."</th>". "<th>".$row["rental_count"]."</th>"."</tr>";
  }
  } else {
    echo "0 results";
  }
  
  $conn->close();
  
?>