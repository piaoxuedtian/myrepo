<?php $this->pageTitle = Yii::app()->name . ' - Error'; ?>

<div class="widget-error">
    <h1 class="error-title">
        <?php echo $error['code']; ?>
    </h1>
    <div class="error-message">
        <?php echo $error['message']; ?>
    </div>
</div>