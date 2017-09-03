<?php

use think\migration\Seeder;

class Article extends Seeder
{
    /**
     * Run Method.
     *
     * Write your database seeder using this method.
     *
     * More information on writing seeders is available here:
     * http://docs.phinx.org/en/latest/seeding.html
     */
    public function run()
    {
        $table = $this->table('article');
        for($i=1;$i<20;$i++) {
            $table->insert([
                'title'=>'我的世界' . $i,
                'short_title'=>'我的世界' . $i,
                'cid'=>round(11,13),
                'keyword'=>'我的世界' . $i,
                'abs'=>'我的世界' . $i,
                'author'=>'我的世界' . $i,
                'source'=>'我的世界' . $i,
                'update_at'=>date('Y-m-d H:i:s'),
                'editor'=>'<p>我的世界</p>',
                'state'=>'publish',
            ])->save();
        }
    }
}