<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>IT Sahayata - Complete API Testing Dashboard</title>
    <style>
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
        }
        
        body { 
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            min-height: 100vh;
        }
        
        .container { 
            max-width: 1400px; 
            margin: auto; 
            background: white;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 10px 50px rgba(0,0,0,0.2);
        }
        
        .header {
            border-bottom: 3px solid #007BFF;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }
        
        h1 { 
            color: #007BFF; 
            font-size: 2.5rem;
            margin-bottom: 5px;
        }
        
        .subtitle {
            color: #666;
            font-size: 1.1rem;
        }
        
        .status-bar {
            display: flex;
            gap: 15px;
            margin: 20px 0;
            flex-wrap: wrap;
        }
        
        .status-card {
            flex: 1;
            min-width: 200px;
            padding: 15px;
            border-radius: 8px;
            color: white;
            text-align: center;
        }
        
        .status-card.total { background: linear-gradient(135deg, #667eea, #764ba2); }
        .status-card.success { background: linear-gradient(135deg, #11998e, #38ef7d); }
        .status-card.failed { background: linear-gradient(135deg, #eb3349, #f45c43); }
        .status-card.pending { background: linear-gradient(135deg, #f2994a, #f2c94c); }
        
        .status-card h3 {
            font-size: 2rem;
            margin-bottom: 5px;
        }
        
        .status-card p {
            font-size: 0.9rem;
            opacity: 0.9;
        }
        
        .endpoint-section {
            margin-bottom: 30px;
            border: 2px solid #f0f0f0;
            border-radius: 10px;
            padding: 20px;
            transition: all 0.3s ease;
            background: #fafafa;
        }
        
        .endpoint-section:hover {
            border-color: #007BFF;
            box-shadow: 0 5px 15px rgba(0,123,255,0.1);
            background: white;
        }
        
        .endpoint-title {
            font-size: 1.3rem;
            color: #007BFF;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
            flex-wrap: wrap;
        }
        
        .method-badge {
            padding: 5px 12px;
            border-radius: 5px;
            font-size: 0.75rem;
            font-weight: bold;
            color: white;
        }
        
        .post { background: #00C853; }
        .get { background: #2196F3; }
        .put { background: #FF9800; }
        .delete { background: #F44336; }
        
        .endpoint-url {
            background: #263238;
            color: #aed581;
            padding: 12px;
            border-radius: 5px;
            font-family: 'Courier New', monospace;
            margin: 10px 0;
            font-size: 0.85rem;
            word-break: break-all;
        }
        
        .note {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        
        .success-note { background: #d4edda; border-left-color: #28a745; }
        .info-note { background: #d1ecf1; border-left-color: #17a2b8; }
        .error-note { background: #f8d7da; border-left-color: #dc3545; }
        
        input, textarea, select {
            width: 100%;
            padding: 12px;
            margin: 8px 0;
            border: 2px solid #e0e0e0;
            border-radius: 5px;
            font-size: 0.95rem;
            transition: border-color 0.3s;
        }
        
        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: #007BFF;
        }
        
        label {
            font-weight: 600;
            color: #333;
            display: block;
            margin-top: 10px;
        }
        
        .test-btn {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            transition: all 0.3s ease;
            margin-top: 15px;
            font-weight: 600;
        }
        
        .test-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(102, 126, 234, 0.4);
        }
        
        .test-btn:disabled {
            background: #ccc;
            cursor: not-allowed;
            transform: none;
        }
        
        .response-box {
            background: #1e1e1e;
            color: #d4d4d4;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
            font-family: 'Courier New', monospace;
            font-size: 0.85rem;
            white-space: pre-wrap;
            max-height: 400px;
            overflow-y: auto;
            display: none;
            border: 2px solid #333;
        }
        
        .response-box.show {
            display: block;
        }
        
        .response-box.success {
            border-color: #28a745;
            background: #1a2f1a;
        }
        
        .response-box.error {
            border-color: #dc3545;
            background: #2f1a1a;
        }
        
        .spinner {
            border: 3px solid #f3f3f3;
            border-top: 3px solid #007BFF;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            animation: spin 1s linear infinite;
            display: none;
            margin: 10px auto;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .token-display {
            background: #e8f5e9;
            padding: 15px;
            border-radius: 8px;
            margin-top: 15px;
            display: none;
        }
        
        .token-display.show {
            display: block;
        }
        
        .token-display input {
            background: white;
            font-family: 'Courier New', monospace;
            font-size: 0.8rem;
        }
        
        .copy-btn {
            background: #28a745;
            color: white;
            border: none;
            padding: 8px 15px;
            border-radius: 5px;
            cursor: pointer;
            margin-top: 10px;
            font-size: 0.9rem;
        }
        
        .copy-btn:hover {
            background: #218838;
        }
        
        .grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }
        
        @media (max-width: 768px) {
            .grid-2 {
                grid-template-columns: 1fr;
            }
            
            .status-bar {
                flex-direction: column;
            }
        }
        
        .api-info {
            background: #f8f9fa;
            padding: 10px 15px;
            border-radius: 5px;
            margin: 10px 0;
            font-size: 0.9rem;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🛠️ IT Sahayata API Testing Dashboard</h1>
            <p class="subtitle">Complete REST API Testing Interface - Test all 16 endpoints with detailed error handling</p>
        </div>

        <!-- Status Bar -->
        <div class="status-bar">
            <div class="status-card total">
                <h3 id="total-tests">0</h3>
                <p>Total Tests</p>
            </div>
            <div class="status-card success">
                <h3 id="success-tests">0</h3>
                <p>Successful</p>
            </div>
            <div class="status-card failed">
                <h3 id="failed-tests">0</h3>
                <p>Failed</p>
            </div>
            <div class="status-card pending">
                <h3 id="pending-tests">16</h3>
                <p>Pending</p>
            </div>
        </div>

        <div class="note info-note">
            <strong>📌 Setup Instructions:</strong><br>
            1. Run the complete SQL schema in phpMyAdmin<br>
            2. Install PHPMailer: <code>composer require phpmailer/phpmailer</code><br>
            3. Create upload folders: <code>mkdir -p uploads/tickets uploads/chat && chmod 777 uploads/*</code><br>
            4. Make sure Apache and MySQL are running on localhost<br>
            5. Start testing from Registration → Verify OTP → Login → Other APIs
        </div>

        <!-- ==================== AUTH APIS ==================== -->
        
        <!-- 1. REGISTER -->
        <div class="endpoint-section">
            <div class="endpoint-title">
                <span class="method-badge post">POST</span>
                <span>1. Register User with OTP</span>
            </div>
            <div class="endpoint-url">http://localhost/it-sahayata-api/api/auth/register.php</div>
            <div class="api-info">📧 Sends 6-digit OTP to email for verification</div>
            
            <div class="grid-2">
                <div>
                    <label>Name:</label>
                    <input type="text" id="reg_name" placeholder="John Doe">
                </div>
                <div>
                    <label>Email:</label>
                    <input type="email" id="reg_email" placeholder="john@example.com">
                </div>
            </div>
            
            <div class="grid-2">
                <div>
                    <label>Phone:</label>
                    <input type="text" id="reg_phone" placeholder="9876543210">
                </div>
                <div>
                    <label>Password:</label>
                    <input type="password" id="reg_password" placeholder="minimum 6 characters">
                </div>
            </div>
            
            <label>Role:</label>
            <select id="reg_role">
                <option value="customer">Customer</option>
                <option value="agent">Agent</option>
            </select>
            
            <button class="test-btn" onclick="testRegister()">🚀 Test Register</button>
            <div class="spinner" id="spinner_register"></div>
            <div class="response-box" id="response_register"></div>
        </div>

        <!-- 2. VERIFY OTP -->
        <div class="endpoint-section">
            <div class="endpoint-title">
                <span class="method-badge post">POST</span>
                <span>2. Verify OTP</span>
            </div>
            <div class="endpoint-url">http://localhost/it-sahayata-api/api/auth/verify_otp.php</div>
            <div class="api-info">✅ Verifies email with 6-digit OTP (valid for 10 minutes)</div>
            
            <label>Email:</label>
            <input type="email" id="verify_email" placeholder="john@example.com">
            
            <label>OTP (Check your email):</label>
            <input type="text" id="verify_otp" placeholder="123456" maxlength="6">
            
            <button class="test-btn" onclick="testVerifyOTP()">✅ Verify OTP</button>
            <div class="spinner" id="spinner_verify"></div>
            <div class="response-box" id="response_verify"></div>
        </div>

        <!-- 3. LOGIN -->
        <div class="endpoint-section">
            <div class="endpoint-title">
                <span class="method-badge post">POST</span>
                <span>3. Login (Get JWT Token)</span>
            </div>
            <div class="endpoint-url">http://localhost/it-sahayata-api/api/auth/login.php</div>
            <div class="api-info">🔐 Returns JWT token valid for 30 days</div>
            
            <label>Email:</label>
            <input type="email" id="login_email" placeholder="john@example.com">
            
            <label>Password:</label>
            <input type="password" id="login_password" placeholder="your password">
            
            <button class="test-btn" onclick="testLogin()">🔐 Login & Get Token</button>
            <div class="spinner" id="spinner_login"></div>
            <div class="response-box" id="response_login"></div>
            
            <div class="token-display" id="token_display">
                <strong style="color: #28a745;">✅ JWT Token Generated!</strong>
                <p style="margin: 10px 0; font-size: 0.9rem;">Copy this token and use it in all protected endpoints:</p>
                <input type="text" id="saved_token" readonly>
                <button class="copy-btn" onclick="copyToken()">📋 Copy Token</button>
                <button class="copy-btn" onclick="autoFillTokens()" style="background: #007BFF; margin-left: 10px;">🔄 Auto-Fill All APIs</button>
            </div>
        </div>

        <!-- 4. LOGOUT -->
        <div class="endpoint-section">
            <div class="endpoint-title">
                <span class="method-badge post">POST</span>
                <span>4. Logout (Invalidate Token)</span>
            </div>
            <div class="endpoint-url">http://localhost/it-sahayata-api/api/auth/logout.php</div>
            <div class="api-info">🚪 Deletes token from database and invalidates session</div>
            
            <label>Authorization Token:</label>
            <input type="text" id="logout_token" placeholder="Paste JWT token" class="token-field">
            
            <button class="test-btn" onclick="testLogout()">🚪 Test Logout</button>
            <div class="spinner" id="spinner_logout"></div>
            <div class="response-box" id="response_logout"></div>
        </div>

        <!-- ==================== TICKET APIS ==================== -->

        <!-- 5. CREATE TICKET -->
        <div class="endpoint-section">
            <div class="endpoint-title">
                <span class="method-badge post">POST</span>
                <span>5. Create Ticket (Customer Only)</span>
            </div>
            <div class="endpoint-url">http://localhost/it-sahayata-api/api/tickets/create.php</div>
            <div class="api-info">🎫 Customer creates new support ticket</div>
            
            <label>Authorization Token:</label>
            <input type="text" id="create_ticket_token" placeholder="Paste customer JWT token" class="token-field">
            
            <label>Category:</label>
            <select id="ticket_category">
                <option value="software">Software</option>
                <option value="hardware">Hardware</option>
                <option value="internet">Internet</option>
                <option value="other">Other</option>
            </select>
            
            <label>Description:</label>
            <textarea id="ticket_desc" rows="3" placeholder="Describe your IT issue in detail..."></textarea>
            
            <label>Priority:</label>
            <select id="ticket_priority">
                <option value="medium" selected>Medium</option>
                <option value="low">Low</option>
                <option value="high">High</option>
            </select>
            
            <button class="test-btn" onclick="testCreateTicket()">🎫 Create Ticket</button>
            <div class="spinner" id="spinner_create_ticket"></div>
            <div class="response-box" id="response_create_ticket"></div>
        </div>

        <!-- 6. LIST TICKETS -->
        <div class="endpoint-section">
            <div class="endpoint-title">
                <span class="method-badge get">GET</span>
                <span>6. Get All Tickets (Role-Based)</span>
            </div>
            <div class="endpoint-url">http://localhost/it-sahayata-api/api/tickets/list.php</div>
            <div class="api-info">📋 Customer sees own tickets | Agent sees assigned tickets | Admin sees all</div>
            
            <label>Authorization Token:</label>
            <input type="text" id="list_token" placeholder="Paste JWT token" class="token-field">
            
            <button class="test-btn" onclick="testListTickets()">📋 Get Tickets List</button>
            <div class="spinner" id="spinner_list"></div>
            <div class="response-box" id="response_list"></div>
        </div>

        <!-- 7. TICKET DETAIL -->
        <div class="endpoint-section">
            <div class="endpoint-title">
                <span class="method-badge get">GET</span>
                <span>7. Get Ticket Details</span>
            </div>
            <div class="endpoint-url">http://localhost/it-sahayata-api/api/tickets/detail.php?ticket_id=X</div>
            <div class="api-info">🔍 Get complete details of a specific ticket</div>
            
            <label>Authorization Token:</label>
            <input type="text" id="detail_token" placeholder="Paste JWT token" class="token-field">
            
            <label>Ticket ID:</label>
            <input type="number" id="detail_ticket_id" placeholder="Enter ticket ID (e.g., 1)">
            
            <button class="test-btn" onclick="testTicketDetail()">🔍 Get Ticket Detail</button>
            <div class="spinner" id="spinner_detail"></div>
            <div class="response-box" id="response_detail"></div>
        </div>

        <!-- 8. UPDATE STATUS -->
        <div class="endpoint-section">
            <div class="endpoint-title">
                <span class="method-badge put">PUT</span>
                <span>8. Update Ticket Status (Agent/Admin Only)</span>
            </div>
            <div class="endpoint-url">http://localhost/it-sahayata-api/api/tickets/update_status.php</div>
            <div class="api-info">🔄 Agent/Admin updates ticket status</div>
            
            <label>Authorization Token:</label>
            <input type="text" id="update_token" placeholder="Paste agent/admin JWT token" class="token-field">
            
            <label>Ticket ID:</label>
            <input type="number" id="update_ticket_id" placeholder="Enter ticket ID">
            
            <label>New Status:</label>
            <select id="update_status">
                <option value="assigned">Assigned</option>
                <option value="in_progress">In Progress</option>
                <option value="resolved">Resolved</option>
                <option value="closed">Closed</option>
            </select>
            
            <button class="test-btn" onclick="testUpdateStatus()">🔄 Update Status</button>
            <div class="spinner" id="spinner_update"></div>
            <div class="response-box" id="response_update"></div>
        </div>

        <!-- ==================== MESSAGE APIS ==================== -->

        <!-- 9. SEND MESSAGE -->
        <div class="endpoint-section">
            <div class="endpoint-title">
                <span class="method-badge post">POST</span>
                <span>9. Send Message (Chat)</span>
            </div>
            <div class="endpoint-url">http://localhost/it-sahayata-api/api/messages/send.php</div>
            <div class="api-info">💬 Send message in ticket chat (Customer ↔️ Agent)</div>
            
            <label>Authorization Token:</label>
            <input type="text" id="msg_token" placeholder="Paste JWT token" class="token-field">
            
            <label>Ticket ID:</label>
            <input type="number" id="msg_ticket_id" placeholder="Enter ticket ID">
            
            <label>Message:</label>
            <textarea id="msg_content" rows="3" placeholder="Type your message..."></textarea>
            
            <button class="test-btn" onclick="testSendMessage()">💬 Send Message</button>
            <div class="spinner" id="spinner_message"></div>
            <div class="response-box" id="response_message"></div>
        </div>

        <!-- 10. GET MESSAGES -->
        <div class="endpoint-section">
            <div class="endpoint-title">
                <span class="method-badge get">GET</span>
                <span>10. Get Messages (Chat History)</span>
            </div>
            <div class="endpoint-url">http://localhost/it-sahayata-api/api/messages/get_messages.php?ticket_id=X</div>
            <div class="api-info">📜 Get all messages for a ticket</div>
            
            <label>Authorization Token:</label>
            <input type="text" id="get_msg_token" placeholder="Paste JWT token" class="token-field">
            
            <label>Ticket ID:</label>
            <input type="number" id="get_msg_ticket_id" placeholder="Enter ticket ID">
            
            <button class="test-btn" onclick="testGetMessages()">📜 Get Chat History</button>
            <div class="spinner" id="spinner_get_msg"></div>
            <div class="response-box" id="response_get_msg"></div>
        </div>

        <!-- 11. UPLOAD FILE -->
        <div class="endpoint-section">
            <div class="endpoint-title">
                <span class="method-badge post">POST</span>
                <span>11. Upload File (Image/PDF/Video)</span>
            </div>
            <div class="endpoint-url">http://localhost/it-sahayata-api/api/messages/upload_file.php</div>
            <div class="api-info">📎 Upload file (Max 10MB - JPG, PNG, PDF, MP4)</div>
            
            <label>Authorization Token:</label>
            <input type="text" id="upload_token" placeholder="Paste JWT token" class="token-field">
            
            <label>Upload Type:</label>
            <select id="upload_type">
                <option value="chat">Chat Attachment</option>
                <option value="ticket">Ticket Attachment</option>
            </select>
            
            <label>Choose File:</label>
            <input type="file" id="upload_file" accept="image/*,.pdf,video/mp4">
            
            <button class="test-btn" onclick="testUploadFile()">📎 Upload File</button>
            <div class="spinner" id="spinner_upload"></div>
            <div class="response-box" id="response_upload"></div>
        </div>

        <!-- ==================== FEEDBACK API ==================== -->

        <!-- 12. SUBMIT FEEDBACK -->
        <div class="endpoint-section">
            <div class="endpoint-title">
                <span class="method-badge post">POST</span>
                <span>12. Submit Feedback (Customer Only)</span>
            </div>
            <div class="endpoint-url">http://localhost/it-sahayata-api/api/feedback/submit.php</div>
            <div class="api-info">⭐ Customer submits rating for resolved ticket</div>
            
            <label>Authorization Token:</label>
            <input type="text" id="feedback_token" placeholder="Paste customer JWT token" class="token-field">
            
            <label>Ticket ID (Must be resolved):</label>
            <input type="number" id="feedback_ticket_id" placeholder="Enter resolved ticket ID">
            
            <label>Rating (1-5 stars):</label>
            <select id="feedback_rating">
                <option value="5">⭐⭐⭐⭐⭐ Excellent</option>
                <option value="4">⭐⭐⭐⭐ Good</option>
                <option value="3">⭐⭐⭐ Average</option>
                <option value="2">⭐⭐ Poor</option>
                <option value="1">⭐ Very Poor</option>
            </select>
            
            <label>Comment (Optional):</label>
            <textarea id="feedback_comment" rows="3" placeholder="Share your experience..."></textarea>
            
            <button class="test-btn" onclick="testSubmitFeedback()">⭐ Submit Feedback</button>
            <div class="spinner" id="spinner_feedback"></div>
            <div class="response-box" id="response_feedback"></div>
        </div>

        <!-- ==================== AGENT APIS ==================== -->

        <!-- 13. ASSIGNED TICKETS -->
        <div class="endpoint-section">
            <div class="endpoint-title">
                <span class="method-badge get">GET</span>
                <span>13. Get Assigned Tickets (Agent Only)</span>
            </div>
            <div class="endpoint-url">http://localhost/it-sahayata-api/api/agent/assigned_tickets.php</div>
            <div class="api-info">👨‍💼 Agent gets all tickets assigned to them with statistics</div>
            
            <label>Authorization Token:</label>
            <input type="text" id="agent_token" placeholder="Paste agent JWT token" class="token-field">
            
            <button class="test-btn" onclick="testAssignedTickets()">👨‍💼 Get My Tickets</button>
            <div class="spinner" id="spinner_agent"></div>
            <div class="response-box" id="response_agent"></div>
        </div>

        <!-- 14. UPDATE RESOLUTION -->
        <div class="endpoint-section">
            <div class="endpoint-title">
                <span class="method-badge post">POST</span>
                <span>14. Update Resolution (Agent Only)</span>
            </div>
            <div class="endpoint-url">http://localhost/it-sahayata-api/api/agent/update_resolution.php</div>
            <div class="api-info">🔧 Agent adds resolution notes and marks ticket as resolved</div>
            
            <label>Authorization Token:</label>
            <input type="text" id="resolution_token" placeholder="Paste agent JWT token" class="token-field">
            
            <label>Ticket ID:</label>
            <input type="number" id="resolution_ticket_id" placeholder="Enter ticket ID">
            
            <label>Resolution Notes:</label>
            <textarea id="resolution_notes" rows="4" placeholder="Explain how you fixed the issue..."></textarea>
            
            <button class="test-btn" onclick="testUpdateResolution()">🔧 Mark as Resolved</button>
            <div class="spinner" id="spinner_resolution"></div>
            <div class="response-box" id="response_resolution"></div>
        </div>

        <!-- ==================== SUMMARY ==================== -->

        <div class="note success-note">
            <strong>🎉 Testing Complete Checklist:</strong><br>
            1. ✅ Register a customer and an agent<br>
            2. ✅ Verify both emails with OTP<br>
            3. ✅ Login as customer → Create 2-3 tickets<br>
            4. ✅ Login as agent → Check assigned tickets (Admin needs to assign via web panel)<br>
            5. ✅ Test chat → Send messages between customer and agent<br>
            6. ✅ Upload files in chat<br>
            7. ✅ Agent marks ticket as resolved<br>
            8. ✅ Customer submits feedback<br>
            9. ✅ Test logout<br><br>
            <strong>Once all tests pass, we'll build the Flutter app! 🚀</strong>
        </div>
    </div>

    <script>
        let totalTests = 0;
        let successTests = 0;
        let failedTests = 0;

        // Update statistics
        function updateStats(success) {
            totalTests++;
            if (success) {
                successTests++;
            } else {
                failedTests++;
            }
            
            document.getElementById('total-tests').textContent = totalTests;
            document.getElementById('success-tests').textContent = successTests;
            document.getElementById('failed-tests').textContent = failedTests;
            document.getElementById('pending-tests').textContent = 16 - totalTests;
        }

        // Show/hide spinner
        function toggleSpinner(id, show) {
            document.getElementById('spinner_' + id).style.display = show ? 'block' : 'none';
        }

        // Display response with proper formatting
        function displayResponse(boxId, data, isError = false) {
            const box = document.getElementById('response_' + boxId);
            box.textContent = JSON.stringify(data, null, 2);
            box.classList.add('show');
            
            if (isError) {
                box.classList.add('error');
                box.classList.remove('success');
            } else {
                box.classList.add('success');
                box.classList.remove('error');
            }
        }

        // Copy token to clipboard
        function copyToken() {
            const token = document.getElementById('saved_token');
            token.select();
            document.execCommand('copy');
            alert('✅ Token copied to clipboard!');
        }

        // Auto-fill token to all fields
        function autoFillTokens() {
            const token = document.getElementById('saved_token').value;
            const tokenFields = document.querySelectorAll('.token-field');
            tokenFields.forEach(field => {
                field.value = token;
            });
            alert('✅ Token auto-filled in all API fields!');
        }

        // ==================== API TEST FUNCTIONS ====================

        // 1. Register
        async function testRegister() {
            const data = {
                name: document.getElementById('reg_name').value,
                email: document.getElementById('reg_email').value,
                phone: document.getElementById('reg_phone').value,
                password: document.getElementById('reg_password').value,
                role: document.getElementById('reg_role').value
            };

            if (!data.name || !data.email || !data.phone || !data.password) {
                alert('❌ Please fill all fields!');
                return;
            }

            toggleSpinner('register', true);

            try {
                const res = await fetch('api/auth/register.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });

                const result = await res.json();
                displayResponse('register', result, !res.ok);
                updateStats(res.ok);

                if (res.ok) {
                    document.getElementById('verify_email').value = data.email;
                }
            } catch (err) {
                displayResponse('register', { error: err.message }, true);
                updateStats(false);
            } finally {
                toggleSpinner('register', false);
            }
        }

        // 2. Verify OTP
        async function testVerifyOTP() {
            const data = {
                email: document.getElementById('verify_email').value,
                otp: document.getElementById('verify_otp').value
            };

            if (!data.email || !data.otp) {
                alert('❌ Please fill all fields!');
                return;
            }

            toggleSpinner('verify', true);

            try {
                const res = await fetch('api/auth/verify_otp.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });

                const result = await res.json();
                displayResponse('verify', result, !res.ok);
                updateStats(res.ok);

                if (res.ok) {
                    document.getElementById('login_email').value = data.email;
                }
            } catch (err) {
                displayResponse('verify', { error: err.message }, true);
                updateStats(false);
            } finally {
                toggleSpinner('verify', false);
            }
        }

        // 3. Login
        async function testLogin() {
            const data = {
                email: document.getElementById('login_email').value,
                password: document.getElementById('login_password').value
            };

            if (!data.email || !data.password) {
                alert('❌ Please fill all fields!');
                return;
            }

            toggleSpinner('login', true);

            try {
                const res = await fetch('api/auth/login.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify(data)
                });

                const result = await res.json();
                displayResponse('login', result, !res.ok);
                updateStats(res.ok);

                if (res.ok && result.data && result.data.token) {
                    document.getElementById('saved_token').value = result.data.token;
                    document.getElementById('token_display').classList.add('show');
                }
            } catch (err) {
                displayResponse('login', { error: err.message }, true);
                updateStats(false);
            } finally {
                toggleSpinner('login', false);
            }
        }

        // 4. Logout
        async function testLogout() {
            const token = document.getElementById('logout_token').value;

            if (!token) {
                alert('❌ Please provide token!');
                return;
            }

            toggleSpinner('logout', true);

            try {
                const res = await fetch('api/auth/logout.php', {
                    method: 'POST',
                    headers: { 'Authorization': 'Bearer ' + token }
                });

                const result = await res.json();
                displayResponse('logout', result, !res.ok);
                updateStats(res.ok);
            } catch (err) {
                displayResponse('logout', { error: err.message }, true);
                updateStats(false);
            } finally {
                toggleSpinner('logout', false);
            }
        }

        // 5. Create Ticket
        async function testCreateTicket() {
            const token = document.getElementById('create_ticket_token').value;
            const data = {
                category: document.getElementById('ticket_category').value,
                description: document.getElementById('ticket_desc').value,
                priority: document.getElementById('ticket_priority').value
            };

            if (!token || !data.description) {
                alert('❌ Please provide token and description!');
                return;
            }

            toggleSpinner('create_ticket', true);

            try {
                const res = await fetch('api/tickets/create.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer ' + token
                    },
                    body: JSON.stringify(data)
                });

                const result = await res.json();
                displayResponse('create_ticket', result, !res.ok);
                updateStats(res.ok);
            } catch (err) {
                displayResponse('create_ticket', { error: err.message }, true);
                updateStats(false);
            } finally {
                toggleSpinner('create_ticket', false);
            }
        }

        // 6. List Tickets
        async function testListTickets() {
            const token = document.getElementById('list_token').value;

            if (!token) {
                alert('❌ Please provide token!');
                return;
            }

            toggleSpinner('list', true);

            try {
                const res = await fetch('api/tickets/list.php', {
                    method: 'GET',
                    headers: { 'Authorization': 'Bearer ' + token }
                });

                const result = await res.json();
                displayResponse('list', result, !res.ok);
                updateStats(res.ok);
            } catch (err) {
                displayResponse('list', { error: err.message }, true);
                updateStats(false);
            } finally {
                toggleSpinner('list', false);
            }
        }

        // 7. Ticket Detail
        async function testTicketDetail() {
            const token = document.getElementById('detail_token').value;
            const ticketId = document.getElementById('detail_ticket_id').value;

            if (!token || !ticketId) {
                alert('❌ Please provide token and ticket ID!');
                return;
            }

            toggleSpinner('detail', true);

            try {
                const res = await fetch(`api/tickets/detail.php?ticket_id=${ticketId}`, {
                    method: 'GET',
                    headers: { 'Authorization': 'Bearer ' + token }
                });

                const result = await res.json();
                displayResponse('detail', result, !res.ok);
                updateStats(res.ok);
            } catch (err) {
                displayResponse('detail', { error: err.message }, true);
                updateStats(false);
            } finally {
                toggleSpinner('detail', false);
            }
        }

        // 8. Update Status
        async function testUpdateStatus() {
            const token = document.getElementById('update_token').value;
            const data = {
                ticket_id: document.getElementById('update_ticket_id').value,
                status: document.getElementById('update_status').value
            };

            if (!token || !data.ticket_id) {
                alert('❌ Please provide token and ticket ID!');
                return;
            }

            toggleSpinner('update', true);

            try {
                const res = await fetch('api/tickets/update_status.php', {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer ' + token
                    },
                    body: JSON.stringify(data)
                });

                const result = await res.json();
                displayResponse('update', result, !res.ok);
                updateStats(res.ok);
            } catch (err) {
                displayResponse('update', { error: err.message }, true);
                updateStats(false);
            } finally {
                toggleSpinner('update', false);
            }
        }

        // 9. Send Message
        async function testSendMessage() {
            const token = document.getElementById('msg_token').value;
            const data = {
                ticket_id: document.getElementById('msg_ticket_id').value,
                message: document.getElementById('msg_content').value
            };

            if (!token || !data.ticket_id || !data.message) {
                alert('❌ Please fill all fields!');
                return;
            }

            toggleSpinner('message', true);

            try {
                const res = await fetch('api/messages/send.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer ' + token
                    },
                    body: JSON.stringify(data)
                });

                const result = await res.json();
                displayResponse('message', result, !res.ok);
                updateStats(res.ok);

                if (res.ok) {
                    document.getElementById('msg_content').value = '';
                }
            } catch (err) {
                displayResponse('message', { error: err.message }, true);
                updateStats(false);
            } finally {
                toggleSpinner('message', false);
            }
        }

        // 10. Get Messages
        async function testGetMessages() {
            const token = document.getElementById('get_msg_token').value;
            const ticketId = document.getElementById('get_msg_ticket_id').value;

            if (!token || !ticketId) {
                alert('❌ Please provide token and ticket ID!');
                return;
            }

            toggleSpinner('get_msg', true);

            try {
                const res = await fetch(`api/messages/get_messages.php?ticket_id=${ticketId}`, {
                    method: 'GET',
                    headers: { 'Authorization': 'Bearer ' + token }
                });

                const result = await res.json();
                displayResponse('get_msg', result, !res.ok);
                updateStats(res.ok);
            } catch (err) {
                displayResponse('get_msg', { error: err.message }, true);
                updateStats(false);
            } finally {
                toggleSpinner('get_msg', false);
            }
        }

        // 11. Upload File
        async function testUploadFile() {
            const token = document.getElementById('upload_token').value;
            const fileInput = document.getElementById('upload_file');
            const uploadType = document.getElementById('upload_type').value;

            if (!token || !fileInput.files[0]) {
                alert('❌ Please provide token and select a file!');
                return;
            }

            const formData = new FormData();
            formData.append('file', fileInput.files[0]);
            formData.append('type', uploadType);

            toggleSpinner('upload', true);

            try {
                const res = await fetch('api/messages/upload_file.php', {
                    method: 'POST',
                    headers: { 'Authorization': 'Bearer ' + token },
                    body: formData
                });

                const result = await res.json();
                displayResponse('upload', result, !res.ok);
                updateStats(res.ok);
            } catch (err) {
                displayResponse('upload', { error: err.message }, true);
                updateStats(false);
            } finally {
                toggleSpinner('upload', false);
            }
        }

        // 12. Submit Feedback
        async function testSubmitFeedback() {
            const token = document.getElementById('feedback_token').value;
            const data = {
                ticket_id: document.getElementById('feedback_ticket_id').value,
                rating: parseInt(document.getElementById('feedback_rating').value),
                comment: document.getElementById('feedback_comment').value
            };

            if (!token || !data.ticket_id) {
                alert('❌ Please provide token and ticket ID!');
                return;
            }

            toggleSpinner('feedback', true);

            try {
                const res = await fetch('api/feedback/submit.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer ' + token
                    },
                    body: JSON.stringify(data)
                });

                const result = await res.json();
                displayResponse('feedback', result, !res.ok);
                updateStats(res.ok);
            } catch (err) {
                displayResponse('feedback', { error: err.message }, true);
                updateStats(false);
            } finally {
                toggleSpinner('feedback', false);
            }
        }

        // 13. Assigned Tickets (Agent)
        async function testAssignedTickets() {
            const token = document.getElementById('agent_token').value;

            if (!token) {
                alert('❌ Please provide agent token!');
                return;
            }

            toggleSpinner('agent', true);

            try {
                const res = await fetch('api/agent/assigned_tickets.php', {
                    method: 'GET',
                    headers: { 'Authorization': 'Bearer ' + token }
                });

                const result = await res.json();
                displayResponse('agent', result, !res.ok);
                updateStats(res.ok);
            } catch (err) {
                displayResponse('agent', { error: err.message }, true);
                updateStats(false);
            } finally {
                toggleSpinner('agent', false);
            }
        }

        // 14. Update Resolution (Agent)
        async function testUpdateResolution() {
            const token = document.getElementById('resolution_token').value;
            const data = {
                ticket_id: document.getElementById('resolution_ticket_id').value,
                resolution: document.getElementById('resolution_notes').value
            };

            if (!token || !data.ticket_id || !data.resolution) {
                alert('❌ Please fill all fields!');
                return;
            }

            toggleSpinner('resolution', true);

            try {
                const res = await fetch('api/agent/update_resolution.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Authorization': 'Bearer ' + token
                    },
                    body: JSON.stringify(data)
                });

                const result = await res.json();
                displayResponse('resolution', result, !res.ok);
                updateStats(res.ok);
            } catch (err) {
                displayResponse('resolution', { error: err.message }, true);
                updateStats(false);
            } finally {
                toggleSpinner('resolution', false);
            }
        }
    </script>
</body>
</html>
