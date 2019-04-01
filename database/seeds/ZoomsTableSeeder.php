<?php

use Illuminate\Database\Seeder;

use App\Models\Zoom;

class ZoomsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $zooms = ["17201"=>'9849490463',
			"17202"=>'8316688740',
			"17203"=>'9398798010',
			"17204"=>'5569343364',
			"17205"=>'7129764874',
			"17206"=>'2949579672',
			"17207"=>'7378975429',
			"17208"=>'7979301909',
			"17209"=>'5762363386',
			"17210"=>'9391632957',
			"17211"=>'6502136826',
			"17212"=>'8815433385',
			"17213"=>'5586701431',
			"17214"=>'6269274715',
			"17215"=>'9292858384',
			"17216"=>'3996659202',
			"17217"=>'5613356170',
			"17218"=>'7434688617',
			"17219"=>'3666444903',
			"17220"=>'3678965289',
			"17221"=>'6976803503',
			"17222"=>'2984132419',
			"17223"=>'2718309026',
			"17224"=>'4601187211',
			"17225"=>'8821181800',
			"17226"=>'6642076180',
			"17227"=>'8700048848',
			"17228"=>'5238803875',
			"17229"=>'9610542876',
			"17230"=>'4528475180',
		];

    	foreach ($zooms as $id => $pmi) {
    		//4528475180	zoom17230@daxiangyingyu.com
    		$email = "zoom{$id}@daxiangyingyu.com";
    		$password = "Love{$id}";
  			Zoom::create([
				'email' => $email,
				'password' => $password,
  			  	'pmi' => $pmi
  			]);
    	}
    }
}
