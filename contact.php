<?php
include 'db_config.php'; // Koneksi ke database

$response = ""; // Variabel untuk menyimpan pesan sukses/error

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $name = isset($_POST['full_name']) ? $_POST['full_name'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
    $subject = isset($_POST['subject']) ? $_POST['subject'] : '';
    $message = isset($_POST['message']) ? $_POST['message'] : '';

    // Validasi email
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $response = "Alamat email tidak valid!";
    } else if (!empty($name) && !empty($email) && !empty($message)) {
        // Menggunakan prepared statement untuk keamanan
        $stmt = $conn->prepare("INSERT INTO contacts (full_name, email, phone, subject, message) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $name, $email, $phone, $subject, $message);

        if ($stmt->execute()) {
            // Kirim email ke pemilik situs
            $to = "wahyuozorahmanurung@gmail.com"; // Email Anda
            $headers = "From: $email";
            $fullMessage = "Name: $name\nEmail: $email\nPhone: $phone\nMessage:\n$message";

            if (mail($to, $subject, $fullMessage, $headers)) {
                $response = "Pesan Anda berhasil dikirim!";
            } else {
                $response = "Pesan tersimpan, tetapi gagal mengirim email.";
            }
        } else {
            $response = "Terjadi kesalahan: " . $stmt->error;
        }
        $stmt->close();
    } else {
        $response = "Semua field harus diisi!";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
    <title>𝑏𝑢𝑛𝑔𝑎</title>
</head>

<body>
    <!-- Header Section -->
    <header>
        <a href="#home" class="logo">𝐁𝐔𝐍𝐆𝐀 𝐂𝐈𝐓𝐑𝐀 𝐀𝐋𝐘𝐒𝐒𝐀</a>
        <div class='bx bx-menu' id="menu-icon"></div>
        <ul class="navbar">
            <li><a href="index.html">𝐻𝑜𝓂𝑒</a></li>
            <li><a href="about.html">𝒜𝒷𝑜𝓊𝓉</a></li>
            <li><a href="portfolio.html">𝒫𝑜𝓇𝓉𝑜𝒻𝑜𝓁𝒾𝑜</a></li>
            <li><a href="contact.php">𝒞𝑜𝓃𝓉𝒶𝒸𝓉</a></li>
        </ul>
        <div class="top-btn">
            <a href="#contact" class="nav-btn">𝑪𝒐𝒏𝒕𝒂𝒄𝒕 𝑴𝒆</a>
        </div>
    </header>

    <!-- Contact Section -->
    <section class="contact" id="contact">
        <h2 class="heading">𝑪𝒐𝒏𝒕𝒂𝒄𝒕 <span>𝑴𝒆</span></h2>
        <form action="contact.php" method="POST">
            <div class="input-box">
                <input type="text" name="full_name" placeholder="Your Name" required>
                <input type="email" name="email" placeholder="Your Email" required>
            </div>
            <div class="input-box">
                <input type="text" name="phone" placeholder="Your Phone">
                <input type="text" name="subject" placeholder="Subject" required>
            </div>
            <textarea name="message" cols="30" rows="10" placeholder="Your Message" required></textarea>
            <input type="submit" value="Send Message" class="btn">
        </form>

        <!-- Menampilkan Respons -->
        <?php if (!empty($response)): ?>
            <p class="response-message"><?php echo htmlspecialchars($response); ?></p>
        <?php endif; ?>
    </section>

    <!-- Footer Section -->
    <footer class="footer">
        <div class="social">
            <a href=" "><i class='bx bxl-linkedin'></i></a>
            <a href=" "><i class='bx bxl-github'></i></a>
            <a href=" "><i class='bx bxl-facebook'></i></a>
            <a href="https://www.instagram.com/bungacitraalyssa"><i class='bx bxl-instagram'></i></a>
        </div>
        <p class="copyright">
            &copy; 𝑏𝑢𝑛𝑔𝑎@2024
        </p>
    </footer>

    <script src="https://unpkg.com/typed.js@2.1.0/dist/typed.umd.js"></script>
    <script src="script.js"></script>
</body>

</html>
