<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics - IT Sahayata Admin</title>
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
        
        /* Analytics Cards */
        .analytics-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .analytics-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            position: relative;
            overflow: hidden;
        }
        
        .analytics-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 4px;
        }
        
        .analytics-card.blue::before { background: #007BFF; }
        .analytics-card.green::before { background: #28a745; }
        .analytics-card.orange::before { background: #ff9800; }
        .analytics-card.red::before { background: #dc3545; }
        .analytics-card.purple::before { background: #6f42c1; }
        
        .analytics-card h3 {
            color: #666;
            font-size: 0.9rem;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        
        .analytics-card .value {
            font-size: 2.5rem;
            font-weight: bold;
            color: #333;
            margin-bottom: 10px;
        }
        
        .analytics-card .change {
            font-size: 0.85rem;
            color: #666;
        }
        
        .analytics-card .change.positive {
            color: #28a745;
        }
        
        .analytics-card .change.negative {
            color: #dc3545;
        }
        
        /* Section */
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
        
        /* Progress Bar */
        .progress-item {
            margin-bottom: 20px;
        }
        
        .progress-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 8px;
        }
        
        .progress-header span {
            font-weight: 600;
            color: #555;
        }
        
        .progress-bar-container {
            width: 100%;
            height: 30px;
            background: #e9ecef;
            border-radius: 15px;
            overflow: hidden;
            position: relative;
        }
        
        .progress-bar {
            height: 100%;
            background: linear-gradient(90deg, #667eea, #764ba2);
            border-radius: 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: 600;
            font-size: 0.85rem;
            transition: width 0.5s ease;
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
            <li><a href="users.php"><span>👥</span> All Users</a></li>
            <li><a href="agents.php"><span>👨‍💼</span> Agents</a></li>
            <li><a href="analytics.php" class="active"><span>📈</span> Analytics</a></li>
        </ul>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="header">
            <h1>Analytics & Reports</h1>
            <div class="user-info">
                <div class="user-avatar" id="userAvatar">A</div>
                <span id="userName">Admin</span>
                <button class="logout-btn" onclick="handleLogout()">Logout</button>
            </div>
        </div>
        
        <!-- Content -->
        <div class="content">
            <div id="loading" class="spinner"></div>
            
            <div id="analyticsContent" style="display: none;">
                <!-- Key Metrics -->
                <div class="analytics-grid">
                    <div class="analytics-card blue">
                        <h3>Total Tickets</h3>
                        <div class="value" id="totalTickets">0</div>
                        <div class="change">All time</div>
                    </div>
                    
                    <div class="analytics-card green">
                        <h3>Resolved Tickets</h3>
                        <div class="value" id="resolvedTickets">0</div>
                        <div class="change" id="resolvedPercent">0%</div>
                    </div>
                    
                    <div class="analytics-card orange">
                        <h3>Pending Tickets</h3>
                        <div class="value" id="pendingTickets">0</div>
                        <div class="change" id="pendingPercent">0%</div>
                    </div>
                    
                    <div class="analytics-card red">
                        <h3>Average Resolution Time</h3>
                        <div class="value" id="avgResolution">N/A</div>
                        <div class="change">Time in hours</div>
                    </div>
                    
                    <div class="analytics-card purple">
                        <h3>Total Users</h3>
                        <div class="value" id="totalUsers">0</div>
                        <div class="change" id="userBreakdown">0 customers, 0 agents</div>
                    </div>
                </div>
                
                <!-- Category Breakdown -->
                <div class="section">
                    <div class="section-header">
                        <h2>Tickets by Category</h2>
                    </div>
                    
                    <div id="categoryBreakdown">
                    </div>
                </div>
                
                <!-- Priority Breakdown -->
                <div class="section">
                    <div class="section-header">
                        <h2>Tickets by Priority</h2>
                    </div>
                    
                    <div id="priorityBreakdown">
                    </div>
                </div>
                
                <!-- Top Agents -->
                <div class="section">
                    <div class="section-header">
                        <h2>Top Performing Agents</h2>
                    </div>
                    
                    <table class="table">
                        <thead>
                            <tr>
                                <th>Agent Name</th>
                                <th>Total Tickets</th>
                                <th>Resolved</th>
                                <th>Success Rate</th>
                            </tr>
                        </thead>
                        <tbody id="topAgents">
                        </tbody>
                    </table>
                </div>
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
        
        // Load analytics
        async function loadAnalytics() {
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
                
                if (ticketsData.data && usersData.data) {
                    const tickets = ticketsData.data.tickets || [];
                    const users = usersData.data.users || [];
                    const userStats = usersData.data.statistics || {};
                    
                    calculateAndDisplayAnalytics(tickets, users, userStats);
                }
            } catch (error) {
                console.error('Error loading analytics:', error);
            } finally {
                document.getElementById('loading').style.display = 'none';
                document.getElementById('analyticsContent').style.display = 'block';
            }
        }
        
        // Calculate and display analytics
        function calculateAndDisplayAnalytics(tickets, users, userStats) {
            // Total tickets
            document.getElementById('totalTickets').textContent = tickets.length;
            
            // Resolved tickets
            const resolved = tickets.filter(t => t.status === 'resolved' || t.status === 'closed');
            document.getElementById('resolvedTickets').textContent = resolved.length;
            
            const resolvedPercent = tickets.length > 0 ? ((resolved.length / tickets.length) * 100).toFixed(1) : 0;
            document.getElementById('resolvedPercent').textContent = `${resolvedPercent}% of total`;
            
            // Pending tickets
            const pending = tickets.filter(t => t.status === 'pending');
            document.getElementById('pendingTickets').textContent = pending.length;
            
            const pendingPercent = tickets.length > 0 ? ((pending.length / tickets.length) * 100).toFixed(1) : 0;
            document.getElementById('pendingPercent').textContent = `${pendingPercent}% of total`;
            
            // Average resolution time (mock data)
            document.getElementById('avgResolution').textContent = '~24h';
            
            // Total users
            document.getElementById('totalUsers').textContent = users.length;
            document.getElementById('userBreakdown').textContent = 
                `${userStats.customers || 0} customers, ${userStats.agents || 0} agents`;
            
            // Category breakdown
            displayCategoryBreakdown(tickets);
            
            // Priority breakdown
            displayPriorityBreakdown(tickets);
            
            // Top agents
            displayTopAgents(tickets, users);
        }
        
        // Display category breakdown
        function displayCategoryBreakdown(tickets) {
            const categories = {
                'software': 0,
                'hardware': 0,
                'internet': 0,
                'other': 0
            };
            
            tickets.forEach(ticket => {
                if (categories.hasOwnProperty(ticket.category)) {
                    categories[ticket.category]++;
                }
            });
            
            const total = tickets.length || 1;
            const container = document.getElementById('categoryBreakdown');
            
            container.innerHTML = Object.entries(categories).map(([category, count]) => {
                const percent = ((count / total) * 100).toFixed(1);
                return `
                    <div class="progress-item">
                        <div class="progress-header">
                            <span style="text-transform: capitalize;">${category}</span>
                            <span>${count} tickets (${percent}%)</span>
                        </div>
                        <div class="progress-bar-container">
                            <div class="progress-bar" style="width: ${percent}%">
                                ${percent}%
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }
        
        // Display priority breakdown
        function displayPriorityBreakdown(tickets) {
            const priorities = {
                'high': 0,
                'medium': 0,
                'low': 0
            };
            
            tickets.forEach(ticket => {
                if (priorities.hasOwnProperty(ticket.priority)) {
                    priorities[ticket.priority]++;
                }
            });
            
            const total = tickets.length || 1;
            const container = document.getElementById('priorityBreakdown');
            
            const colors = {
                'high': '#dc3545',
                'medium': '#ff9800',
                'low': '#28a745'
            };
            
            container.innerHTML = Object.entries(priorities).map(([priority, count]) => {
                const percent = ((count / total) * 100).toFixed(1);
                return `
                    <div class="progress-item">
                        <div class="progress-header">
                            <span style="text-transform: capitalize;">${priority} Priority</span>
                            <span>${count} tickets (${percent}%)</span>
                        </div>
                        <div class="progress-bar-container">
                            <div class="progress-bar" style="width: ${percent}%; background: ${colors[priority]}">
                                ${percent}%
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }
        
        // Display top agents
        function displayTopAgents(tickets, users) {
            const agents = users.filter(u => u.role === 'agent');
            
            const agentStats = agents.map(agent => {
                const assignedTickets = tickets.filter(t => t.agent_id == agent.id);
                const resolvedTickets = assignedTickets.filter(t => t.status === 'resolved' || t.status === 'closed');
                const successRate = assignedTickets.length > 0 
                    ? ((resolvedTickets.length / assignedTickets.length) * 100).toFixed(1) 
                    : 0;
                
                return {
                    name: agent.name,
                    total: assignedTickets.length,
                    resolved: resolvedTickets.length,
                    successRate: successRate
                };
            });
            
            // Sort by success rate
            agentStats.sort((a, b) => b.successRate - a.successRate);
            
            const tbody = document.getElementById('topAgents');
            
            if (agentStats.length === 0) {
                tbody.innerHTML = '<tr><td colspan="4" style="text-align: center; padding: 30px;">No agents data available</td></tr>';
                return;
            }
            
            tbody.innerHTML = agentStats.slice(0, 10).map(agent => `
                <tr>
                    <td>${agent.name}</td>
                    <td>${agent.total}</td>
                    <td>${agent.resolved}</td>
                    <td style="font-weight: 600; color: ${agent.successRate >= 80 ? '#28a745' : agent.successRate >= 50 ? '#ff9800' : '#dc3545'}">
                        ${agent.successRate}%
                    </td>
                </tr>
            `).join('');
        }
        
        // Logout
        function handleLogout() {
            if (confirm('Are you sure you want to logout?')) {
                localStorage.removeItem('admin_token');
                localStorage.removeItem('admin_user');
                window.location.href = 'index.php';
            }
        }
        
        // Load analytics on page load
        loadAnalytics();
    </script>
</body>
</html>
