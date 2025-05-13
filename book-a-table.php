
  <?php
  // Enable full error reporting (for development only)
  error_reporting(E_ALL);
  ini_set('display_errors', 1);
  
  // Database connection settings
  $servername = "localhost";
  $username = "root";   // Replace with your DB username
  $password = "";       // Replace with your DB password
  $dbname = "digital-menu"; // Your database name
  
  // Create connection
  $conn = new mysqli($servername, $username, $password, $dbname);
  
  // Check connection
  if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
  }
  
  // Check if form is submitted
  if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Collect and sanitize input values
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $phone = htmlspecialchars(trim($_POST['phone']));
    $date = $_POST['date'];
    $time = $_POST['time'];
    $people = intval($_POST['people']);
    $message = htmlspecialchars(trim($_POST['message']));
  
    // Validate required fields
    if (empty($name) || empty($email) || empty($phone) || empty($date) || empty($time) || empty($people)) {
      echo "Please fill in all required fields.";
      exit;
    }
  
    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO reservations (name, email, phone, reservation_date, reservation_time, number_of_people, message) VALUES (?, ?, ?, ?, ?, ?, ?)");
  
    if ($stmt === false) {
      echo "Error in SQL prepare: " . $conn->error;
      exit;
    }
  
    // Bind the parameters
    $stmt->bind_param("sssssis", $name, $email, $phone, $date, $time, $people, $message);
  
    // Execute and check
    if ($stmt->execute()) {
      echo "Your reservation request has been submitted successfully. We will contact you soon!";
    } else {
      echo "Error executing SQL: " . $stmt->error;
    }
  
    // Close statement
    $stmt->close();
  } else {
    echo "Invalid request method.";
  }
  
  // Close connection
  $conn->close();
  ?>
  
