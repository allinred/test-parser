<?if (is_array($data)):?>
<h1><?=$data['title']?></h1>
<? if (isset($data['img_src']) && $data['img_src']):?>
    <img src="<?=$data['img_src']?>">
<? endif;?>
<div class="article_text">
    <?=$data['detail']?>
</div>
<?else:
    print_r($data);
endif;?>