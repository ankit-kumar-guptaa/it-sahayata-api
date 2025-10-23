<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT Sahayata - Admin Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        
        .login-container {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 15px 50px rgba(0,0,0,0.3);
            width: 100%;
            max-width: 450px;
        }
        
        .logo {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .logo h1 {
            color: #007BFF;
            font-size: 2rem;
            margin-bottom: 5px;
        }
        
        .logo p {
            color: #666;
            font-size: 0.95rem;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        label {
            display: block;
            margin-bottom: 8px;
            color: #333;
            font-weight: 600;
        }
        
        input {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        
        input:focus {
            outline: none;
            border-color: #007BFF;
        }
        
        .btn-login {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 1.1rem;
            font-weight: 600;
            cursor: pointer;
            transition: transform 0.3s;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        
        .btn-login:disabled {
            background: #ccc;
            cursor: not-allowed;
        }
        
        .alert {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #007BFF;
            border-radius: 50%;
            width: 20px;
            height: 20px;
            animation: spin 1s linear infinite;
            display: none;
            margin: 0 auto;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #666;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo">
            <h1>🛠️ IT Sahayata</h1>
            <p>Admin Portal - Manage Support System</p>
        </div>
        
        <div class="alert alert-error" id="alert"></div>
        
        <form id="loginForm" onsubmit="return handleLogin(event)">
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" placeholder="admin@itsahayata.com" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" placeholder="Enter your password" required>
            </div>
            
            <button type="submit" class="btn-login" id="loginBtn">
                Login to Admin Panel
            </button>
            
            <div class="spinner" id="spinner"></div>
        </form>
        
        <div class="footer">
            © 2025 IT Sahayata. All rights reserved.
        </div>
    </div>
    
    <script>
        async function handleLogin(e) {
            e.preventDefault();
            
            const email = document.getElementById('email').value;
            const password = document.getElementById('password').value;
            const btn = document.getElementById('loginBtn');
            const spinner = document.getElementById('spinner');
            const alert = document.getElementById('alert');
            
            btn.disabled = true;
            btn.textContent = 'Logging in...';
            spinner.style.display = 'block';
            alert.style.display = 'none';
            
            try {
                const response = await fetch('../api/auth/login.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ email, password })
                });
                
                const result = await response.json();
                
                if (response.ok && result.data) {
                    // Check if user is admin
                    if (result.data.user.role !== 'admin') {
                        showAlert('Access Denied! Admin credentials required.', 'error');
                        btn.disabled = false;
                        btn.textContent = 'Login to Admin Panel';
                        spinner.style.display = 'none';
                        return;
                    }
                    
                    // Save token and user data
                    localStorage.setItem('admin_token', result.data.token);
                    localStorage.setItem('admin_user', JSON.stringify(result.data.user));
                    
                    showAlert('Login successful! Redirecting...', 'success');
                    
                    setTimeout(() => {
                        window.location.href = 'dashboard.php';
                    }, 1000);
                } else {
                    showAlert(result.error || result.message || 'Login failed', 'error');
                    btn.disabled = false;
                    btn.textContent = 'Login to Admin Panel';
                }
            } catch (error) {
                showAlert('Network error. Please try again.', 'error');
                btn.disabled = false;
                btn.textContent = 'Login to Admin Panel';
            } finally {
                spinner.style.display = 'none';
            }
        }
        
        function showAlert(message, type) {
            const alert = document.getElementById('alert');
            alert.textContent = message;
            alert.className = 'alert alert-' + type;
            alert.style.display = 'block';
        }
        
        // Check if already logged in
        if (localStorage.getItem('admin_token')) {
            window.location.href = 'dashboard.php';
        }
    </script>
</body>
</html>
