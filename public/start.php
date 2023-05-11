<!DOCTYPE html>
<html>
<head>
    <title>Username Form</title>
    <style>
        body {
            background-color: #60bbb9;
            text-align: center;
            font-family: "Comic Sans MS", cursive, sans-serif;
        }

        h1 {
            color: #333333;
            margin-top: 50px;
        }

        form {
            margin-top: 50px;
        }

        label {
            color: #333333;
            font-size: 18px;
            font-weight: bold;
            margin-right: 10px;
        }

        input[type="text"] {
            padding: 10px;
            border: none;
            border-radius: 5px;
            box-shadow: 1px 1px 5px rgba(0, 0, 0, 0.1);
        }

        input[type="submit"] {
            padding: 10px 20px;
            background-color: #ff99cc;
            border: none;
            border-radius: 5px;
            color: #ffffff;
            font-weight: bold;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            background-color: #ff66a3;
        }
    </style>
</head>
<body>
    <h1>OwO Username Form</h1>
    <form method="GET" action="https://owocc.1x.no/index.php">
        <label for="username">Username:</label>
        <input type="text" id="username" name="user" required>
        <br><br>
        <input type="submit" value="Submit">
    </form>
</body>
</html>
