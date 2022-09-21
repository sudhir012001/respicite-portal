<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link rel="stylesheet" href="<?php echo base_url().'assets/css/bootstrap.css'; ?>">
    <link rel="stylesheet" href="<?php echo base_url().'assets/css/style.css'; ?>">
</head>
<body>
<header>
  <nav class="navbar navbar-expand-md navbar-dark fixed-top bg-dark">
    <div class="container">
     
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarCollapse" aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="col-md-2 text-right text-white">Welcome, <?php echo $user['fullname']; ?> <a href="<?php echo base_url().'UserController/logout'; ?>" class="nav-item text-white">Logout</a></div>
    </div>
  </nav>
 
</header>
</body>
</html>