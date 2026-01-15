<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>帐户注册</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            background: #f9f9ff;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #f9f9ff, #e0e7ff);
            position: relative;
            overflow: hidden;
        }

        .container {
            text-align: center;
            position: relative;
        }

        .logo {
            margin-bottom: 20px;
        }

        .logo-img {
            width: 50px;
            height: 50px;
            vertical-align: middle;
        }

        .signup-box {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            width: 400px;
            text-align: left;
        }

        .signup-box h2 {
            font-size: 24px;
            color: #333;
            margin-bottom: 10px;
        }

        .subtitle {
            color: #666;
            font-size: 14px;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .name-fields {
            display: flex;
            gap: 10px;
        }

        input {
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            width: 100%;
        }

        .half-width {
            width: 48%;
        }

        label {
            font-size: 12px;
            color: #666;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        button {
            padding: 12px;
            border: none;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
        }

        button[type="submit"] {
            background: #7366ff;
            color: white;
            font-weight: bold;
        }

        .or-text {
            text-align: center;
            color: #666;
            margin: 20px 0;
            font-size: 14px;
        }

        .social-buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
        }

        .social-btn {
            width: 40px;
            height: 40px;
            border: 1px solid #ddd;
            border-radius: 50%;
            background: white;
            font-size: 16px;
            color: #666;
            cursor: pointer;
        }

        .social-btn.linkedin {
            color: #7366ff;
        }

        .social-btn.x {
            color: #000;
        }

        .social-btn.facebook {
            color: #7366ff;
        }

        .social-btn.google {
            color: #7366ff;
        }

        .signin-text {
            text-align: center;
            color: #666;
            font-size: 12px;
            margin-top: 20px;
        }

        .signin-text a {
            color: #7366ff;
            text-decoration: none;
            font-weight: bold;
        }

        .signin-text a:hover {
            text-decoration: underline;
        }

        .text-danger {
            color: #ff1234;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="logo">
            <h2 style="color: #333;">后台管理</h2>
        </div>
        <div class="signup-box">

            <form action="{{ route('admin.user.registerPost') }}" method="post">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                <label>姓名：</label>
                <div class="name-fields">
                    <input type="text" placeholder="名" name="first_name">
                    <input type="text" placeholder="姓" name="last_name">
                </div>
                @error('first_name')
                <span class="text-danger">{{ $message }}</span>
                @enderror
                <label>邮箱：</label>
                <input type="email" placeholder="邮箱地址" name="email">
                @error('email')
                <span class="text-danger">{{ $message }}</span>
                @enderror
                <label>密码：</label>
                <input type="password" placeholder="请输入密码" name="password">
                @error('password')
                <span class="text-danger">{{ $message }}</span>
                @enderror
                <button type="submit">注册</button>
            </form>
            <p class="signin-text">已经有帐户? <a href="{{ route('login') }}">登陆</a></p>
        </div>
    </div>
    </head>
</body>

</html>