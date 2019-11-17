<?php
namespace modules\git;


class Git {

    private $commands = [];
    private $directory = ".";
    private $gitBin = "git";
    private $remote;
    private $directRun = true;

    public function changeDirectory($directory) : void{
        $this->addCommand("cd $directory");
        $this->directory = $directory;
    }

    public function addRemote($remoteName, $remoteUrl):void{
        $this->addCommand($this->gitBin." remote add $remoteName $remoteUrl");
    }

    public function setRemote($remote): void{
        $this->remote = $remote;
    }

    public function commit($message) : void {
        $this->addCommand($this->gitBin." commit -m \"".$message."\"");
    }

    public function initIfNot() : void {
        if (!file_exists($this->directory."/.git")) {
            $this->addCommand($this->gitBin." init");
        }
    }

    public function add($what) : void {
        $this->addCommand($this->gitBin." add $what");
    }

    public function clearCommands() : void {
        $this->commands = [];
    }

    /**
     * @param $var1 / Remote / Branch (If param length == 1)
     * @param null $var2 / Branch
     */
    public function push($var1, $var2 = null) : void {
        $remote = "";
        $branch = "master";
        if ($var2 == null) {
            $remote = $this->remote;
            $branch = $var1;
        } else {

        }

        $this->addCommand($this->gitBin." push ");

        if ($this->directRun) $this->run();
    }

    private function addCommand(string $cmd):void{
        $this->commands["ID_".rand(0000,1111).hash("SHA1", $cmd)] = $cmd;
    }

    /**
     * Runs the commands
     */
    public function run() : void {

        $command = "";
        foreach ($this->commands as $cmdid=>$cmd) {
            $command .= $cmd . "\n";
            unset($this->commands[$cmdid]);
        }

        $this->exec($command);

    }

    private function exec($cmd) : void {
        $disablefunc = [];
        $disablefunc = explode(",", str_replace(" ", "", @ini_get("disable_functions")));
        if(is_callable("exec") && !in_array("exec", $disablefunc)) {
            exec($cmd);
        } elseif(is_callable("system") && !in_array("system", $disablefunc)) {
            system($cmd);
        } elseif(is_callable("passthru") && !in_array("passthru", $disablefunc)) {
            passthru($cmd);
        } elseif(is_callable("popen") && !in_array("popen", $disablefunc) && is_resource($h = popen($cmd, "r"))) {
            $result = "";
            if(is_callable("fread") && !in_array("fread", $disablefunc)) {
                while(!feof($h)) {
                    $result .= fread($h, 1024);
                }
            } else {
                while(!feof($h)) {
                    $result .= fgets($h, 1024);
                }
            }
            pclose($h);
        } else {
            trigger_error("Cannot execute the command due to server restrictions.", E_USER_WARNING);
        }
    }

    public function setDirectRun(bool $directRun): void{
        $this->directRun = $directRun;
    }

}