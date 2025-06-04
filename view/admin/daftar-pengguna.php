<?php
// File: admin/daftar-pengguna.php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: login.php");
    exit;
}
include '../../config/Connection.php';

// Tambahkan error handling
$query = "SELECT * FROM auth ORDER BY created_at DESC";
$pengguna = mysqli_query($conn, $query);

// CEK JIKA QUERY GAGAL
if (!$pengguna) {
    die("Error dalam query: " . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daftar Pengguna</title>
    <link rel="stylesheet" href="../../assets/css/admin-style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

</head>
<body>
    <?php 
    include 'sidebar.php'; 
    ?>

    <div class="content">
        <div class="header">
            <h1><i class="fas fa-users"></i> Daftar Pengguna Terdaftar</h1>
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Cari nama, email, atau ID pengguna...">
                <button type="button" onclick="searchUsers()"><i class="fas fa-search"></i></button>
            </div>
        </div>

        <!-- Info hasil pencarian -->
        <div id="searchInfo" class="search-results-info" style="display: none;">
            <i class="fas fa-info-circle"></i>
            <span id="resultCount">0</span> pengguna ditemukan
        </div>

        <div class="user-grid" id="userGrid">
            <?php while($user = mysqli_fetch_assoc($pengguna)): ?>
            <div class="user-card" data-name="<?= strtolower(htmlspecialchars($user['nama'])) ?>" 
                 data-email="<?= strtolower(htmlspecialchars($user['email'])) ?>" 
                 data-id="<?= $user['id_pengguna'] ?>">
                <div class="user-avatar">
                    <?php if(!empty($user['gambar'])): ?>
                        <img src="../../assets/avatars/<?= htmlspecialchars($user['gambar']) ?>" alt="Avatar">
                    <?php else: ?>
                        <div class="default-avatar">
                            <i class="fas fa-user-circle"></i>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="user-info">
                    <h3 class="user-name"><?= htmlspecialchars($user['nama']) ?></h3>
                    <p class="user-email-text"><?= htmlspecialchars($user['email']) ?></p>
                    <div class="user-meta">
                        <span class="join-date">
                            <i class="fas fa-calendar-alt"></i>
                            <?php 
                            if (!empty($user['created_at']) && $user['created_at'] != '0000-00-00') {
                                echo date('d M Y', strtotime($user['created_at']));
                            } else {
                                echo 'Tanggal tidak tersedia';
                            }
                            ?>
                        </span>
                        <span class="user-id">
                            <i class="fas fa-id-card"></i>
                            ID: <span class="user-id-text"><?= $user['id_pengguna'] ?></span>
                        </span>
                    </div>
                </div>

                <div class="user-actions">
                    <a href="edit-pengguna.php?id=<?= $user['id_pengguna'] ?>" class="btn-edit-user">
                        <i class="fas fa-edit"></i> Edit
                    </a>
                    <form method="POST" action="../../controllers/admin/hapus-pengguna.php" 
                          style="display: inline-block;"
                          onsubmit="return confirm('Hapus pengguna ini secara permanen?')">
                        <input type="hidden" name="id" value="<?= $user['id_pengguna'] ?>">
                        <button type="submit" class="btn-delete-user">
                            <i class="fas fa-trash"></i> Hapus
                        </button>
                    </form>
                </div>
            </div>
            <?php endwhile; ?>
        </div>

        <!-- Pesan ketika tidak ada hasil -->
        <div id="noResults" class="no-results" style="display: none;">
            <i class="fas fa-search"></i>
            <h3>Tidak ada pengguna yang ditemukan</h3>
            <p>Coba gunakan kata kunci yang berbeda</p>
        </div>
    </div>

    <script>
    // Fungsi pencarian pengguna
    function searchUsers() {
        const searchInput = document.getElementById('searchInput');
        const searchTerm = searchInput.value.toLowerCase().trim();
        const userCards = document.querySelectorAll('.user-card');
        const noResults = document.getElementById('noResults');
        const searchInfo = document.getElementById('searchInfo');
        const resultCount = document.getElementById('resultCount');
        
        let visibleCount = 0;
        
        // Reset highlight sebelumnya
        clearHighlights();
        
        userCards.forEach(card => {
            const name = card.getAttribute('data-name');
            const email = card.getAttribute('data-email');
            const id = card.getAttribute('data-id');
            
            // Cek apakah search term cocok dengan nama, email, atau ID
            const isMatch = name.includes(searchTerm) || 
                           email.includes(searchTerm) || 
                           id.includes(searchTerm);
            
            if (searchTerm === '' || isMatch) {
                card.classList.remove('hidden');
                card.style.display = 'block';
                visibleCount++;
                
                // Highlight text yang cocok jika ada search term
                if (searchTerm !== '') {
                    highlightText(card, searchTerm);
                }
            } else {
                card.classList.add('hidden');
                card.style.display = 'none';
            }
        });
        
        // Tampilkan info hasil pencarian
        if (searchTerm !== '') {
            searchInfo.style.display = 'block';
            resultCount.textContent = visibleCount;
        } else {
            searchInfo.style.display = 'none';
        }
        
        // Tampilkan pesan "tidak ada hasil" jika diperlukan
        if (visibleCount === 0 && searchTerm !== '') {
            noResults.style.display = 'block';
        } else {
            noResults.style.display = 'none';
        }
    }
    
    // Fungsi untuk highlight text
    function highlightText(card, searchTerm) {
        const userName = card.querySelector('.user-name');
        const userEmail = card.querySelector('.user-email-text');
        const userId = card.querySelector('.user-id-text');
        
        // Highlight nama
        if (userName.textContent.toLowerCase().includes(searchTerm)) {
            userName.innerHTML = highlightMatch(userName.textContent, searchTerm);
        }
        
        // Highlight email
        if (userEmail.textContent.toLowerCase().includes(searchTerm)) {
            userEmail.innerHTML = highlightMatch(userEmail.textContent, searchTerm);
        }
        
        // Highlight ID
        if (userId.textContent.toLowerCase().includes(searchTerm)) {
            userId.innerHTML = highlightMatch(userId.textContent, searchTerm);
        }
    }
    
    // Fungsi untuk menambahkan highlight pada text
    function highlightMatch(text, searchTerm) {
        const regex = new RegExp(`(${searchTerm})`, 'gi');
        return text.replace(regex, '<span class="highlight">$1</span>');
    }
    
    // Fungsi untuk clear highlight
    function clearHighlights() {
        const highlights = document.querySelectorAll('.highlight');
        highlights.forEach(highlight => {
            const parent = highlight.parentNode;
            parent.replaceChild(document.createTextNode(highlight.textContent), highlight);
            parent.normalize();
        });
    }
    
    // Event listener untuk input pencarian (real-time search)
    document.getElementById('searchInput').addEventListener('input', function() {
        searchUsers();
    });
    
    // Event listener untuk tombol Enter
    document.getElementById('searchInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            searchUsers();
        }
    });
    
    // Dropdown functionality (kode asli)
    document.querySelectorAll('.dropdown-header').forEach(header => {
        header.addEventListener('click', () => {
            const dropdown = header.parentElement;
            dropdown.classList.toggle('active');
        });
    });

    // File input functionality (kode asli)
    const fileInput = document.getElementById('gambar');
    if (fileInput) {
        fileInput.addEventListener('change', function(e) {
            const label = document.querySelector('.file-input-label span');
            if (label) {
                if (e.target.files.length > 0) {
                    label.textContent = e.target.files[0].name;
                } else {
                    label.textContent = 'Ganti Gambar Produk (Opsional)';
                }
            }
        });
    }
    
    // Reset pencarian saat halaman dimuat
    window.addEventListener('load', function() {
        document.getElementById('searchInput').value = '';
        searchUsers();
    });
    </script>
</body>
</html>