<?php
require_once('style.html');
require_once('connection.php');
// Update rental_rate of films
$sql = "UPDATE film SET rental_duration = 1.5 WHERE rating = 'G' AND length < 60";
if (!$conn->query($sql)) {
    echo "Error updating rental rate: " . $conn->error;
} else {

    // Select films with updated rental duration
    $sql = "SELECT title, rental_duration  FROM film WHERE rating = 'G' AND length < 60";
    $result = $conn->query($sql);

    echo "<button onclick=\"window.location.href='http://localhost/mysql/form.html'\"> Back </button>";
    echo "<br><br><b>4.10: Viết truy vấn SQL để cập nhật giá thuê của tất cả các phim trong cơ sở dữ liệu có xếp hạng 'G' và thời lượng dưới 1 giờ, đặt giá mới là $1,50:</b><br>";

    if ($result === FALSE) {
        echo "Error executing SQL query: " . $conn->error;
    } else if ($result->num_rows > 0) {
        echo "<table>
        <tr>
        <th> title </th>
        <th> rental_duration </th>
        </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr><td>" . $row["title"] . "</td><td>" . $row["rental_duration"] . "</td></tr>";
        }
        echo "</table>";
    } else {
        echo "0 results";
    }

}

$conn->close();
?>
