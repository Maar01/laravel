<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class sendPost extends Command
{
    private $response;
    private $endpoint;
    private $curl;
    private $error;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'client:post {ext=fakepost}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send simple post with curl';

    /**
     * Create a new command instance.
     *
     * @param string $endpoint
     */
    public function __construct(string $endpoint = 'https://atomic.incfile.com/')
    {
        parent::__construct();
        $this->endpoint = $endpoint;
        $this->curl = curl_init();

    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->endpoint .= $this->argument('ext');

        $this->execRequest();

        if ($this->isSuccessfull()) {
            $this->info($this->response);
        } else {
            $this->error($this->error);
        }

    }

    /**
     * @version 03/09/2019
     * @author Mario Avila
     */
    private function execRequest() : void {
        curl_setopt_array($this->curl, $this->getCurlOpt());

        $this->response = curl_exec($this->curl);
        $this->error = curl_error($this->curl);

        curl_close($this->curl);
    }

    /**
     * @return array
     * @version 03/09/2019
     * @author Mario Avila
     */
    private function getCurlOpt() : array {
        return array(
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_POSTFIELDS     => '{}',
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_ENCODING       => '',
            CURLOPT_TIMEOUT        => 30,
            CURLOPT_URL            => $this->endpoint,
        );
    }

    /**
     * @return bool
     * @version 03/09/2019
     * @author Mario Avila
     */
    public function isSuccessfull() : bool {
        return $this->error === '';
    }

    /**
     * @return mixed
     * @version 03/09/2019
     * @author Mario Avila
     */
    public function getError() : string {
        return $this->error;
    }
}
