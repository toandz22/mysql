/*Viết truy vấn SQL để trả về tên của tất cả các diễn viên đã xuất hiện trong hơn 20 bộ phim trong cơ sở dữ liệu.*/

SELECT actor.first_name, actor.last_name 
FROM actor
JOIN film_actor ON actor.actor_id = film_actor.actor_id
group by actor.first_name, actor.last_name 
having count(film_actor.actor_id) >20;
