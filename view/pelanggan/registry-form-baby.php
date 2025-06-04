<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Baby Registry</title>
  <link rel="stylesheet" href="../style.css">
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
  </style>
</head>
<body>
  <?php include 'navbar.php'; ?>

  <div class="form-container">
    <div class="form-title">Welcome to your one-stop baby registry</div>
    <form action="#" method="post">
      <div class="form-group">
        <label for="first-name">Your name</label>
        <input type="text" id="first-name" name="first_name" placeholder="First name">
      </div>
      <div class="form-group">
        <input type="text" id="last-name" name="last_name" placeholder="Last name">
      </div>
      <div class="form-group">
        <label for="arrival-date">Expected arrival date</label>
        <input type="date" id="arrival-date" name="arrival_date">
      </div>

      <div class="form-section">
        <div class="form-group">
          <label for="address">Ship gifts to this address</label>
          <select id="address" name="address">
            <option value="">Select an address</option>
            <option value="home">Home Address</option>
            <option value="office">Office Address</option>
          </select>
        </div>
        <label class="checkbox-label">
          <input type="checkbox" name="share_address" checked>
          Share my address with sellers to allow gifts to be shipped to my address
        </label>
        <label class="checkbox-label">
          <input type="checkbox" name="allow_other_gifts">
          Allow gift givers to ship me additional gifts not on this registry
        </label>
      </div>

      <div class="form-section">
        <label class="checkbox-label">
          <input type="checkbox" name="amazon_gift_card" checked>
          Register for Amazon Gift Cards
        </label>
        <label class="checkbox-label">
          <input type="checkbox" name="diaper_fund" checked>
          Register for a diaper fund
        </label>
        <label class="checkbox-label">
          <input type="checkbox" name="group_gifting">
          Allow group gifting
        </label>
        <div class="form-group">
          <label for="min-group-gift">Set minimum price for group gifting</label>
          <input type="number" id="min-group-gift" name="min_group_price" placeholder="Rp 200">
        </div>
      </div>

      <div class="form-section">
        <label>Registry Privacy</label>
        <label class="checkbox-label">
          <input type="radio" name="privacy" value="public">
          Public
        </label>
        <label class="checkbox-label">
          <input type="radio" name="privacy" value="shareable" checked>
          Shareable
        </label>
        <label class="checkbox-label">
          <input type="radio" name="privacy" value="private">
          Private
        </label>

        <label>Email options</label>
        <label class="checkbox-label">
          <input type="checkbox" name="email_alerts">
          Receive alerts when gifts are purchased
        </label>
        <label class="checkbox-label">
          <input type="checkbox" name="newsletter">
          Receive our registry newsletter filled with maternity tips and deals
        </label>
      </div>

      <div class="form-footer">
        <button type="submit">Create registry</button>
      </div>
    </form>
  </div>
</body>
</html>
