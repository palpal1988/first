<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/create_account.css">
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="col-xs-12 col-sm-12">
                <div>
                    <h2>新規登録</h1>
                </div>
                <form method="post" action="create_account_check.php">
                    <label for="name">名前</label>
                    <input type="text" class="form-control" name="name" id="name">
                    <label for="userEmail">メール</label>
                    <input type="text" class="form-control" name="email" id="email">
                    <label for="userId">ID</label>
                    <input type="text" class="form-control" name="userId" id="userId">
                    <label for="password">パスワード</label>
                    <input type="password" class="form-control" name="password" id="password">
                    <label for="password">パスワード（再確認）</label>
                    <input type="password" class="form-control" name="password2" id="password2">
                    <input type="hidden" name="post_flg" value="1">
                    <div style="text-align:center; margin-top:20px;">
                        <button class="btn btn-primary" id="createBtn">新規登録</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="http://code.jquery.com/jquery-2.1.4.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>

</html>