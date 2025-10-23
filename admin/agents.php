<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Agents - IT Sahayata Admin</title>
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
        
        /* Agent Cards */
        .agents-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .agent-card {
            background: white;
            padding: 25px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }
        
        .agent-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 5px 20px rgba(0,0,0,0.15);
        }
        
        .agent-header {
            display: flex;
            align-items: center;
            gap: 15px;
            margin-bottom: 15px;
            padding-bottom: 15px;
            border-bottom: 2px solid #f0f0f0;
        }
        
        .agent-avatar {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
        }
        
        .agent-info h3 {
            color: #333;
            font-size: 1.2rem;
            margin-bottom: 5px;
        }
        
        .agent-info p {
            color: #666;
            font-size: 0.9rem;
        }
        
        .agent-stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
            margin: 15px 0;
        }
        
        .stat-item {
            text-align: center;
            padding: 10px;
            background: #f8f9fa;
            border-radius: 8px;
        }
        
        .stat-item h4 {
            font-size: 1.5rem;
            color: #007BFF;
            margin-bottom: 5px;
        }
        
        .stat-item p {
            font-size: 0.85rem;
            color: #666;
        }
        
        .agent-contact {
            margin-top: 15px;
            padding-top: 15px;
            border-top: 1px solid #f0f0f0;
        }
        
        .contact-item {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 8px;
            color: #555;
            font-size: 0.9rem;
        }
        
        .contact-item span {
            font-size: 1.2rem;
        }
        
        .badge {
            display: inline-block;
            padding: 4px 12px;
            border-radius: 12px;
            font-size: 0.85rem;
            font-weight: 600;
        }
        
        .badge-verified { background: #d4edda; color: #155724; }
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
        }
        
        .section-header h2 {
            color: #333;
            font-size: 1.3rem;
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
            <li><a href="agents.php" class="active"><span>👨‍💼</span> Agents</a></li>
            <li><a href="analytics.php"><span>📈</span> Analytics</a></li>
        </ul>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="header">
            <h1>Agent Management</h1>
            <div class="user-info">
                <div class="user-avatar" id="userAvatar">A</div>
                <span id="userName">Admin</span>
                <button class="logout-btn" onclick="handleLogout()">Logout</button>
            </div>
        </div>
        
        <!-- Content -->
        <div class="content">
            <div class="section">
                <div class="section-header">
                    <h2>Active Agents (<span id="agentCount">0</span>)</h2>
                </div>
                
                <div id="agentsLoading" class="spinner"></div>
                
                <div class="agents-grid" id="agentsGrid" style="display: none;">
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
        
        // Load agents
        async function loadAgents() {
            try {
                const [agentsRes, ticketsRes] = await Promise.all([
                    fetch(`${API_BASE}/admin/users.php?role=agent`, {
                        headers: { 'Authorization': 'Bearer ' + adminToken }
                    }),
                    fetch(`${API_BASE}/tickets/list.php`, {
                        headers: { 'Authorization': 'Bearer ' + adminToken }
                    })
                ]);
                
                const agentsData = await agentsRes.json();
                const ticketsData = await ticketsRes.json();
                
                if (agentsData.data && agentsData.data.users) {
                    const agents = agentsData.data.users;
                    const tickets = ticketsData.data?.tickets || [];
                    
                    // Calculate stats for each agent
                    const agentsWithStats = agents.map(agent => {
                        const assignedTickets = tickets.filter(t => t.agent_id == agent.id);
                        const resolvedTickets = assignedTickets.filter(t => t.status === 'resolved' || t.status === 'closed');
                        const activeTickets = assignedTickets.filter(t => t.status !== 'resolved' && t.status !== 'closed');
                        
                        return {
                            ...agent,
                            total_tickets: assignedTickets.length,
                            resolved_tickets: resolvedTickets.length,
                            active_tickets: activeTickets.length
                        };
                    });
                    
                    displayAgents(agentsWithStats);
                }
            } catch (error) {
                console.error('Error loading agents:', error);
            } finally {
                document.getElementById('agentsLoading').style.display = 'none';
                document.getElementById('agentsGrid').style.display = 'grid';
            }
        }
        
        // Display agents
        function displayAgents(agents) {
            const grid = document.getElementById('agentsGrid');
            document.getElementById('agentCount').textContent = agents.length;
            
            if (agents.length === 0) {
                grid.innerHTML = '<div style="grid-column: 1/-1; text-align: center; padding: 50px; color: #999;">No agents found</div>';
                return;
            }
            
            grid.innerHTML = agents.map(agent => `
                <div class="agent-card">
                    <div class="agent-header">
                        <div class="agent-avatar">${agent.name[0].toUpperCase()}</div>
                        <div class="agent-info">
                            <h3>${agent.name}</h3>
                            <span class="badge badge-${agent.is_verified == 1 ? 'verified' : 'unverified'}">
                                ${agent.is_verified == 1 ? '✅ Verified' : '❌ Unverified'}
                            </span>
                        </div>
                    </div>
                    
                    <div class="agent-stats">
                        <div class="stat-item">
                            <h4>${agent.total_tickets || 0}</h4>
                            <p>Total</p>
                        </div>
                        <div class="stat-item">
                            <h4>${agent.active_tickets || 0}</h4>
                            <p>Active</p>
                        </div>
                        <div class="stat-item">
                            <h4>${agent.resolved_tickets || 0}</h4>
                            <p>Resolved</p>
                        </div>
                    </div>
                    
                    <div class="agent-contact">
                        <div class="contact-item">
                            <span>📧</span>
                            <span>${agent.email}</span>
                        </div>
                        <div class="contact-item">
                            <span>📱</span>
                            <span>${agent.phone}</span>
                        </div>
                        <div class="contact-item">
                            <span>📅</span>
                            <span>Joined: ${formatDate(agent.created_at)}</span>
                        </div>
                    </div>
                </div>
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
        
        // Load agents on page load
        loadAgents();
    </script>
</body>
</html>
