<?PHP

namespace psaintlaurent\XHProfLogger;

class AbstractLogger
{
    protected $logger;
    protected $data;
    protected $supplemental_data;

    public function __construct($path, $supplemental_data=array()) {

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
        	$this->data["supplemental_data"] = $this->supplemental_data;
		$this->__writeXHProfLoggingSession();
        	if($exit) { exit; }
	}
    }

    abstract protected function __writeXHProfLoggingSession();

    public function __destruct() {}
}
