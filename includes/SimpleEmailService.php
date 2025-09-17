<?php
/**
 * Simple Email Service for Render.com
 * This service provides a fallback when SMTP is not available
 */

class SimpleEmailService {
    
    public function sendRegistrationCredentials($email, $username, $password, $fullName, $role) {
        // For now, we'll just log the credentials and return true
        // In a real scenario, you might want to use a different email service
        
        $logMessage = "REGISTRATION CREDENTIALS\n";
        $logMessage .= "======================\n";
        $logMessage .= "Email: $email\n";
        $logMessage .= "Full Name: $fullName\n";
        $logMessage .= "Username: $username\n";
        $logMessage .= "Password: $password\n";
        $logMessage .= "Role: $role\n";
        $logMessage .= "Login URL: " . (defined('LOGIN_URL') ? LOGIN_URL : 'https://barangay172.onrender.com/auth/login.php') . "\n";
        $logMessage .= "Generated: " . date('Y-m-d H:i:s') . "\n";
        $logMessage .= "======================\n\n";
        
        // Log to error log
        error_log($logMessage);
        
        // Also save to a file for manual sending
        $this->saveCredentialsToFile($email, $username, $password, $fullName, $role);
        
        return true;
    }
    
    private function saveCredentialsToFile($email, $username, $password, $fullName, $role) {
        $filename = 'pending_emails.txt';
        $content = "Email: $email | Username: $username | Password: $password | Name: $fullName | Role: $role | Date: " . date('Y-m-d H:i:s') . "\n";
        
        // Append to file
        file_put_contents($filename, $content, FILE_APPEND | LOCK_EX);
    }
    
    public function getLastError() {
        return "Email service is using fallback mode. Check logs for credentials.";
    }
    
    public function createRegistrationEmailBody($username, $password, $fullName, $role) {
        $loginUrl = defined('LOGIN_URL') ? LOGIN_URL : 'https://barangay172.onrender.com/auth/login.php';
        
        return "
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Your Barangay 172 Urduja Account</title>
            <style>
                body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
                .container { max-width: 600px; margin: 0 auto; padding: 20px; }
                .header { background: linear-gradient(135deg, #ff8829 0%, #ff6b35 50%, #f7931e 100%); color: white; padding: 20px; text-align: center; border-radius: 8px 8px 0 0; }
                .content { background: #f9f9f9; padding: 30px; border-radius: 0 0 8px 8px; }
                .credentials { background: #fff; padding: 20px; border-radius: 8px; margin: 20px 0; border-left: 4px solid #ff8829; }
                .button { display: inline-block; background: #ff8829; color: white; padding: 12px 24px; text-decoration: none; border-radius: 6px; margin: 20px 0; }
                .footer { text-align: center; margin-top: 30px; color: #666; font-size: 14px; }
            </style>
        </head>
        <body>
            <div class='container'>
                <div class='header'>
                    <h1>Welcome to Barangay 172 Urduja!</h1>
                    <p>Your account has been successfully created</p>
                </div>
                <div class='content'>
                    <h2>Hello $fullName,</h2>
                    <p>Your account has been successfully created in the Barangay 172 Urduja Management System.</p>
                    
                    <div class='credentials'>
                        <h3>Your Login Credentials:</h3>
                        <p><strong>Username:</strong> $username</p>
                        <p><strong>Password:</strong> $password</p>
                        <p><strong>Role:</strong> " . ucfirst(str_replace('_', ' ', $role)) . "</p>
                    </div>
                    
                    <p>You can now log in to the system using the credentials above.</p>
                    
                    <a href='$loginUrl' class='button'>Login to Your Account</a>
                    
                    <p><strong>Important Security Notes:</strong></p>
                    <ul>
                        <li>Please change your password after your first login</li>
                        <li>Keep your login credentials secure and do not share them</li>
                        <li>If you have any questions, please contact the barangay office</li>
                    </ul>
                </div>
                <div class='footer'>
                    <p>Barangay 172 Urduja - Caloocan City</p>
                    <p>This is an automated message. Please do not reply to this email.</p>
                </div>
            </div>
        </body>
        </html>";
    }
    
    public function createPlainTextVersion($username, $password, $fullName, $role) {
        $loginUrl = defined('LOGIN_URL') ? LOGIN_URL : 'https://barangay172.onrender.com/auth/login.php';
        
        return "
Welcome to Barangay 172 Urduja Management System!

Hello $fullName,

Your account has been successfully created in the Barangay 172 Urduja Management System.

Your Login Credentials:
- Username: $username
- Password: $password
- Role: " . ucfirst(str_replace('_', ' ', $role)) . "

Login URL: $loginUrl

Important Security Notes:
- Please change your password after your first login
- Keep your login credentials secure and do not share them
- If you have any questions, please contact the barangay office

Barangay 172 Urduja - Caloocan City
This is an automated message. Please do not reply to this email.
        ";
    }
}
?>
