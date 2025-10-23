<?php
require_once '../includes/config.php';
session_start();
require_once '../includes/database.php';
require_once '../includes/EmailService.php';

$error = '';
$success = '';

function generateTempPassword($length = 10) {
    $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*';
    $password = '';
    for ($i = 0; $i < $length; $i++) {
        $password .= $chars[random_int(0, strlen($chars) - 1)];
    }
    return $password;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');

    if (empty($email)) {
        $error = 'Please enter your email address.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } else {
        try {
            $db = new Database();
            $conn = $db->getConnection();

            // Find user by email
            $stmt = $conn->prepare('SELECT id, username, full_name, email FROM users WHERE email = ? LIMIT 1');
            $stmt->execute([$email]);
            $user = $stmt->fetch();

            if (!$user) {
                $error = 'No account found with that email address.';
            } else {
                // Generate temp password and update
                $tempPassword = generateTempPassword(10);
                $hashed = password_hash($tempPassword, PASSWORD_DEFAULT);

                $up = $conn->prepare('UPDATE users SET password = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?');
                $up->execute([$hashed, $user['id']]);

                // Send email
                $mailer = new EmailService();
                $sent = $mailer->sendPasswordReset($user['email'], $user['username'], $tempPassword, $user['full_name']);

                if ($sent) {
                    $success = 'A temporary password has been sent to your email. Please check your inbox (and spam folder).';
                } else {
                    $error = 'We updated your password, but failed to send email. Please contact support for assistance.';
                }
            }
        } catch (Exception $e) {
            $error = 'An error occurred. Please try again later.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password - Barangay 172</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { 'poppins': ['Poppins', 'sans-serif'] },
                    colors: { 'barangay-orange': '#ff6700', 'barangay-green': '#2E8B57' }
                }
            }
        }
    </script>
    <style>
        body { background-image: url('../assets/images/hall.png'); background-size: cover; background-position: center; background-repeat: no-repeat; background-attachment: fixed; }
        body::before { content: ''; position: fixed; top:0; left:0; width:100%; height:100%; background-color: rgba(255,255,255,0.85); z-index: -1; }
    </style>
</head>
<body class="font-poppins min-h-screen">
    <nav class="bg-barangay-orange shadow-sm border-b border-orange-600">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="../index.html#login" class="flex items-center space-x-3 text-white hover:text-orange-100 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                        <span class="font-medium">Back to Login</span>
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <div class="flex min-h-screen items-center justify-center py-8 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full">
            <div class="bg-white rounded-2xl shadow-xl border border-gray-100 p-6">
                <div class="text-center mb-4">
                    <h2 class="text-2xl font-bold text-gray-900 mb-1">Forgot Password</h2>
                    <p class="text-sm text-gray-600">Enter your account email and we'll send you a temporary password.</p>
                </div>

                <?php if ($success): ?>
                    <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-3 py-2 rounded-xl text-sm">
                        <?= htmlspecialchars($success) ?>
                    </div>
                <?php endif; ?>
                <?php if ($error): ?>
                    <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-3 py-2 rounded-xl text-sm">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="space-y-4" novalidate>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email Address</label>
                        <input id="email" name="email" type="email" required class="block w-full px-3 py-2 border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-barangay-green focus:border-transparent transition-all duration-200 text-gray-900 placeholder-gray-500" placeholder="Enter your email">
                    </div>
                    <button type="submit" class="w-full py-2 px-4 text-sm font-semibold rounded-xl text-white bg-barangay-orange hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-barangay-orange shadow">
                        Send Temporary Password
                    </button>
                </form>

                <div class="text-center mt-4">
                    <a href="login.php" class="text-sm text-barangay-green hover:text-green-700 font-medium underline">Remembered your password? Sign in</a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
