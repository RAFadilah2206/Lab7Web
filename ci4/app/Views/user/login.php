<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <!-- External CSS -->
    <link rel="stylesheet" href="<?= base_url('/style.css'); ?>">

    <!-- Inline Styles -->
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background-color: #f4f4f4;
            margin: 0;
        }

        #login-wrapper {
            width: 100%;
            max-width: 400px;
            background: #fff;
            padding: 30px 20px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border-radius: 10px;
        }

        h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .alert {
            color: red;
            background-color: #ffe6e6;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            text-align: center;
        }

        label {
            font-weight: 500;
        }

        .form-control {
            width: 94%;
            padding: 10px;
            margin-top: 15px;
            margin-bottom: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 15px;
        }

        .btn {
            width: 100%;
            padding: 10px;
            margin-top: 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 15px;
            cursor: pointer;
        }

        .btn:hover {
            background-color: #0056b3;
        }
    </style>
</head>

<body>
    <div id="login-wrapper">
        <h2>Sign In</h2>

        <?php if (session()->getFlashdata('flash_msg')): ?>
            <div class="alert">
                <?= session()->getFlashdata('flash_msg'); ?>
            </div>
        <?php endif; ?>

        <form action="<?= base_url('/user/login'); ?>" method="post">
            <?= csrf_field(); ?>

            <label for="InputForEmail">Email Address</label>
            <input type="email" name="email" class="form-control" id="InputForEmail" placeholder="Masukkan email..."
                value="<?= set_value('email'); ?>" required autocomplete="off">

            <label for="InputForPassword">Password</label>
            <input type="password" name="password" class="form-control" id="InputForPassword"
                placeholder="Masukkan password..." required autocomplete="off">

            <button type="submit" class="btn">Login</button>
        </form>
    </div>
</body>

</html>