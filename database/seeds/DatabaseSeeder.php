<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        /*DB::table('users')->insert([
        		'name'=>'admin',
        		'email'=>'admin@gmail.com',
        		'password'=>bcrypt('123456'),
        		'role_id'=> 1,


        	]);*/
        //$this->call(RolesSeeder::class);
        //$this->call(UsersSeeder::class);
        $this->call(PrivilegsSeeder::class);
        //$this->call(AttendanceTypeSeeder::class);

    }
}
/**
* 
*/
class UsersSeeder extends Seeder
{
	
	/**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        DB::table('users')->insert([
            ['name'=>'admin','email'=>'admin@gmail.com','password'=>bcrypt('123456'),'role_id'=> 1,],
        	['name'=>'demo','email'=>'demo@gmail.com','password'=>bcrypt('123456'),'role_id'=> 2,],
        	['name'=>'normal1','email'=>'normal1@gmail.com','password'=>bcrypt('123456'),'role_id'=> 2,],
        	['name'=>'normal2','email'=>'normal2@gmail.com','password'=>bcrypt('123456'),'role_id'=> 2,]
        ]);
    }
}
class RolesSeeder extends Seeder
{
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        DB::table('roles')->insert([
            ['roles_name'=>'admin','privileges_id'=>'10,11,12,13,14,15,16,17,18,19,20,21,22,23,24,25,26,27,28,29,30,31,32,33,34,35,36,37,38,39,40,41,42,43,44,45,46,47,48,49,50,51'],
            ['roles_name'=>'Nomal','privileges_id'=>'14,15,19,23,24,25,26',],
        ]);
    }
}
class PrivilegsSeeder extends Seeder
{
    
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        DB::table('privilegs')->insert([
            ['privilege_name'=>'Quản lý phân quyền','router'=>'roles','parent_id'=>0],
            ['privilege_name'=>'Quản lý hồ sơ','router'=>'hoso','parent_id'=>0],
            ['privilege_name'=>'Quản lý chấm công','router'=>'chamcong','parent_id'=>0],
            ['privilege_name'=>'Quản lý lương thưởng','router'=>'luongthuong','parent_id'=>0],
            ['privilege_name'=>'Quản lý đánh giá nhân viên','router'=>'danhgia','parent_id'=>0],
            ['privilege_name'=>'Đề xuất','router'=>'dexuat','parent_id'=>0],
            ['privilege_name'=>'Quản lý người dùng','router'=>'user','parent_id'=>0],
            ['privilege_name'=>'Quản trị','router'=>'user','parent_id'=>0],
            ['privilege_name'=>'Quản lý quá trình công tác','router'=>'user','parent_id'=>0],
             
            ['privilege_name'=>'Danh sách roles','router'=>'roles-list','parent_id'=>1],
            ['privilege_name'=>'Thêm Roles','router'=>'roles-add','parent_id'=>1],
            ['privilege_name'=>'Chỉnh sửa Roles','router'=>'roles-edit','parent_id'=>1],
            ['privilege_name'=>'Xóa Roles','router'=>'roles-del','parent_id'=>1],
            
            
            ['privilege_name'=>'Danh sách hồ sơ','router'=>'hoso-list','parent_id'=>2],
            ['privilege_name'=>'Thêm hồ sơ','router'=>'hoso-add','parent_id'=>2],
            ['privilege_name'=>'Sửa hồ sơ','router'=>'hoso-edit','parent_id'=>2],
            ['privilege_name'=>'Xóa hồ sơ','router'=>'hoso-del','parent_id'=>2],
            ['privilege_name'=>'Gán tài khoản đăng nhập','router'=>'hoso-assign','parent_id'=>2],
            
            ['privilege_name'=>'Danh sách chấm công','router'=>'chamcong-list','parent_id'=>3], 
            ['privilege_name'=>'Thêm chấm công','router'=>'chamcong-add','parent_id'=>3],
            ['privilege_name'=>'Sửa chấm công','router'=>'chamcong-edit','parent_id'=>3],
            ['privilege_name'=>'Xóa chấm công','router'=>'chamcong-del','parent_id'=>3],
            ['privilege_name'=>'Chấm công tổng hợp','router'=>'chamcong-tonghop','parent_id'=>3],
            ['privilege_name'=>'Chấm công đi làm','router'=>'chamcong-dilam','parent_id'=>3],
            ['privilege_name'=>'Chấm công đi muộn','router'=>'chamcong-dimuon','parent_id'=>3],
            ['privilege_name'=>'Ngày phép','router'=>'chamcong-ngayphep','parent_id'=>3],

            ['privilege_name'=>'Cấu hình chung lương thưởng','router'=>'luongthuong-cauhinh','parent_id'=>4],
            ['privilege_name'=>'Danh sách lương thưởng','router'=>'luongthuong-list','parent_id'=>4],
            ['privilege_name'=>'Thêm lương thưởng','router'=>'luongthuong-add','parent_id'=>4],
            ['privilege_name'=>'Sửa lương thưởng','router'=>'luongthuong-edit','parent_id'=>4],
            ['privilege_name'=>'Xóa lương thưởng','router'=>'luongthuong-del','parent_id'=>4],

            ['privilege_name'=>'Danh sách đánh giá nhân viên','router'=>'danhgia-list','parent_id'=>5],
            ['privilege_name'=>'Thêm đánh giá nhân viên','router'=>'danhgia-add','parent_id'=>5],
            ['privilege_name'=>'Sửa đánh giá nhân viên','router'=>'danhgia-edit','parent_id'=>5],
            ['privilege_name'=>'Xóa đánh giá nhân viên','router'=>'danhgia-del','parent_id'=>5],

            ['privilege_name'=>'Danh sách đề xuất','router'=>'dexuat-list','parent_id'=>6],
            ['privilege_name'=>'Thêm đề xuất','router'=>'dexuat-add','parent_id'=>6],
            ['privilege_name'=>'Sửa đề xuất','router'=>'dexuat-edit','parent_id'=>6],
            ['privilege_name'=>'Xóa đề xuất','router'=>'dexuat-del','parent_id'=>6],

            ['privilege_name'=>'Danh sách người dùng','router'=>'user-list','parent_id'=>7],
            ['privilege_name'=>'Thêm người dùng','router'=>'user-add','parent_id'=>7],
            ['privilege_name'=>'Sửa người dùng','router'=>'user-edit','parent_id'=>7],
            ['privilege_name'=>'Xóa người dùng','router'=>'user-del','parent_id'=>7],

            ['privilege_name'=>'Danh sách chức năng','router'=>'quantri-list','parent_id'=>8],

            ['privilege_name'=>'Danh sách quá trình công tác','router'=>'quatrinh-list','parent_id'=>9],
            ['privilege_name'=>'Chi tiết quá trình công tác','router'=>'quatrinh-detail','parent_id'=>9],
            ['privilege_name'=>'Thêm quá trình công tác','router'=>'quatrinh-add','parent_id'=>9],
            ['privilege_name'=>'Sửa quá trình công tác','router'=>'quatrinh-edit','parent_id'=>9],
            ['privilege_name'=>'Xóa quá trình công tác','router'=>'quatrinh-del','parent_id'=>9],
            ['privilege_name'=>'Thêm hệ số chức danh','router'=>'quatrinh-addratio','parent_id'=>9],
            ['privilege_name'=>'Sửa hệ số chức danh','router'=>'quatrinh-editratio','parent_id'=>9],
            ['privilege_name'=>'Xóa hệ số chức danh','router'=>'quatrinh-delratio','parent_id'=>9],
            
        ]);
    }
}
/*['name'=>'admin','email'=>'admin1@gmail.com','password'=>bcrypt('123456'),'role_id'=> 1,]
['name'=>'admin1','email'=>'admin2@gmail.com','password'=>bcrypt('123456'),'role_id'=> 1,]
['name'=>'admin2','email'=>'admin3@gmail.com','password'=>bcrypt('123456'),'role_id'=> 1,]*/

Class  AttendanceTypeSeeder extends Seeder{
    public function run(){
        // $this->call(UsersTableSeeder::class);
        DB::table('attendance_type')->insert([
                 ['title'=>'Hưởng lương (HL).','symbol'=>'x'],
                 ['title'=>'Nghỉ phép','symbol'=>'p'],
                 ['title'=>'Ốm','symbol'=>'Ô'],
                 ['title'=>'Con ốm','symbol'=>'Cô'],
                 ['title'=>'Thai sản','symbol'=>'Ts'],
                 ['title'=>'Tai nạn','symbol'=>'T'],
                 ['title'=>'Hội nghị, học tập','symbol'=>'H'],
                 ['title'=>'Nghỉ bù','symbol'=>'Nb'],
                 ['title'=>'Nghỉ không lương','symbol'=>'No'],
                 ['title'=>'Nghỉ lễ','symbol'=>'Nl'],
                 ['title'=>'Không đi muộn','symbol'=>'O'],
                 ['title'=>'Đi muộn','symbol'=>'X'],
            ]);

    }

}