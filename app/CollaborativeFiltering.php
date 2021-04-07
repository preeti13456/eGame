<?php

namespace App;

use Auth;
use App\Rating;
use App\Article;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class CollaborativeFiltering
{
    public static function getRecommendations()
    {
        if (count(Rating::userRatings())) {
            $user_id = Auth::user()->id;

            $process = new Process(
                '/../wamp64/www/eGame/app/Python/Python38-32/python.exe /../wamp64/www/eGame/app/Python/sql_to_csv.py'
            );
            $process->run();

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }

            $process_2 = new Process(
                "/../wamp64/www/eGame/app/Python/Python38-32/python.exe /../wamp64/www/eGame/app/Python/collaborative_filtering.py {$user_id}"
            );
            $process_2->run();

            if (!$process_2->isSuccessful()) {
                throw new ProcessFailedException($process_2);
            }

            $outPutArticlesToArray = json_decode($process_2->getOutput());

            for ($i = 0; $i < sizeof($outPutArticlesToArray); $i++) {
                $articles[] = Article::find($outPutArticlesToArray[$i]);
            }

            return $articles;
        } else {
            return 0;
        }
    }
}
