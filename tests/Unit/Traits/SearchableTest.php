<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Contacts\Contact;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class SearchableTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function testSearchContactsReturnsCollection()
    {
        $contact = factory('App\Models\Contacts\Contact')->make();
        $searchResults = $contact->search($contact->first_name, $contact->account_id, 10, 'created_at desc');

        $this->assertInstanceOf('Illuminate\Pagination\LengthAwarePaginator', $searchResults);
    }

    /** @test */
    public function testSearchContactsThroughFirstNameAndResultContainsContact()
    {
        $contact = factory('App\Models\Contacts\Contact')->create(['first_name' => 'FirstName']);
        $searchResults = $contact->search($contact->first_name, $contact->account_id, 10, 'created_at desc');

        $this->assertTrue($searchResults->contains($contact));
    }

    /** @test */
    public function testSearchContactsThroughMiddleNameAndResultContainsContact()
    {
        $contact = factory('App\Models\Contacts\Contact')->create(['middle_name' => 'MiddleName']);
        $searchResults = $contact->search($contact->middle_name, $contact->account_id, 10, 'created_at desc');

        $this->assertTrue($searchResults->contains($contact));
    }

    /** @test */
    public function testSearchContactsThroughLastNameAndResultContainsContact()
    {
        $contact = factory(Contact::class)->create(['last_name' => 'LastName']);

        $searchResults = $contact->search($contact->last_name, $contact->account_id, 10, 'created_at desc');

        $this->assertTrue($searchResults->contains($contact));
    }

    /** @test */
    public function testFailingSearchContacts()
    {
        $contact = factory(Contact::class)->create(['first_name' => 'TestShouldFail']);
        $searchResults = $contact->search('TestWillSucceed', $contact->account_id, 10, 'created_at desc');

        $this->assertFalse($searchResults->contains($contact));
    }
}
