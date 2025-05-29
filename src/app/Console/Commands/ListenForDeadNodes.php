<?php

namespace App\Console\Commands;

use App\Models\Node;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;

class ListenForDeadNodes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:listen-for-dead-nodes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $nodes = Node::with('lastMeasurement')->get();
        $contacts = explode(",", config('sms.contacts'));
        foreach ($nodes as $node) {
            $lastMeasurement = $node->lastMeasurement;
            if ($lastMeasurement->created_at->diffInMinutes(now()) >= 60) { //1h
                $lastSend = $lastMeasurement->created_at->format('Y-m-d H:i:s');
                $nodeType = $node->type->name;
                foreach ($contacts as $phone) {
                    Http::timeout(120)->post('http://sms-api:5005/send-sms', [
                        'phone' => '0557140039',
                        'message' => $nodeType . " not sending data since " . $lastSend,
                    ]);
                }
            }
        }
    }
}
