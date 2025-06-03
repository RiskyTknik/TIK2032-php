<?php
// Koneksi ke database
$host = 'localhost';
$user = 'root'; // ganti jika user Anda berbeda
$pass = '';
$db   = 'tik2025_php'; // Nama database sudah diganti sesuai permintaan

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die('Koneksi gagal: ' . $conn->connect_error);
}

// Fungsi untuk membaca komentar dari database
function get_comments($image) {
    global $conn;
    $comments = [];
    $stmt = $conn->prepare("SELECT name, comment FROM gallery_comments WHERE image = ? ORDER BY created_at ASC");
    $stmt->bind_param("s", $image);
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $comments[] = $row;
    }
    $stmt->close();
    return $comments;
}

// Fungsi untuk menyimpan komentar ke database
function save_comment($image, $name, $comment) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO gallery_comments (image, name, comment) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $image, $name, $comment);
    $stmt->execute();
    $stmt->close();
}

// Proses form komentar
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['gallery_image'])) {
    $image = $_POST['gallery_image'];
    $name = trim($_POST['name'] ?? 'Anonim');
    $comment = trim($_POST['comment'] ?? '');
    if ($comment !== '') {
        save_comment($image, $name, $comment);
    }
    // Redirect agar tidak resubmit saat refresh
    header('Location: index.php#gallery');
    exit;
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>ikyy WEBSITE</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Tombol Ganti Mode -->
    <button class="mode-toggle" onclick="toggleMode()">Ganti Mode</button>

    <nav>
        <a href="#" onclick="showSection(event, 'home')">Home</a>
        <a href="#" onclick="showSection(event, 'about')">About Me</a>
        <a href="#" onclick="showSection(event, 'gallery')">Gallery</a>
        <a href="#" onclick="showSection(event, 'blog')">Blog</a>
        <a href="#" onclick="showSection(event, 'contact')">Contact</a>
    </nav>
    
    <div id="home" class="section active">
        <h1>Portofolio saya</h1>
        <img src="css/Images/portofolio.jpg" alt="Foto Portofolio 1" width="300" class="home-img">
    </div>
    
    <div id="about" class="section">
        <h1>About Me</h1>
        <p>Halo, nama saya Risky Imbat. Saya seorang pengembang web yang memiliki ketertarikan dalam teknologi, pengolahan citra, dan pemrograman. Saya suka belajar hal-hal baru dan selalu berusaha untuk meningkatkan kemampuan saya di dunia teknologi.</p>
        
    </div>

    <div id="gallery" class="section">
        <h1>Gallery</h1>
        <?php
        $gallery = [
            ['file' => 'Gunung Merapi.jpg', 'alt' => 'Foto 1', 'width' => 300],
            ['file' => 'gunung merbabu.jpg', 'alt' => 'Foto 2', 'width' => 211],
            ['file' => 'pemandangan alam pantai dan matahari.jpg', 'alt' => 'Foto 3', 'width' => 211],
        ];
        foreach ($gallery as $img) {
            $imgPath = 'css/Images/' . $img['file'];
            echo '<div style="margin-bottom:30px;">';
            echo '<img class="gallery-img" src="' . htmlspecialchars($imgPath) . '" alt="' . htmlspecialchars($img['alt']) . '">';
            // Tampilkan komentar
            $comments = get_comments($img['file']);
            echo '<div class="gallery-comments">';
            echo '<strong>Komentar:</strong>';
            if ($comments) {
                foreach ($comments as $c) {
                    echo '<div class="comment-item">';
                    echo '<b>' . htmlspecialchars($c['name']) . ':</b> ' . htmlspecialchars($c['comment']);
                    echo '</div>';
                }
            } else {
                echo '<em>Belum ada komentar.</em>';
            }
            echo '</div>';
            // Form komentar
            echo '<form method="post" class="gallery-comment-form">';
            echo '<input type="hidden" name="gallery_image" value="' . htmlspecialchars($img['file']) . '">';
            echo '<input type="text" name="name" placeholder="Nama">';
            echo '<textarea name="comment" placeholder="Tulis komentar..." required></textarea>';
            echo '<button type="submit">Kirim</button>';
            echo '</form>';
            echo '</div>';
        }
        ?>
    </div>
    
    <div id="blog" class="section">
        <h1>Blog</h1>
        <article>
            <h2><a href="https://id.wikipedia.org/wiki/Gunung_Merbati" target="_blank">Gunung Merbabu</a></h2>
            <p>Gunung Merbabu (Hanacaraka: ꦒꦸꦤꦸꦁꦩꦼꦂꦧꦧꦸ) adalah gunung api yang bertipe Stratovulcano...</p>
        </article>
        <article>
            <h2><a href="https://id.wikipedia.org/wiki/Gunung_Merapi" target="_blank">Gunung Merapi</a></h2>
            <p>Gunung Merapi (ketinggian puncak 2.930 mdpl, per 2010)...</p>
        </article>
        <article>
            <h2><a href="https://id.wikipedia.org/wiki/Pantai" target="_blank">Pantai</a></h2>
            <p>Pantai adalah sebuah bentuk geografis yang terdiri dari pasir...</p>
        </article>
    </div>
    
    <div id="contact" class="section">
        <h1>Contact</h1>
        <div class="contact-info">
            <img src="https://cdn.jsdelivr.net/npm/simple-icons@v11/icons/gmail.svg" alt="Email"> 
            <span>Email: riskyimbat026@student.unsrat.ac.id</span>
        </div>
        <div class="contact-info">
            <img src="https://cdn.jsdelivr.net/npm/simple-icons@v11/icons/instagram.svg" alt="Instagram"> 
            <span>Instagram: <a href="https://instagram.com/ikyyafns" target="_blank">@ikyyafns</a></span>
        </div>
    </div>

    <!-- JavaScript eksternal -->
    <script src="script.js"></script>
</body>
</html>
