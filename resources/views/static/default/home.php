<?php
/**
 * @var League\Plates\Template\Template $this
 * @var string $title
 */
?>
<?php $this->layout('main', ['title' => $title]) ?>

<?php $this->push('styles') ?>
<link rel="stylesheet" href="<?=$this->asset('/css/home.css')?>">
<?php $this->end() ?>

<?php $this->start('body') ?>
<a href="/admin">ADMIN </a>
<div id="app">Start home</div>
<?php $this->end() ?>

<?php $this->push('scripts'); ?>
<script src="<?=$this->asset('/js/homejs.js')?>"></script>
<?php $this->end(); ?>
