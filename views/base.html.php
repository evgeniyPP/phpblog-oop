<!DOCTYPE html>
<html lang="ru">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?if (\DEV_MODE): ?>
    <meta http-equiv="Cache-Control" content="no-cache">
    <?endif;?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/8.0.1/normalize.min.css">
    <link rel="stylesheet" href="<?=ROOT?>styles/global.css">
    <link rel="stylesheet" href="<?=ROOT?>styles/<?=$stylefile?>.css">
    <title><?=$title?></title>
</head>

<body>
    <?=$content?>
</body>

</html>