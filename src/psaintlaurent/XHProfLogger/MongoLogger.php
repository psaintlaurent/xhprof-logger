<?PHP

namespace psaintlaurent\XHProfLogger;

class MongoLogger extends AbstractLogger
{

    private $__un;
    private $__pwd;
    private $__url;
    private $__db;
    private $__collection;

    public function __construct($conn, $supplemental_data) {

        list($this->__un, $this->__pwd, $this->__url, $this->__db, $this->__collection) = $conn;
        $this->__supplemental_data = $supplemental_data;
        $this->startXHProfLogging();
    }

    protected function __writeXHProfLoggingSession() {

            $m = new \MongoClient("mongodb://{$this->__un}:{$this->__pwd}@{$this->__url}/{$this->__db}");
            $m->connect();
            $db = new \MongoDB($m, $this->__db);
            $this->data["supplemental_data"] = $this->__supplemental_data;
            $logger = $db->{$this->__collection};
            $logger->insert($this->__data);
            $m->close();
    }

    public function __destruct() {}
}
