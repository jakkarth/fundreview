<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{

    public $count = 0, $sum = 0, $sort = 0;

    static function allOrdered() {
        $all = Review::all()->shuffle();
        $ret = array();
        foreach ($all as $review) {
            if (empty($ret[$review->fundraiser])) {
                $ret[$review->fundraiser] = $review;
                $ret[$review->fundraiser]->count = 1;
                $ret[$review->fundraiser]->sum = $review->rating;
            } else {
                $ret[$review->fundraiser]->count++;
                $ret[$review->fundraiser]->sum += $review->rating;
            }
        }

        $c = $all->count() / (count($ret));
        $m = 3;
        foreach ($ret as $k=>$r) {
            $ret[$k]->rating = $ret[$k]->sum / $ret[$k]->count;
            $ret[$k]->sort = ($c * $m + $ret[$k]->sum) / ($c + $ret[$k]->count);
        }
        return collect($ret)->sortBy('sort')->reverse();
    }
}
