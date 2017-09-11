<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Hashtag;
use App\Populartag;

class PopulatePopularHashtags extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'PopulatePopularHashtags:populatetags';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Populates the populartags table with top 10 tags and their count';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        try {
            $popular_tags = (new Hashtag)->popular();
            Populartag::truncate();
            foreach ($popular_tags as $tag) {
                Populartag::create([
                    'tag' => $tag->tag,
                    'tag_count' => $tag->tag_count,
                ]);
            }
        } catch (Exception $e) {
            throw new Exception($e->getMessage());
        }        
    }
}
