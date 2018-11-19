<?php echo '<?php'; ?>

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        /**
         *  Create Users Table
         *
         */
        Schema::create('{{ $prionusers['tables']['users'] }}', function (Blueprint $table) {
            $table->increments('id');
            $table
                ->string('first_name', 50)
                ->nullable();
            $table
                ->string('last_name', 50)
                ->nullable();
            $table
                ->string('password', 200)
                ->nullable();
            $table
                ->string('active')
                ->default('0');
            $table
                ->string('verified')
                ->default('0');
            $table
                ->string('deleted')
                ->default('0');
            $table
                ->timestamp('created_at')
                ->useCurrent();
            $table
                ->timestamp('updated_at')
                ->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));
        });

        /**
         * Verification Codes for Password Resets, Emails, Phone Numbers
         *
         */
        Schema::create('{{ $prionusers['tables']['verification_codes'] }}', function (Blueprint $table) {
            $table->increments('id');
            $table
                ->string('code')
                ->nullable();
            $table
                ->string('model')
                ->nullable();
            $table
                ->integer('model_id')
                ->default('0');
            $table
                ->timestamp('created_at')
                ->useCurrent();
            $table
                ->timestamp('delete_at');

			$cols = ['model','model_id'];
            $table->index($cols, 'index_verification_codes_model_model_id');
        });


        /**
         *  Create Table to Store User Emails
         *
         */
        Schema::create('{{ $prionusers['tables']['user_emails'] }}', function (Blueprint $table) {
            $table->increments('id');
            $table
                ->integer('user_id')
                ->default('0');
            $table
                ->string('email')
                ->nullable();
			$table
                ->string('type')
                ->nullable();
            $table
                ->tinyInteger('default')
                ->default('0');
            $table
                ->tinyInteger('verified')
                ->default('0');
            $table
                ->timestamp('created_at')
                ->useCurrent();
            $table
                ->timestamp('verified_at');

			$table->foreign('user_id')
				->references('id')->on('{{ $prionusers['tables']['users'] }}')
				->onDelete('cascade');
            $table->index('email', 'index_user_emails_email');
        });


        /**
         *  Create Table to Store User Emails
         *
         */
        Schema::create('{{ $prionusers['tables']['user_phones'] }}', function (Blueprint $table) {
            $table->increments('id');
            $table
                ->integer('user_id')
                ->default('0');
            $table
                ->string('number')
                ->nullable();
            $table
                ->string('type')
                ->nullable();
            $table
                ->tinyInteger('default')
                ->default('0');
            $table
                ->tinyInteger('verified')
                ->default('0');
            $table
                ->timestamp('created_at')
                ->useCurrent();
            $table
                ->timestamp('verified_at');

			$table->foreign('user_id')
				->references('id')->on('{{ $prionusers['tables']['users'] }}')
				->onDelete('cascade');
            $table->index('number', 'index_user_phones_number');
        });


        /**
         * User Addresses
         *
         */
        Schema::create('{{ $prionusers['tables']['user_addresses'] }}', function (Blueprint $table) {
            $table->increments('id');
            $table
                ->integer('user_id')
                ->default('0');
            $table
                ->string('street1', 300)
                ->nullable();
            $table
                ->string('street2', 300)
                ->nullable();
            $table
                ->string('street3', 300)
                ->nullable();
            $table
                ->string('city', 150)
                ->nullable();
            $table
                ->string('region', 150)
                ->nullable();
            $table
                ->string('country', 150)
                ->nullable();
            $table
                ->string('postal_code', 20)
                ->nullable();
            $table
                ->tinyInteger('verified')
                ->default('0');
            $table
                ->tinyInteger('default')
                ->default('0');
            $table
                ->timestamp('created_at')
                ->useCurrent();
            $table
                ->timestamp('updated_at')
                ->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

			$table->foreign('user_id')
				->references('id')->on('{{ $prionusers['tables']['users'] }}')
				->onDelete('cascade');

			$cols = ['country','region','city'];
            $table->index($cols, 'index_user_addresses_country_region_city');
        });


		/**
         *  Create Table to Store User Emails
         *
         */
        Schema::create('{{ $prionusers['tables']['user_profiles'] }}', function (Blueprint $table) {
            $table->increments('id');
            $table
                ->integer('user_id')
                ->default('0');
            $table
                ->string('url', 500)
                ->nullable();
            $table
                ->string('type', 50)
                ->nullable();
            $table
                ->tinyInteger('verified')
                ->default('0');
            $table
                ->timestamp('created_at')
                ->useCurrent();
            $table
                ->timestamp('verified_at');

			$table->foreign('user_id')
				->references('id')->on('{{ $prionusers['tables']['users'] }}')
				->onDelete('cascade');
            $table->index('type', 'index_user_profiles_type');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('{{ $prionusers['tables']['users'] }}');
        Schema::drop('{{ $prionusers['tables']['verification_codes'] }}');
        Schema::drop('{{ $prionusers['tables']['user_emails'] }}');
        Schema::drop('{{ $prionusers['tables']['user_phones'] }}');
        Schema::drop('{{ $prionusers['tables']['user_addresses'] }}');
        Schema::drop('{{ $prionusers['tables']['user_profiles'] }}');
    }
}