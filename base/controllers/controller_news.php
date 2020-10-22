<?
class Controller_News extends Controller
{

    function __construct()
    {
        $this->model = new Model_News();
        $this->view = new View();
    }

    function action_index($prop = '')
    {
        if ($prop == 'parse')
        {
            $data = $this->model->parse_data();
            $this->view->generate('service.php', $data);
        }
        elseif($prop == 'clear')
        {
            $data = $this->model->clear_data();
            $this->view->generate('service.php', $data);
        }
        elseif($prop)
        {
            $data = $this->model->get_detail_news($prop);
            $this->view->generate('news_detail.php', $data);
        }
        else
        {
            $data = $this->model->get_news_list();
            $this->view->generate('news_list.php', $data);
        }

    }

}