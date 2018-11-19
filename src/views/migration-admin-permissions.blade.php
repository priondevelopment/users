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
        Schema::table('{{ $prionusers['tables']['admin_groups'] }}', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')
                ->nullable();
            $table->text('description');
            $table
                ->timestamp('created_at')
                ->useCurrent();
            $table
                ->timestamp('updated_at')
                ->default(DB::raw('CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'));

            $table->index('name', 'index_admin_groups_name');
        });

        /**
         * Associate Users to Accounts. Each account will have one owner
         *
         */
        Schema::table('{{ $prionusers['tables']['admin_group_editors'] }}', function (Blueprint $table) {
            $table->increments('id');
            $table
                ->integer('admin_group_id')
                ->default('0');
            $table->tinyInteger('manage_groups');
            $table->tinyInteger('manage_users');
            $table
            	->tinyInteger('exclude')
            	->default('0');
            $table
                ->timestamp('expires_at');
            $table
                ->timestamp('created_at')
                ->useCurrent();

            $table->foreign('admin_group_id')
                ->references('id')->on('{{ $prionusers['tables']['admin_groups'] }}')
                ->onDelete('cascade');
        });


        /**
         * Associate Users to Accounts. Each account will have one owner
         *
         */
        Schema::table('{{ $prionusers['tables']['user_admin_groups'] }}', function (Blueprint $table) {
            $table->increments('id');
            $table
                ->integer('admin_group_id')
                ->default('0');
            $table
                ->integer('user_id')
                ->default('0');
            $table->timestamp('expires_at');
            $table
                ->timestamp('created_at')
                ->useCurrent();

            $table->foreign('admin_group_id')
                ->references('id')->on('{{ $prionusers['tables']['admin_groups'] }}')
                ->onDelete('cascade');
            $table->foreign('user_id')
                ->references('id')->on('{{ $prionusers['tables']['users'] }}')
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
        Schema::drop('{{ $prionusers['tables']['admin_groups'] }}');
        Schema::drop('{{ $prionusers['tables']['admin_group_editors'] }}');
        Schema::drop('{{ $prionusers['tables']['user_admin_groups'] }}');
    }
}