<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{

    public $count = 0, $sum = 0, $sort = 0;

    /**
     * Return an ordered collection of all of the review records
     * we have in the system. Ordering is based on the Bayesian average.
     *
     * @return collection of ordered reviews.
     */
    static function allOrdered() {
        $ret = array();

        //retrive all of the records
        $all = Review::all();

        //If there are no reviews in the system, short-circuit an empty response
        if (count($ret) < 1) {
            return collect(array());
        }

        //calculate the sum of all stars, and count of reviews, grouping by fundraiser
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

        //calculate the Bayesian averages with some reasonable factors. These
        //aren't intended to be displayed to the user, but are used for
        //ordering.
        $c = $all->count() / (count($ret));
        $m = 3;
        foreach ($ret as $k=>$r) {
            $ret[$k]->rating = $ret[$k]->sum / $ret[$k]->count;
            $ret[$k]->sort = ($c * $m + $ret[$k]->sum) / ($c + $ret[$k]->count);
        }

        //reversed here because sort defaults to ascending
        return collect($ret)->sortBy('sort')->reverse();
    }
}
