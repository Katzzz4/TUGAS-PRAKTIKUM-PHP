<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Baby Registry</title>
  <link rel="stylesheet" href="../../assets/css/style.css">
  <style>
    .form-container {
      max-width: 800px;
      margin: 40px auto;
      background: #fff;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
    }
    .form-title {
      font-size: 24px;
      font-weight: bold;
      margin-bottom: 20px;
      display: flex;
      align-items: center;
      gap: 10px;
    }
    .form-title .emoji {
      font-size: 30px;
    }
    .form-group {
      margin-bottom: 15px;
    }
    .form-group label {
      display: block;
      font-weight: bold;
      margin-bottom: 5px;
    }
    .form-group input,
    .form-group select {
      width: 100%;
      padding: 10px;
      border: 1px solid #ccc;
      border-radius: 5px;
    }
    .form-section {
      margin-top: 30px;
    }
    .checkbox-label {
      display: flex;
      align-items: center;
      margin-bottom: 8px;
    }
    .checkbox-label input {
      margin-right: 10px;
    }
    .form-footer {
      margin-top: 30px;
      text-align: center;
    }
    .form-footer button {
      padding: 12px 20px;
      background-color: #f0c14b;
      border: 1px solid #a88734;
      border-radius: 5px;
      cursor: pointer;
      font-weight: bold;
    }

    /* Registry Menu Cards */
    .registry-heading {
      max-width: 1200px;
      margin: 30px auto 10px;
      padding: 0 40px;
      font-size: 26px;
      font-weight: bold;
    }
    .registry-menu {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
      gap: 20px;
      padding: 20px 40px 40px;
      background: #f9f9f9;
      max-width: 1200px;
      margin: auto;
    }
    a.registry-card {
      display: block;
      color: inherit;
      text-decoration: none;
    }
    .registry-card {
      background: white;
      border: 1px solid #ddd;
      border-radius: 10px;
      padding: 20px;
      text-align: center;
      transition: all 0.3s ease;
      box-shadow: 0 2px 5px rgba(0,0,0,0.05);
      cursor: pointer;
    }
    .registry-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 8px 16px rgba(0,0,0,0.15);
    }
    .registry-icon {
      font-size: 40px;
      margin-bottom: 10px;
    }
    .registry-title {
      font-weight: bold;
      font-size: 16px;
      margin-bottom: 5px;
    }
    .registry-desc {
      font-size: 14px;
      color: #555;
    }
  </style>
</head>
<body>
  <?php include 'navbar.php'; ?>

  <!-- Registry Menu Heading -->
  <div class="registry-heading">Create a registry or gift list</div>

  <!-- Registry Menu Page -->
  <div class="registry-menu">
    <a href="registry-form-baby.php" class="registry-card">
      <div class="registry-icon">👶</div>
      <div class="registry-title">Baby</div>
      <div class="registry-desc">Essentials to support your growing family</div>
    </a>
    <a href="#" class="registry-card">
      <div class="registry-icon">💍</div>
      <div class="registry-title">Wedding</div>
      <div class="registry-desc">Everything you need for a life together</div>
    </a>
    <a href="#" class="registry-card">
      <div class="registry-icon">🎂</div>
      <div class="registry-title">Birthday</div>
      <div class="registry-desc">Celebrate another year around the sun</div>
    </a>
    <a href="#" class="registry-card">
      <div class="registry-icon">🎄</div>
      <div class="registry-title">Holiday</div>
      <div class="registry-desc">Share gift ideas with family and friends</div>
    </a>
    <a href="#" class="registry-card">
      <div class="registry-icon">🏠</div>
      <div class="registry-title">Housewarming</div>
      <div class="registry-desc">Furnish your new home</div>
    </a>
    <a href="#" class="registry-card">
      <div class="registry-icon">🎓</div>
      <div class="registry-title">Graduation</div>
      <div class="registry-desc">Prepare for your next chapter</div>
    </a>
    <a href="#" class="registry-card">
      <div class="registry-icon">🐶</div>
      <div class="registry-title">Pet</div>
      <div class="registry-desc">For your pet's special day</div>
    </a>
    <a href="#" class="registry-card">
      <div class="registry-icon">🎁</div>
      <div class="registry-title">Other occasions</div>
      <div class="registry-desc">Customize your Gift List for any occasion</div>
    </a>
  </div>

  <?php include 'footer.php'; ?>

</body>
</html>
