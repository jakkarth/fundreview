<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ExampleTest extends TestCase
{

    /**
     * Make sure the main listing page loads
     *
     * @return void
     */
    public function testListingPageLoads()
    {
        factory(\App\Review::class, 10)->make();
        $response = $this->get('/');
        $response->assertStatus(200);
    }

    /**
     * Make sure the submission page loads
     *
     * @return void
     */
    public function testSubmissionPageLoads()
    {
        $response = $this->get('/submit');
        $response->assertStatus(200);
    }

    /**
     * Populate the database with a single review
     *
     * @return id of the created review
     */
    public function testActualSubmission()
    {
        /* during testing, using the 'Use RefreshDatabase' combined with a failed test somehow resulted in all of the
           non-test data in the database getting trashed. As such, I'm choosing to manually delete my possible test
           record here. I acknowledge that this is a poor way to do it.
        */

        //cleanup
        \DB::delete('delete from reviews where fundraiser=\'testFundraiser\'');

        //submit an erroneous request (front-end validation should catch this, but we're bypassing that here, so this verifies the back-end validation reslts in an error
        $response = $this->post('/submit', ['fundraiser'=>'testFundraiser','name'=>'testy mctester','email'=>'testymctester','review'=>'this is my review', 'rating'=>4]);
        $response->assertStatus(302);
        $response->assertRedirect('/submit');
        $responseContinued = $this->get($response->headers->get('Location'));
        $message = $responseContinued->assertSee('The email must be a valid email address');

        //and try a successful submission and verify the data ends up in the database as expected
        $response = $this->post('/submit', ['fundraiser'=>'testFundraiser','name'=>'testy mctester','email'=>'testymctester@example.com','review'=>'this is my review', 'rating'=>4]);
        $response->assertStatus(302);
        $response->assertRedirect('/');

        $this->assertDatabaseHas('reviews', ['fundraiser'=>'testFundraiser','name'=>'testy mctester','email'=>'testymctester@example.com','review'=>'this is my review', 'rating'=>4]);
        $review = \App\Review::where('fundraiser','=','testFundraiser')->get()->first();
        return $review->id;
    }

    /**
     * Validate that the details view loads properly.
     *
     * @id the ID of the review we test-created.
     * @depends testActualSubmission
     * @return void
     */
    public function testDetailPageLoads($id) {
        $response = $this->get('/details/'.$id);
        $response->assertStatus(200);
        $response->assertSee('this is my review');
    }
}
