<?php
/**
 * @var League\Plates\Template\Template $this
 * @var string $title
 */
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=$this->e($title)?></title>
    <link rel="stylesheet" href="<?=$this->asset('/css/style.css')?>">
    <?=$this->section('styles')?>
    <link href="https://fonts.googleapis.com/css?family=Abel" rel="stylesheet">
</head>
<body>
<?=$this->section('body')?>
<?php echo getenv('TEST') ?>
<noscript>
    <p>To use <mark>True Framework</mark>, please enable JavaScript.</p>
</noscript>
<script src="<?=$this->asset('/js/vendor.bundle.js')?>"></script>
<script src="<?=$this->asset('/js/app.js')?>"></script>
<?=$this->section('scripts')?>
</body>
</html>
