<?
class Model_News extends Model
{
    public function get_news_list()
    {
        if ($arr = DB::request("SELECT * FROM news"))
            return $arr;
        else
            return 'Нет новостей.';
    }

    public function get_detail_news($code_url)
    {
        if ($arr = DB::request("SELECT * FROM news WHERE url='".$code_url ."'"))
            return $arr[0];
        else
            return 'Ошибка. Нет такой новости.';
    }

    public function parse_data()
    {
        $date = new DateTime();
        $time_param =  $date->getTimestamp();

        $result = '';
        $url = 'https://nsk.rbc.ru/v10/ajax/get-news-feed/project/rbcnews.nsk/lastDate/'.$time_param.'/limit/15';
        $page = Utils::get_data_from_url($url);

        $DOM = new DOMDocument;
        @$DOM->loadHTML($page);
        $list = $DOM->getElementsByTagName('a');

        for ($i=0; $i < $list->length; $i++){
            $news = [];
            $element = $list->item($i);
            $link = $element->getAttribute('href');
            preg_match ("/http.*\?/", $link, $link);
            $link = trim($link[0], '?');

            $page = Utils::get_data_from_url($link[0]);
            unset($DOM);
            $DOM = new DOMDocument;
            $DOM->preserveWhiteSpace = true;
            @$DOM->loadHTML($page);
            $h1s = $DOM->getElementsByTagName('h1');

            if (!$h1s->length){
                continue;
            }
            $news['title'] = $h1s[0]->textContent;
            $news['url'] = Utils::translite($news['title']);
            if (empty($news['url'])){
                continue;
            }
            $xpath = new DOMXpath($DOM);
            $anonce = $xpath->query("//div[contains(@class,'article__text__overview')]");
            if ($anonce->length){
                $anonce_text = trim($anonce[0]->textContent);
                $anonce_text = substr($anonce_text,0, 197).'...';
            }

            $details = $xpath->query("//div[contains(@itemprop,'articleBody')]/div/p | //div[contains(@itemprop,'articleBody')]/p | //div[contains(@class, 'article__text')]/p");
            if ($details->length){
                foreach ($details as $detail) {
                    $detail_arr[] = trim($detail->textContent);
                }
                $news['detail'] = implode('<br>', $detail_arr);
            }

            if (!isset($anonce_text)){
                if (isset($news['detail']))
                    $anonce_text = substr($news['detail'],0, 197).'...';
            }
            $news['anonce'] = $anonce_text;

            $img_el = $xpath->query("//div[contains(@class,'article__main-image__wrap')]/img")->item(0);
            if ($img_el){
                $img_src = $img_el->getAttribute('src');
                $news['img_src'] = $img_src;
            }else{
                $news['img_src'] = '';
            }

            if (!empty($news)){
                if(DB::request("INSERT INTO news(title, anonce, detail, img_src, url) VALUES('".$news['title']."', '".$news['anonce']."', '".$news['detail']."', '".$news['img_src']."', '".$news['url']."')"))
                    echo 'news added<br>';
            }
        }

        return $result;
    }

    public function clear_data()
    {
        if(DB::request("TRUNCATE TABLE news"))
            echo 'news deleted<br>';
    }
}