<?php
require_once('style.html');
require_once('connection.php');

$records_per_page = 10;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $records_per_page;

$sql = "SELECT CONCAT(A.first_name,' ',A.last_name) AS full_name1, CONCAT(B.first_name,' ',B.last_name) AS full_name2, COUNT(*) AS num_movies
FROM film_actor AS fa1
JOIN film_actor AS fa2 ON fa1.film_id = fa2.film_id  
JOIN actor AS A ON fa1.actor_id = A.actor_id
JOIN actor AS B ON fa2.actor_id = B.actor_id AND A.first_name != B.first_name AND A.last_name != B.last_name
GROUP BY full_name1, full_name2
ORDER BY num_movies DESC
LIMIT $records_per_page OFFSET $offset";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<div id='button'><button onclick=\"window.location.href='http://localhost/mysql/form.html'\"><b>Back</b></button></div>";
    echo "<br></br>";
    echo "<b> 3.6: Viết truy vấn SQL để trả về tên của tất cả các diễn viên đã xuất hiện trong ít nhất một bộ phim cùng với mọi diễn viên khác trong cơ sở dữ liệu, cùng với số lượng phim họ đã xuất hiện cùng nhau: </b>";
    echo "<table>
        <tr>
            <th>full_name1</th>
            <th>full_name2</th>
            <th>num_movies</th>
        </tr>";

    while ($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>" . $row["full_name1"] . "</td>
            <td>" . $row["full_name2"] . "</td>
            <td>" . $row["num_movies"] . "</td>
        </tr>";
    }

    echo "</table>";
} else {
    echo "No results found.";
}

// calculate total number of pages
$sql = "SELECT COUNT(*) AS total_records FROM (SELECT COUNT(*) FROM film_actor GROUP BY actor_id HAVING COUNT(*) > 1) AS sub";
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
s