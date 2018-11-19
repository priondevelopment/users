<?php echo '<?php'; ?>

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /**
         * Create the Accounts Table. Each Account has access to the CDP
         *
         */
        Schema::table('{{ $prionusers['tables']['user_api_token'] }}', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('api_token_id');
            $table->integer('user_id');
			$table
                ->tinyInteger('active')
                ->default('0');
            $table
                ->timestamp('expires_at');
            $table
                ->timestamp('created_at')
                ->useCurrent();
            $table
                ->timestamp('updated_at')
                ->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            $table->foreign('api_token_id')
                ->references('id')->on('{{ $prionusers['tables']['api_tokens'] }}')
                ->onDelete('cascade');
            $table->foreign('user_id')
                ->references('id')->on('{{ $prionusers['tables']['users'] }}')
                ->onDelete('cascade');
        });

        /**
         * Associate Users to Accounts. Each account will have one owner
         *
         */
        Schema::table('{{ $prionusers['tables']['user_permissions'] }}', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('permission_id');
            $table->integer('user_id');
            $table->integer('account_id');
            $table
                ->tinyInteger('exclude')
                ->default('0');
            $table->timestamp('expires_at');
            $table
                ->timestamp('created_at')
                ->useCurrent();

            $table->foreign('permission_id')
                ->references('id')->on('{{ $prionusers['tables']['permissions'] }}')
                ->onDelete('cascade');
            $table->foreign('user_id')
                ->references('id')->on('{{ $prionusers['tables']['users'] }}')
                ->onDelete('cascade');
        });

		/**
         * Associate Users to Accounts. Each account will have one owner
         *
         */
        Schema::table('{{ $prionusers['tables']['user_permission_groups'] }}', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('permission_group_id');
            $table->integer('user_id');
            $table->integer('account_id');
            $table->timestamp('expires_at');
            $table
                ->timestamp('created_at')
                ->useCurrent();

            $table->foreign('permission_group_id')
                ->references('id')->on('{{ $prionusers['tables']['permission_groups'] }}')
                ->onDelete('cascade');
            $table->foreign('user_id')
                ->references('id')->on('{{ $prionusers['tables']['users'] }}')
                ->onDelete('cascade');
        });


		/**
         * Associate Users to Accounts. Each account will have one owner
         *
         */
        Schema::table('{{ $prionusers['tables']['permission_group_editors'] }}', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('permission_group_id');
            $table->integer('user_id');
            $table->integer('account_id');
			$table
                ->tinyInteger('manage_users')
                ->default('0');
			$table
                ->tinyInteger('manage_groups')
                ->default('0');
			$table
                ->tinyInteger('exclude')
                ->default('0');

            $table->timestamp('expires_at');
            $table
                ->timestamp('created_at')
                ->useCurrent();

            $table->foreign('permission_group_id')
                ->references('id')->on('{{ $prionusers['tables']['permission_groups'] }}')
                ->onDelete('cascade');
            $table->foreign('user_id')
                ->references('id')->on('{{ $prionusers['tables']['users'] }}')
                ->onDelete('cascade');
        });

		/**
         * Associate Users to Accounts. Each account will have one owner
         *
         */
        Schema::table('{{ $prionusers['tables']['permission_editors'] }}', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('permission_id');
            $table->integer('user_id');
            $table->integer('account_id');
			$table
                ->tinyInteger('exclude')
                ->default('0');
            $table->timestamp('expires_at');
            $table
                ->timestamp('created_at')
                ->useCurrent();

            $table->foreign('permission_group_id')
                ->references('id')->on('{{ $prionusers['tables']['permissions'] }}')
                ->onDelete('cascade');
            $table->foreign('user_id')
                ->references('id')->on('{{ $prionusers['tables']['users'] }}')
                ->onDelete('cascade');
        });


		/**
         * Associate Users to Accounts. Each account will have one owner
         *
         */
        Schema::table('{{ $prionusers['tables']['roles'] }}', function (Blueprint $table) {
            $table->increments('id');
            $table
            	->string('name', 200)
            	->nullable();
            $table->text('description');
            $table->integer('account_id');
			$table
                ->tinyInteger('manage_users')
                ->default('0');
			$table
                ->tinyInteger('manage_groups')
                ->default('0');
            $table->timestamp('expires_at');
            $table
                ->timestamp('created_at')
                ->useCurrent();
            $table
                ->timestamp('updated_at')
                ->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            $table->foreign('account_id')
                ->references('id')->on('{{ $prionusers['tables']['accounts'] }}')
                ->onDelete('cascade');
        });


		/**
         * Associate Users to Accounts. Each account will have one owner
         *
         */
        Schema::table('{{ $prionusers['tables']['role_permission_groups'] }}', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('role_id');
            $table->integer('permission_group_id');
			$table
                ->tinyInteger('manage_users')
                ->default('0');
			$table
                ->tinyInteger('manage_groups')
                ->default('0');
            $table->timestamp('expires_at');
            $table
                ->timestamp('created_at')
                ->useCurrent();

            $table->foreign('role_id')
                ->references('id')->on('{{ $prionusers['tables']['roles'] }}')
                ->onDelete('cascade');
            $table->foreign('permission_group_id')
                ->references('id')->on('{{ $prionusers['tables']['permission_groups'] }}')
                ->onDelete('cascade');
        });

		/**
         * Associate Users to Manage Roles
         *
         */
        Schema::table('{{ $prionusers['tables']['role_editors'] }}', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('role_id');
            $table->integer('user_id');
            $table->integer('account_id');
			$table
                ->tinyInteger('manage_rolls')
                ->default('0');
			$table
                ->tinyInteger('manage_role_groups')
                ->default('0');
            $table->timestamp('expires_at');
            $table
                ->timestamp('created_at')
                ->useCurrent();

            $table->foreign('role_id')
                ->references('id')->on('{{ $prionusers['tables']['roles'] }}')
                ->onDelete('cascade');
            $table->foreign('user_id')
                ->references('id')->on('{{ $prionusers['tables']['users'] }}')
                ->onDelete('cascade');
            $table->foreign('account_id')
                ->references('id')->on('{{ $prionusers['tables']['accounts'] }}')
                ->onDelete('cascade');
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('{{ $prionusers['tables']['user_api_token'] }}');
        Schema::drop('{{ $prionusers['tables']['user_permissions'] }}');
        Schema::drop('{{ $prionusers['tables']['user_permission_groups'] }}');
        Schema::drop('{{ $prionusers['tables']['permission_group_editors'] }}');
        Schema::drop('{{ $prionusers['tables']['permission_editors'] }}');
        Schema::drop('{{ $prionusers['tables']['roles'] }}');
        Schema::drop('{{ $prionusers['tables']['role_permission_groups'] }}');
        Schema::drop('{{ $prionusers['tables']['role_editors'] }}');
    }
}