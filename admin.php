<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header("Location: login.php");
    exit;
}

$conn = new mysqli("localhost", "root", "", "digital-menu");
if ($conn->connect_error) die("DB Error");

// Add Dish
if (isset($_POST['add'])) {
    $name = $_POST['name'];
    $desc = $_POST['desc'];
    $price = $_POST['price'];

    // Handle image upload
    $image = "";
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
        $filename = uniqid() . '.' . $ext;
        $targetPath = "uploads/" . $filename;
        move_uploaded_file($_FILES['image']['tmp_name'], $targetPath);
        $image = $targetPath;
    }

    $stmt = $conn->prepare("INSERT INTO dishes (name, description, image, price) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("sssd", $name, $desc, $image, $price);
    $stmt->execute();
    $stmt->close();

    header("Location: admin.php");
    exit;
}

// Delete Dish
if (isset($_POST['delete_id'])) {
    // Delete image file first
    $id = $_POST['delete_id'];
    $res = $conn->query("SELECT image FROM dishes WHERE id = $id");
    $img = $res->fetch_assoc()['image'];
    if ($img && file_exists($img)) unlink($img);

    $stmt = $conn->prepare("DELETE FROM dishes WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    header("Location: admin.php");
    exit;
}

$dishes = $conn->query("SELECT * FROM dishes ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Admin - Yummy</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background: #f8f9fa; font-family: 'Segoe UI', sans-serif; }
    .container { max-width: 900px; margin-top: 40px; }
    .card { margin-bottom: 20px; }
    img.thumb { max-height: 150px; object-fit: cover; margin-top: 10px; border-radius: 10px; }
  </style>
</head>
<body>

<div class="container">
  <h2 class="mb-4 text-center">🍽️ Yummy Admin Panel</h2>

  <!-- Add Dish Form -->
  <div class="card p-4 shadow-sm">
    <h4>Add New Dish</h4>
    <form method="post" enctype="multipart/form-data">
      <div class="mb-3">
        <input type="text" name="name" class="form-control" placeholder="Dish Name" required>
      </div>
      <div class="mb-3">
        <textarea name="desc" class="form-control" placeholder="Description" required></textarea>
      </div>
      <div class="mb-3">
        <input type="file" name="image" accept="image/*" class="form-control" required>
      </div>
      <div class="mb-3">
        <input type="number" name="price" step="0.01" class="form-control" placeholder="Price" required>
      </div>
      <input type="submit" name="add" class="btn btn-success" value="Add Dish">
    </form>
  </div>

  <!-- Existing Dishes -->
  <div class="mt-4">
    <h4>Current Dishes</h4>
    <div class="row">
      <?php while ($row = $dishes->fetch_assoc()): ?>
        <div class="col-md-6">
          <div class="card p-3 shadow-sm">
            <h5>
              <?= htmlspecialchars($row['name']) ?>
              <form method="post" style="display:inline;">
                <input type="hidden" name="delete_id" value="<?= $row['id'] ?>">
                <button type="submit" class="btn btn-sm btn-danger float-end">Delete</button>
              </form>
            </h5>
            <p class="text-muted"><?= htmlspecialchars($row['description']) ?></p>
            <p><strong>Price:</strong> $<?= number_format($row['price'], 2) ?></p>
            <?php if (!empty($row['image'])): ?>
              <img src="<?= $row['image'] ?>" class="img-fluid thumb" alt="Dish image">
            <?php endif; ?>
          </div>
        </div>
      <?php endwhile; ?>
    </div>
  </div>
</div>

</body>
</html>
