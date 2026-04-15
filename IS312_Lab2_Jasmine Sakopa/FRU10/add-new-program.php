<?php
/*
 * Author: [Jasmine SAKOPA]
 * Date:   18th March 2026
 * Unit:   IS312 Web Application Development
 * File:   add-new-program.php
 *         Receives POST data from new-program.html,
 *         validates it server-side, and inserts a new
 *         record into the Program table in the FRU10 database.
 */

/* -------------------------------------------------------
 * Database connection settings
 * Update host/username/password if your XAMPP differs.
 * ------------------------------------------------------- */
$host     = "localhost";
$user     = "root";
$password = "";          /* XAMPP default has no password */
$database = "FRU10";

/* -------------------------------------------------------
 * Only process the form when the request is POST
 * ------------------------------------------------------- */
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    /* Retrieve and sanitise each field from the form */
    $programCode = trim($_POST["programCode"] ?? "");
    $programName = trim($_POST["programName"] ?? "");
    $duration    = trim($_POST["duration"]    ?? "");
    $faculty     = trim($_POST["faculty"]     ?? "");

    /* Server-side validation: ensure no field is empty */
    if ($programCode === "" || $programName === "" ||
        $duration    === "" || $faculty     === "") {

        $errorMsg = "All fields are required. Please go back and fill in every field.";

    } else {

        /* Connect to the MySQL database */
        $conn = new mysqli($host, $user, $password, $database);

        /* Check connection */
        if ($conn->connect_error) {
            $errorMsg = "Database connection failed: " . $conn->connect_error;
        } else {

            /*
             * Use a prepared statement to safely insert the data.
             * This prevents SQL injection attacks.
             */
            $stmt = $conn->prepare(
                "INSERT INTO Program (ProgramCode, ProgramName, Duration, Faculty)
                 VALUES (?, ?, ?, ?)"
            );

            /* Bind parameters: s = string, i = integer */
            $stmt->bind_param("ssis", $programCode, $programName, $duration, $faculty);

            /* Execute and check result */
            if ($stmt->execute()) {
                $successMsg = "Program <strong>" . htmlspecialchars($programName) .
                              "</strong> (" . htmlspecialchars($programCode) .
                              ") was added successfully.";
            } else {
                $errorMsg = "Failed to insert record: " . $stmt->error;
            }

            $stmt->close();
            $conn->close();
        }
    }

} else {
    /* Page was accessed directly without a form submission */
    $errorMsg = "No form data received. Please use the form to submit a program.";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FRU10 | Program Submission Result</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Display&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --ink:   #1a1a2e; --paper: #f5f0e8; --gold:  #c9973a;
            --muted: #7a7060; --line:  #d8d0c0; --ok:    #27ae60;
            --error: #c0392b; --accent:#2e5d9e;
        }
        body { font-family: 'DM Sans', sans-serif; background: var(--paper);
               color: var(--ink); min-height: 100vh; display: flex; flex-direction: column; }
        header { border-bottom: 1px solid var(--line); padding: 22px 48px;
                 display: flex; align-items: baseline; gap: 16px; }
        header .logo { font-family: 'DM Serif Display', serif; font-size: 1.6rem;
                       color: var(--ink); text-decoration: none; }
        header .logo span { color: var(--gold); }
        main { flex: 1; display: flex; align-items: center; justify-content: center; padding: 56px 24px; }
        .result-card { background: #fff; border: 1px solid var(--line); border-radius: 4px;
                       max-width: 480px; width: 100%; padding: 48px; text-align: center; }
        .icon { font-size: 2.5rem; margin-bottom: 20px; }
        .result-card h1 { font-family: 'DM Serif Display', serif; font-size: 1.8rem;
                          font-weight: 400; margin-bottom: 14px; }
        .result-card p { font-size: 0.9rem; color: var(--muted); line-height: 1.7;
                         font-weight: 300; margin-bottom: 32px; }
        .result-card p strong { color: var(--ink); font-weight: 500; }
        .btn { display: inline-block; padding: 11px 28px; border-radius: 3px;
               font-family: 'DM Sans', sans-serif; font-size: 0.88rem; font-weight: 500;
               text-decoration: none; letter-spacing: 0.05em; transition: background 0.15s; }
        .btn-primary { background: var(--ink); color: var(--paper); margin-right: 12px; }
        .btn-primary:hover { background: var(--accent); }
        .btn-secondary { background: transparent; color: var(--muted);
                         border: 1px solid var(--line); }
        .btn-secondary:hover { border-color: var(--ink); color: var(--ink); }
        .success-bar { border-left: 3px solid var(--ok); }
        .error-bar   { border-left: 3px solid var(--error); }
        footer { border-top: 1px solid var(--line); padding: 16px 48px;
                 font-size: 0.75rem; color: var(--muted); font-weight: 300; }
    </style>
</head>
<body>

<header>
    <a href="index.html" class="logo">FRU<span>10</span></a>
</header>

<main>
    <div class="result-card <?php echo isset($successMsg) ? 'success-bar' : 'error-bar'; ?>">

        <?php if (isset($successMsg)): ?>
            <!-- Success state -->
            <div class="icon">&#10003;</div>
            <h1>Program Added</h1>
            <p><?php echo $successMsg; ?></p>
            <a href="new-program.html" class="btn btn-primary">Add Another</a>
            <a href="student-listing.php" class="btn btn-secondary">View Students</a>

        <?php else: ?>
            <!-- Error state -->
            <div class="icon">&#10007;</div>
            <h1>Something Went Wrong</h1>
            <p><?php echo htmlspecialchars($errorMsg); ?></p>
            <a href="new-program.html" class="btn btn-primary">Go Back</a>
            <a href="index.html" class="btn btn-secondary">Home</a>

        <?php endif; ?>

    </div>
</main>

<footer>IS312 Web Application Development &mdash; Lab 2 &mdash; FRU10</footer>

</body>
</html>
