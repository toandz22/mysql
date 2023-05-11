<?php
require_once('style.html');
require_once('connection.php');

$records_per_page = 15;
$page = isset($_GET['page']) ? intval($_GET['page']) : 1;
$offset = ($page - 1) * $records_per_page;

$sql = " SELECT distinct a.first_name, a.last_name
FROM actor a 
JOIN film_actor fa ON fa.actor_id = a.actor_id
JOIN film f ON f.film_id = fa.film_id
where f.rating ='PG-13' AND length >'120'
and a.actor_id IN (
SELECT distinct actor.actor_id
FROM actor 
JOIN film_actor  ON film_actor.actor_id = actor.actor_id
JOIN film  ON film.film_id = film_actor.film_id
where film.rating ='R' AND length <'90'
)
LIMIT $records_per_page OFFSET $offset";

$result = $conn->query($sql);
echo " <button onclick=\"window.location.href='http://localhost/mysql/form.html'\"><b> Back </b> </button>";
if ($result->num_rows > 0) {
    
    echo "<br> </br>";
    echo "<b> 3.10: Viết truy vấn SQL để trả về tên của tất cả các diễn viên đã xuất hiện trong một bộ phim có xếp hạng 'PG-13' và thời lượng hơn 2 giờ, đồng thời cũng đã xuất hiện trong một bộ phim có xếp hạng 'R' và thời lượng dưới 90 phút : </b>";
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
$sql = "SELECT COUNT(*) AS total_records FROM film";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$total_records = $row['total_records'];
$total_pages = ceil($total_records / $records_per_page);

// display pagination links
echo "<div class='pagination'>";
echo "<button onclick=\"window.location.href='http://localhost/mysql/level3/3.9.php?page=1'\"><b> first page </b> </button>";
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
