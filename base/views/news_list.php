<?if (is_array($data)):?>
    <div class="news_list">
        <? foreach ($data as $news):?>
            <div class="news_item">
                <h2><?=$news['title']?></h2>
                <div class="anonce">
                    <?=$news['anonce']?>
                </div>
                <a href="<?=$news['url']?>">Подробнее</a>
            </div>
        <? endforeach; ?>
    </div>
<?else:
    print_r($data);
endif;?>