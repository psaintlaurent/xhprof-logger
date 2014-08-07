<?PHP

namespace psaintlaurent\XHProfLogger;

class FileWriter
{
    protected $logger;
    protected $data;
    protected $path;
    protected $supplemental_data;

    public function __construct($path, $supplemental_data=array()) {

        $this->path = $path;
        $this->supplemental_data = $supplemental_data;
        $this->startXHProfLogging();
    }

    public function startXHProfLogging() {

        declare(ticks = 1);
        register_shutdown_function(array($this, "stopXHProfLogging"));
        pcntl_signal(SIGTERM, array($this, "stopXHProfLogging"));
        pcntl_signal(SIGINT, array($this, "stopXHProfLogging"));
        pcntl_signal(SIGHUP, array($this, "stopXHProfLogging"));
        pcntl_signal(SIGUSR1, array($this, "stopXHProfLogging"));
        xhprof_enable(XHPROF_FLAGS_CPU | XHPROF_FLAGS_MEMORY);
    }

    public function addExtraData($supplemental_data=array()) {

        $this->supplemental_data[] =  $supplemental_data;
    }

    public function stopXHProfLogging($exit=true) {

	if(empty($this->data)) {

	        $this->data = xhprof_disable();
        	$this->logger = fopen($this->path, "a+");
        	$this->data["supplemental_data"] = $this->supplemental_data;
        	fwrite($this->logger, json_encode($this->data).PHP_EOL);
        	fclose($this->logger);
        	if($exit) { exit; }
	}
    }

    public function __destruct() {}
}
