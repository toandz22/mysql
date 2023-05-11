<?php
require_once ('style.html');
require_once ('connection.php');
$sql ="SELECT DISTINCT customer.first_name, customer.last_name
FROM customer
JOIN rental ON customer.customer_id = rental.customer_id
JOIN inventory ON rental.inventory_id = inventory.inventory_id
JOIN film ON inventory.film_id = film.film_id
JOIN film_category ON film.film_id = film_category.film_id
JOIN category ON film_category.category_id = category.category_id
WHERE NOT EXISTS (
  SELECT *
  FROM category
WHERE NOT EXISTS (
    SELECT *
    FROM film_category
    JOIN inventory ON film_category.film_id = inventory.film_id
    JOIN rental ON inventory.inventory_id = rental.inventory_id
    WHERE rental.customer_id = customer.customer_id
    AND category.category_id = film_category.category_id
  ) )
  ";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
  echo "<button onclick=\"window.location.href='http://localhost/mysql/form.html'\"> Back </button>";
  echo "<br> </br>";
  echo "<b> 2.9: Viết truy vấn SQL để trả về tên của tất cả các khách hàng đã thuê phim từ danh mục mà họ chưa từng thuê trước đó : </b>";
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