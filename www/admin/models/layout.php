<html>
<head>
    <title><?= $this->e($title) ?></title>
    <link rel="stylesheet" href="http://yui.yahooapis.com/pure/0.6.0/pure-min.css">
    <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>

    <style>
        #page {
          margin-left: 50px;
          margin-right: 50px;
        }

        #menu-container {
            display: inline-block;
            height: 33px;
            padding-bottom: 20px;
        }
    </style>

</head>
<body>

<div id="menu-container">
    <?= $this->fetch('admin::models/menu') ?>
</div>

<div id="page">
    <?=$this->section('page')?>
</div>

</body>
</html>
