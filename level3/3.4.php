<?php
require_once('style.html');
require_once('connection.php');

$records_per_page = 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $records_per_page;

$sql = "SELECT concat(c.first_name,'',c.last_name) as full_name, 
COUNT(*) AS total_rentals, 
SUM(f.rental_rate) AS total_fees
FROM customer c
JOIN rental r ON c.customer_id = r.customer_id
JOIN inventory i ON r.inventory_id = i.inventory_id
JOIN film f ON i.film_id = f.film_id
JOIN film_category fc ON f.film_id = fc.film_id
JOIN category ca ON fc.category_id = ca.category_id
GROUP BY full_name
HAVING total_rentals >0
LIMIT $records_per_page OFFSET $offset";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<id ='button'> <button onclick=\"window.location.href='http://localhost/mysql/form.html'\"><b> Back </b> </button>";
    echo "<br> </br>";
    echo "<b> 3.4: Viết một truy vấn SQL để trả về tên của tất cả các khách hàng đã thuê mọi bộ phim trong một danh mục, cùng với tổng số phim đã thuê và tổng phí thuê : </b>";
    echo "<table>
        <tr>
            <th>full_name</th>
            <th>total_rentals</th>
            <th>total_fees</th>
        </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>" . $row["full_name"] . "</td>
            <td>" . $row["total_rentals"] . "</td>
            <td>" . $row["total_fees"] . "</td>
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
