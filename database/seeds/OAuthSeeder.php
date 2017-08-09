<?php
use Illuminate\Database\Seeder;

class OAuthSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->createClient();
    }

    public function createClient()
    {
        $client = new \Laravel\Passport\Client();
        $client->id = 1;
        $client->name = 'Development Access Client';
        $client->secret = 'test';
        $client->redirect = 'http://localhost';
        $client->personal_access_client = 0;
        $client->password_client = 1;
        $client->revoked = 0;
        $client->save();
    }

}
