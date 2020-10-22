<?
class View
{

    function generate($template_view, $data = null)
    {
        include 'views/header.php';
        include 'views/'.$template_view;
        include 'views/footer.php';
    }
}