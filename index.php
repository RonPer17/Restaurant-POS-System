<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Point of Sale</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background: url('rest.jpg') no-repeat center center fixed;
            background-size: cover;
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            margin: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            backdrop-filter: blur(8px);
        }

        .container {
            background: rgba(255, 255, 255, 0.15);
            padding: 50px;
            border-radius: 15px;
            box-shadow: 0 8px 16px rgba(0, 0, 0, 0.3);
            display: flex;
            flex-direction: column;
            align-items: center;
            backdrop-filter: blur(10px);
            width: 350px;
        }

        .title {
            font-size: 32px;
            font-weight: 600;
            color: #fff;
            margin-bottom: 20px;
        }

        .buttons {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 15px;
            margin-top: 20px;
            width: 100%;
        }

        .btn {
            background: linear-gradient(135deg,rgb(72, 5, 54), #ff4b2b);
            color: white;
            padding: 15px;
            font-size: 18px;
            font-weight: 600;
            border: none;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            cursor: pointer;
            transition: all 0.3s ease;
            width: 100%;
            text-align: center;
            box-shadow: 0 4px 10px rgba(106, 5, 93, 0.3);
        }

        .btn:hover {
            transform: scale(1.05);
            box-shadow: 0 6px 14px rgba(255, 75, 43, 0.5);
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="title">Hotzarap Bulalohan & Kambingan</div>
        <div class="buttons">
            <a href="Restro/admin/indexx.php" class="btn">Admin</a>
            <a href="Restro/cashier/" class="btn">Cashier</a>
        </div>
    </div>
</body>

</html>
