<?php
class Database {
    private $host = 'localhost';
    private $db_name = 'kosmarket_db';
    private $username = 'root';
    private $password = '';
    private $conn;

    public function getConnection() {
        $this->conn = null;
        
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $exception) {
            echo "Connection error: " . $exception->getMessage();
        }
        
        return $this->conn;
    }
}

// Session management
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!function_exists('isLoggedIn')) {
    function isLoggedIn() {
        return isset($_SESSION['user_id']);
    }
}

if (!function_exists('formatRupiah')) {
    function formatRupiah($angka) {
        return "Rp " . number_format($angka, 0, ',', '.');
    }
}

if (!function_exists('generateAngkatan')) {
    function generateAngkatan($email) {
        // Extract angkatan from email format: 123456789@stis.ac.id (9 digit)
        if (preg_match('/^(\d{9})@stis\.ac\.id$/', $email, $matches)) {
            return substr($matches[1], 0, 2);
        }
        return '00';
    }
}

if (!function_exists('validateSTISEmail')) {
    function validateSTISEmail($email) {
        return preg_match('/^[0-9]{9}@stis\.ac\.id$/', $email);
    }
}

if (!function_exists('timeAgo')) {
    function timeAgo($datetime) {
        $time = time() - strtotime($datetime);
        
        if ($time < 60) return 'baru saja';
        if ($time < 3600) return floor($time/60) . ' menit lalu';
        if ($time < 86400) return floor($time/3600) . ' jam lalu';
        if ($time < 2592000) return floor($time/86400) . ' hari lalu';
        if ($time < 31536000) return floor($time/2592000) . ' bulan lalu';
        return floor($time/31536000) . ' tahun lalu';
    }
}

if (!function_exists('getCategoryEmoji')) {
    function getCategoryEmoji($category) {
        $emojis = [
            'Elektronik' => '📱',
            'Pakaian' => '👕',
            'Buku & Alat Tulis' => '📚',
            'Furniture' => '🪑',
            'Furnitur' => '🪑',
            'Peralatan Dapur' => '🍳',
            'Olahraga' => '⚽',
            'Kecantikan' => '💄',
            'Makanan & Minuman' => '🍕',
            'Lainnya' => '📦'
        ];
        
        return $emojis[$category] ?? '📦';
    }
}
?>