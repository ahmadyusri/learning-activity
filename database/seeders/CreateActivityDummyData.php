<?php

namespace Database\Seeders;

use App\Models\LA\LAActivity;
use App\Models\LA\LAMethod;
use App\Models\User;
use Illuminate\Database\Seeder;

class CreateActivityDummyData extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $User = User::first();
        if (!$User) {
            throw new \Exception("User not found, please migrate User Data First");
        }

        try {
            \DB::beginTransaction();

            // Insert Method
            LAMethod::insert([
                [
                    'name' => 'Workshop / Self Learning',
                    'order' => 1,
                    'created_by' => $User->id,
                ], [
                    'name' => 'Sharing Practice / Professional\'s Talk',
                    'order' => 2,
                    'created_by' => $User->id,
                ], [
                    'name' => 'Discussion Room',
                    'order' => 3,
                    'created_by' => $User->id,
                ], [
                    'name' => 'Coaching',
                    'order' => 4,
                    'created_by' => $User->id,
                ], [
                    'name' => 'Mentoring',
                    'order' => 5,
                    'created_by' => $User->id,
                ]
            ]);

            $LAMethod1 = LAMethod::where('order', 1)->first();
            $LAMethod2 = LAMethod::where('order', 2)->first();
            $LAMethod3 = LAMethod::where('order', 3)->first();
            $LAMethod4 = LAMethod::where('order', 4)->first();
            $LAMethod5 = LAMethod::where('order', 5)->first();

            // Insert Activity
            LAActivity::insert([
                [
                    'method_id' => $LAMethod1->id,
                    'name' => 'Fundamental of Superindependence',
                    'start_date' => '2022-01-02',
                    'end_date' => '2022-01-05',
                    'created_by' => $User->id,
                ],
                [
                    'method_id' => $LAMethod1->id,
                    'name' => 'Introduction to TIC Industry',
                    'start_date' => '2022-01-03',
                    'end_date' => '2022-01-05',
                    'created_by' => $User->id,
                ],
                [
                    'method_id' => $LAMethod1->id,
                    'name' => 'Rindam "Bela Negara"',
                    'start_date' => '2022-01-04',
                    'end_date' => '2022-01-05',
                    'created_by' => $User->id,
                ],
                [
                    'method_id' => $LAMethod1->id,
                    'name' => 'Human Resources Generalist',
                    'start_date' => '2022-01-05',
                    'end_date' => '2022-01-10',
                    'created_by' => $User->id,
                ],
                [
                    'method_id' => $LAMethod1->id,
                    'name' => 'Basic Finance For Business',
                    'start_date' => '2022-01-10',
                    'end_date' => '2022-01-15',
                    'created_by' => $User->id,
                ],
                [
                    'method_id' => $LAMethod1->id,
                    'name' => 'Basic Auditing',
                    'start_date' => '2022-02-02',
                    'end_date' => '2022-02-05',
                    'created_by' => $User->id,
                ],
                [
                    'method_id' => $LAMethod1->id,
                    'name' => 'Business Legal',
                    'start_date' => '2022-02-03',
                    'end_date' => '2022-02-05',
                    'created_by' => $User->id,
                ],
                [
                    'method_id' => $LAMethod1->id,
                    'name' => 'General Affair',
                    'start_date' => '2022-02-04',
                    'end_date' => '2022-02-05',
                    'created_by' => $User->id,
                ],
                [
                    'method_id' => $LAMethod1->id,
                    'name' => 'Risk Management',
                    'start_date' => '2022-02-05',
                    'end_date' => '2022-02-05',
                    'created_by' => $User->id,
                ],
                [
                    'method_id' => $LAMethod1->id,
                    'name' => 'Basic Business',
                    'start_date' => '2022-02-12',
                    'end_date' => '2022-02-15',
                    'created_by' => $User->id,
                ],
                [
                    'method_id' => $LAMethod1->id,
                    'name' => 'Basic Salesmanship',
                    'start_date' => '2022-06-02',
                    'end_date' => '2022-06-05',
                    'created_by' => $User->id,
                ],
                [
                    'method_id' => $LAMethod1->id,
                    'name' => 'Cretive Thingking',
                    'start_date' => '2022-06-02',
                    'end_date' => '2022-06-05',
                    'created_by' => $User->id,
                ],
                [
                    'method_id' => $LAMethod1->id,
                    'name' => 'Data Analytics',
                    'start_date' => '2022-06-02',
                    'end_date' => '2022-06-05',
                    'created_by' => $User->id,
                ],

                [
                    'method_id' => $LAMethod2->id,
                    'name' => 'Sharing Practice',
                    'start_date' => '2022-03-12',
                    'end_date' => '2022-03-15',
                    'created_by' => $User->id,
                ],
                [
                    'method_id' => $LAMethod2->id,
                    'name' => 'Sharing Practice',
                    'start_date' => '2022-05-12',
                    'end_date' => '2022-05-15',
                    'created_by' => $User->id,
                ],

                [
                    'method_id' => $LAMethod3->id,
                    'name' => 'Ask The Experts',
                    'start_date' => '2022-03-02',
                    'end_date' => '2022-03-05',
                    'created_by' => $User->id,
                ],
                [
                    'method_id' => $LAMethod3->id,
                    'name' => 'Ask The Experts',
                    'start_date' => '2022-04-12',
                    'end_date' => '2022-04-15',
                    'created_by' => $User->id,
                ],
                [
                    'method_id' => $LAMethod3->id,
                    'name' => 'Ask The Experts',
                    'start_date' => '2022-05-02',
                    'end_date' => '2022-05-05',
                    'created_by' => $User->id,
                ],

                [
                    'method_id' => $LAMethod4->id,
                    'name' => 'Group Coaching',
                    'start_date' => '2022-05-12',
                    'end_date' => '2022-05-15',
                    'created_by' => $User->id,
                ],

                [
                    'method_id' => $LAMethod5->id,
                    'name' => 'Monitoring Session',
                    'start_date' => '2022-03-05',
                    'end_date' => '2022-03-10',
                    'created_by' => $User->id,
                ],
                [
                    'method_id' => $LAMethod5->id,
                    'name' => 'Monitoring Session',
                    'start_date' => '2022-04-12',
                    'end_date' => '2022-04-15',
                    'created_by' => $User->id,
                ],
                [
                    'method_id' => $LAMethod5->id,
                    'name' => 'Monitoring Session',
                    'start_date' => '2022-05-02',
                    'end_date' => '2022-05-05',
                    'created_by' => $User->id,
                ],
            ]);

            \DB::commit();
        } catch (\Throwable $th) {
            \DB::rollback();
            throw new \Exception($th->getMessage());
        }
    }
}
