INSERT INTO users (user_id) VALUES (123456);
INSERT INTO users (user_id) VALUES (234567);
INSERT INTO users (user_id) VALUES (345678);
INSERT INTO users (user_id) VALUES (567890);
INSERT INTO users (user_id) VALUES (345123);
INSERT INTO users (user_id) VALUES (987333);
INSERT INTO users (user_id) VALUES (987123);

INSERT INTO downloads (download_id, user_id, ts, rev, source_app, server) VALUES 
(2277138, 123456, '2023-03-25 07:26:41', 0, 'app1', 'user@host3'),
(9400696, 234567, '2022-01-31 09:50:04', 0, 'app1', 'user@host1'),
(34343955, 345678, '2023-11-16 13:43:18', 1, 'app2', 'user@host2'),
(2571099, 123456, '2022-06-19 02:03:19', 0, 'app4', 'user@host1'),
(43726887, 567890, '2024-09-18 12:12:56', 1, 'app1', 'user@host1'),
(282392405, 345123, '2024-09-13 10:07:32', 0, 'app2', 'user@host1'),
(282099767, 987333, '2024-04-19 07:20:16', 0, 'app3', 'user@host2'),
(6003932, 123456, '2024-08-26 01:02:20', 1, 'app3', 'user@host1'),
(6230041, 123456, '2024-02-28 20:38:00', 0, 'app1', 'user@host1'),
(74415349, 987123, '2023-01-15 23:02:09', 0, 'app1', 'user@host3');