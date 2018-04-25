<?php

namespace Tests\Unit;

use App\Services\Mentions;
use App\Team;
use App\Ticket;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MentionsTest extends TestCase
{
    use RefreshDatabase;

     /** @test */
      public function can_find_mentions_in_text()
      {
         $mentions = Mentions::findIn("this is a text with @some mentions and I @want_to make sure @them all are@found");
         $this->assertCount(4, $mentions);
         $this->assertEquals(["some", "want_to", "them", "found"], $mentions);
      }

       /** @test */
        public function does_find_users_mentioned()
        {
            factory(User::class)->create(["name" => "WithCapitalLetters"]);
            factory(User::class)->create(["name" => "with spaces"]);

            $users = Mentions::findUsersFor(["WithCapitalLetters", "with_spaces", "non_existing"]); //Sqlite differenciates capital than non capital while mysql does not
            $this->assertCount(2, $users);
            $this->assertEquals(1, $users[0]->id);
            $this->assertEquals(2, $users[1]->id);
        }

}
