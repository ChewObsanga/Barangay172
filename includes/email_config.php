<?php
// Email Configuration for PHPMailer with Gmail SMTP
// IMPORTANT: You need to set up an App Password in your Gmail account

// Gmail SMTP Configuration
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_SECURE', 'tls');
define('SMTP_USERNAME', 'caloocancitybrgy.172@gmail.com');
define('SMTP_PASSWORD', 'cqqb wxtp wlkc dltc');
define('SMTP_FROM_EMAIL', 'noreply@barangay172urduja.com');
define('SMTP_FROM_NAME', 'Barangay 172 Urduja Management System');

// Email Templates
define('EMAIL_SUBJECT_REGISTRATION', 'Your Barangay 172 Urduja Management System Account');
define('EMAIL_SUBJECT_RESET_PASSWORD', 'Password Reset Request - Barangay 172 Urduja');

// System URLs
define('SYSTEM_URL', 'http://localhost:8000');
define('LOGIN_URL', SYSTEM_URL . '/auth/login.php');

// Email sending status
define('EMAIL_ENABLED', true);

/*
 * 🔑 SETUP INSTRUCTIONS:
 * 
 * 1. ENABLE 2-FACTOR AUTHENTICATION on your Gmail account:
 *    - Go to myaccount.google.com → Security → 2-Step Verification
 *    - Turn it ON and follow the setup process
 * 
 * 2. GENERATE APP PASSWORD:
 *    - Go to Security → 2-Step Verification → App passwords
 *    - Select "Mail" from dropdown
 *    - Click "Generate"
 *    - Copy the 16-character password (e.g., "abcd efgh ijkl mnop")
 * 
 * 3. UPDATE THIS FILE:
 *    - Replace 'your-email@gmail.com' with your actual Gmail address
 *    - Replace 'your-16-char-app-password' with the App Password from step 2
 * 
 * 4. TEST:
 *    - Try registering a new user
 *    - Check if email is received
 *    - Check spam folder if no email arrives
 * 
 * ⚠️ SECURITY NOTES:
 * - NEVER use your regular Gmail password
 * - ONLY use App Passwords for applications
 * - Keep your App Password secure
 * - You can revoke App Passwords anytime from Google Account settings
 */
?>
