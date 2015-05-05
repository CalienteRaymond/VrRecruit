<?php

class AddTwiliorequestsTable extends Ruckusing_Migration_Base
{
    public function up()
    {
    	$twiliorequests = $this->create_table('twiliorequests', ['id' => false, 'options' => 'Engine=InnoDB']);
        $twiliorequests->column(
            'id',
            'integer',
            [
                'primary_key' => true,
                'auto_increment' => true,
                'null' => false
            ]
        );
        $twiliorequests->column('created_at','datetime');
        $twiliorequests->column('To','text');
        $twiliorequests->column('Body','text');
        $twiliorequests->finish();
    }//up()

    public function down()
    {
    	$this->drop_table("twiliorequests");
    }//down()
}
