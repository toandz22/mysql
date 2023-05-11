<?php
require_once ('style.html');
require_once ('connection.php');

$sql ="SELECT distinct concat( a.first_name,' ',a.last_name )as full_name
from actor a 
join film_actor fa on fa.actor_id = a.actor_id
join film f on f.film_id = fa.film_id
where f.rating = 'R 'and f.length > 60
AND NOT EXISTS (
    SELECT null
    FROM film_actor fa2 
    JOIN film f2 ON f2.film_id = fa2.film_id 
    WHERE fa2.actor_id = a.actor_id 
    AND f2.rating = 'G'                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                    
) ;";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo "<button onclick=\"window.location.href='http://localhost/mysql/form.html'\"> Back </button>";
    echo "<br> </br>";
    echo "<b> 3.2: Viết truy vấn SQL để trả về tên của tất cả các diễn viên đã xuất hiện trong một bộ phim có xếp hạng 'R' và thời lượng hơn 2 giờ, nhưng chưa bao giờ xuất hiện trong một bộ phim có xếp hạng 'G': </b>";
    echo "<table><tr><th>full_name</th></tr>";
    
    // output data of each row
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>".$row["full_name"]."</td>"."</tr>";
    }
} else {
    echo "<tr><td colspan='2'>0 results</td></tr>";
}
echo "</table>";

$conn->close();
?>
