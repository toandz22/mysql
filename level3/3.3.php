<?php
require_once('style.html');
require_once('connection.php');

$records_per_page = 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $records_per_page;

$sql = "SELECT CONCAT(first_name, ' ', last_name) AS full_name, 
COUNT(rental.rental_id) as total_rentals, 
SUM(film.rental_rate) as total_rental_fee
FROM customer
JOIN rental ON customer.customer_id = rental.customer_id
JOIN inventory ON rental.inventory_id = inventory.inventory_id
JOIN film ON inventory.film_id = film.film_id
GROUP BY full_name
HAVING total_rentals > 10
LIMIT $records_per_page OFFSET $offset";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<id ='button'> <button onclick=\"window.location.href='http://localhost/mysql/form.html'\"><b> Back </b> </button>";
    echo "<br> </br>";
    echo "<b> 3.3: Viết truy vấn SQL để trả về tên của tất cả khách hàng đã thuê hơn 10 bộ phim trong một giao dịch, cùng với số lượng phim họ đã thuê và tổng phí thuê : </b>";
    echo "<table>
        <tr>
            <th>Full Name</th>
            <th>Total Rentals</th>
            <th>Total Rental Fee</th>
        </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>" . $row["full_name"] . "</td>
            <td>" . $row["total_rentals"] . "</td>
            <td>" . $row["total_rental_fee"] . "</td>
        </tr>";
    }

    echo "</table>";
} else {
    echo "No results found.";
}

// calculate total number of pages
$sql = "SELECT COUNT(*) AS total_records FROM customer";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$total_records = $row['total_records'];
$total_pages = ceil($total_records / $records_per_page);

// display pagination links
echo "<div class='pagination'>";

for ($i = max(1, $page - 10); $i <= min($page + 10, $total_pages); $i++) {
    if ($i == $page) {
        echo "<a class='active' href='#'>" . $i . "</a>";
    } else {
        echo "<a href='?page=" . $i . "'>" . $i . "</a>";
    }
}
echo "</div>";

// close database connection
$conn->close();


?>
