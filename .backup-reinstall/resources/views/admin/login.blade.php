<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Admin Login - Voucher System</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * { 
            margin: 0; 
            padding: 0; 
            box-sizing: border-box; 
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background: linear-gradient(135deg, #1e3a8a 0%, #3b82f6 50%, #06b6d4 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }
        
        /* Animated Background */
        body::before {
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 200%;
            height: 200%;
            background: radial-gradient(circle, rgba(59, 130, 246, 0.1) 1px, transparent 1px);
            background-size: 40px 40px;
            animation: backgroundMove 20s linear infinite;
        }
        
        @keyframes backgroundMove {
            0% { transform: translate(0, 0); }
            100% { transform: translate(40px, 40px); }
        }
        
        .login-wrapper {
            position: relative;
            z-index: 1;
            width: 100%;
            max-width: 450px;
        }
        
        .login-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border-radius: 20px;
            box-shadow: 0 25px 70px rgba(0, 0, 0, 0.3);
            padding: 50px 40px;
            position: relative;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        /* Logo Section */
        .logo {
            text-align: center;
            margin-bottom: 35px;
        }
        
        .logo-icon {
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
            width: 80px;
            height: 80px;
            border-radius: 20px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 36px;
            margin-bottom: 15px;
            box-shadow: 0 10px 30px rgba(59, 130, 246, 0.4);
            animation: logoFloat 3s ease-in-out infinite;
        }
        
        @keyframes logoFloat {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        
        h1 {
            color: #1e293b;
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 8px;
            text-align: center;
        }
        
        .subtitle {
            color: #64748b;
            font-size: 14px;
            text-align: center;
            margin-bottom: 5px;
        }
        
        .system-status {
            display: inline-flex;
            align-items: center;
            background: #dcfce7;
            color: #16a34a;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 10px;
        }
        
        .system-status::before {
            content: '';
            width: 6px;
            height: 6px;
            background: #16a34a;
            border-radius: 50%;
            margin-right: 6px;
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.3; }
        }
        
        /* Alerts */
        .alert {
            padding: 14px 16px;
            border-radius: 10px;
            margin-bottom: 24px;
            font-size: 14px;
            display: flex;
            align-items: start;
            gap: 12px;
            animation: slideDown 0.3s ease;
        }
        
        @keyframes slideDown {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .alert-error {
            background: #fee2e2;
            color: #dc2626;
            border-left: 4px solid #dc2626;
        }
        
        .alert-success {
            background: #dcfce7;
            color: #16a34a;
            border-left: 4px solid #16a34a;
        }
        
        .alert i {
            font-size: 18px;
            margin-top: 1px;
        }
        
        /* Form Groups */
        .form-group {
            margin-bottom: 24px;
            position: relative;
        }
        
        label {
            display: block;
            color: #334155;
            font-weight: 600;
            margin-bottom: 10px;
            font-size: 14px;
        }
        
        .input-wrapper {
            position: relative;
        }
        
        .input-icon {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94a3b8;
            font-size: 18px;
            pointer-events: none;
            transition: color 0.3s;
        }
        
        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 14px 16px 14px 48px;
            border: 2px solid #e2e8f0;
            border-radius: 10px;
            font-size: 15px;
            background: white;
            transition: all 0.3s;
            font-family: inherit;
        }
        
        input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
        }
        
        input:focus + .input-icon {
            color: #3b82f6;
        }
        
        /* Password Toggle */
        .password-toggle {
            position: absolute;
            right: 16px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #94a3b8;
            cursor: pointer;
            font-size: 18px;
            padding: 4px;
            transition: color 0.3s;
        }
        
        .password-toggle:hover {
            color: #3b82f6;
        }
        
        /* Remember & Forgot */
        .form-footer {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 24px;
            font-size: 14px;
        }
        
        .checkbox-group {
            display: flex;
            align-items: center;
            cursor: pointer;
        }
        
        .checkbox-group input {
            width: 18px;
            height: 18px;
            margin-right: 8px;
            cursor: pointer;
            accent-color: #3b82f6;
        }
        
        .checkbox-group label {
            margin: 0;
            font-weight: 500;
            color: #475569;
            cursor: pointer;
        }
        
        /* Button */
        .btn-login {
            width: 100%;
            padding: 16px;
            background: linear-gradient(135deg, #3b82f6 0%, #1e40af 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4);
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .btn-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(59, 130, 246, 0.5);
        }
        
        .btn-login:active {
            transform: translateY(0);
        }
        
        .btn-login:disabled {
            opacity: 0.6;
            cursor: not-allowed;
            transform: none;
        }
        
        .btn-login .spinner {
            width: 18px;
            height: 18px;
            border: 2px solid rgba(255, 255, 255, 0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.8s linear infinite;
            display: none;
        }
        
        .btn-login.loading .spinner {
            display: block;
        }
        
        .btn-login.loading .btn-text {
            display: none;
        }
        
        @keyframes spin {
            to { transform: rotate(360deg); }
        }
        
        /* Back to Home Link */
        .back-link {
            text-align: center;
            margin-top: 24px;
        }
        
        .back-link a {
            color: #ffffff;
            text-decoration: none;
            font-weight: 600;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 10px 20px;
            background: rgba(255, 255, 255, 0.2);
            border-radius: 8px;
            transition: all 0.3s;
            backdrop-filter: blur(10px);
        }
        
        .back-link a:hover {
            background: rgba(255, 255, 255, 0.3);
            transform: translateY(-2px);
        }
        
        /* Footer */
        .footer {
            text-align: center;
            margin-top: 30px;
            color: rgba(255, 255, 255, 0.9);
            font-size: 13px;
        }
        
        /* Responsive */
        @media (max-width: 480px) {
            .login-container {
                padding: 40px 30px;
            }
            
            h1 {
                font-size: 24px;
            }
            
            .logo-icon {
                width: 70px;
                height: 70px;
                font-size: 32px;
            }
        }
    </style>
</head>
<body>
    <div class="login-wrapper">
        <div class="login-container">
            <div class="logo">
                <div class="logo-icon">
                    <i class="fas fa-shield-halved"></i>
                </div>
                <h1>Admin Dashboard</h1>
                <p class="subtitle">Sistem Manajemen Voucher Internet</p>
                <span class="system-status">
                    System Online
                </span>
            </div>

            @if (session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    <div>
                        <strong>Berhasil!</strong><br>
                        {{ session('success') }}
                    </div>
                </div>
            @endif

            @if ($errors->any())
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>
                        <strong>Login Gagal!</strong><br>
                        {{ $errors->first() }}
                    </div>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.post') }}" id="loginForm">
                @csrf
                
                <div class="form-group">
                    <label for="username">
                        <i class="fas fa-user"></i> Username
                    </label>
                    <div class="input-wrapper">
                        <input 
                            type="text" 
                            id="username" 
                            name="username" 
                            value="{{ old('username') }}"
                            placeholder="Masukkan username admin"
                            required
                            autofocus
                            autocomplete="username"
                        >
                        <i class="fas fa-user input-icon"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="password">
                        <i class="fas fa-lock"></i> Password
                    </label>
                    <div class="input-wrapper">
                        <input 
                            type="password" 
                            id="password" 
                            name="password" 
                            placeholder="Masukkan password"
                            required
                            autocomplete="current-password"
                        >
                        <i class="fas fa-lock input-icon"></i>
                        <button type="button" class="password-toggle" onclick="togglePassword()">
                            <i class="fas fa-eye" id="toggleIcon"></i>
                        </button>
                    </div>
                </div>

                <div class="form-footer">
                    <div class="checkbox-group">
                        <input type="checkbox" id="remember" name="remember">
                        <label for="remember">Ingat saya</label>
                    </div>
                </div>

                <button type="submit" class="btn-login" id="loginBtn">
                    <span class="spinner"></span>
                    <span class="btn-text">
                        <i class="fas fa-sign-in-alt"></i> Masuk Dashboard
                    </span>
                </button>
            </form>
        </div>

        <div class="back-link">
            <a href="/">
                <i class="fas fa-arrow-left"></i>
                Kembali ke Halaman Utama
            </a>
        </div>

        <div class="footer">
            <i class="fas fa-shield-alt"></i> Secure Admin Access<br>
            © {{ date('Y') }} Voucher System. All rights reserved.
        </div>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('toggleIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleIcon.classList.remove('fa-eye');
                toggleIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                toggleIcon.classList.remove('fa-eye-slash');
                toggleIcon.classList.add('fa-eye');
            }
        }

        // Form submission with loading state
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const btn = document.getElementById('loginBtn');
            btn.classList.add('loading');
            btn.disabled = true;
        });

        // Auto-focus on error
        @if ($errors->any())
            document.addEventListener('DOMContentLoaded', function() {
                document.getElementById('username').focus();
            });
        @endif
    </script>
</body>
</html>
