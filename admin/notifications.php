<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Send Notifications - IT Sahayata Admin</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: #f5f7fa;
        }
        
        /* Reuse sidebar and header styles from dashboard.php */
        .sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: 260px;
            height: 100vh;
            background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
            color: white;
            overflow-y: auto;
            z-index: 1000;
        }
        
        .sidebar-header {
            padding: 25px 20px;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        
        .sidebar-header h2 {
            font-size: 1.5rem;
            margin-bottom: 5px;
        }
        
        .sidebar-header p {
            font-size: 0.85rem;
            opacity: 0.8;
        }
        
        .sidebar-menu {
            list-style: none;
            padding: 20px 0;
        }
        
        .sidebar-menu li {
            margin-bottom: 5px;
        }
        
        .sidebar-menu a {
            display: flex;
            align-items: center;
            padding: 12px 20px;
            color: white;
            text-decoration: none;
            transition: background 0.3s;
        }
        
        .sidebar-menu a:hover,
        .sidebar-menu a.active {
            background: rgba(255,255,255,0.2);
        }
        
        .sidebar-menu a span {
            margin-right: 12px;
            font-size: 1.2rem;
        }
        
        .main-content {
            margin-left: 260px;
            min-height: 100vh;
        }
        
        .header {
            background: white;
            padding: 20px 30px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header h1 {
            color: #333;
            font-size: 1.8rem;
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: #667eea;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
        }
        
        .logout-btn {
            padding: 8px 20px;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.9rem;
        }
        
        .content {
            padding: 30px;
        }
        
        /* Notification Form */
        .notification-form {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            max-width: 800px;
            margin: 0 auto;
        }
        
        .form-group {
            margin-bottom: 25px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
            font-size: 1.1rem;
        }
        
        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            font-size: 1rem;
            font-family: inherit;
        }
        
        .form-group textarea {
            min-height: 120px;
            resize: vertical;
        }
        
        .form-group input:focus,
        .form-group select:focus,
        .form-group textarea:focus {
            outline: none;
            border-color: #007BFF;
        }
        
        .recipient-options {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-top: 10px;
        }
        
        .recipient-option {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 12px;
            border: 2px solid #e0e0e0;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .recipient-option:hover {
            border-color: #007BFF;
        }
        
        .recipient-option.selected {
            border-color: #007BFF;
            background: #e3f2fd;
        }
        
        .recipient-option input {
            width: auto;
            margin: 0;
        }
        
        .btn {
            padding: 15px 30px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1rem;
            font-weight: 600;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: #007BFF;
            color: white;
        }
        
        .btn-primary:hover {
            background: #0056b3;
        }
        
        .btn-primary:disabled {
            background: #ccc;
            cursor: not-allowed;
        }
        
        .alert {
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: none;
        }
        
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
        
        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #007BFF;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            display: inline-block;
            margin-right: 10px;
            vertical-align: middle;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>🛠️ IT Sahayata</h2>
            <p>Admin Control Panel</p>
        </div>
        
        <ul class="sidebar-menu">
            <li><a href="dashboard.php"><span>📊</span> Dashboard</a></li>
            <li><a href="tickets.php"><span>🎫</span> Manage Tickets</a></li>
            <li><a href="users.php"><span>👥</span> All Users</a></li>
            <li><a href="agents.php"><span>👨‍💼</span> Agents</a></li>
            <li><a href="analytics.php"><span>📈</span> Analytics</a></li>
            <li><a href="notifications.php" class="active"><span>🔔</span> Send Notifications</a></li>
        </ul>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="header">
            <h1>Send Notifications</h1>
            <div class="user-info">
                <div class="user-avatar" id="userAvatar">A</div>
                <span id="userName">Admin</span>
                <button class="logout-btn" onclick="handleLogout()">Logout</button>
            </div>
        </div>
        
        <!-- Content -->
        <div class="content">
            <div id="successAlert" class="alert alert-success">
                ✅ Notification sent successfully!
            </div>
            
            <div id="errorAlert" class="alert alert-error">
                ❌ 
            </div>
            
            <div class="notification-form">
                <h2 style="margin-bottom: 30px; color: #333;">Send Broadcast Notification</h2>
                
                <div class="form-group">
                    <label for="notificationTitle">Notification Title</label>
                    <input type="text" id="notificationTitle" placeholder="Enter notification title" required>
                </div>
                
                <div class="form-group">
                    <label for="notificationMessage">Message</label>
                    <textarea id="notificationMessage" placeholder="Enter your notification message..." required></textarea>
                </div>
                
                <div class="form-group">
                    <label>Send To</label>
                    <div class="recipient-options">
                        <label class="recipient-option" onclick="selectRecipient('all')">
                            <input type="radio" name="recipient" value="all" checked>
                            📢 All Users
                        </label>
                        <label class="recipient-option" onclick="selectRecipient('customers')">
                            <input type="radio" name="recipient" value="customers">
                            👥 Customers Only
                        </label>
                        <label class="recipient-option" onclick="selectRecipient('agents')">
                            <input type="radio" name="recipient" value="agents">
                            👨‍💼 Agents Only
                        </label>
                        <label class="recipient-option" onclick="selectRecipient('admins')">
                            <input type="radio" name="recipient" value="admins">
                            🛠️ Admins Only
                        </label>
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="notificationType">Notification Type</label>
                    <select id="notificationType">
                        <option value="broadcast">📢 Broadcast Announcement</option>
                        <option value="promotional">🎯 Promotional</option>
                        <option value="maintenance">🔧 System Maintenance</option>
                        <option value="urgent">🚨 Urgent Alert</option>
                    </select>
                </div>
                
                <button class="btn btn-primary" onclick="sendNotification()" id="sendBtn">
                    📤 Send Notification
                </button>
            </div>
        </div>
    </div>
    
    <script>
        const API_BASE = '../api';
        let adminToken = localStorage.getItem('admin_token');
        let adminUser = JSON.parse(localStorage.getItem('admin_user') || '{}');
        
        // Check authentication
        if (!adminToken || adminUser.role !== 'admin') {
            window.location.href = 'index.php';
        }
        
        // Set user info
        document.getElementById('userName').textContent = adminUser.name || 'Admin';
        document.getElementById('userAvatar').textContent = (adminUser.name || 'A')[0].toUpperCase();
        
        // Select recipient
        function selectRecipient(value) {
            document.querySelectorAll('.recipient-option').forEach(option => {
                option.classList.remove('selected');
            });
            
            const selectedOption = document.querySelector(`.recipient-option input[value="${value}"]`).parentElement;
            selectedOption.classList.add('selected');
        }
        
        // Send notification
        async function sendNotification() {
            const title = document.getElementById('notificationTitle').value.trim();
            const message = document.getElementById('notificationMessage').value.trim();
            const recipient = document.querySelector('input[name="recipient"]:checked').value;
            const type = document.getElementById('notificationType').value;
            
            if (!title || !message) {
                showError('Please fill in all required fields');
                return;
            }
            
            // Determine recipient type and ID
            let recipientType, recipientId;
            switch (recipient) {
                case 'all':
                    recipientType = 'all';
                    recipientId = 'all';
                    break;
                case 'customers':
                    recipientType = 'customer';
                    recipientId = 'all_customers';
                    break;
                case 'agents':
                    recipientType = 'agent';
                    recipientId = 'all_agents';
                    break;
                case 'admins':
                    recipientType = 'admin';
                    recipientId = 'all_admins';
                    break;
            }
            
            const sendBtn = document.getElementById('sendBtn');
            const originalText = sendBtn.textContent;
            sendBtn.disabled = true;
            sendBtn.innerHTML = '<span class="spinner"></span> Sending...';
            
            try {
                const response = await fetch(`${API_BASE}/notifications/send.php`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer ' + adminToken
                    },
                    body: JSON.stringify({
                        title: title,
                        message: message,
                        type: type,
                        recipient_type: recipientType,
                        recipient_id: recipientId,
                        metadata: JSON.stringify({
                            broadcast: true,
                            sent_by: adminUser.name,
                            sent_at: new Date().toISOString()
                        })
                    })
                });
                
                const result = await response.json();
                
                if (response.ok) {
                    showSuccess('Notification sent successfully to all ' + recipient + '!');
                    // Clear form
                    document.getElementById('notificationTitle').value = '';
                    document.getElementById('notificationMessage').value = '';
                } else {
                    showError(result.error || result.message || 'Failed to send notification');
                }
            } catch (error) {
                console.error('Error sending notification:', error);
                showError('Network error. Please try again.');
            } finally {
                sendBtn.disabled = false;
                sendBtn.textContent = originalText;
            }
        }
        
        // Show success message
        function showSuccess(message) {
            const alert = document.getElementById('successAlert');
            alert.textContent = '✅ ' + message;
            alert.style.display = 'block';
            
            // Hide error alert if visible
            document.getElementById('errorAlert').style.display = 'none';
            
            // Auto hide after 5 seconds
            setTimeout(() => {
                alert.style.display = 'none';
            }, 5000);
        }
        
        // Show error message
        function showError(message) {
            const alert = document.getElementById('errorAlert');
            alert.innerHTML = '❌ ' + message;
            alert.style.display = 'block';
            
            // Hide success alert if visible
            document.getElementById('successAlert').style.display = 'none';
        }
        
        // Logout
        function handleLogout() {
            if (confirm('Are you sure you want to logout?')) {
                localStorage.removeItem('admin_token');
                localStorage.removeItem('admin_user');
                window.location.href = 'index.php';
            }
        }
        
        // Initialize recipient selection
        document.addEventListener('DOMContentLoaded', function() {
            selectRecipient('all');
        });
    </script>
</body>
</html>