<?php
require_once ('style.html');
require_once ('connection.php');
$sql ="SELECT category.name, DATE_FORMAT(SEC_TO_TIME(AVG(TIME_TO_SEC(rental.return_date - rental.rental_date))), '%H:%i:%s') AS avg_rental_duration
FROM film
JOIN film_category ON film.film_id = film_category.film_id
JOIN category ON film_category.category_id = category.category_id
JOIN inventory ON film.film_id = inventory.film_id
JOIN rental ON inventory.inventory_id = rental.inventory_id
WHERE rental.return_date IS NOT NULL
GROUP BY category.name;";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
  echo "<button onclick=\"window.location.href='http://localhost/mysql/form.html'\"> Back </button>";
  echo "<b>1.4: Viết truy vấn SQL để trả về thời lượng thuê trung bình cho từng danh mục phim trong cơ sở dữ liệu : </b>";
  echo "<table><tr><th>name</th><th>avg_rental_duration</th></tr>";
    
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr><th>".$row["name"]."</th>". "<th>".$row["avg_rental_duration"]."</th>"."</tr>";
  }
  } else {
    echo "0 results";
  }
  
  $conn->close();
?>