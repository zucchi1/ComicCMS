<?php

declare(strict_types=1);

use Phinx\Seed\AbstractSeed;

class CreateAdmUsersRenew extends AbstractSeed
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * https://book.cakephp.org/phinx/0/en/seeding.html
     */
    public function run(): void
    {
        $password="pass";
        $password_hash=hash("sha256", $password);
        $data1=[
            'mail_address'=>'kazuki@gmail.com',
            'password'=> $password_hash
        ];
        $posts = $this->table('adm_admin_users');
        $posts->insert($data1)
              ->save();
        
        $data2=[
            'name'=>"ONE PIECE",
            'author_name'=> '尾田栄一郎',
            'summary'=>"俺の財宝か？欲しけりゃくれてやる！"
        ];
        $posts = $this->table('mst_titles');
        $posts->insert($data2)
                ->save();

        $data=[
            'title_id'=>"1",
            'name'=> "1章",
            'start_date'=> "2030-03-03",
        ];
        $posts = $this->table('mst_chapters');
        $posts->insert($data)
                ->save();
    }
    
}
