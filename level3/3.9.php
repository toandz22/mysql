<?php
require_once('style.html');
require_once('connection.php');

$records_per_page = 15;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $records_per_page;

$sql = "SELECT DISTINCT customer.first_name, customer.last_name
        FROM customer 
        JOIN rental ON rental.customer_id = customer.customer_id
        JOIN inventory ON rental.inventory_id = inventory.inventory_id
        JOIN film ON inventory.film_id = film.film_id
        WHERE film.length <= 180
        AND customer.customer_id NOT IN (
            SELECT DISTINCT rental.customer_id
            FROM rental
            JOIN inventory ON rental.inventory_id = inventory.inventory_id
            JOIN film_category ON inventory.film_id = film_category.film_id
            WHERE film_category.category_id NOT IN (
                SELECT DISTINCT film_category.category_id
                FROM rental
                JOIN inventory ON rental.inventory_id = inventory.inventory_id
                JOIN film_category ON inventory.film_id = film_category.film_id
                WHERE rental.customer_id = customer.customer_id
            )
        )
        LIMIT $records_per_page OFFSET $offset";

$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

if ($result->num_rows > 0) {
    echo "<id ='btn'> <button onclick=\"window.location.href='http://localhost/mysql/form.html'\"><b> Back </b> </button>";
    echo "<br> </br>";
    echo "<b> 3.9: Viết truy vấn SQL để trả về tên của tất cả các khách hàng đã thuê phim từ danh mục mà họ chưa bao giờ thuê trước đây và cũng chưa bao giờ thuê phim dài hơn 3 giờ : </b>";
    echo "<table>
            <tr>
                <th>first_name</th>
                <th>last_name</th>
            </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
                <td>" . $row["first_name"] . "</td>
                <td>" . $row["last_name"] . "</td>
            </tr>";
    }

    echo "</table>";
} else {
    echo "No results found.";
}

// calculate total number of pages
$sql = "SELECT COUNT(*) AS total_records FROM customer";
$result = $conn->query($sql);

if (!$result) {
    die("Query failed: " . $conn->error);
}

$row = $result->fetch_assoc();
$total_records = $row['total_records'];
$total_pages = ceil($total_records / $records_per_page);

// display pagination links
echo "<div class='pagination'>";
echo "<button onclick=\"window.location.href='http://localhost/mysql/level3/3.9.php?page=1'\"><b> first page </b> </button>";
for ($i = max(1, $page - 15); $i <= min($page + 15, $total_pages); $i++) {
    
    if ($i == $page) {
        
        echo "<a class='active' href='#'>" . $i . "</a>";
    } else {
        echo "<a href='?page=" . $i . "'>" . $i . "</a>";
    }
}
echo "<button onclick=\"window.location.href='http://localhost/mysql/level3/3.9.php?page= $total_pages'\"><b> Last page </b> </button>";
echo "</div>";

// close database connection
$conn->close();

?>
