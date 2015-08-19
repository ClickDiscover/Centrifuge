<html>
<head>
    <title><?= $this->e($title) ?></title>
    <link rel="stylesheet" href="/static/admin/pure-min.css">
    <link rel="stylesheet" href="/static/admin/grids-responsive-min.css">
    <script src="/static/admin/jquery-2.1.4.min.js"></script>

    <style>
        #page {
          margin-left: 50px;
          margin-right: 50px;
        }

        .menu {
            // position: relative;
            display: inline-block;
            padding-bottom: 40px;
        }

        .menu ul {
            height: 20px;
        }

        pre { font-family: monospace; }

        <?= $this->section('style') ?>
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
