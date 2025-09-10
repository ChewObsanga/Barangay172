<?php
/**
 * Migration script to switch from MySQL to PostgreSQL for Render deployment
 * Run this script before deploying to Render
 */

echo "Starting migration to Render configuration...\n";

// Copy render config files
if (file_exists('includes/config_render.php')) {
    copy('includes/config_render.php', 'includes/config.php');
    echo "✓ Updated config.php for Render\n";
} else {
    echo "✗ config_render.php not found\n";
}

if (file_exists('includes/database_render.php')) {
    copy('includes/database_render.php', 'includes/database.php');
    echo "✓ Updated database.php for Render\n";
} else {
    echo "✗ database_render.php not found\n";
}

// Create public directory if it doesn't exist
if (!is_dir('public')) {
    mkdir('public', 0755, true);
    echo "✓ Created public directory\n";
}

// Create public/index.php if it doesn't exist
if (!file_exists('public/index.php')) {
    $indexContent = '<?php
// Redirect to the main index.html file
header(\'Location: ../index.html\');
exit();
?>';
    file_put_contents('public/index.php', $indexContent);
    echo "✓ Created public/index.php\n";
}

// Create public/.htaccess if it doesn't exist
if (!file_exists('public/.htaccess')) {
    $htaccessContent = 'RewriteEngine On

# Redirect all requests to index.php
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]

# Security headers
Header always set X-Content-Type-Options nosniff
Header always set X-Frame-Options DENY
Header always set X-XSS-Protection "1; mode=block"
Header always set Referrer-Policy "strict-origin-when-cross-origin"

# Cache static assets
<FilesMatch "\.(css|js|png|jpg|jpeg|gif|ico|svg)$">
    ExpiresActive On
    ExpiresDefault "access plus 1 month"
</FilesMatch>';
    file_put_contents('public/.htaccess', $htaccessContent);
    echo "✓ Created public/.htaccess\n";
}

echo "\nMigration completed successfully!\n";
echo "Your application is now ready for Render deployment.\n";
echo "\nNext steps:\n";
echo "1. Push your code to GitHub\n";
echo "2. Connect your repository to Render\n";
echo "3. Deploy using the render.yaml configuration\n";
echo "4. Set up environment variables in Render dashboard\n";
echo "5. Test your deployed application\n";
?>
