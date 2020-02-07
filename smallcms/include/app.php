<?php
require_once dirname(__FILE__)."/db.php";

class app {
    protected $action = null;
    protected $db     = null;
    protected $config = array();
    protected $sqlDbg = array();

    public function __construct($config)
    {
        $this->action = $_GET["action"];
        $this->config = $config;

        $dbConfig = $config["db"];
        $this->db = new DB($dbConfig["host"], $dbConfig["user"], $dbConfig["password"], $dbConfig["db_name"]);
    }

    protected function preExecute()
    {
        session_start();

        if ($this->action != "login") {
            if (!isset($_SESSION['user_id'])){
                header("Location: ?action=login");
                exit;
            } elseif (empty($this->action)) {
                $this->action = "show";
            }
        }
    }

    protected function postExecute()
    {
        $this->render();
    }

    public function execute()
    {
        $this->preExecute();

        $method = "execute" . ucfirst($this->action);

        if (is_callable(array($this, $method))) {
            $this->$method();
        } else {
            header("Location: ?action=404&ref=".$this->action);
        }

        $this->postExecute();
    }

    public function executeLogin()
    {
        if (isset($_POST["auth_login"]) && isset($_POST["auth_pasword"])) {
            $sql = sprintf('SELECT * FROM `user` WHERE `login` = \'%s\' AND `password` = \'%s\'', $_POST["auth_login"], $_POST["auth_pasword"]);
            $this->sqlDbg[] = $sql;
            $result = $this->db->get_row($sql);
            if ($result){
                $_SESSION['user_id'] = $result['id'];
                header("Location: ?action=show");
            }
        }
    }

    public function executeLogout()
    {
        session_destroy();
        header("Location: ?action=login");
        exit;
    }

    public function execute404()
    {
        $this->ref = $this->getGetParam("ref", "");
    }

    public function executeShow()
    {
        $sql = 'SELECT COUNT(*) as count FROM `article`';
        $this->sqlDbg[] = $sql;
        $articlesCount = $this->db->get_row($sql);

        $perPage = $this->config["articles_per_page"];

        $this->pages = ceil($articlesCount['count'] / $perPage);
        $this->page  = $this->getGetParam("page", 1);

        $first = ($this->page - 1) * $perPage;

        $order = $this->getGetParam('by', 'id');

        $sql = sprintf('SELECT * FROM `article` ORDER BY %s LIMIT %s,%s', $order, $first, $perPage);
        $this->sqlDbg[] = $sql;
        $this->articles = $this->db->get_rows($sql);
    }

    public function executeArticle()
    {
        $this->id  = $this->getGetParam("id", 1);
        $sql = sprintf("SELECT * FROM `article` WHERE `id`=%s", $this->id);
        $this->sqlDbg[] = $sql;
        $this->article = $this->db->get_row($sql);
    }

    public function executeAdd()
    {
        $submit = $this->getGetParam("commit", null);
        if ($submit == "commit") {
            $sql = sprintf("INSERT INTO `article` (`title`, `description`, `content`) VALUES ('%s', '%s', '%s')", $this->getGetParam("title", ""), $this->getGetParam("description", ""), $this->getGetParam("content", ""));
            $this->sqlDbg[] = $sql;
            $res = $this->db->query($sql);
            header("Location: ?action=show");
        } else {
        	echo "fail" . $submit;
        }
    }

    public function executeDelete()
    {
        $this->id  = $this->getGetParam("id", 1);
        $sql = sprintf("DELETE FROM `article` WHERE `id`=%s", $this->id);
        $this->sqlDbg[] = $sql;
        $res = $this->db->query($sql);
        header("Location: ?action=show");
    }

    protected function getGetParam($name, $default)
    {
        if (isset($_GET[$name])) {
            return $_GET[$name];
        }
        if (isset($_POST[$name])) {
            return $_POST[$name];
        }
        return $default;
    }

    protected function render()
    {
        $tpl = $this->action;
        ob_start();
        require_once dirname(__FILE__) . "/../www/phtml/header.php";
        require_once dirname(__FILE__) . "/../www/phtml/{$tpl}.php";
        require_once dirname(__FILE__) . "/../www/phtml/footer.php";
        $content = ob_get_contents();
        ob_end_clean();

        echo $content;
        exit;
    }
}
