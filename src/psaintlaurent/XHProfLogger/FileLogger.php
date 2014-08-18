<?PHP

namespace psaintlaurent\XHProfLogger;

class FileLogger extends AbstractLogger
{

    private $__path;

    public function __construct($path, $supplemental_data=array()) {

        $this->__path = $path;
        $this->supplemental_data = $supplemental_data;
        $this->startXHProfLogging();
    }

    protected function __writeXHProfLoggingSession() {
        $this->logger = fopen($this->__path, "a+");
    	fwrite($this->logger, json_encode($this->data).PHP_EOL);
        fclose($this->logger);
    }

    public function __destruct() {}
}
