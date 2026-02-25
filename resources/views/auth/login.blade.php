<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login Student Portal</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins', sans-serif;
        }

        body {
            display: flex;
            height: 100vh;
            background: #f5f6fa;
        }

        .left {
            width: 50%;
            background: linear-gradient(135deg, #2c3e91, #3949ab);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 40px;
        }

        .left h1 {
            font-size: 36px;
            line-height: 1.4;
        }

        .left span {
            color: #ffd54f;
        }

        .right {
            width: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .login-box {
            width: 400px;
        }

        .login-box h2 {
            font-size: 28px;
            margin-bottom: 30px;
            color: #2c3e91;
        }

        .form-group {
            margin-bottom: 18px;
        }

        label {
            display: block;
            margin-bottom: 6px;
            font-size: 14px;
        }

        input[type="text"],
        input[type="password"] {
            width: 100%;
            padding: 12px;
            border-radius: 10px;
            border: 1px solid #ddd;
            font-size: 14px;
        }

        .row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            font-size: 13px;
        }

        .row a {
            text-decoration: none;
            color: #3949ab;
        }

        .btn {
            width: 100%;
            padding: 12px;
            border: none;
            border-radius: 10px;
            background: #4b4bb7;
            color: white;
            font-size: 15px;
            cursor: pointer;
        }

        .btn:hover {
            background: #3838a8;
        }

        .version {
            text-align: center;
            margin-top: 15px;
            font-size: 12px;
            color: #aaa;
        }

        @media (max-width: 900px) {
            .left {
                display: none;
            }

            .right {
                width: 100%;
            }
        }
    </style>
</head>
<body>

    <div class="left">
        <h1>
            Selamat Datang <br>
            di <span>Student Portal</span>
        </h1>
    </div>

    <div class="right">
        <div class="login-box">
            <h2>Login Student Portal</h2>

            <form>
                <div class="form-group">
                    <label>ID Pengguna</label>
                    <input type="text" placeholder="Username">
                </div>

                <div class="form-group">
                    <label>Password</label>
                    <input type="password" placeholder="Password">
                </div>

                <div class="row">
                    <div>
                        <input type="checkbox"> Remember me
                    </div>
                    <a href="#">Lupa Kata Sandi?</a>
                </div>

                <button type="submit" class="btn">Masuk</button>

                <div class="version">
                    Versi 20250411.2
                </div>
            </form>
        </div>
    </div>

</body>
</html>
