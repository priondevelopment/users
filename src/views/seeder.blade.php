<?php echo '<?php' ?>

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PrionUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Create a Default User
        $this->command->info('Create a Default User');
        $user = PrionUsers\Models\{{ $user_table }}::create([
            'email' => "test@test.priondevelopment.com",
            'password' => Hash::make("testtest"),
            'first_name' => "Test User",
            'last_name' => "Please Delete",
            'active' => 1,
            'deleted' => 0,
        ]);


        // Create the Default Permissions

        // Give PrionUser Permissions
    }
}
