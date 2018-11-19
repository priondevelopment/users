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
        Schema::table('{{ $prionusers['tables']['accounts'] }}', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')
                ->nullable();
            $table->string('title_legal')
                ->nullable();
			$table
                ->tinyInteger('active')
                ->default('0');
			$table
                ->tinyInteger('verified')
                ->default('0');
            $table
                ->timestamp('created_at')
                ->useCurrent();
            $table
                ->timestamp('updated_at')
                ->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            $table->index('title', 'index_accounts_title');
        });

        /**
         * Associate Users to Accounts. Each account will have one owner
         *
         */
        Schema::table('{{ $prionusers['tables']['account_users'] }}', function (Blueprint $table) {
            $table->increments('id');
            $table
                ->integer('account_id')
                ->default('0');
            $table
                ->integer('user_id')
                ->default('0');
            $table
                ->tinyInteger('primary')
                ->default('0');
            $table
                ->tinyInteger('verified')
                ->default('0');
            $table
                ->tinyInteger('account_owner')
                ->default('0');
            $table
                ->timestamp('expires_at');
            $table
                ->timestamp('created_at')
                ->useCurrent();
            $table
                ->timestamp('updated_at')
                ->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            $table->foreign('account_id')
                ->references('id')->on('accounts')
                ->onDelete('cascade');
            $table->foreign('user_id')
                ->references('id')->on('users')
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
        Schema::drop('{{ $prionusers['tables']['accounts'] }}');
        Schema::drop('{{ $prionusers['tables']['account_users'] }}');
    }
}