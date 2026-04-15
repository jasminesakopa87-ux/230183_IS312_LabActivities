<?php
/*
 * Author: [Jasmine SAKOPA]
 * Date:   18th March 2026
 * Unit:   IS312 Web Application Development
 * File:   student-listing.php
 *         Connects to the FRU10 database, retrieves all
 *         records from the Student table, and displays
 *         them in a formatted HTML table.
 */

/* -------------------------------------------------------
 * Database connection settings
 * ------------------------------------------------------- */
$host     = "localhost";
$user     = "root";
$password = "";
$database = "FRU10";

/* Connect to MySQL */
$conn = new mysqli($host, $user, $password, $database);

/* Check connection */
if ($conn->connect_error) {
    $dbError = "Database connection failed: " . $conn->connect_error;
} else {
    /* Query to retrieve all students */
    $sql    = "SELECT StudentNo, Firstname, Lastname, Gender, ContactNo, ProgramCode
               FROM Student
               ORDER BY StudentNo ASC";
    $result = $conn->query($sql);

    if (!$result) {
        $dbError = "Query failed: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FRU10 | Student Listing</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --ink:   #1a1a2e; --paper: #f5f0e8; --gold:  #c9973a;
            --muted: #7a7060; --line:  #d8d0c0; --accent:#2e5d9e;
            --error: #c0392b;
        }
        body { font-family: 'DM Sans', sans-serif; background: var(--paper);
               color: var(--ink); min-height: 100vh; display: flex; flex-direction: column; }

        /* Header */
        header { border-bottom: 1px solid var(--line); padding: 22px 48px;
                 display: flex; align-items: baseline; gap: 16px; }
        header .logo { font-family: 'DM Serif Display', serif; font-size: 1.6rem;
                       color: var(--ink); text-decoration: none; }
        header .logo span { color: var(--gold); }
        header nav { margin-left: auto; }
        header nav a { font-size: 0.82rem; color: var(--muted); text-decoration: none;
                       font-weight: 300; }
        header nav a:hover { color: var(--ink); }

        /* Page content */
        main { flex: 1; padding: 48px; }
        .page-eyebrow { font-size: 0.7rem; font-weight: 500; letter-spacing: 0.18em;
                        text-transform: uppercase; color: var(--gold); margin-bottom: 8px; }
        h1 { font-family: 'DM Serif Display', serif; font-size: 2rem; font-weight: 400;
             margin-bottom: 6px; }
        .page-desc { font-size: 0.85rem; color: var(--muted); font-weight: 300;
                     margin-bottom: 36px; }

        /* Table wrapper */
        .table-wrap { background: #fff; border: 1px solid var(--line); border-radius: 4px;
                      overflow-x: auto; }
        table { width: 100%; border-collapse: collapse; font-size: 0.87rem; }

        /* Table head */
        thead tr { border-bottom: 2px solid var(--line); }
        thead th { padding: 14px 20px; text-align: left; font-size: 0.72rem; font-weight: 500;
                   letter-spacing: 0.1em; text-transform: uppercase; color: var(--muted);
                   white-space: nowrap; }

        /* Table body rows */
        tbody tr { border-bottom: 1px solid var(--line); transition: background 0.1s; }
        tbody tr:last-child { border-bottom: none; }
        tbody tr:hover { background: var(--paper); }
        tbody td { padding: 13px 20px; color: var(--ink); vertical-align: middle; }

        /* Gender badge */
        .badge { display: inline-block; padding: 2px 10px; border-radius: 20px;
                 font-size: 0.72rem; font-weight: 500; letter-spacing: 0.05em; }
        .badge-m { background: #e8f0fb; color: var(--accent); }
        .badge-f { background: #fce8f0; color: #9e2e6d; }

        /* Program code pill */
        .prog { display: inline-block; padding: 2px 10px; border-radius: 3px;
                background: var(--paper); border: 1px solid var(--line);
                font-size: 0.78rem; font-weight: 500; color: var(--ink); }

        /* Empty / error state */
        .state-row td { text-align: center; padding: 48px 20px; color: var(--muted);
                        font-size: 0.88rem; font-weight: 300; }

        /* Summary bar */
        .summary { margin-top: 16px; font-size: 0.78rem; color: var(--muted);
                   font-weight: 300; }

        /* Error box */
        .db-error { background: #fff5f5; border: 1px solid #f5c6c6; border-radius: 4px;
                    padding: 20px 24px; color: var(--error); font-size: 0.88rem;
                    margin-bottom: 24px; }

        footer { border-top: 1px solid var(--line); padding: 16px 48px;
                 font-size: 0.75rem; color: var(--muted); font-weight: 300; }
    </style>
</head>
<body>

<header>
    <a href="index.html" class="logo">FRU<span>10</span></a>
    <nav><a href="index.html">&larr; Back to home</a></nav>
</header>

<main>
    <div class="page-eyebrow">Records</div>
    <h1>Student Listing</h1>
    <p class="page-desc">All students currently enrolled in the FRU10 database.</p>

    <?php if (isset($dbError)): ?>
        <!-- Display database error if connection or query failed -->
        <div class="db-error">
            <strong>Database Error:</strong> <?php echo htmlspecialchars($dbError); ?>
        </div>
    <?php endif; ?>

    <div class="table-wrap">
        <table>
            <thead>
                <tr>
                    <th>Student No</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Gender</th>
                    <th>Contact No</th>
                    <th>Program Code</th>
                </tr>
            </thead>
            <tbody>
                <?php
                /* Check that the query ran and returned rows */
                if (!isset($dbError) && $result && $result->num_rows > 0):
                    /* Loop through each student record */
                    while ($row = $result->fetch_assoc()):
                        /* Choose badge style based on gender */
                        $genderClass = ($row["Gender"] === "Male") ? "badge-m" : "badge-f";
                ?>
                <tr>
                    <td><?php echo htmlspecialchars($row["StudentNo"]); ?></td>
                    <td><?php echo htmlspecialchars($row["Firstname"]);  ?></td>
                    <td><?php echo htmlspecialchars($row["Lastname"]);   ?></td>
                    <td>
                        <span class="badge <?php echo $genderClass; ?>">
                            <?php echo htmlspecialchars($row["Gender"]); ?>
                        </span>
                    </td>
                    <td><?php echo htmlspecialchars($row["ContactNo"]);    ?></td>
                    <td>
                        <span class="prog">
                            <?php echo htmlspecialchars($row["ProgramCode"]); ?>
                        </span>
                    </td>
                </tr>
                <?php
                    endwhile;
                else:
                ?>
                <!-- Shown when there are no student records -->
                <tr class="state-row">
                    <td colspan="6">No student records found in the database.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php
    /* Display total count below the table */
    if (!isset($dbError) && $result && $result->num_rows > 0):
        echo '<p class="summary">Showing ' . $result->num_rows . ' student(s).</p>';
    endif;
    ?>

</main>

<footer>IS312 Web Application Development &mdash; Lab 2 &mdash; FRU10</footer>

<?php
/* Close the database connection */
if (isset($conn)) {
    $conn->close();
}
?>
</body>
</html>
