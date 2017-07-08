<?php
/**
 * @var League\Plates\Template\Template $this
 * @var string $title
 */
?>
<?php $this->layout('main', ['title' => $title]) ?>

<?php $this->push('styles')?>
<link rel="stylesheet" href="<?=$this->asset('/css/admin.css')?>">
<?php $this->end() ?>

<?php $this->start('body') ?>
<h1>Admin panel</h1>
<div id="app">Start admin</div>
<?php $this->end() ?>

<?php $this->push('scripts'); ?>
<script src="<?=$this->asset('/js/admin.js')?>"></script>
<?php $this->end(); ?>
