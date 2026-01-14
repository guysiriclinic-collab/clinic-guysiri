<!DOCTYPE html>
<html lang="th">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>เลือกสาขา - กายสิริคลินิก</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        :root {
            /* Blue Theme Colors (ตรงกับระบบหลัก) */
            --theme-ocean-50: #f0f9ff;
            --theme-ocean-100: #e0f2fe;
            --theme-ocean-200: #bae6fd;
            --theme-ocean-300: #7dd3fc;
            --theme-ocean-400: #38bdf8;
            --theme-ocean-500: #0ea5e9;
            --theme-ocean-600: #0284c7;
            --theme-ocean-700: #0369a1;
            --theme-sky-50: #f0f9ff;
            --theme-sky-100: #e0f2fe;
            --theme-sky-500: #3b82f6;
            --theme-sky-600: #2563eb;
            --theme-navy-600: #1e40af;
            --theme-navy-700: #1e3a8a;
            --theme-navy-800: #1e293b;
        }

        body {
            background: linear-gradient(135deg, var(--theme-sky-500) 0%, var(--theme-ocean-600) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            font-family: 'Sarabun', sans-serif;
        }

        .container-custom {
            max-width: 1200px;
            width: 100%;
        }

        .header-card {
            background: white;
            border-radius: 15px;
            padding: 35px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(30, 58, 138, 0.2);
            text-align: center;
            border: 2px solid var(--theme-ocean-200);
        }

        .header-card h1 {
            color: var(--theme-navy-700);
            font-weight: 700;
            margin-bottom: 15px;
            font-size: 32px;
        }

        .header-card p {
            color: var(--theme-navy-600);
            font-size: 18px;
            margin-bottom: 0;
        }

        .branch-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin-top: 30px;
        }

        .branch-card {
            background: white;
            border-radius: 15px;
            padding: 30px;
            text-align: center;
            box-shadow: 0 4px 12px rgba(30, 58, 138, 0.1);
            transition: all 0.3s ease;
            cursor: pointer;
            border: 2px solid var(--theme-ocean-200);
        }

        .branch-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 12px 24px rgba(30, 58, 138, 0.2);
            border-color: var(--theme-ocean-500);
        }

        .branch-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, var(--theme-sky-500) 0%, var(--theme-ocean-600) 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 40px;
            color: white;
            box-shadow: 0 4px 8px rgba(37, 99, 235, 0.3);
        }

        .branch-name {
            font-size: 22px;
            font-weight: 700;
            color: var(--theme-navy-700);
            margin-bottom: 12px;
        }

        .branch-info {
            color: var(--theme-navy-600);
            font-size: 14px;
            margin-bottom: 15px;
        }

        .branch-info i {
            color: var(--theme-ocean-500);
        }

        .btn-select {
            background: linear-gradient(135deg, var(--theme-sky-500) 0%, var(--theme-ocean-600) 100%);
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 10px;
            font-weight: 600;
            font-size: 15px;
            transition: all 0.3s ease;
            width: 100%;
            box-shadow: 0 2px 4px rgba(37, 99, 235, 0.2);
        }

        .btn-select:hover {
            background: linear-gradient(135deg, var(--theme-sky-600) 0%, var(--theme-ocean-700) 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(37, 99, 235, 0.3);
            color: white;
        }

        .user-info {
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(10px);
            border-radius: 12px;
            padding: 20px 25px;
            color: white;
            text-align: center;
            margin-bottom: 25px;
            border: 1px solid rgba(255,255,255,0.2);
        }

        .user-info h5 {
            font-weight: 600;
            margin-bottom: 8px;
        }

        .user-info p {
            margin-bottom: 12px;
            opacity: 0.95;
        }

        .logout-btn {
            background: rgba(255,255,255,0.2);
            color: white;
            border: 2px solid rgba(255,255,255,0.5);
            padding: 8px 20px;
            border-radius: 8px;
            font-weight: 500;
            margin-top: 5px;
            transition: all 0.3s ease;
        }

        .logout-btn:hover {
            background: white;
            color: var(--theme-ocean-600);
            border-color: white;
        }
    </style>
</head>
<body>
    <div class="container-custom">
        <!-- User Info -->
        <div class="user-info">
            <h5 class="mb-2">
                <i class="bi bi-person-circle me-2"></i>
                ยินดีต้อนรับ, <strong>{{ auth()->user()->name }}</strong>
            </h5>
            <p class="mb-2">กรุณาเลือกสาขาที่ต้องการเข้าทำงาน</p>
            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                @csrf
                <button type="submit" class="logout-btn">
                    <i class="bi bi-box-arrow-right me-1"></i>ออกจากระบบ
                </button>
            </form>
        </div>

        <!-- Header -->
        <div class="header-card">
            <h1><i class="bi bi-building me-2"></i>เลือกสาขาที่ต้องการจัดการ</h1>
            <p>คลิกที่การ์ดสาขาเพื่อเข้าสู่ระบบจัดการสาขานั้น</p>
        </div>

        <!-- Branch Cards -->
        <div class="branch-grid">
            @foreach($branches as $branch)
            <div class="branch-card" onclick="selectBranch('{{ $branch->id }}')">
                <div class="branch-icon">
                    <i class="bi bi-building"></i>
                </div>
                <div class="branch-name">{{ $branch->name }}</div>
                <div class="branch-info">
                    <i class="bi bi-geo-alt me-1"></i>{{ $branch->address ?? 'ไม่ระบุที่อยู่' }}
                </div>
                <div class="branch-info">
                    <i class="bi bi-telephone me-1"></i>{{ $branch->phone ?? 'ไม่ระบุเบอร์โทร' }}
                </div>
                <form method="POST" action="{{ route('branch.switch') }}" id="branch-form-{{ $branch->id }}">
                    @csrf
                    <input type="hidden" name="branch_id" value="{{ $branch->id }}">
                    <button type="submit" class="btn btn-select">
                        <i class="bi bi-check-circle me-2"></i>เลือกสาขานี้
                    </button>
                </form>
            </div>
            @endforeach
        </div>
    </div>

    <script>
        function selectBranch(branchId) {
            document.getElementById('branch-form-' + branchId).submit();
        }
    </script>
</body>
</html>
