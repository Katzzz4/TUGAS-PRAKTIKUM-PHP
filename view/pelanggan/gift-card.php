<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Akun Saya</title>
  <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
  
  <?php include 'navbar.php'; ?>

   <div class="container">
        <div class="gift-cards-section">
            <h1 class="gift-cards-title">Gift Cards & Account Balance</h1>
            
            <div class="gift-cards-grid">
                <!-- Redeem Amazon Gift Cards -->
                <div class="gift-card-item">
                    <div class="gift-card-icon">
                        <i class="fas fa-credit-card"></i>
                        <div class="card-text">XXXX</div>
                    </div>
                    <h3>Redeem Amazon Gift Cards</h3>
                    <p>Enter your gift card code to add balance to your account</p>
                    <button class="btn-gift-card">Redeem Now</button>
                </div>

                <!-- View Balance -->
                <div class="gift-card-item">
                    <div class="gift-card-icon">
                        <i class="fas fa-dollar-sign"></i>
                        <div class="balance-arrow">
                            <i class="fas fa-arrow-right"></i>
                        </div>
                    </div>
                    <h3>View your balance</h3>
                    <p>Current balance:</p>
                    <button class="btn-gift-card">View Details</button>
                </div>

                <!-- Auto-Reload -->
                <div class="gift-card-item">
                    <div class="gift-card-icon">
                        <i class="fas fa-sync-alt"></i>
                        <div class="dollar-symbol">$</div>
                    </div>
                    <h3>Set up Auto-Reload</h3>
                    <p>Automatically add balance when it gets low</p>
                    <button class="btn-gift-card" 
                           > Set Up Auto-Reload
                    </button>
                </div>
            </div>

            <!-- Additional Options -->
            <div class="gift-card-options">
                <h2>More Gift Card Options</h2>
                <div class="options-grid">
                    <div class="option-item">
                        <i class="fas fa-gift"></i>
                        <span>Buy Gift Cards</span>
                    </div>
                    <div class="option-item">
                        <i class="fas fa-history"></i>
                        <span>Transaction History</span>
                    </div>
                    <div class="option-item">
                        <i class="fas fa-question-circle"></i>
                        <span>Help & Support</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Redeem Modal -->
    <div id="redeemModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Redeem Gift Card</h2>
            <form id="redeemForm">
                <input type="text" id="giftCardCode" placeholder="Enter gift card code" required>
                <button type="submit" class="btn-redeem">Redeem</button>
            </form>
            <div id="redeemMessage"></div>
        </div>
    </div>

  <?php
  include 'footer.php'; 
  ?>

</body>
</html>
