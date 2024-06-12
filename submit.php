<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $state = $_POST['state'];
    $domain = $_POST['domain'];
    $contact = $_POST['contact'];
    $year = $_POST['year'];

    $data = [
        'id' => uniqid(),
        'name' => $name,
        'email' => $email,
        'state' => $state,
        'domain' => $domain,
        'contact' => $contact,
        'year' => $year,
        'status' => 'Not Done'
    ];

    $file = 'students.json';
    if (file_exists($file)) {
        $current_data = file_get_contents($file);
        $array_data = json_decode($current_data, true);
        $array_data[] = $data;
        $final_data = json_encode($array_data, JSON_PRETTY_PRINT);
    } else {
        $array_data = array();
        $array_data[] = $data;
        $final_data = json_encode($array_data, JSON_PRETTY_PRINT);
    }

    if (file_put_contents($file, $final_data)) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false]);
    }
}
?>
