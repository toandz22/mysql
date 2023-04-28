/* Viết một truy vấn SQL để trả lại 5 bộ phim được thuê nhiều nhất trong cơ sở dữ liệu, cùng với số lần họ đã được thuê. */

SELECT film.title, COUNT(rental.rental_id) AS rental_count
FROM film
JOIN inventory ON film.film_id = inventory.film_id
JOIN rental ON inventory.inventory_id = rental.inventory_id
GROUP BY film.film_id
ORDER BY rental_count DESC
LIMIT 5;
