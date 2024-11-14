SELECT 
    d.*
FROM 
    downloads d
JOIN 
    (SELECT 
         user_id, 
         MAX(ts) AS most_recent_activity
     FROM 
         downloads
     GROUP BY 
         user_id) sub
ON 
    d.user_id = sub.user_id AND d.ts = sub.most_recent_activity;
