<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Tickets - IT Sahayata Admin</title>
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
        
        /* Filters */
        .filters {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            align-items: center;
        }
        
        .filters select {
            padding: 10px 15px;
            border: 2px solid #e0e0e0;
            border-radius: 5px;
            font-size: 0.95rem;
        }
        
        .filters select:focus {
            outline: none;
            border-color: #007BFF;
        }
        
        /* Tickets Section */
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
        
        .badge-pending { background: #fff3cd; color: #856404; }
        .badge-assigned { background: #cfe2ff; color: #084298; }
        .badge-in-progress { background: #d1ecf1; color: #0c5460; }
        .badge-resolved { background: #d4edda; color: #155724; }
        .badge-closed { background: #d1d3e2; color: #383d41; }
        
        .priority-high { color: #dc3545; font-weight: bold; }
        .priority-medium { color: #ff9800; }
        .priority-low { color: #28a745; }
        
        .btn {
            padding: 6px 12px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 0.85rem;
            margin-right: 5px;
        }
        
        .btn-primary {
            background: #007BFF;
            color: white;
        }
        
        .btn-success {
            background: #28a745;
            color: white;
        }
        
        .btn-primary:hover {
            background: #0056b3;
        }
        
        /* Modal */
        .modal {
            display: none;
            position: fixed;
            z-index: 2000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
        }
        
        .modal-content {
            background: white;
            margin: 5% auto;
            padding: 30px;
            width: 90%;
            max-width: 500px;
            border-radius: 10px;
            position: relative;
        }
        
        .close {
            position: absolute;
            right: 20px;
            top: 15px;
            font-size: 2rem;
            cursor: pointer;
            color: #999;
        }
        
        .close:hover {
            color: #333;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        
        .form-group select {
            width: 100%;
            padding: 10px;
            border: 2px solid #e0e0e0;
            border-radius: 5px;
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
            <li><a href="tickets.php" class="active"><span>🎫</span> Manage Tickets</a></li>
            <li><a href="users.php"><span>👥</span> All Users</a></li>
            <li><a href="agents.php"><span>👨‍💼</span> Agents</a></li>
            <li><a href="analytics.php"><span>📈</span> Analytics</a></li>
        </ul>
    </div>
    
    <!-- Main Content -->
    <div class="main-content">
        <!-- Header -->
        <div class="header">
            <h1>Manage Tickets</h1>
            <div class="user-info">
                <div class="user-avatar" id="userAvatar">A</div>
                <span id="userName">Admin</span>
                <button class="logout-btn" onclick="handleLogout()">Logout</button>
            </div>
        </div>
        
        <!-- Content -->
        <div class="content">
            <!-- Filters -->
            <div class="filters">
                <label>Filter by Status:</label>
                <select id="filterStatus" onchange="filterTickets()">
                    <option value="all">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="assigned">Assigned</option>
                    <option value="in_progress">In Progress</option>
                    <option value="resolved">Resolved</option>
                    <option value="closed">Closed</option>
                </select>
                
                <label>Filter by Priority:</label>
                <select id="filterPriority" onchange="filterTickets()">
                    <option value="all">All Priority</option>
                    <option value="high">High</option>
                    <option value="medium">Medium</option>
                    <option value="low">Low</option>
                </select>
            </div>
            
            <!-- Tickets Table -->
            <div class="section">
                <div class="section-header">
                    <h2>All Tickets</h2>
                    <span id="ticketCount">0 tickets</span>
                </div>
                
                <div id="ticketsLoading" class="spinner"></div>
                
                <table class="table" id="ticketsTable" style="display: none;">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer</th>
                            <th>Category</th>
                            <th>Description</th>
                            <th>Attachments</th>
                            <th>Priority</th>
                            <th>Status</th>
                            <th>Agent</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody id="ticketsBody">
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    
    <!-- Assign Agent Modal -->
    <div id="assignModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h2 style="margin-bottom: 20px;">Assign Agent to Ticket</h2>
            
            <div class="form-group">
                <label>Select Agent:</label>
                <select id="agentSelect">
                    <option value="">Loading agents...</option>
                </select>
            </div>
            
            <button class="btn btn-success" onclick="assignAgent()" style="width: 100%; padding: 12px;">
                Assign Agent
            </button>
        </div>
    </div>
    
    <script>
        const API_BASE = '../api';
        let adminToken = localStorage.getItem('admin_token');
        let adminUser = JSON.parse(localStorage.getItem('admin_user') || '{}');
        let allTickets = [];
        let agents = [];
        let currentTicketId = null;
        
        // Check authentication
        if (!adminToken || adminUser.role !== 'admin') {
            window.location.href = 'index.php';
        }
        
        // Set user info
        document.getElementById('userName').textContent = adminUser.name || 'Admin';
        document.getElementById('userAvatar').textContent = (adminUser.name || 'A')[0].toUpperCase();
        
        // Load data
        async function loadData() {
            await Promise.all([
                loadTickets(),
                loadAgents()
            ]);
        }
        
        // Load tickets
        async function loadTickets() {
            try {
                const response = await fetch(`${API_BASE}/tickets/list.php`, {
                    headers: { 'Authorization': 'Bearer ' + adminToken }
                });
                
                const result = await response.json();
                
                if (result.data && result.data.tickets) {
                    allTickets = result.data.tickets;
                    displayTickets(allTickets);
                }
            } catch (error) {
                console.error('Error loading tickets:', error);
            } finally {
                document.getElementById('ticketsLoading').style.display = 'none';
                document.getElementById('ticketsTable').style.display = 'table';
            }
        }
        
        // Load agents
        async function loadAgents() {
            try {
                const response = await fetch(`${API_BASE}/admin/users.php?role=agent`, {
                    headers: { 'Authorization': 'Bearer ' + adminToken }
                });
                
                const result = await response.json();
                
                if (result.data && result.data.users) {
                    agents = result.data.users;
                    populateAgentDropdown();
                }
            } catch (error) {
                console.error('Error loading agents:', error);
            }
        }
        
        // Populate agent dropdown
        function populateAgentDropdown() {
            const select = document.getElementById('agentSelect');
            
            if (agents.length === 0) {
                select.innerHTML = '<option value="">No agents available</option>';
                return;
            }
            
            select.innerHTML = '<option value="">-- Select Agent --</option>' +
                agents.map(agent => 
                    `<option value="${agent.id}">${agent.name} (${agent.email})</option>`
                ).join('');
        }
        
        // Display tickets
        function displayTickets(tickets) {
            const tbody = document.getElementById('ticketsBody');
            document.getElementById('ticketCount').textContent = `${tickets.length} tickets`;
            
            if (tickets.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8" style="text-align: center; padding: 30px;">No tickets found</td></tr>';
                return;
            }
            
            tbody.innerHTML = tickets.map(ticket => {
                // Get agent name if assigned
                const assignment = ticket.agent_name || 'Unassigned';
                
                // Handle file attachments
                let attachmentsHtml = 'No files';
                if (ticket.file_url) {
                    const fileUrls = ticket.file_url.split(',');
                    attachmentsHtml = fileUrls.map(url => 
                        `<a href="${url}" target="_blank" style="display: block; margin-bottom: 2px; color: #007BFF; text-decoration: none; font-size: 12px;">📎 View File</a>`
                    ).join('');
                }
                
                return `
                    <tr>
                        <td>#${ticket.id}</td>
                        <td>${ticket.customer_name}<br><small style="color: #666;">${ticket.customer_phone}</small></td>
                        <td style="text-transform: capitalize;">${ticket.category}</td>
                        <td style="max-width: 200px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;">
                            ${ticket.description}
                        </td>
                        <td style="max-width: 120px;">${attachmentsHtml}</td>
                        <td><span class="priority-${ticket.priority}">${ticket.priority.toUpperCase()}</span></td>
                        <td><span class="badge badge-${ticket.status}">${ticket.status}</span></td>
                        <td>${assignment}</td>
                        <td>
                            <button class="btn btn-primary" onclick="openAssignModal(${ticket.id})">
                                ${ticket.agent_id ? 'Reassign' : 'Assign'}
                            </button>
                        </td>
                    </tr>
                `;
            }).join('');
        }
        
        // Filter tickets
        function filterTickets() {
            const statusFilter = document.getElementById('filterStatus').value;
            const priorityFilter = document.getElementById('filterPriority').value;
            
            let filtered = allTickets;
            
            if (statusFilter !== 'all') {
                filtered = filtered.filter(t => t.status === statusFilter);
            }
            
            if (priorityFilter !== 'all') {
                filtered = filtered.filter(t => t.priority === priorityFilter);
            }
            
            displayTickets(filtered);
        }
        
        // Open assign modal
        function openAssignModal(ticketId) {
            currentTicketId = ticketId;
            document.getElementById('assignModal').style.display = 'block';
        }
        
        // Close modal
        function closeModal() {
            document.getElementById('assignModal').style.display = 'none';
            currentTicketId = null;
        }
        
        // Assign agent
        async function assignAgent() {
            const agentId = document.getElementById('agentSelect').value;
            
            if (!agentId) {
                alert('Please select an agent');
                return;
            }
            
            try {
                const response = await fetch(`${API_BASE}/admin/assign_agent.php`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer ' + adminToken
                    },
                    body: JSON.stringify({
                        ticket_id: currentTicketId,
                        agent_id: agentId
                    })
                });
                
                const result = await response.json();
                
                if (response.ok) {
                    alert('✅ Agent assigned successfully!');
                    closeModal();
                    loadTickets(); // Reload tickets
                } else {
                    alert('❌ ' + (result.error || result.message || 'Failed to assign agent'));
                }
            } catch (error) {
                alert('❌ Network error. Please try again.');
                console.error('Error assigning agent:', error);
            }
        }
        
        // Logout
        function handleLogout() {
            if (confirm('Are you sure you want to logout?')) {
                localStorage.removeItem('admin_token');
                localStorage.removeItem('admin_user');
                window.location.href = 'index.php';
            }
        }
        
        // Close modal when clicking outside
        window.onclick = function(event) {
            const modal = document.getElementById('assignModal');
            if (event.target == modal) {
                closeModal();
            }
        }
        
        // Load data on page load
        loadData();
    </script>
</body>
</html>
