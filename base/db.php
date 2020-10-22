<?
Class Db{
    public static function request($query)
    {
        $mysqli = new mysqli("localhost", "", "", "");

        if ($mysqli->connect_errno) {
            printf("Не удалось подключиться: %s\n", $mysqli->connect_error);
            exit();
        }

        $mysqli->set_charset("utf8");

        if ($query){
            $arr = array();
            if ($result = $mysqli->query($query)) {
                if ($result && !is_bool($result)){
                    while ($row = $result->fetch_assoc()) {
                        $arr[] = $row;
                    }
                }
                else{
                    $arr = $result;
                }
            }
            $mysqli->close();
            return $arr;
        }
    }
}