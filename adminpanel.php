<?php
$file = 'students.json';
$students = array();
if (file_exists($file)) {
    $students = json_decode(file_get_contents($file), true);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['id']) && isset($_POST['action'])) {
        $id = $_POST['id'];
        $action = $_POST['action'];

        if ($action === 'delete') {
            $students = array_filter($students, function($student) use ($id) {
                return $student['id'] !== $id;
            });
        } else {
            foreach ($students as &$student) {
                if ($student['id'] === $id) {
                    $student['status'] = $action === 'done' ? 'Done' : 'Not Done';
                    break;
                }
            }
        }

        file_put_contents($file, json_encode($students, JSON_PRETTY_PRINT));
        header('Location: adminpanel.php');
        exit;
    }

    if (isset($_POST['clear']) && $_POST['clear'] === 'clear_all') {
        // Clear all data
        file_put_contents($file, json_encode([]));
        header('Location: adminpanel.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }
        .container {
            width: 80%;
            margin: auto;
            background: #fff;
            padding: 20px;
            box-shadow: 0px 0px 10px 0px #000;
            margin-top: 50px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        table, th, td {
            border: 1px solid #ddd;
        }
        th, td {
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .status-btn, .delete-btn, .clear-btn {
            padding: 5px 10px;
            border: none;
            cursor: pointer;
            margin-right: 5px;
        }
        .done-btn {
            background-color: #4CAF50;
            color: white;
        }
        .not-done-btn {
            background-color: #f44336;
            color: white;
        }
        .delete-btn {
            background-color: #555;
            color: white;
        }
        .clear-btn {
            background-color: #FF6347;
            color: white;
            margin-top: 20px;
            display: block;
            width: 100%;
            text-align: center;
        }
        a.mail-link {
            color: #007bff;
            text-decoration: none;
        }
        a.mail-link:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Admin Panel</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Email</th>
                <th>State</th>
                <th>Domain</th>
                <th>Contact Number</th>
                <th>Year</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($students as $student): ?>
            <tr>
                <td><?php echo htmlspecialchars($student['id']); ?></td>
                <td><?php echo htmlspecialchars($student['name']); ?></td>
                <td><a class="mail-link" href="mailto:<?php echo htmlspecialchars($student['email']); ?>"><?php echo htmlspecialchars($student['email']); ?></a></td>
                <td><?php echo htmlspecialchars($student['state']); ?></td>
                <td><?php echo htmlspecialchars($student['domain']); ?></td>
                <td><?php echo htmlspecialchars($student['contact']); ?></td>
                <td><?php echo htmlspecialchars($student['year']); ?></td>
                <td>
                    <button class="status-btn <?php echo $student['status'] === 'Done' ? 'done-btn' : 'not-done-btn'; ?>">
                        <?php echo htmlspecialchars($student['status']); ?>
                    </button>
                </td>
                <td>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars($student['id']); ?>">
                        <button type="submit" name="action" value="not_done" class="status-btn not-done-btn">Not Done</button>
                        <button type="submit" name="action" value="done" class="status-btn done-btn">Done</button>
                        <button type="submit" name="action" value="delete" class="delete-btn">Delete</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
        <form method="POST">
            <button type="submit" name="clear" value="clear_all" class="clear-btn">Clear All Data</button>
        </form>
    </div>
</body>
</html>
