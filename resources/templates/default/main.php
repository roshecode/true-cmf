<?php
/**
 * @var League\Plates\Template\Template $this
 * @var string $title
 */
?>
<?php $this->layout('main', ['title' => $title]) ?>

<?php $this->push('styles') ?>
<link rel="stylesheet" href="<?=$this->asset('/css/main.css')?>">
<?php $this->end() ?>

<?php $this->start('body') ?>
<div id="app">Start home</div>
<?php $this->end() ?>

<?php $this->push('scripts'); ?>
<script src="<?=$this->asset('/js/main.js')?>"></script>
<?php $this->end(); ?>
