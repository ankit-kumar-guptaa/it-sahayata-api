<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Users - IT Sahayata Admin</title>
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
        
        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
        }
        
        .stat-card h3 {
            font-size: 2.5rem;
            color: #333;
            margin-bottom: 8px;
        }
        
        .stat-card p {
            color: #666;
            font-size: 0.95rem;
        }
        
        .stat-card.customers { border-top: 4px solid #007BFF; }
        .stat-card.agents { border-top: 4px solid #28a745; }
        .stat-card.admins { border-top: 4px solid #ff9800; }
        .stat-card.verified { border-top: 4px solid #17a2b8; }
        
        /* Filters */
        .filters {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
        }
        
        .filters label {
            font-weight: 600;
            color: #555;
        }
        
        .filters select,
        .filters input {
            padding: 10px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 5px;
            font-size: 0.95rem;
        }
        
        .filters select:focus,
        .filters input:focus {
            outline: none;
            border-color: #007BFF;
        }
        
        /* Users Section */
        .section {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .section-header h2 {
            color: #333;
            font-size: 1.3rem;
        }
        
        .table {
            width: 100%;
            border-collapse: collapse;
        }
        
        .table thead {
            background: #f8f9fa;
        }
        
        .table th,
        .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }
        
        .table th {
            font-weight: 600;
            color: #555;
        }
        
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .badge-customer { background: #cfe2ff; color: #084298; }
        .badge-agent { background: #d4edda; color: #155724; }
        .badge-admin { background: #fff3cd; color: #856404; }
        .badge-verified { background: #d1ecf1; color: #0c5460; }
        .badge-unverified { background: #f8d7da; color: #721c24; }
        
        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #007BFF;
            border-radius: 50%;
            width: 50px;
            height: 50px;
            animation: spin 1s linear infinite;
            margin: 50px auto;
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
            <li><a href="users.php" class="active"><span>👥</span> All Users</a></li>
            <li><a href="agents.php"><span>👨‍💼</span> Agents</a></li>
            <li><a href="analytics.php"><span>📈</span> Analytics</a></li>
        </ul>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="header">
            <h1>User Management</h1>
            <div class="user-info">
                <div class="user-avatar" id="userAvatar">A</div>
                <span id="userName">Admin</span>
                <button class="logout-btn" onclick="handleLogout()">Logout</button>
            </div>
        </div>
        
        <!-- Content -->
        <div class="content">
            <!-- Statistics -->
            <div class="stats-grid">
                <div class="stat-card customers">
                    <h3 id="totalCustomers">0</h3>
                    <p>👤 Customers</p>
                </div>
                
                <div class="stat-card agents">
                    <h3 id="totalAgents">0</h3>
                    <p>👨‍💼 Agents</p>
                </div>
                
                <div class="stat-card admins">
                    <h3 id="totalAdmins">0</h3>
                    <p>🔑 Admins</p>
                </div>
                
                <div class="stat-card verified">
                    <h3 id="verifiedUsers">0</h3>
                    <p>✅ Verified Users</p>
                </div>
            </div>
            
            <!-- Filters -->
            <div class="filters">
                <label>Filter by Role:</label>
                <select id="filterRole" onchange="filterUsers()">
                    <option value="all">All Roles</option>
                    <option value="customer">Customers</option>
                    <option value="agent">Agents</option>
                    <option value="admin">Admins</option>
                </select>
                
                <label>Filter by Status:</label>
                <select id="filterVerified" onchange="filterUsers()">
                    <option value="all">All Status</option>
                    <option value="1">Verified</option>
                    <option value="0">Unverified</option>
                </select>
                
                <label>Search:</label>
                <input type="text" id="searchInput" placeholder="Search by name or email..." oninput="filterUsers()">
            </div>
            
            <!-- Users Table -->
            <div class="section">
                <div class="section-header">
                    <h2>All Users</h2>
                    <span id="userCount">0 users</span>
                </div>
                
                <div id="usersLoading" class="spinner"></div>
                
                <table class="table" id="usersTable" style="display: none;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Registered</th>
                        </tr>
                    </thead>
                    <tbody id="usersBody">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <script>
        const API_BASE = '../api';
        let adminToken = localStorage.getItem('admin_token');
        let adminUser = JSON.parse(localStorage.getItem('admin_user') || '{}');
        let allUsers = [];
        
        // Check authentication
        if (!adminToken || adminUser.role !== 'admin') {
            window.location.href = 'index.php';
        }
        
        // Set user info
        document.getElementById('userName').textContent = adminUser.name || 'Admin';
        document.getElementById('userAvatar').textContent = (adminUser.name || 'A')[0].toUpperCase();
        
        // Load users
        async function loadUsers() {
            try {
                const response = await fetch(`${API_BASE}/admin/users.php`, {
                    headers: { 'Authorization': 'Bearer ' + adminToken }
                });
                
                const result = await response.json();
                
                if (result.data) {
                    allUsers = result.data.users || [];
                    displayUsers(allUsers);
                    
                    // Update statistics
                    if (result.data.statistics) {
                        const stats = result.data.statistics;
                        document.getElementById('totalCustomers').textContent = stats.customers || 0;
                        document.getElementById('totalAgents').textContent = stats.agents || 0;
                        document.getElementById('totalAdmins').textContent = stats.admins || 0;
                        document.getElementById('verifiedUsers').textContent = stats.verified_users || 0;
                    }
                }
            } catch (error) {
                console.error('Error loading users:', error);
            } finally {
                document.getElementById('usersLoading').style.display = 'none';
                document.getElementById('usersTable').style.display = 'table';
            }
        }
        
        // Display users
        function displayUsers(users) {
            const tbody = document.getElementById('usersBody');
            document.getElementById('userCount').textContent = `${users.length} users`;
            
            if (users.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; padding: 30px;">No users found</td></tr>';
                return;
            }
            
            tbody.innerHTML = users.map(user => `
                <tr>
                    <td>#${user.id}</td>
                    <td>${user.name}</td>
                    <td>${user.email}</td>
                    <td>${user.phone}</td>
                    <td><span class="badge badge-${user.role}">${user.role.toUpperCase()}</span></td>
                    <td>
                        <span class="badge badge-${user.is_verified == 1 ? 'verified' : 'unverified'}">
                            ${user.is_verified == 1 ? '✅ Verified' : '❌ Unverified'}
                        </span>
                    </td>
                    <td>${formatDate(user.created_at)}</td>
                </tr>
            `).join('');
        }
        
        // Filter users
        function filterUsers() {
            const roleFilter = document.getElementById('filterRole').value;
            const verifiedFilter = document.getElementById('filterVerified').value;
            const searchQuery = document.getElementById('searchInput').value.toLowerCase();
            
            let filtered = allUsers;
            
            // Filter by role
            if (roleFilter !== 'all') {
                filtered = filtered.filter(u => u.role === roleFilter);
            }
            
            // Filter by verified status
            if (verifiedFilter !== 'all') {
                filtered = filtered.filter(u => u.is_verified == verifiedFilter);
            }
            
            // Search filter
            if (searchQuery) {
                filtered = filtered.filter(u => 
                    u.name.toLowerCase().includes(searchQuery) ||
                    u.email.toLowerCase().includes(searchQuery)
                );
            }
            
            displayUsers(filtered);
        }
        
        // Format date
        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-IN', { 
                year: 'numeric', 
                month: 'short', 
                day: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }
        
        // Logout
        function handleLogout() {
            if (confirm('Are you sure you want to logout?')) {
                localStorage.removeItem('admin_token');
                localStorage.removeItem('admin_user');
                window.location.href = 'index.php';
            }
        }
        
        // Load users on page load
        loadUsers();
    </script>
</body>
</html>
