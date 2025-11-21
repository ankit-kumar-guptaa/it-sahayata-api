<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT Sahayata - Admin Dashboard</title>
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
        
        /* Sidebar */
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
        
        /* Main Content */
        .main-content {
            margin-left: 260px;
            min-height: 100vh;
        }
        
        /* Header */
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
        
        .logout-btn:hover {
            background: #c82333;
        }
        
        /* Content Area */
        .content {
            padding: 30px;
        }
        
        /* Stats Cards */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .stat-info h3 {
            font-size: 2rem;
            color: #333;
            margin-bottom: 5px;
        }
        
        .stat-info p {
            color: #666;
            font-size: 0.95rem;
        }
        
        .stat-icon {
            font-size: 3rem;
            opacity: 0.3;
        }
        
        .stat-card.blue { border-left: 4px solid #007BFF; }
        .stat-card.green { border-left: 4px solid #28a745; }
        .stat-card.orange { border-left: 4px solid #ff9800; }
        .stat-card.red { border-left: 4px solid #dc3545; }
        
        /* Recent Activity */
        .section {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
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
        
        .badge-pending { background: #fff3cd; color: #856404; }
        .badge-assigned { background: #cfe2ff; color: #084298; }
        .badge-in-progress { background: #d1ecf1; color: #0c5460; }
        .badge-resolved { background: #d4edda; color: #155724; }
        .badge-closed { background: #d1d3e2; color: #383d41; }
        
        .priority-high { color: #dc3545; font-weight: bold; }
        .priority-medium { color: #ff9800; }
        .priority-low { color: #28a745; }
        
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
        
        @media (max-width: 768px) {
            .sidebar {
                width: 0;
                overflow: hidden;
            }
            
            .main-content {
                margin-left: 0;
            }
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
            <li><a href="dashboard.php" class="active"><span>📊</span> Dashboard</a></li>
            <li><a href="tickets.php"><span>🎫</span> Manage Tickets</a></li>
            <li><a href="users.php"><span>👥</span> All Users</a></li>
            <li><a href="agents.php"><span>👨‍💼</span> Agents</a></li>
            <li><a href="analytics.php"><span>📈</span> Analytics</a></li>
            <li><a href="notifications.php"><span>🔔</span> Send Notifications</a></li>
        </ul>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="header">
            <h1>Dashboard Overview</h1>
            <div class="user-info">
                <div class="user-avatar" id="userAvatar">A</div>
                <span id="userName">Admin</span>
                <button class="logout-btn" onclick="handleLogout()">Logout</button>
            </div>
        </div>
        
        <!-- Content -->
        <div class="content">
            <!-- Stats Cards -->
            <div class="stats-grid">
                <div class="stat-card blue">
                    <div class="stat-info">
                        <h3 id="totalTickets">0</h3>
                        <p>Total Tickets</p>
                    </div>
                    <div class="stat-icon">🎫</div>
                </div>
                
                <div class="stat-card orange">
                    <div class="stat-info">
                        <h3 id="pendingTickets">0</h3>
                        <p>Pending Tickets</p>
                    </div>
                    <div class="stat-icon">⏳</div>
                </div>
                
                <div class="stat-card green">
                    <div class="stat-info">
                        <h3 id="totalAgents">0</h3>
                        <p>Active Agents</p>
                    </div>
                    <div class="stat-icon">👨‍💼</div>
                </div>
                
                <div class="stat-card red">
                    <div class="stat-info">
                        <h3 id="totalCustomers">0</h3>
                        <p>Customers</p>
                    </div>
                    <div class="stat-icon">👥</div>
                </div>
            </div>
            
            <!-- Recent Tickets -->
            <div class="section">
                <div class="section-header">
                    <h2>Recent Tickets</h2>
                    <a href="tickets.php" style="color: #007BFF; text-decoration: none;">View All →</a>
                </div>
                
                <div id="ticketsLoading" class="spinner"></div>
                
                <table class="table" id="ticketsTable" style="display: none;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Category</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody id="ticketsBody">
                    </tbody>
                </table>
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
        
        // Load dashboard data
        async function loadDashboard() {
            await Promise.all([
                loadStatistics(),
                loadRecentTickets()
            ]);
        }
        
        // Load statistics
        async function loadStatistics() {
            try {
                const [ticketsRes, usersRes] = await Promise.all([
                    fetch(`${API_BASE}/tickets/list.php`, {
                        headers: { 'Authorization': 'Bearer ' + adminToken }
                    }),
                    fetch(`${API_BASE}/admin/users.php`, {
                        headers: { 'Authorization': 'Bearer ' + adminToken }
                    })
                ]);
                
                const ticketsData = await ticketsRes.json();
                const usersData = await usersRes.json();
                
                if (ticketsData.data) {
                    const tickets = ticketsData.data.tickets || [];
                    document.getElementById('totalTickets').textContent = tickets.length;
                    document.getElementById('pendingTickets').textContent = 
                        tickets.filter(t => t.status === 'pending').length;
                }
                
                if (usersData.data && usersData.data.statistics) {
                    const stats = usersData.data.statistics;
                    document.getElementById('totalAgents').textContent = stats.agents || 0;
                    document.getElementById('totalCustomers').textContent = stats.customers || 0;
                }
            } catch (error) {
                console.error('Error loading statistics:', error);
            }
        }
        
        // Load recent tickets
        async function loadRecentTickets() {
            try {
                const response = await fetch(`${API_BASE}/tickets/list.php`, {
                    headers: { 'Authorization': 'Bearer ' + adminToken }
                });
                
                const result = await response.json();
                
                if (result.data && result.data.tickets) {
                    const tickets = result.data.tickets.slice(0, 10); // Last 10
                    displayTickets(tickets);
                }
            } catch (error) {
                console.error('Error loading tickets:', error);
            } finally {
                document.getElementById('ticketsLoading').style.display = 'none';
                document.getElementById('ticketsTable').style.display = 'table';
            }
        }
        
        // Display tickets
        function displayTickets(tickets) {
            const tbody = document.getElementById('ticketsBody');
            
            if (tickets.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" style="text-align: center; padding: 30px;">No tickets found</td></tr>';
                return;
            }
            
            tbody.innerHTML = tickets.map(ticket => `
                <tr>
                    <td>#${ticket.id}</td>
                    <td>${ticket.customer_name}</td>
                    <td><span style="text-transform: capitalize;">${ticket.category}</span></td>
                    <td><span class="priority-${ticket.priority}">${ticket.priority.toUpperCase()}</span></td>
                    <td><span class="badge badge-${ticket.status}">${ticket.status}</span></td>
                    <td>${formatDate(ticket.created_at)}</td>
                </tr>
            `).join('');
        }
        
        // Format date
        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-IN', { 
                year: 'numeric', 
                month: 'short', 
                day: 'numeric' 
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
        
        // Load dashboard on page load
        loadDashboard();
    </script>
</body>
</html>
