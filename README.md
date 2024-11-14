# Solutions for a coding assignment

## Q1

Follow the instructions in non-standard http headers on
https://secret.macrobond.com/squeezywink
Send us the answers to the questions and the source code of the PHP script.

---

### Solution

1. Content of the secret, non-standard headers:

```
X-Zazzlepuff-Task-Line1:
	Download name_dataset.zip (3.3GB) from https://github.com/philipperemy/name-dataset?tab=readme-ov-file#full-dataset
X-Zazzlepuff-Task-Line2:
	Write PHP script that will count gender distribution in the database from Czechia
X-Zazzlepuff-Task-Line3:
	Peak memory usage should not exceed 2MB
X-Zazzlepuff-Task-Line4:
	What is the number of Females in this file?
```

2. PHP script that will count gender distribution in the database from Czechia

```php
<?php

$startMemory = memory_get_usage(true);

$filePath = 'dataset/data/CZ.csv';

$femaleCount = 0;
$maleCount = 0;

if (($handle = fopen($filePath, 'rb')) !== false) {
    while (!feof($handle)) {
        $line = fgets($handle);
        if ($line !== false) {
            $data = str_getcsv($line);
            if (isset($data[2])) {
                if ($data[2] === 'F') {
                    $femaleCount++;
                } elseif ($data[2] === 'M') {
                    $maleCount++;
                }
            }
            unset($data);
        }
    }
    fclose($handle);
}

$knownGenderCount = $femaleCount + $maleCount;

if ($knownGenderCount > 0) {
    $femalePercentage = ($femaleCount / $knownGenderCount) * 100;
    $malePercentage = ($maleCount / $knownGenderCount) * 100;
} else {
    $femalePercentage = $malePercentage = 0;
}

$peakMemory = memory_get_peak_usage(true);

echo "Females: $femaleCount (" . number_format($femalePercentage, 2) . "%)\n";
echo "Males: $maleCount (" . number_format($malePercentage, 2) . "%)\n";

echo "Peak Memory Usage: " . ($peakMemory) . " bytes \n";

```

- output

```
czech_gender.php
Females: 652699 (50.93%)
Males: 628907 (49.07%)
Peak Memory Usage: 2097152 bytes
```

3. Peak RAM usage do not exceeds 2MB as 2097152 bytes equals 2 megabytes.

4. The number of females in the file equals to: 652699

## Q2

In a Symfony application, how would you create a custom ultimate-console command?
Please provide a brief explanation and code examples.

---

### Solution

1. I would use the Symfony MakerBundle to generate the boilerpate:

```bash
php bin/console make:command
```

2. Then I specify it's name:

```bash
app:validate-environment
```

3. Lastly I alter pregenerated code:

```php
<?php
namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Dotenv\Dotenv;

#[AsCommand(
    name: 'app:validate-environment',
    description: 'Validate .env configurations and inform about the missing enviroment variable',
)]
class ValidateEnvironmentCommand extends Command
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Environment Validation');
        $envPath = __DIR__ . '/../../.env';
        if (!file_exists($envPath)) {
            $io->error('.env file not found.');
            return Command::FAILURE;
        }

        $dotenv = new Dotenv();
        $dotenv->usePutenv();
        $dotenv->loadEnv($envPath);

        $envVars = $_ENV;

        $requiredVars = [
            'DATABASE_URL' => 'The database URL.',
            'DATABASE_USERNAME' => 'The database username.',
            'DATABASE_PASSWORD' => 'The database password.',
            'TEST_ENV' => 'Test env',
            'APP_SECRET' => 'A secret key used for various security-related purposes.',
        ];

        $hasErrors = false;

        foreach ($requiredVars as $variable => $description) {
            if (empty($envVars[$variable] ?? null)) {
                $io->error(sprintf('Missing required environment variable: %s (%s)', $variable, $description));
                $hasErrors = true;
            }
        }

        if ($hasErrors) {
            $io->error('Environment variable validation failed successfully.');
            return Command::FAILURE;
        }

        $io->success('Environment validation passed successfully. Are required enviroment variables are set.');
        return Command::SUCCESS;
    }
}
```

2. Usage example:

- Define needed environmental variables in command's code:

```php
        $requiredVars = [
            'DATABASE_URL' => 'The database URL.',
            'DATABASE_USERNAME' => 'The database username.',
            'DATABASE_PASSWORD' => 'The database password.',
            'TEST_ENV' => 'Test env',
            'APP_SECRET' => 'A secret key used for various security-related purposes.',
        ];

```

- To run this command execute this code:

```bash
php bin/console app:validate-environment
```

3. Decription:

   This console command verifies whether all required environment variables are set. It is particularly beneficial for large projects with a significant number of environment variables. This command should be integrated into the CI/CD pipeline to prevent runtime errors caused by misconfigured environments.

## Q3

You have following CSV file:

```csv
"user_id","download_id","ts","rev","source_app","server"
123456,2277138,"2023-03-25 07:26:41",0,"app1","user@host3"
234567,9400696,"2022-01-31 09:50:04",0,"app1","user@host1"
345678,34343955,"2023-11-16 13:43:18",1,"app2","user@host2"
123456,2571099,"2022-06-19 02:03:19",0,"app4","user@host1"
567890,43726887,"2024-09-18 12:12:56",1,"app1","user@host1"
345123,282392405,"2024-09-13 10:07:32",0,"app2","user@host1"
987333,282099767,"2024-04-19 07:20:16",0,"app3","user@host2"
123456,6003932,"2024-08-26 01:02:20",1,"app3","user@host1"
123456,6230041,"2024-02-28 20:38:00",0,"app1","user@host1"
987123,74415349,"2023-01-15 23:02:09",0,"app1","user@host3"
```

1. Please propose MySQL table schema for such dataset as a ‘CREATE TABLE ...’
   statement.
2. Write SELECT query which returns up to 10 most active users in the last 180 days.

3. Write SELECT query which returns only the most recent activity for each user_id.

---

### Solution

1.  MySQL table schema:

- Create users table:
  ```sql
  CREATE TABLE users (
  user_id BIGINT NOT NULL PRIMARY KEY
  );
  ```
- Create downloads table:

```sql
CREATE TABLE downloads (
    download_id BIGINT NOT NULL,
    user_id BIGINT NOT NULL,
    ts DATETIME NOT NULL,
    rev TINYINT NOT NULL,
    source_app VARCHAR(255) NOT NULL,
    server VARCHAR(255) NOT NULL,
    PRIMARY KEY (download_id, user_id),
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

```

- You can populate it with a sample data:

```sql
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
```

2. SELECT query which returns up to the 10 most active users in the last 180 days.

- query

```sql
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
```

- output

```
+---------+----------------+
| user_id | activity_count |
+---------+----------------+
|  123456 |              1 |
|  567890 |              1 |
|  345123 |              1 |
+---------+----------------+
```

3. Write SELECT query which returns only the most recent activity for each user_id.

- query

```sql
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

```

- output

```
+-------------+---------+---------------------+-----+------------+------------+
| download_id | user_id | ts                  | rev | source_app | server     |
+-------------+---------+---------------------+-----+------------+------------+
|     6003932 |  123456 | 2024-08-26 01:02:20 |   1 | app3       | user@host1 |
|     9400696 |  234567 | 2022-01-31 09:50:04 |   0 | app1       | user@host1 |
|    34343955 |  345678 | 2023-11-16 13:43:18 |   1 | app2       | user@host2 |
|    43726887 |  567890 | 2024-09-18 12:12:56 |   1 | app1       | user@host1 |
|    74415349 |  987123 | 2023-01-15 23:02:09 |   0 | app1       | user@host3 |
|   282099767 |  987333 | 2024-04-19 07:20:16 |   0 | app3       | user@host2 |
|   282392405 |  345123 | 2024-09-13 10:07:32 |   0 | app2       | user@host1 |
+-------------+---------+---------------------+-----+------------+------------+
```
