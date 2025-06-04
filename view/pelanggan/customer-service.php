<?php
require_once '../../config/Connection.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Customer Service - Amazon.com</title>
    <link rel="stylesheet"href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"/>
    <link rel="stylesheet" href="../../assets/css/style.css" />
</head>
<body>

<?php include 'navbar.php'; ?>

    <div class="customer-service-container">
        <!-- Header Section -->
        <div class="cs-header">
            <h1><i class="fa-solid fa-headset"></i> Customer Service</h1>
            <p>Kami siap membantu Anda 24/7. Temukan jawaban cepat atau hubungi tim support kami.</p>
        </div>

        <!-- Emergency Banner -->
        <div class="emergency-banner">
            <i class="fa-solid fa-exclamation-triangle"></i>
            <strong>Darurat?</strong> Hubungi hotline kami: <strong>0800-1234-5678</strong> (24 jam)
        </div>

        <!-- Quick Help Section -->
        <div class="quick-help-section">
            <h2 class="section-title">🚀 Bantuan Cepat</h2>
            <div class="quick-help-grid">
                <div class="help-card">
                    <div class="help-card-icon">
                        <i class="fa-solid fa-truck"></i>
                    </div>
                    <h3>Lacak Pesanan</h3>
                    <p>Cek status pengiriman dan estimasi waktu tiba pesanan Anda</p>
                    <a href="track-order.php" class="help-card-btn">Lacak Sekarang</a>
                </div>

                <div class="help-card">
                    <div class="help-card-icon">
                        <i class="fa-solid fa-undo"></i>
                    </div>
                    <h3>Return & Refund</h3>
                    <p>Kembalikan produk atau ajukan pengembalian dana dengan mudah</p>
                    <a href="returns-orders.php" class="help-card-btn">Proses Return</a>
                </div>

                <div class="help-card">
                    <div class="help-card-icon">
                        <i class="fa-solid fa-user"></i>
                    </div>
                    <h3>Akun Saya</h3>
                    <p>Kelola profil, alamat, metode pembayaran, dan preferensi akun</p>
                    <a href="account.php" class="help-card-btn">Buka Akun</a>
                </div>

                <div class="help-card">
                    <div class="help-card-icon">
                        <i class="fa-solid fa-credit-card"></i>
                    </div>
                    <h3>Pembayaran</h3>
                    <p>Bantuan terkait metode pembayaran, cicilan, dan masalah transaksi</p>
                    <a href="#payment-help" class="help-card-btn">Bantuan Bayar</a>
                </div>
            </div>
        </div>

        <!-- Contact Methods -->
        <h2 class="section-title">📞 Hubungi Kami</h2>
        <div class="contact-methods">
            <div class="contact-card">
                <div class="contact-icon">
                    <i class="fa-solid fa-phone"></i>
                </div>
                <h3>Telepon</h3>
                <div class="contact-info">
                    <strong>0800-1234-5678</strong><br>
                    Bebas pulsa dari seluruh Indonesia
                </div>
                <div class="contact-hours">
                    <i class="fa-solid fa-clock"></i> 24 jam setiap hari
                </div>
                <a href="tel:08001234567" class="help-card-btn">Hubungi Sekarang</a>
            </div>

            <div class="contact-card">
                <div class="contact-icon">
                    <i class="fa-brands fa-whatsapp"></i>
                </div>
                <h3>WhatsApp</h3>
                <div class="contact-info">
                    <strong>+62 853 1111 5555</strong><br>
                    Chat langsung dengan customer service
                </div>
                <div class="contact-hours">
                    <i class="fa-solid fa-clock"></i> 06:00 - 24:00 WIB
                </div>
                <a href="https://wa.me/6285311115555" class="help-card-btn" target="_blank">Chat WhatsApp</a>
            </div>

            <div class="contact-card">
                <div class="contact-icon">
                    <i class="fa-solid fa-envelope"></i>
                </div>
                <h3>Email</h3>
                <div class="contact-info">
                    <strong>customer@id.amazon.com</strong><br>
                    Kirim pertanyaan detail via email
                </div>
                <div class="contact-hours">
                    <i class="fa-solid fa-clock"></i> Respon dalam 2-4 jam
                </div>
                <a href="mailto:customer@id.amazon.com" class="help-card-btn">Kirim Email</a>
            </div>
        </div>

        <!-- FAQ Section -->
        <div class="faq-section">
            <h2 class="section-title">❓ Pertanyaan Umum (FAQ)</h2>
            
            <div class="faq-item">
                <button class="faq-question" onclick="toggleFAQ(this)">
                    Bagaimana cara melacak pesanan saya? 
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
                <div class="faq-answer">
                    <p>Anda dapat melacak pesanan melalui halaman "Your Orders" di akun Anda atau menggunakan nomor resi yang dikirim via email/SMS. Masukkan nomor resi di halaman tracking untuk melihat status terkini.</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question" onclick="toggleFAQ(this)">
                    Berapa lama proses refund? 
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
                <div class="faq-answer">
                    <p>Proses refund membutuhkan waktu 3-7 hari kerja setelah produk diterima di warehouse kami. Dana akan dikembalikan ke metode pembayaran yang sama dengan yang Anda gunakan saat pembelian.</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question" onclick="toggleFAQ(this)">
                    Apakah bisa mengubah alamat pengiriman? 
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
                <div class="faq-answer">
                    <p>Alamat pengiriman hanya bisa diubah sebelum pesanan diproses (status "Preparing for Shipment"). Setelah itu, Anda perlu menghubungi customer service untuk bantuan khusus.</p>
                </div>
            </div>

            <div class="faq-item">
                <button class="faq-question" onclick="toggleFAQ(this)">
                    Bagaimana cara menggunakan voucher/promo? 
                    <i class="fa-solid fa-chevron-down"></i>
                </button>
                <div class="faq-answer">
                    <p>Masukkan kode voucher di halaman checkout pada kolom "Promo Code" sebelum melakukan pembayaran. Pastikan voucher masih berlaku dan memenuhi syarat minimum pembelian.</p>
                </div>
            </div>
        </div>

        <!-- Contact Form -->
        <div class="contact-form-section">
            <h2 class="section-title">✍️ Kirim Pesan</h2>
            <form class="contact-form" action="#" method="POST">
                <div class="form-group">
                    <label for="subject">Subjek Pertanyaan *</label>
                    <select id="subject" name="subject" required>
                        <option value="">Pilih kategori...</option>
                        <option value="order">Pesanan & Pengiriman</option>
                        <option value="return">Return & Refund</option>
                        <option value="payment">Pembayaran</option>
                        <option value="account">Masalah Akun</option>
                        <option value="product">Informasi Produk</option>
                        <option value="other">Lainnya</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="name">Nama Lengkap *</label>
                    <input type="text" id="name" name="name" required>
                </div>

                <div class="form-group">
                    <label for="email">Email *</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="form-group">
                    <label for="phone">Nomor Telepon</label>
                    <input type="tel" id="phone" name="phone">
                </div>

                <div class="form-group">
                    <label for="order_number">Nomor Pesanan (jika ada)</label>
                    <input type="text" id="order_number" name="order_number" placeholder="contoh: 123-4567890-1234567">
                </div>

                <div class="form-group">
                    <label for="message">Pesan Detail *</label>
                    <textarea id="message" name="message" placeholder="Jelaskan masalah atau pertanyaan Anda secara detail..." required></textarea>
                </div>

                <button type="submit" class="submit-btn">
                    <i class="fa-solid fa-paper-plane"></i> Kirim Pesan
                </button>
            </form>
        </div>
    </div>

        <?php include 'footer.php'; ?>

    <script>
        function resizeSelect(select) {
            const helper = document.getElementById('widthHelper');
            helper.textContent = select.options[select.selectedIndex].text;
            const computed = getComputedStyle(select);
            helper.style.font = computed.font;
            select.style.width = helper.offsetWidth + 48 + 'px';
        }

        document.addEventListener("DOMContentLoaded", function() {
            const select = document.getElementById("categorySelect");
            resizeSelect(select);

            if (select.offsetWidth < 60) {
                select.style.width = '56px';
            }
        });

        function toggleFAQ(button) {
            const answer = button.nextElementSibling;
            const icon = button.querySelector('i');
            
            // Close all other FAQ items
            document.querySelectorAll('.faq-answer').forEach(item => {
                if (item !== answer) {
                    item.classList.remove('active');
                }
            });
            
            document.querySelectorAll('.faq-question i').forEach(item => {
                if (item !== icon) {
                    item.style.transform = 'rotate(0deg)';
                }
            });
            
            // Toggle current FAQ item
            answer.classList.toggle('active');
            
            if (answer.classList.contains('active')) {
                icon.style.transform = 'rotate(180deg)';
            } else {
                icon.style.transform = 'rotate(0deg)';
            }
        }

        // Form submission handler
        document.querySelector('.contact-form').addEventListener('submit', function(e) {
            e.preventDefault();
            
            // Get form data
            const formData = new FormData(this);
            
            // Show success message (you can implement actual form submission here)
            alert('Terima kasih! Pesan Anda telah dikirim. Tim customer service kami akan segera menghubungi Anda.');
            
            // Reset form
            this.reset();
        });
    </script>
</body>
</html>