<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เข้าสู่ระบบ - กายสิริ คลินิกกายภาพบำบัด</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Sarabun:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        :root {
            /* Sky Blue Palette */
            --sky-50: #f0f9ff;
            --sky-100: #e0f2fe;
            --sky-200: #bae6fd;
            --sky-300: #7dd3fc;
            --sky-500: #0ea5e9;
            --sky-600: #0284c7;

            /* Ocean Blue Palette */
            --ocean-400: #60a5fa;
            --ocean-500: #3b82f6;
            --ocean-600: #2563eb;

            /* Navy Blue Palette */
            --navy-500: #1e40af;
            --navy-600: #1e3a8a;
            --navy-700: #1e3a8a;
            --navy-800: #1e2a5e;
            --navy-900: #0f172a;

            /* White */
            --white: #ffffff;
            --white-95: rgba(255, 255, 255, 0.95);
        }

        body {
            font-family: 'Sarabun', sans-serif;
            background: linear-gradient(135deg, var(--ocean-500) 0%, var(--navy-600) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            position: relative;
            overflow: hidden;
        }

        /* Background pattern */
        body::before {
            content: '';
            position: absolute;
            width: 200%;
            height: 200%;
            background-image:
                radial-gradient(circle at 20% 80%, var(--sky-500) 0%, transparent 50%),
                radial-gradient(circle at 80% 20%, var(--ocean-600) 0%, transparent 50%),
                radial-gradient(circle at 40% 40%, var(--navy-600) 0%, transparent 50%);
            opacity: 0.1;
            animation: float 20s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translate(-50%, -50%) rotate(0deg); }
            50% { transform: translate(-50%, -50%) rotate(180deg); }
        }

        .login-container {
            background: var(--white);
            padding: 50px 40px;
            border-radius: 20px;
            box-shadow:
                0 20px 60px rgba(30, 58, 138, 0.15),
                0 0 0 1px rgba(30, 58, 138, 0.05);
            width: 100%;
            max-width: 420px;
            position: relative;
            z-index: 1;
            backdrop-filter: blur(10px);
        }

        /* Top accent bar */
        .login-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, var(--sky-500), var(--ocean-500), var(--navy-600));
            border-radius: 20px 20px 0 0;
        }

        .logo-section {
            text-align: center;
            margin-bottom: 35px;
        }

        .logo-img {
            width: 100px;
            height: 100px;
            margin: 0 auto 15px;
            filter: drop-shadow(0 4px 6px rgba(30, 58, 138, 0.1));
            animation: pulse 2s ease-in-out infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }

        h1 {
            color: var(--navy-700);
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 5px;
            letter-spacing: -0.5px;
        }

        .subtitle {
            color: var(--ocean-600);
            font-size: 16px;
            font-weight: 400;
            letter-spacing: 0.5px;
        }

        .form-group {
            margin-bottom: 24px;
        }

        label {
            display: block;
            color: var(--navy-700);
            font-weight: 500;
            margin-bottom: 8px;
            font-size: 15px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid var(--sky-200);
            border-radius: 10px;
            font-size: 15px;
            font-family: 'Sarabun', sans-serif;
            transition: all 0.3s ease;
            background: var(--white);
        }

        input:focus {
            outline: none;
            border-color: var(--ocean-500);
            box-shadow: 0 0 0 4px rgba(59, 130, 246, 0.1);
            background: var(--sky-50);
        }

        input::placeholder {
            color: #94a3b8;
        }

        .error {
            color: #ef4444;
            font-size: 14px;
            margin-top: 5px;
        }

        .remember-me {
            display: flex;
            align-items: center;
            margin-bottom: 24px;
        }

        .remember-me input[type="checkbox"] {
            width: 18px;
            height: 18px;
            margin-right: 10px;
            cursor: pointer;
            accent-color: var(--ocean-500);
        }

        .remember-me label {
            margin: 0;
            font-weight: 400;
            cursor: pointer;
            color: var(--navy-600);
        }

        .btn {
            width: 100%;
            padding: 15px;
            background: linear-gradient(135deg, var(--sky-500) 0%, var(--ocean-600) 100%);
            color: white;
            border: none;
            border-radius: 10px;
            font-size: 17px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
            font-family: 'Sarabun', sans-serif;
            letter-spacing: 0.5px;
            position: relative;
            overflow: hidden;
        }

        .btn::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
            transition: left 0.5s;
        }

        .btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(37, 99, 235, 0.3);
        }

        .btn:hover::before {
            left: 100%;
        }

        .credentials-info {
            margin-top: 25px;
            padding: 16px;
            background: linear-gradient(135deg, var(--sky-50) 0%, var(--sky-100) 100%);
            border-left: 4px solid var(--ocean-500);
            border-radius: 8px;
            font-size: 14px;
            color: var(--navy-600);
        }

        .credentials-info strong {
            color: var(--navy-800);
        }

        .footer-text {
            text-align: center;
            margin-top: 25px;
            padding-top: 20px;
            border-top: 1px solid var(--sky-100);
            color: var(--ocean-600);
            font-size: 13px;
        }

        /* Responsive adjustments */
        @media (max-width: 480px) {
            .login-container {
                padding: 40px 25px;
            }

            h1 {
                font-size: 24px;
            }

            .logo-img {
                width: 80px;
                height: 80px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <div class="logo-section">
            @php
                $logoPath = public_path('pic/LOGO-PNG-01.png');
                $logoUrl = file_exists($logoPath) ? asset('pic/LOGO-PNG-01.png') : asset('images/logo.png');
            @endphp
            <img src="{{ $logoUrl }}" alt="กายสิริ คลินิก" class="logo-img">
            <h1>กายสิริ</h1>
            <p class="subtitle">คลินิกกายภาพบำบัด</p>
        </div>

        @if ($errors->any())
            <div class="error" style="margin-bottom: 20px; padding: 10px; background: #fee; border-radius: 5px;">
                @foreach ($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" autocomplete="off">
            @csrf

            <div class="form-group">
                <label for="username">ชื่อผู้ใช้</label>
                <input
                    type="text"
                    id="username"
                    name="username"
                    value="{{ old('username') }}"
                    placeholder="กรอกชื่อผู้ใช้"
                    required
                    autofocus
                >
            </div>

            <div class="form-group">
                <label for="password">รหัสผ่าน</label>
                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="กรอกรหัสผ่าน"
                    required
                >
            </div>

            <div class="remember-me">
                <input type="checkbox" id="remember" name="remember">
                <label for="remember">จดจำการเข้าสู่ระบบ</label>
            </div>

            <button type="submit" class="btn">เข้าสู่ระบบ</button>
        </form>

        <div class="credentials-info">
            <strong>ข้อมูลเข้าสู่ระบบทดสอบ:</strong><br>
            ชื่อผู้ใช้: <strong>admin</strong><br>
            รหัสผ่าน: <strong>password</strong>
        </div>

        <div class="footer-text">
            © 2024 กายสิริ คลินิกกายภาพบำบัด - ระบบจัดการคลินิก
        </div>
    </div>
</body>
</html>
