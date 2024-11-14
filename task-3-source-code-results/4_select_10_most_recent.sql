SELECT 
    u.user_id,
    COUNT(*) AS activity_count
FROM 
    users u
JOIN 
    downloads d ON u.user_id = d.user_id
WHERE 
    d.ts >= DATE_SUB(NOW(), INTERVAL 180 DAY)
GROUP BY 
    u.user_id
ORDER BY 
    activity_count DESC
LIMIT 10;